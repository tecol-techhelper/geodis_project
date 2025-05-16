<?php

namespace App\Console\Commands;

use App\Jobs\ProcessEdifactFileJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessEdifactFileCommand extends Command
{
    protected $signature = 'edifact:process';
    protected $description = 'Connect by SFTP (local for now), download .edi files and run the job';

    public function handle()
    {
        $remote = Storage::disk('sftp');
        $files = $remote->files('/');

        foreach ($files as $remoteRoute) {
            if (!str_ends_with($remoteRoute, '.edi')) continue;

            try {
                $content = $remote->get($remoteRoute);
                Log::debug("Contenido tamaño: " . strlen($content));

                $tipoMensaje = 'unknown';
                if (str_contains($content, '+IFTSTA:')) {
                    $tipoMensaje = 'IFTSTA';
                } elseif (str_contains($content, '+IFCSUM:')) {
                    $tipoMensaje = 'IFCSUM';
                }

                $fileName = basename($remoteRoute);
                $prefixedFileName = "{$tipoMensaje}_{$fileName}";
                $localRoute = "edifact/inbox/{$tipoMensaje}/{$prefixedFileName}";
                $fullPath = storage_path("app/{$localRoute}");

                $directory = dirname($fullPath);
                if (!is_dir($directory)) {
                    mkdir($directory, recursive: true);
                }

                Log::debug("Ruta a guardar: {$fullPath}");

                File::put($fullPath, $content);

                if (!File::exists($fullPath)) {
                    Log::error("El archivo no se guardó correctamente en {$fullPath}");
                    continue;
                }

                usleep(200000);

                dispatch(new ProcessEdifactFileJob(
                    $fullPath,
                    $prefixedFileName,
                    $tipoMensaje,
                    $localRoute
                ));

                Log::info("Archivo enviado procesado correctamente: {$fileName}");
            } catch (\Throwable $e) {
                Log::error("Fallo procesando {$remoteRoute}: " . $e->getMessage());
            }
        }
    }
}
