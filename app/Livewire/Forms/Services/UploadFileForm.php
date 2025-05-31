<?php

namespace App\Livewire\Forms\Services;

use App\Models\FileType;
use App\Models\SupportFile;
use App\Services\SharePointUploader;
use Illuminate\Support\Facades\Auth;
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
            $this->addError('files', 'Debe seleccionar al menos 1 archivo');
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

        flash()->title('Archivos Eliminados!')->error("Los archivos fueron removidos correctamente!");
        $this->tempFiles = [];
    }

    public function saveFiles(SharePointUploader $sharePointUploader): void
    {
        foreach ($this->tempFiles as $temp) {
            $localPath = storage_path('app/private/' . $temp['temp_path']);
            $extension = pathinfo($temp['fileName'], PATHINFO_EXTENSION);

            $remoteFileName = $temp['fileName'];

            try {
                $sharePointResponse = $sharePointUploader->upload($localPath, $remoteFileName);
                $sharePointUrl = $sharePointResponse['webUrl'] ?? null;
            } catch (\Throwable $e) {
                $this->addError('form.files', "Error en SharePoint: {$e->getMessage()}");
                continue;
            }

            $fileType = FileType::where('file_type', $temp['file_type'])->first();
            $fileTypeId = $fileType?->id ?? null;

            if (Storage::disk('sftp')->put($remoteFileName, fopen($localPath, 'r+'))) {
                SupportFile::create([
                    'file_name'      => explode('.', $remoteFileName)[0],
                    'file_url'       => $sharePointUrl, // solo SFTP por ahora
                    'file_size'      => filesize($localPath),
                    'file_extension' => $extension,
                    'uploaded_at'    => now()->toDateString(),
                    'user_id'        => Auth::id(),
                    'file_type_id'   => $fileTypeId,
                ]);

                if (Storage::exists($temp['fileName'])) {
                    Storage::delete($temp['fileName']);
                }
            } else {
                $this->addError('form.files', "No se pudo subir el archivo {$remoteFileName}");
            }
        }
        flash()->title('Archivos Guardados')->success('Los archivos han sido almacenados correctamente!');
        $this->clearTempFiles();
    }
}
