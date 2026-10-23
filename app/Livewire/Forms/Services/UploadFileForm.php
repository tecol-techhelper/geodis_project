<?php

namespace App\Livewire\Forms\Services;

use App\Models\Service;
use App\Models\User;
use App\Services\SharePointUploader;
use App\Services\SupportDocumentNaming;
use App\Services\SupportFileDistributor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\WithFileUploads;

class UploadFileForm extends Form
{
    use WithFileUploads;

    private const ALLOWED_FILE_TYPES = SupportDocumentNaming::TYPES;

    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp', 'mp4', 'mov', 'avi', 'wmv', 'pdf', 'doc', 'docx', 'xls', 'xlsx'];

    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'video/mp4',
        'video/quicktime',
        'video/x-msvideo',
        'video/x-ms-wmv',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/zip',
        'application/octet-stream',
    ];

    #[Validate([
        'files' => 'array|nullable',
        'files.*' => 'file|max:20480|mimes:jpg,jpeg,png,webp,mp4,mov,avi,wmv,pdf,doc,docx,xls,xlsx',
    ], onUpdate: false)]
    public array $files = [];

    #[Locked]
    public array $tempFiles = [];

    #[Validate('string|in:CLI,CLP,IC,IF,RT,ID,TRC,TDC,GABF301,PDR,GPS,RP', onUpdate: false)]
    public ?string $file_type = null;

    public ?string $free_text = null;

    #[Locked]
    public ?int $service_id = null;

    private function canManageSupports(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        $username = strtolower(trim((string) ($user->username ?? '')));
        if ($username === 'administrador') {
            return true;
        }

        return method_exists($user, 'hasRole') && $user->hasRole('admin');
    }

    public function uploadFiles(): void
    {
        abort_unless($this->canManageSupports(), 403);
        $this->resetValidation();
        $this->validate([
            'files' => ['required', 'array', 'min:1'],
            'file_type' => ['required', 'in:'.implode(',', self::ALLOWED_FILE_TYPES)],
            'free_text' => ['nullable', 'string', 'max:255'],
        ]);
        $naming = app(SupportDocumentNaming::class);
        $service = Service::findOrFail($this->service_id);
        try {
            $targets = $naming->targets($service);
            $observations = $naming->normalizeObservations($this->free_text);
            $prepared = [];
            foreach ($this->files as $file) {
                if ($error = $this->fileValidationError($file)) {
                    $this->addError('files', $error);

                    return;
                }
                $extension = strtolower($file->getClientOriginalExtension());
                $names = [];
                foreach ($targets as $target) {
                    $suffix = $naming->observations($service, $observations, $target['sho']);
                    $names[] = $naming->filename($target['identifier'], $this->file_type, $suffix, $extension);
                }
                $originalName = basename(str_replace('\\', '/', $file->getClientOriginalName()));
                if (mb_strlen($originalName) > 255) {
                    $this->addError('files', 'El nombre original del archivo no puede superar 255 caracteres.');

                    return;
                }
                $prepared[] = ['file' => $file, 'extension' => $extension, 'names' => $names, 'original' => $originalName];
            }
        } catch (ValidationException $exception) {
            foreach ($exception->errors() as $field => $messages) {
                $this->addError($field, $messages[0]);
            }

            return;
        }
        foreach ($prepared as $item) {
            $tempPath = $item['file']->storeAs('temp_support_files', Str::uuid().'.'.$item['extension'], 'local');
            $this->tempFiles[] = [
                'fileName' => $item['original'],
                'temp_path' => $tempPath,
                'extension' => $item['extension'],
                'file_type' => $this->file_type,
                'observations' => $observations,
                'identifiers' => array_column($targets, 'identifier'),
                'names' => $item['names'],
            ];
        }
        $this->files = [];
    }

    public function removeTempFiles(int $index): void
    {
        abort_unless($this->canManageSupports(), 403);
        if (! isset($this->tempFiles[$index])) {
            return;
        }
        $path = $this->tempFiles[$index]['temp_path'];
        if ($this->isTempPathSafe($path)) {
            Storage::disk('local')->delete($path);
        }
        unset($this->tempFiles[$index]);
        $this->tempFiles = array_values($this->tempFiles);
    }

    public function clearTempFiles(): void
    {
        abort_unless($this->canManageSupports(), 403);
        foreach (array_keys($this->tempFiles) as $index) {
            // Keep other users' Livewire temporary files intact.
            $path = $this->tempFiles[$index]['temp_path'];
            if ($this->isTempPathSafe($path)) {
                Storage::disk('local')->delete($path);
            }
        }
        $this->tempFiles = [];
    }

    public function saveFiles(SupportFileDistributor $distributor): bool
    {
        abort_unless($this->canManageSupports(), 403);
        $this->resetValidation();
        if (! $this->ensureUploadIsNotRateLimited()) {
            return false;
        }
        if ($this->tempFiles === []) {
            $this->addError('files', 'No hay archivos pendientes de guardar.');

            return false;
        }
        $service = Service::findOrFail($this->service_id);
        $naming = app(SupportDocumentNaming::class);
        $allComplete = true;
        foreach ($this->tempFiles as $index => $temp) {
            try {
                if (! $this->isTempPathSafe($temp['temp_path'])) {
                    throw new \RuntimeException('El archivo temporal no está disponible. Vuelva a seleccionarlo.');
                }
                if (Storage::disk('local')->size($temp['temp_path']) > 20 * 1024 * 1024
                    || ! $this->isAllowedExtension($temp['extension'])) {
                    throw new \RuntimeException('El archivo no cumple las restricciones de formato o tamaño.');
                }
                // Re-resolve against the database before creating any remote copies.
                $targets = $naming->targets($service->fresh());
                foreach ($targets as $target) {
                    $suffix = $naming->observations($service, $temp['observations'], $target['sho']);
                    $naming->filename($target['identifier'], $temp['file_type'], $suffix, $temp['extension']);
                }
                $support = $distributor->stage($service, $temp);
                unset($this->tempFiles[$index]);
                Storage::disk('local')->delete($temp['temp_path']);
                $distributor->enqueue($support);
            } catch (ValidationException $exception) {
                foreach ($exception->errors() as $field => $messages) {
                    $this->addError($field, $messages[0]);
                }
                $allComplete = false;
            } catch (\Throwable $exception) {
                Log::warning('UploadFileForm:distribution_failed', ['service_id' => $this->service_id, 'exception_class' => get_class($exception), 'message' => $exception->getMessage() ?: 'Excepción sin mensaje']);
                $this->addError('files', 'No se completó el guardado. Revise los archivos pendientes y los estados en Cargados.');
                $allComplete = false;
            }
        }
        $this->tempFiles = array_values($this->tempFiles);
        if ($allComplete) {
            flash()->title('Soportes en espera')->info('El envío continuará en segundo plano. Consulte el progreso en Cargados.');
        }

        return $allComplete;
    }

    public function retrySupportFile(int $id, Service $service, SupportFileDistributor $distributor): void
    {
        abort_unless($this->canManageSupports(), 403);
        $this->resetValidation('supportRetry');
        if (! $this->ensureUploadIsNotRateLimited()) {
            return;
        }
        $support = $service->support_files()->findOrFail($id);
        try {
            $distributor->enqueue($support);
        } catch (\Throwable $exception) {
            Log::warning('UploadFileForm:retry_failed', ['support_file_id' => $id, 'exception_class' => get_class($exception), 'message' => $exception->getMessage() ?: 'Excepción sin mensaje']);
            $this->addError('supportRetry', 'No se pudo programar el reintento. Verifique que el archivo original siga disponible y revise el registro de errores.');
        }
    }

    public function deleteSupportFile(int $supportFileId, Service $service, SharePointUploader $sharePointUploader): void
    {
        abort_unless($this->canManageSupports(), 403);
        $this->resetErrorBag('supportDeletion');
        $file = $service->support_files()->findOrFail($supportFileId);

        try {
            if ($file->getRawOriginal('original_name') !== null) {
                app(SupportFileDistributor::class)->deleteGroup($file);

                return;
            }
            if (blank($file->file_url)) {
                throw new \RuntimeException('El soporte no tiene un enlace de SharePoint.');
            }

            $sharePointUploader->delete($file->file_url);
            if (! $file->delete()) {
                throw new \RuntimeException('No se pudo eliminar el registro del soporte.');
            }
        } catch (\Throwable $e) {
            Log::error('UploadFileForm@deleteSupportFile:error', [
                'service_id' => $service->id,
                'support_file_id' => $supportFileId,
                'message' => $e->getMessage(),
            ]);
            $this->addError('supportDeletion', 'No se pudo completar la eliminación del soporte. Inténtelo nuevamente; si persiste, revise el registro de errores.');

            return;
        }

        flash()->title('Soporte eliminado')->success('El soporte fue eliminado de SharePoint y del listado.');
    }

    private function isAllowedExtension(string $extension): bool
    {
        return in_array($extension, self::ALLOWED_EXTENSIONS, true);
    }

    private function isAllowedMimeType(string $mimeType): bool
    {
        return in_array(strtolower($mimeType), self::ALLOWED_MIME_TYPES, true);
    }

    private function fileValidationError($file): ?string
    {
        if (! is_object($file) || ! method_exists($file, 'getClientOriginalExtension')) {
            return 'El archivo seleccionado no es válido.';
        }

        $extension = strtolower((string) $file->getClientOriginalExtension());
        if (! $this->isAllowedExtension($extension)) {
            return "Extensión no permitida: .{$extension}.";
        }

        $size = method_exists($file, 'getSize') ? (int) $file->getSize() : 0;
        if ($size > 20 * 1024 * 1024) {
            return 'El archivo supera el tamaño máximo permitido de 20 MB.';
        }

        $mimeType = method_exists($file, 'getMimeType') ? (string) $file->getMimeType() : '';
        if ($mimeType !== '' && ! $this->isAllowedMimeType($mimeType)) {
            return "Tipo de archivo no permitido: {$mimeType}.";
        }

        return null;
    }

    private function isTempPathSafe(string $tempPath): bool
    {
        $baseDir = Storage::disk('local')->path('temp_support_files');
        $fullPath = Storage::disk('local')->path($tempPath);

        $baseReal = realpath($baseDir);
        $fullReal = realpath($fullPath);

        if (! $baseReal || ! $fullReal) {
            return false;
        }

        return str_starts_with($fullReal, $baseReal.DIRECTORY_SEPARATOR);
    }

    private function ensureUploadIsNotRateLimited(): bool
    {
        $key = 'support-files:'.(Auth::id() ?? 'guest').'|'.request()->ip();
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, 30)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($key);
            $this->addError('files', "Demasiadas cargas. Intenta de nuevo en {$seconds} segundos.");

            return false;
        }
        \Illuminate\Support\Facades\RateLimiter::hit($key, 60);

        return true;
    }
}
