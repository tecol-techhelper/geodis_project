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
        public string $localDisk = 'public',
        public string $localBaseDir = 'edifact'
    ) {}

    public function handle(): void
    {
        /** @var EdifactFile $file */
        $file = EdifactFile::query()
            ->with(['service'])
            ->findOrFail($this->edifactFileId);

        // Validaciones mínimas
        if ($file->direction !== EdifactFile::DIRECTION_OUT) {
            throw new \RuntimeException("El edifact_file #{$file->id} no es OUT.");
        }
        if ($file->message_type !== EdifactFile::TYPE_IFTSTA) {
            throw new \RuntimeException("El edifact_file #{$file->id} no es IFTSTA.");
        }

        $now = Carbon::now();

        // Si ya está enviado, no repetir
        if ($file->status === EdifactFile::STATUS_SENT) {
            return;
        }

        $payload = (string) $this->payload;

        // Auditoría del intento
        $file->attempts = (int) ($file->attempts ?? 0) + 1;
        $file->last_attempt_at = $now;
        $file->error_message = null;
        $file->payload_hash = hash('sha256', $payload);

        $fileName = $file->file_name ?: ('IFTSTA_' . ($file->transmission_id ?? $file->id) . '.edi');

        /**
         * REMOTO: TODO PLANO EN IFTSTA (rutas RELATIVAS al disk)
         * OJO: no usar "/IFTSTA" porque Flysystem lo trata distinto según el adapter/root.
         */
        $remoteDir  = 'IFTSTA';
        $remotePath = $this->joinPath($remoteDir, $fileName); // => IFTSTA/archivo.edi

        /**
         * LOCAL: TODO PLANO EN edifact/OUT/IFTSTA (rutas relativas al disk local)
         */
        $localDir  = $this->joinPath(trim($this->localBaseDir, '/'), 'OUT', 'IFTSTA');
        $localPath = $this->joinPath($localDir, $fileName);

        // root configurado del disk remoto (para dejar evidencia en logs)
        $remoteRoot = config("filesystems.disks.{$this->remoteDisk}.root");

        // Contexto común de logs
        $ctx = [
            'edifact_file_id' => $file->id,
            'attempts'        => $file->attempts,
            'remote_disk'     => $this->remoteDisk,
            'remote_root'     => $remoteRoot,
            'remote_path'     => $remotePath,
            'local_disk'      => $this->localDisk,
            'local_path'      => $localPath,
        ];

        // 1) Guardar copia local (auditable)
        try {
            Log::info('IFTSTA upload stage', $ctx + ['stage' => 'local_save_start']);

            $local = Storage::disk($this->localDisk);

            // Crear carpeta local si aplica
            try {
                $local->makeDirectory($localDir);
            } catch (Throwable $e) {
                // no crítico
            }

            $okLocal = $local->put($localPath, $payload);
            if (!$okLocal) {
                throw new \RuntimeException("No se pudo guardar copia local en {$this->localDisk}:{$localPath}");
            }

            $file->file_path = $localPath;
            $file->file_url  = $this->publicUrlForLocalPath($this->localDisk, $localPath);

            $file->remote_disk = $this->remoteDisk;
            $file->remote_path = $remotePath;

            $file->save();

            Log::info('IFTSTA upload stage', $ctx + [
                'stage' => 'local_save_ok',
                'payload_bytes' => strlen($payload),
            ]);
        } catch (Throwable $e) {
            $this->markFailed($file, $e);

            Log::error('Fallo en upload IFTSTA (auditable)', $ctx + [
                'stage' => 'local_save',
                'error' => $this->exceptionToArray($e),
            ]);

            throw $e;
        }

        // 2) Subir a SFTP
        try {
            Log::info('IFTSTA upload stage', $ctx + ['stage' => 'remote_upload_start']);

            $remote = Storage::disk($this->remoteDisk);

            /**
             * IMPORTANTE para SFTP “quisquilloso”:
             * - NO makeDirectory remoto (tiende a chmod/visibility).
             * - put() “limpio”.
             * - RUTA RELATIVA (sin slash inicial).
             */
            $ok = $remote->put($remotePath, $payload);

            if (!$ok) {
                // Cuando el adapter no lanza excepción, esto es lo único que te queda.
                throw new \RuntimeException("Storage::disk({$this->remoteDisk})->put() devolvió false.");
            }

            $file->status      = EdifactFile::STATUS_SENT;
            $file->sent_at     = $now;
            $file->uploaded_at = $now;
            $file->save();

            Log::info('IFTSTA upload stage', $ctx + ['stage' => 'remote_upload_ok']);
        } catch (Throwable $e) {
            $this->markFailed($file, $e);

            Log::error('Fallo en upload IFTSTA (auditable)', $ctx + [
                'stage' => 'remote_upload',
                'error' => $this->exceptionToArray($e),
            ]);

            throw $e;
        }
    }

    /**
     * Une segmentos de ruta y devuelve SIEMPRE una ruta RELATIVA (sin slash inicial),
     * normalizada (sin //, sin ./, sin backslashes).
     */
    private function joinPath(string ...$parts): string
    {
        $clean = [];

        foreach ($parts as $p) {
            $p = str_replace('\\', '/', (string) $p);
            $p = trim($p);

            if ($p === '') continue;

            // Evitar que alguien meta "/IFTSTA" y rompa el comportamiento del root del disk
            $p = ltrim($p, '/');

            // Normalizar dobles slashes internos
            $p = preg_replace('#/+#', '/', $p);

            // Quitar "./" al inicio
            $p = preg_replace('#^\./#', '', $p);

            if ($p !== '') $clean[] = $p;
        }

        return implode('/', $clean);
    }

    private function markFailed(EdifactFile $file, Throwable $e): void
    {
        if (defined(EdifactFile::class . '::STATUS_FAILED')) {
            $file->status = EdifactFile::STATUS_FAILED;
        }

        $file->error_message = mb_substr($e->getMessage(), 0, 65000);
        $file->save();
    }

    private function exceptionToArray(Throwable $e): array
    {
        $out = [
            'class'   => get_class($e),
            'code'    => $e->getCode(),
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
        ];

        if ($prev = $e->getPrevious()) {
            $out['previous'] = [
                'class'   => get_class($prev),
                'code'    => $prev->getCode(),
                'message' => $prev->getMessage(),
                'file'    => $prev->getFile(),
                'line'    => $prev->getLine(),
            ];
        }

        return $out;
    }

    private function publicUrlForLocalPath(string $disk, string $path): ?string
    {
        if ($disk !== 'public') return null;

        $base = config('filesystems.disks.public.url'); // ej: https://dominio.com/storage
        if (!is_string($base) || trim($base) === '') return null;

        // Asegurar ruta sin slash inicial
        $path = ltrim(str_replace('\\', '/', $path), '/');

        return rtrim($base, '/') . '/' . $path;
    }
}
