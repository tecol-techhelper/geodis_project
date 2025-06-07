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

    #[Validate('string|in:CLI,CLP,IC,IF,RO,RT,ID,TRC,TDC')]
    public string|null $file_type = null;


    public function uploadFiles(): void
    {
        if (!$this->files) {
            $this->addError('form.files', 'Debe seleccionar al menos 1 archivo');
        }
        foreach ($this->files as $file) {
            if (!$this->file_type || $this->file_type === '') {
                $this->addError('file_type', 'Debes seleccionar un tipo de soporte.');
                return;
            }

            $extension = $file->getClientOriginalExtension();

            $newFileName = 'N_' . $this->file_type . '_' . Str::random(8) . "." . $extension;

            $tempPath = $file->storeAs('temp_support_files', $newFileName, 'local');

            $this->tempFiles[] = [
                'fileName' => $newFileName,
                'temp_path' => $tempPath,
                'file_type' => $this->file_type
            ];
            flash()->title('Archivo Cargado')->info("Archivo <b>{$newFileName}</b> Cargado Correctamente!");
        }

        $this->resetErrorBag('files');
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
            $successfulCount = 0; // Contador de archivos exitosos
            $totalCount = count($this->tempFiles); // Total de archivos

            foreach ($this->tempFiles as $temp) {
                $localPath = storage_path('app/private/' . $temp['temp_path']);
                $extension = pathinfo($temp['fileName'], PATHINFO_EXTENSION);
                $remoteFileName = $temp['fileName'];

                $year = now()->format('Y');
                $month = now()->format('m');
                $remotePath = "DOCS/{$year}/{$month}/{$remoteFileName}";

                try {
                    $sharePointResponse = $sharePointUploader->upload($localPath, $remotePath);
                    $sharePointUrl = $sharePointResponse['webUrl'] ?? null;
                } catch (\Throwable $e) {
                    $this->addError('form.files', "Error en SharePoint: {$e->getMessage()}");
                    continue;
                }

                // Subida a SFTP
                Storage::disk('sftp_geodis')->put($remotePath, fopen($localPath, 'r+'));

                sleep(1);

                // Validar existencia en servidor remoto
                if (Storage::disk('sftp_geodis')->exists($remotePath)) {
                    try {
                        $fileType = FileType::where('file_type', $temp['file_type'])->first();
                        $fileTypeId = $fileType?->id ?? null;

                        SupportFile::create([
                            'file_name'      => explode('.', $remoteFileName)[0],
                            'file_url'       => $sharePointUrl,
                            'file_size'      => filesize($localPath),
                            'file_extension' => $extension,
                            'uploaded_at'    => now()->toDateString(),
                            'user_id'        => Auth::id(),
                            'file_type_id'   => $fileTypeId,
                        ]);

                        if (Storage::exists($temp['fileName'])) {
                            Storage::delete($temp['fileName']);
                        }

                        $successfulCount++; // Marca como exitoso
                    } catch (\Throwable $e) {
                        Log::error("Fallo al guardar en la base de datos: " . $e->getMessage());
                    }
                } else {
                    $this->addError('form.files', "No se confirmó la subida del archivo {$remoteFileName} al servidor SFTP.");
                }
            }

            // Mostrar mensaje solo si todos fueron exitosos
            if ($successfulCount === $totalCount) {
                flash()->title('Archivos Guardados')->success('Todos los archivos han sido almacenados correctamente!');
                $this->clearTempFiles();
            } else {
                flash()->title('Carga Parcial')->warning("Se almacenaron correctamente {$successfulCount} de {$totalCount} archivos.");
            }
        }
    }
}
