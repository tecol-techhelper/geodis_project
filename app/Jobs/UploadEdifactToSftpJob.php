<?php

namespace App\Jobs;

use App\Models\EdifactFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class UploadEdifactToSftpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;

    public function backoff(): array
    {
        return [10, 30, 60, 180, 300];
    }

    public function __construct(
        public int $edifactFileId,
        public string $payload,
        public string $remoteDisk = 'sftp_geodis',
        public ?string $remoteDir = null,
        public string $localDisk = 'public',
        public string $localBaseDir = 'edifact'
    ) {}

    public function handle(): void
    {
        /** @var EdifactFile $file */
        $file = EdifactFile::query()
            ->with(['service'])
            ->findOrFail($this->edifactFileId);

        // Coherencia mínima
        if ($file->direction !== EdifactFile::DIRECTION_OUT) {
            throw new \RuntimeException("El edifact_file #{$file->id} no es OUT. direction={$file->direction}");
        }

        if ($file->message_type !== EdifactFile::TYPE_IFTSTA) {
            throw new \RuntimeException("El edifact_file #{$file->id} no es IFTSTA. message_type={$file->message_type}");
        }

        $now = Carbon::now();

        // Si ya está enviado, no repetir
        if ($file->status === EdifactFile::STATUS_SENT) {
            return;
        }

        // Auditoría del intento
        $payload = (string) $this->payload;
        $file->attempts = (int) ($file->attempts ?? 0) + 1;
        $file->last_attempt_at = $now;
        $file->error_message = null;
        $file->payload_hash = hash('sha256', $payload);

        // Nombre archivo
        $fileName = $file->file_name ?: ('IFTSTA_' . ($file->transmission_id ?? $file->id) . '.edi');

        // Fecha para rutas
        $year = $now->format('Y');
        $month = $now->format('m');

        // Consecutivo para carpeta (prioridad: service->consecutive)
        $consecutive = null;

        if ($file->service && filled($file->service->consecutive)) {
            $consecutive = (string) $file->service->consecutive;
        } elseif (filled($file->purchase_order)) {
            $consecutive = (string) $file->purchase_order;
        } elseif (filled($file->transmission_id)) {
            $consecutive = (string) $file->transmission_id;
        } else {
            $consecutive = (string) $file->id;
        }

        $consecutive = trim((string) $consecutive);
        $consecutive = $consecutive === '' ? (string) $file->id : $consecutive;

        /**
         * REMOTO obligatorio:
         * /IFTSTA/{AÑO}/{MES}/{CONSECUTIVO}
         */
        $remoteDir = $this->remoteDir;
        if (!is_string($remoteDir) || trim($remoteDir) === '') {
            $remoteDir = "IFTSTA/{$year}/{$month}/{$consecutive}";
        } else {
            $remoteDir = trim($remoteDir, "/ \t\n\r\0\x0B");
        }

        $remotePath = rtrim($remoteDir, '/') . '/' . $fileName;

        /**
         * LOCAL auditable:
         * edifact/OUT/IFTSTA/{Y}/{m}/{consecutivo}/archivo.edi
         */
        $localDir = trim($this->localBaseDir, '/')
            . "/OUT/IFTSTA/{$year}/{$month}/{$consecutive}";

        $localPath = rtrim($localDir, '/') . '/' . $fileName;

        // 1) Guardar copia local
        try {
            $local = Storage::disk($this->localDisk);

            // Muchos adapters soportan esto; si no, put() suele crear la ruta igual
            try {
                $local->makeDirectory($localDir);
            } catch (Throwable $e) {
                // no es crítico
            }

            $okLocal = $local->put($localPath, $payload);
            if (!$okLocal) {
                throw new \RuntimeException("No se pudo guardar copia local en {$this->localDisk}:{$localPath}");
            }

            // Persistencia base
            $file->file_path = $localPath;

            /**
             * NO usar $local->url() para evitar el error del IDE y porque no es universal.
             * Si el disk es "public", construimos URL con config.
             */
            $file->file_url = $this->publicUrlForLocalPath($this->localDisk, $localPath);

            // Metadata remota
            $file->remote_disk = $this->remoteDisk;
            $file->remote_path = $remotePath;

            $file->save();
        } catch (Throwable $e) {
            $file->status = defined(EdifactFile::class . '::STATUS_FAILED')
                ? EdifactFile::STATUS_FAILED
                : $file->status;

            $file->error_message = mb_substr($e->getMessage(), 0, 65000);
            $file->save();

            Log::error('Fallo guardando copia local IFTSTA', [
                'edifact_file_id' => $file->id,
                'local_disk' => $this->localDisk,
                'local_path' => $localPath,
                'attempts' => $file->attempts,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }

        // 2) Subir a SFTP
        try {
            $remote = Storage::disk($this->remoteDisk);

            // Intento de crear carpeta (si el server/adaptador lo soporta)
            try {
                $remote->makeDirectory(rtrim($remoteDir, '/'));
            } catch (Throwable $e) {
                // no crítico
            }

            $ok = $remote->put($remotePath, $payload);

            if (!$ok) {
                throw new \RuntimeException("Storage::disk({$this->remoteDisk})->put() devolvió false.");
            }

            // Éxito
            $file->status = EdifactFile::STATUS_SENT;
            $file->sent_at = $now;
            $file->uploaded_at = $now;
            $file->save();
        } catch (Throwable $e) {
            $file->status = defined(EdifactFile::class . '::STATUS_FAILED')
                ? EdifactFile::STATUS_FAILED
                : $file->status;

            $file->error_message = mb_substr($e->getMessage(), 0, 65000);
            $file->save();

            Log::error('Fallo subiendo IFTSTA a SFTP', [
                'edifact_file_id' => $file->id,
                'remote_disk' => $this->remoteDisk,
                'remote_path' => $remotePath,
                'attempts' => $file->attempts,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Devuelve URL pública SOLO si el disk tiene configuración pública (ej: disk "public").
     * Si no aplica, retorna null y listo.
     */
    private function publicUrlForLocalPath(string $disk, string $path): ?string
    {
        // Solo el disk "public" suele tener url web por defecto
        if ($disk !== 'public') {
            return null;
        }

        $base = config('filesystems.disks.public.url'); // ej: https://dominio.com/storage
        if (!is_string($base) || trim($base) === '') {
            return null;
        }

        return rtrim($base, '/') . '/' . ltrim($path, '/');
    }
}
