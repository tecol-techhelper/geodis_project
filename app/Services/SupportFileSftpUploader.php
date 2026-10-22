<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SupportFileSftpUploader
{
    private function sftpDiagnosticContext(string $remotePath, int $serviceId): array
    {
        $root = (string) config('filesystems.disks.sftp_geodis.root', '');

        return [
            'service_id' => $serviceId,
            'disk' => 'sftp_geodis',
            'root' => $root,
            'remote_path' => $remotePath,
            'effective_path' => rtrim($root, '/').'/'.ltrim($remotePath, '/'),
            'timeout_seconds' => config('filesystems.disks.sftp_geodis.timeout'),
        ];
    }

    private function sftpExceptionContext(\Throwable $exception): array
    {
        $errors = [];
        do {
            $errors[] = [
                'class' => get_class($exception),
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
            ];
            $exception = $exception->getPrevious();
        } while ($exception !== null && count($errors) < 5);

        return ['exceptions' => $errors];
    }

    public function upload(string $remoteDir, string $remoteFileName, string $remotePath, string $localPath, int $serviceId): array
    {
        $attempts = 0;
        $lastError = null;
        $context = $this->sftpDiagnosticContext($remotePath, $serviceId);

        while ($attempts < 5) {
            $stage = 'local_file_check';
            try {
                clearstatcache(true, $localPath);
                if (! is_file($localPath) || ! is_readable($localPath)) {
                    throw new \RuntimeException('El archivo temporal no existe o no se puede leer.');
                }
                $localSize = filesize($localPath);
                if ($localSize === false) {
                    throw new \RuntimeException('No se pudo determinar el tamaño del archivo temporal.');
                }
                /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
                $disk = Storage::disk('sftp_geodis');
                Log::info('UploadFileForm@sftp:upload_start', $context + [
                    'attempt' => $attempts + 1,
                    'local_size_bytes' => $localSize,
                ]);
                $stage = 'file_write';
                $result = $disk->putFileAs(
                    $remoteDir,
                    new \Illuminate\Http\File($localPath),
                    $remoteFileName
                );

                if ($result === false) {
                    throw new \RuntimeException('putFileAs retorno false');
                }

                Log::info('UploadFileForm@sftp:write_completed', $context + [
                    'attempt' => $attempts + 1,
                    'returned_path' => $result,
                ]);

                // The receiving system can consume and remove DOCS files immediately.
                // A successful acknowledged write must not be resent because it disappeared.
                return [true, null];
            } catch (\Throwable $e) {
                $lastError = $e->getMessage();
                Log::warning('UploadFileForm@sftp:upload_attempt_failed', $context + [
                    'attempt' => $attempts + 1,
                    'stage' => $stage,
                ] + $this->sftpExceptionContext($e));
                usleep(500000 * ($attempts + 1));
                $attempts++;
            }
        }

        Log::error('UploadFileForm@sftp:upload_failed', $context + ['message' => $lastError]);

        return [false, $lastError];
    }
}
