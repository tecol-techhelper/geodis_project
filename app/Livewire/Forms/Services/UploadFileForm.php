<?php

namespace App\Livewire\Forms\Services;

use App\Core\InternalControllers\AuditController;
use App\Models\FileType;
use App\Models\Service;
use App\Models\SupportFile;
use App\Models\User;
use App\Services\SharePointUploader;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\WithFileUploads;

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
    public ?string $file_type = null;

    public ?string $free_text = null;
    public ?int $service_id = null;

    private function canManageSupports(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        return $user->hasRole('admin') || $user->hasRole('coord');
    }

    public function uploadFiles(): void
    {
        if (!$this->canManageSupports()) {
            return;
        }

        // $this->files siempre será array por la declaración, pero igual lo normalizamos.
        $incomingFiles = is_array($this->files) ? $this->files : array_filter([$this->files]);

        Log::info('UploadFileForm@uploadFiles:start', [
            'service_id'   => $this->service_id,
            'file_type'    => $this->file_type,
            'files_count'  => count($incomingFiles),
            'files_type'   => gettype($this->files),
        ]);

        if (count($incomingFiles) === 0) {
            $this->addError('form.files', 'Debe seleccionar al menos 1 archivo');
            Log::warning('UploadFileForm@uploadFiles:no_files');
            return;
        }

        if (!$this->file_type) {
            $this->addError('form.file_type', 'Debes seleccionar un tipo de soporte.');
            Log::warning('UploadFileForm@uploadFiles:no_file_type');
            return;
        }

        try {
            foreach ($incomingFiles as $file) {
                $extension = $file->getClientOriginalExtension();
                $freeText = trim((string) $this->free_text);
                $safeFreeText = $freeText !== '' ? preg_replace('/[^A-Za-z0-9_-]/', '_', $freeText) : 'SIN_TEXTO';

                $newFileName = 'N_' . $this->file_type . '_' . $safeFreeText . '_' . Str::random(8) . '.' . $extension;

                $tempPath = $file->storeAs('temp_support_files', $newFileName, 'local');

                $this->tempFiles[] = [
                    'fileName'   => $newFileName,
                    'temp_path'  => $tempPath,
                    'file_type'  => $this->file_type,
                ];

                flash()->title('Archivo Cargado')->info("Archivo <b>{$newFileName}</b> Cargado Correctamente!");
            }
        } catch (\Throwable $e) {
            Log::error('UploadFileForm@uploadFiles:error', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            $this->addError('form.files', 'Error al cargar archivos temporales.');
            return;
        }

        // Limpieza correcta del error bag
        $this->resetErrorBag('form.files');
        $this->files = [];

        Log::info('UploadFileForm@uploadFiles:done', [
            'temp_files_count' => count($this->tempFiles),
        ]);
    }

    public function removeTempFiles(int $index): void
    {
        if (!$this->canManageSupports()) {
            return;
        }

        if (!isset($this->tempFiles[$index])) {
            return;
        }

        $fileName = $this->tempFiles[$index]['fileName'];
        $path = $this->tempFiles[$index]['temp_path'];

        if ($path && Storage::disk('local')->exists($path)) {
            Storage::disk('local')->delete($path);
        }

        unset($this->tempFiles[$index]);
        $this->tempFiles = array_values($this->tempFiles);

        flash()->title('Archivo Eliminado!')->error("El archivo <b>{$fileName}</b> fue removido correctamente!");
    }

    public function clearTempFiles(): void
    {
        if (!$this->canManageSupports()) {
            return;
        }

        foreach ($this->tempFiles as $file) {
            if (!empty($file['temp_path']) && Storage::disk('local')->exists($file['temp_path'])) {
                Storage::disk('local')->delete($file['temp_path']);
            }
        }

        $this->tempFiles = [];
        \Illuminate\Support\Facades\File::cleanDirectory(storage_path('app/livewire-tmp'));
    }

    public function saveFiles(SharePointUploader $sharePointUploader): bool
    {
        if (!$this->canManageSupports()) {
            return false;
        }

        if (!$this->service_id) {
            $this->addError('form.files', 'No se encontro el servicio asociado para guardar los soportes.');
            return false;
        }

        if (count($this->tempFiles) === 0) {
            $this->addError('form.files', 'No hay archivos temporales para guardar.');
            return false;
        }

        $service = Service::query()->select(['id', 'consecutive'])->find($this->service_id);
        $serviceConsecutive = $service?->consecutive !== null ? (string) $service->consecutive : (string) $this->service_id;
        $serviceFolder = preg_replace('/[^A-Za-z0-9_-]/', '_', $serviceConsecutive) ?: (string) $this->service_id;

        $year = now()->format('Y');
        $month = now()->format('m');
        $remoteDir = "DOCS/{$year}/{$month}/{$serviceFolder}";

        try {
            $this->ensureRemoteDirectory($remoteDir);
        } catch (\Throwable $e) {
            $this->addError('form.files', "Error creando directorio remoto: {$e->getMessage()}");
            return false;
        }

        $successfulCount = 0;
        $totalCount = count($this->tempFiles);

        foreach ($this->tempFiles as $temp) {
            $localPath = Storage::disk('local')->path($temp['temp_path']);
            $extension = pathinfo($temp['fileName'], PATHINFO_EXTENSION);
            $remoteFileName = $temp['fileName'];
            $remotePath = "{$remoteDir}/{$remoteFileName}";

            if (!is_file($localPath)) {
                $this->addError('form.files', "No se encontro el archivo temporal: {$localPath}");
                continue;
            }

            try {
                $sharePointResponse = $sharePointUploader->upload($localPath, $remotePath);
                $sharePointUrl = $sharePointResponse['webUrl'] ?? null;
            } catch (\Throwable $e) {
                $this->addError('form.files', "Error en SharePoint: {$e->getMessage()}");
                continue;
            }

            [$uploaded, $sftpError] = $this->uploadToSftp($remoteDir, $remoteFileName, $remotePath, $localPath);

            if (!$uploaded) {
                $this->addError('form.files', "No se confirmo la subida del archivo {$remoteFileName} en el SFTP. {$sftpError}");
                continue;
            }

            try {
                $fileType = FileType::where('file_type', $temp['file_type'])->first();
                $fileTypeId = $fileType?->id;

                $supportFile = SupportFile::create([
                    'file_name'      => pathinfo($remoteFileName, PATHINFO_FILENAME),
                    'file_url'       => $sharePointUrl,
                    'file_size'      => filesize($localPath),
                    'file_extension' => $extension,
                    'uploaded_at'    => now()->toDateString(),
                    'service_id'     => $this->service_id,
                    'user_id'        => Auth::id(),
                    'file_type_id'   => $fileTypeId,
                    'uploaded_sftp'  => 1,
                    'sftp_error'     => null,
                ]);

                (new AuditController())->log(
                    model: $supportFile,
                    userId: Auth::id(),
                    username: Auth::user()?->username,
                    userRole: Auth::user()?->roles->first()?->rol_key,
                    action: 'CREATED'
                );

                Storage::disk('local')->delete($temp['temp_path']);
                $successfulCount++;
            } catch (\Throwable $e) {
                Log::error('Fallo al guardar en BD: ' . $e->getMessage(), [
                    'service_id' => $this->service_id,
                    'file'       => $remoteFileName,
                ]);
            }
        }

        if ($successfulCount === $totalCount) {
            flash()->title('Archivo Guardado')->success('Todos los Soportes han sido almacenados correctamente');
            $this->clearTempFiles();
            return true;
        }

        flash()->title('Carga Parcial')->warning("Se almacenaron correctamente {$successfulCount} de {$totalCount} archivos.");
        return false;
    }

    private function ensureRemoteDirectory(string $remoteDir): void
    {
        if (!Storage::disk('sftp_geodis')->exists($remoteDir)) {
            Storage::disk('sftp_geodis')->makeDirectory($remoteDir);
        }

        $attempts = 0;
        while ($attempts < 5 && !Storage::disk('sftp_geodis')->exists($remoteDir)) {
            usleep(300000 * ($attempts + 1));
            $attempts++;
        }

        if (!Storage::disk('sftp_geodis')->exists($remoteDir)) {
            throw new \RuntimeException("No se pudo confirmar la creacion del directorio remoto {$remoteDir} por latencia.");
        }
    }

    private function uploadToSftp(string $remoteDir, string $remoteFileName, string $remotePath, string $localPath): array
    {
        $attempts = 0;
        $lastError = null;

        while ($attempts < 5) {
            try {
                $result = Storage::disk('sftp_geodis')->putFileAs(
                    $remoteDir,
                    new \Illuminate\Http\File($localPath),
                    $remoteFileName
                );

                if ($result === false) {
                    throw new \RuntimeException('putFileAs retorno false');
                }

                $verifyTries = 0;
                while ($verifyTries < 5 && !Storage::disk('sftp_geodis')->exists($remotePath)) {
                    usleep(300000 * ($verifyTries + 1));
                    $verifyTries++;
                }

                if (!Storage::disk('sftp_geodis')->exists($remotePath)) {
                    Log::warning("Subida SFTP sin verificacion inmediata por latencia: {$remotePath}");
                }

                return [true, null];
            } catch (\Throwable $e) {
                $lastError = $e->getMessage();
                Log::warning("Reintento {$attempts} fallido para {$remoteFileName}: {$lastError}");
                usleep(500000 * ($attempts + 1));
                $attempts++;
            }
        }

        return [false, $lastError];
    }
}
