<?php

namespace App\Livewire\Forms\Services;

use App\Models\FileType;
use App\Models\SupportFile;
use App\Services\SharePointUploader;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class UploadFileForm extends Form
{
    use WithFileUploads;

    #[Validate([
        'files' => 'array|nullable',
        'files.*' => 'file|max:10240',
    ])]
    public array $files = [];

    public array $tempFiles = [];

    #[Validate('string|in:CLI,CLP,IC,IF,RO,RT,ID,TRC,TDC,GABF301,PDR,GPS,RP')]
    public string|null $file_type = null;


    public string|null $free_text = null;


    public function uploadFiles(): void
    {
        if (!$this->files) {
            $this->addError('form.files', 'Debe seleccionar al menos 1 archivo');
        }
        if (!$this->file_type || $this->file_type === '') {
            $this->addError('file_type', 'Debes seleccionar un tipo de soporte.');
            return;
        }
        foreach ($this->files as $file) {

            $extension = $file->getClientOriginalExtension();

            $newFileName = 'N_' . $this->file_type . '_' . $this->free_text . '_' . Str::random(8) . "." . $extension;

            $tempPath = $file->storeAs('temp_support_files', $newFileName, 'local');

            $this->tempFiles[] = [
                'fileName' => $newFileName,
                'temp_path' => $tempPath,
                'file_type' => $this->file_type
            ];
            flash()->title('Archivo Cargado')->info("Archivo <b>{$newFileName}</b> Cargado Correctamente!");
        }

        $this->resetErrorBag('files');
        // dd($this->tempFiles);
        $this->files = [];
    }

    public function removeTempFiles(int $index): void
    {
        if (isset($this->tempFiles[$index])) {
            $fileName = $this->tempFiles[$index]['fileName'];
            $path = $this->tempFiles[$index]['temp_path'];

            if ($path && Storage::exists($path)) {
                Storage::delete($path);
            }

            unset($this->tempFiles[$index]);

            flash()->title('Archivo Eliminado!')->error("El archivo <b>{$fileName}</b> fue removido correctamente!");

            $this->tempFiles = array_values($this->tempFiles);
        }
    }

    public function clearTempFiles(): void
    {
        foreach ($this->tempFiles as $file) {
            if (Storage::exists($file['temp_path'])) {
                Storage::delete($file['temp_path']);
            }
        }
        $this->tempFiles = [];
        \Illuminate\Support\Facades\File::cleanDirectory(\storage_path('app/livewire-tmp'));
    }

    public function saveFiles(SharePointUploader $sharePointUploader): void
    {
        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('coord')) {
            $successfulCount = 0;
            $totalCount = count($this->tempFiles);

            // Directorio remoto (DOCS/año/mes)
            $year  = now()->format('Y');
            $month = now()->format('m');
            $remoteDir = "DOCS/{$year}/{$month}";

            // --- Crear directorio remoto con reintentos por latencia ---
            try {
                if (!Storage::disk('sftp')->exists($remoteDir)) {
                    Storage::disk('sftp')->makeDirectory($remoteDir);

                    $attempts = 0;
                    while ($attempts < 5 && !Storage::disk('sftp')->exists($remoteDir)) {
                        usleep(300000 * ($attempts + 1)); // backoff: 0.3s, 0.6s, 0.9s...
                        $attempts++;
                    }

                    if ($attempts === 5) {
                        $this->addError('form.files', "No se pudo confirmar la creación del directorio remoto {$remoteDir} por latencia.");
                        return;
                    }
                }
            } catch (\Throwable $e) {
                $this->addError('form.files', "Error creando directorio remoto: {$e->getMessage()}");
                return;
            }

            foreach ($this->tempFiles as $temp) {
                $localPath = storage_path('app/private/' . ltrim($temp['temp_path'], '/'));
                $extension = pathinfo($temp['fileName'], PATHINFO_EXTENSION);
                $remoteFileName = $temp['fileName'];
                $remotePath = "{$remoteDir}/{$remoteFileName}";

                if (!is_file($localPath)) {
                    $this->addError('form.files', "No se encontró el archivo temporal: {$localPath}");
                    continue;
                }

                // 1) Subida a SharePoint
                try {
                    $sharePointResponse = $sharePointUploader->upload($localPath, $remotePath);
                    $sharePointUrl = $sharePointResponse['webUrl'] ?? null;
                } catch (\Throwable $e) {
                    $this->addError('form.files', "Error en SharePoint: {$e->getMessage()}");
                    continue;
                }

                // 2) Subida a SFTP con reintentos por latencia
                $uploaded = false;
                $attempts = 0;

                while (!$uploaded && $attempts < 5) {
                    try {
                        Storage::disk('sftp')->putFileAs(
                            $remoteDir,
                            new \Illuminate\Http\File($localPath),
                            $remoteFileName
                        );

                        if (Storage::disk('sftp')->exists($remotePath)) {
                            $uploaded = true;
                            break;
                        }
                    } catch (\Throwable $e) {
                        Log::warning("Reintento {$attempts} fallido para {$remoteFileName}: " . $e->getMessage());
                    }

                    usleep(500000 * ($attempts + 1)); // backoff: 0.5s, 1s, 1.5s...
                    $attempts++;
                }

                if (!$uploaded) {
                    $this->addError('form.files', "No se confirmó la subida del archivo {$remoteFileName} en el SFTP (latencia alta).");
                    continue;
                }

                // 3) Registro en BD
                try {
                    $fileType   = \App\Models\FileType::where('file_type', $temp['file_type'])->first();
                    $fileTypeId = $fileType?->id ?? null;

                    \App\Models\SupportFile::create([
                        'file_name'      => explode('.', $remoteFileName)[0],
                        'file_url'       => $sharePointUrl,
                        'file_size'      => filesize($localPath),
                        'file_extension' => $extension,
                        'uploaded_at'    => now()->toDateString(),
                        'user_id'        => Auth::id(),
                        'file_type_id'   => $fileTypeId,
                    ]);

                    Storage::disk('local')->delete($temp['temp_path']);
                    $successfulCount++;
                } catch (\Throwable $e) {
                    Log::error("Fallo al guardar en BD: " . $e->getMessage());
                }
            }

            // 4) Resumen al usuario
            if ($successfulCount === $totalCount) {
                flash()->title('Archivos Guardados')->success('Todos los archivos han sido almacenados correctamente!');
                $this->clearTempFiles();
            } else {
                flash()->title('Carga Parcial')->warning("Se almacenaron correctamente {$successfulCount} de {$totalCount} archivos.");
            }
        }
    }
}
