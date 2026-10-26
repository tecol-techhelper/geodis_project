<?php

namespace App\Console\Commands;

use App\Jobs\ProcessEdifactFileJob;
use App\Models\EdifactFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProcessEdifactFileCommand extends Command
{
    protected $signature = 'edifact:process
        {--remote-dir=/IFCSUM : Directorio remoto inbound en sftp_geodis}
        {--processed-dir=processed : Subdirectorio dentro de remote-dir para mover OK}
        {--error-dir=error : Subdirectorio dentro de remote-dir para mover FAIL}
        {--move-remote=1 : 1=mover archivo remoto a processed/error (recomendado)}
        {--delete-remote=0 : 1=eliminar remoto al finalizar OK (no recomendado si quieres trazabilidad)}
        {--max=0 : Máximo de archivos por corrida (0=sin límite)}';

    protected $description = 'Procesa archivos IFCSUM inbound desde sftp_geodis (/IFCSUM), guarda local y despacha el Job';

    public function handle(): int
    {
        // Evita ejecuciones simultáneas
        $lock = Cache::lock('edifact:process:lock', 300);
        if (!$lock->get()) {
            $this->info('edifact:process ya se está ejecutando.');
            return self::SUCCESS;
        }

        try {
            // Remote dir debe ser /IFCSUM por defecto
            $remoteDir = $this->normalizeRemoteDir((string) $this->option('remote-dir'));

            // processed/error son subfolders dentro de /IFCSUM
            $processedSubdir = trim((string) $this->option('processed-dir'), " \t\n\r\0\x0B/");
            $errorSubdir     = trim((string) $this->option('error-dir'), " \t\n\r\0\x0B/");

            $processedDir = rtrim($remoteDir, '/') . '/' . ($processedSubdir !== '' ? $processedSubdir : 'processed');
            $errorDir     = rtrim($remoteDir, '/') . '/' . ($errorSubdir !== '' ? $errorSubdir : 'error');

            $moveRemote   = (bool) ((int) $this->option('move-remote'));
            $deleteRemote = (bool) ((int) $this->option('delete-remote'));
            $max          = (int) $this->option('max');

            $remote = Storage::disk('sftp_geodis');

            // Listar SOLO dentro de /IFCSUM
            $files = $remote->files($remoteDir);

            // Filtrar .edi
            $ediFiles = array_values(array_filter(
                $files,
                fn($p) => Str::endsWith(Str::lower($p), '.edi')
            ));

            if (empty($ediFiles)) {
                $this->info("No hay .edi en {$remoteDir} (sftp_geodis).");
                return self::SUCCESS;
            }

            // Asegurar directorio local base
            $localDir = 'edifact/inbox/IFCSUM';
            try {
                Storage::disk('local')->makeDirectory($localDir);
            } catch (\Throwable $e) {
                // Si esto falla, no tiene sentido seguir
                Log::error('No se pudo crear/asegurar el directorio local inbox', [
                    'localDir' => $localDir,
                    'error' => $e->getMessage(),
                ]);
                $this->error("No se pudo preparar el directorio local: {$localDir}");
                return self::FAILURE;
            }

            $processedCount = 0;

            foreach ($ediFiles as $remotePath) {
                if ($max > 0 && $processedCount >= $max) break;

                $fileName = basename($remotePath);

                try {
                    $content = $remote->get($remotePath);

                    if ($content === null || $content === '') {
                        Log::warning('EDIFACT vacío/no legible', ['remote' => $remotePath]);
                        $this->moveRemote($remote, $remotePath, $errorDir, $moveRemote);
                        continue;
                    }

                    // Inbound estricto: IFCSUM
                    if (!Str::contains($content, '+IFCSUM:')) {
                        Log::warning('Archivo inbound no es IFCSUM. Se envía a error.', [
                            'remote' => $remotePath,
                            'file' => $fileName,
                        ]);
                        $this->moveRemote($remote, $remotePath, $errorDir, $moveRemote);
                        continue;
                    }

                    // Idempotencia temprana: extraer transmission_id del UNB
                    $transmissionId = $this->extractTransmissionIdFromUNB($content);

                    if ($transmissionId && EdifactFile::where('transmission_id', $transmissionId)->exists()) {
                        Log::info('Transmisión ya procesada. Se omite.', [
                            'transmission_id' => $transmissionId,
                            'remote' => $remotePath,
                        ]);

                        // Ya está procesado, lo sacamos del inbox remoto
                        $this->moveRemote($remote, $remotePath, $processedDir, $moveRemote);
                        if ($deleteRemote) $remote->delete($remotePath);
                        continue;
                    }

                    // Guardado local
                    $localRelative = "{$localDir}/{$fileName}";
                    $written = Storage::disk('local')->put($localRelative, $content);
                    $localFullPath = Storage::disk('local')->path($localRelative);

                    // Validación fuerte del guardado
                    $exists = is_file($localFullPath);
                    $size   = $exists ? filesize($localFullPath) : 0;

                    if ($written !== true || !$exists || $size <= 0) {
                        Log::error('No se pudo guardar el archivo local (NO se mueve remoto)', [
                            'remote' => $remotePath,
                            'local' => $localFullPath,
                            'written' => $written,
                            'exists' => $exists,
                            'size' => $exists ? $size : null,
                        ]);

                        // Importante: el fallo fue local. No castigues el remoto moviéndolo.
                        continue;
                    }

                    // Despachar Job con tipoMensaje fijo: IFCSUM
                    ProcessEdifactFileJob::dispatch(
                        $localFullPath,
                        $fileName,
                        'IFCSUM',
                        $localRelative
                    );

                    Log::info('Job despachado', [
                        'file' => $fileName,
                        'remote' => $remotePath,
                        'local' => $localRelative,
                        'transmission_id' => $transmissionId,
                    ]);

                    // Post-procesado remoto: mover a /IFCSUM/processed por defecto
                    $this->moveRemote($remote, $remotePath, $processedDir, $moveRemote);
                    if ($deleteRemote) $remote->delete($remotePath);

                    $processedCount++;
                } catch (\Throwable $e) {
                    Log::error('Error procesando archivo inbound', [
                        'remote' => $remotePath,
                        'file' => $fileName,
                        'error' => $e->getMessage(),
                    ]);

                    // Este sí es error del procesamiento del archivo (o del driver remoto). Se puede enviar a error.
                    $this->moveRemote($remote, $remotePath, $errorDir, $moveRemote);
                }
            }

            $this->info("Finalizado. Jobs despachados: {$processedCount}");
            return self::SUCCESS;
        } finally {
            optional($lock)->release();
        }
    }

    /**
     * Normaliza directorio remoto:
     * - Debe empezar con /
     * - No deja vacío (fallback /IFCSUM)
     * - Quita slash final excepto si es "/"
     */
    private function normalizeRemoteDir(string $dir): string
    {
        $dir = trim($dir);
        if ($dir === '') $dir = '/IFCSUM';
        if (!Str::startsWith($dir, '/')) $dir = '/' . $dir;

        $dir = rtrim($dir, '/');
        return $dir === '' ? '/IFCSUM' : $dir;
    }

    /**
     * UNB+...+...+...+YYMMDD:HHMM+<transmission_id>...
     * En tu parser, transmission_id viene de $parts[5].
     */
    private function extractTransmissionIdFromUNB(string $content): ?string
    {
        $firstSeg = Str::before($content, "'");
        if (!Str::startsWith($firstSeg, 'UNB+')) return null;

        $parts = explode('+', $firstSeg);
        $id = $parts[5] ?? null;
        $id = is_string($id) ? trim($id) : null;

        return ($id !== '') ? $id : null;
    }

    private function moveRemote($remoteDisk, string $remotePath, string $targetDir, bool $enabled): void
    {
        if (!$enabled) return;
        if ($targetDir === '') return;

        $fileName = basename($remotePath);
        $targetPath = rtrim($targetDir, '/') . '/' . $fileName;

        try {
            // Algunos SFTP no soportan makeDirectory bien. Si falla, no importa.
            try {
                $remoteDisk->makeDirectory($targetDir);
            } catch (\Throwable $e) {
            }

            $remoteDisk->move($remotePath, $targetPath);

            Log::info('Archivo remoto movido', [
                'from' => $remotePath,
                'to' => $targetPath,
            ]);
        } catch (\Throwable $e) {
            Log::warning('No se pudo mover el archivo remoto', [
                'from' => $remotePath,
                'to' => $targetPath,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
