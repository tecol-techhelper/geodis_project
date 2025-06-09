<?php

namespace App\Console\Commands;

use App\Jobs\ProcessEdifactFileJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Artisan command to process Edifact file from SFTP server
 * 
 * Command connects by SFTP, downloads all files with '.edi' extension 
 * and send them to ProcessEdifactFile to parse them and save data extracted
 * 
 * Command: php artisan edifact:process
 * 
 * Spected files location: /
 * File supported: IFTSTA and IFCSUM
 */

class ProcessEdifactFileCommand extends Command
{
    // Command name and description
    protected $signature = 'edifact:process';
    protected $description = 'Connect by SFTP (local for now), download .edi files and run the job';

    /**
     * Runs the command
     * 
     * - Connects to configured disk as "sftp"
     * - Filter just '.edi' files
     * - Detects message type (IFTSTA or IFCSUM)
     * - Save files on local route storage/app/edifact/inbox/
     * - Launches a background job to process the file
     */
    public function handle()
    {
        // Get connection to SFTP disk and list all files
        $remote = Storage::disk('sftp');
        $files = $remote->files('/');

        foreach ($files as $remoteRoute) {
            // Skips no .edi files
            if (!str_ends_with($remoteRoute, '.edi')) continue;

            try {
                //Get all content file
                $content = $remote->get($remoteRoute);
                Log::debug("Contenido tamaño: " . strlen($content));

                // Determinate message type based on content
                $messageType = 'unknown';
                if (str_contains($content, '+IFTSTA:')) {
                    $messageType = 'IFTSTA';
                } elseif (str_contains($content, '+IFCSUM:')) {
                    $messageType = 'IFCSUM';
                }

                // Build local routes in the project
                $fileName = basename($remoteRoute);
                $localRoute = "edifact/inbox/{$messageType}/{$fileName}";
                $fullPath = storage_path("app/public/{$localRoute}");

                // Creates directory if not exist
                $directory = dirname($fullPath);
                if (!is_dir($directory)) {
                    mkdir($directory, recursive: true);
                }

                Log::debug("Ruta a guardar: {$fullPath}");

                // Save file in local disk
                Storage::disk('public')->put($localRoute, $content);

                // Validates that files was saved correctly
                if (!File::exists($fullPath)) {
                    Log::error("El archivo no se guardó correctamente en {$fullPath}");
                    continue;
                }

                usleep(200000);

                // Lauch job to process file
                dispatch(new ProcessEdifactFileJob(
                    $fullPath,
                    $fileName,
                    $messageType,
                    $localRoute
                ));

                Log::info("Archivo enviado procesado correctamente: {$fileName}");
            } catch (\Throwable $e) {
                Log::error("Fallo procesando {$remoteRoute}: " . $e->getMessage());
            }
        }
    }
}
