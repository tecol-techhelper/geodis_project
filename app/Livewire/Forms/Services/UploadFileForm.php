<?php

namespace App\Livewire\Forms\Services;

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
    public string|null $file_type=null;


    public function uploadFiles(): void
    {
        foreach ($this->files as $file) {
            if (!$this->file_type || $this->file_type === '') {
                $this->addError('file_type', 'Debes seleccionar un tipo de soporte.');
                return;
            }

            $extension = $file->getClientOriginalExtension();

            $newFileName = 'N_' . $this->file_type . '_' . Str::random(8) . "." . $extension;

            $tempPath = $file->storeAs('temp_support_files', $newFileName);

            $this->tempFiles[] = [
                'fileName' => $newFileName,
                'temp_path' => $tempPath
            ];
        }
        $this->reset('files');
    }

    public function removeTempFiles(int $index): void
    {
        if (isset($this->tempFiles[$index])) {
            $path = $this->tempFiles[$index]['temp_path'];

            if ($path && Storage::exists($path)) {
                Storage::delete($path);
            }

            unset($this->tempFiles[$index]);

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
    }
}
