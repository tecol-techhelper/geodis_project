<?php

namespace App\Services;

use App\Models\FileType;
use App\Models\Service;
use App\Models\SupportFile;
use App\Models\SupportFileCopy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class SupportFileDistributor
{
    public function __construct(
        private SupportDocumentNaming $naming,
        private SharePointUploader $sharePoint,
        private SupportFileSftpUploader $sftp,
    ) {}

    public function stage(Service $service, array $temp): SupportFile
    {
        // Every new original must cover all current identifiers, regardless of client metadata.
        $targets = $this->naming->targets($service->fresh());
        $fileType = FileType::query()->where('file_type', $temp['file_type'])->firstOrFail();
        $source = 'support_sources/'.Str::uuid().'.'.$temp['extension'];
        if (! Storage::disk('local')->copy($temp['temp_path'], $source)) {
            throw new RuntimeException('No se pudo conservar el archivo para generar sus copias.');
        }

        try {
            return Cache::lock('support-file-name-allocation', 30)->block(5, function () use ($service, $temp, $targets, $fileType, $source) {
                return DB::transaction(function () use ($service, $temp, $targets, $fileType, $source) {
                    $support = SupportFile::create([
                        'file_name' => Str::limit(pathinfo($temp['fileName'], PATHINFO_FILENAME), 64, ''),
                        'original_name' => $temp['fileName'],
                        'file_extension' => $temp['extension'],
                        'file_size' => Storage::disk('local')->size($source),
                        'uploaded_at' => now()->toDateString(),
                        'service_id' => $service->id,
                        'user_id' => Auth::id(),
                        'file_type_id' => $fileType->id,
                        'local_path' => $source,
                        'observations' => $temp['observations'],
                        'uploaded_sftp' => false,
                    ]);
                    $folder = preg_replace('/[^A-Za-z0-9_-]/', '_', (string) $service->consecutive) ?: (string) $service->id;
                    $directory = 'DOCS/'.now()->format('Y/m').'/'.$folder;
                    $originalStem = Str::limit(trim(preg_replace('/[^A-Za-z0-9_-]/', '_', Str::ascii(pathinfo($temp['fileName'], PATHINFO_FILENAME))), '_'), 150, '');
                    $sharepointPath = $directory.'/SUP-'.$support->id.'-'.($originalStem ?: 'soporte').'.'.$temp['extension'];
                    foreach ($targets as $target) {
                        $sequence = 1;
                        $suffix = $this->naming->observations($service, $temp['observations'], $target['sho']);
                        do {
                            $name = $this->naming->filename($target['identifier'], $temp['file_type'], $suffix, $temp['extension'], $sequence++);
                        } while (SupportFileCopy::where('file_name', $name)->exists()
                            || SupportFile::withTrashed()->where('file_name', pathinfo($name, PATHINFO_FILENAME))->where('file_extension', $temp['extension'])->exists());

                        $support->copies()->create([
                            'identifier' => $target['identifier'],
                            'cni' => $target['cni'],
                            'file_name' => $name,
                            'associations' => $target['associations'],
                            'sharepoint_path' => $sharepointPath,
                        ]);
                    }

                    return $support;
                });
            });
        } catch (\Throwable $exception) {
            Storage::disk('local')->delete($source);
            throw $exception;
        }
    }

    public function distribute(SupportFile $support): bool
    {
        return Cache::lock('support-file:'.$support->id, 600)->block(1, function () use ($support) {
            $support->refresh();
            if ($support->trashed() || $support->deletion_started_at) {
                throw new RuntimeException('Este soporte está eliminado o tiene una eliminación pendiente.');
            }
            $copies = $support->copies()->orderBy('id')->get();
            if ($copies->isEmpty()) {
                throw new RuntimeException('El soporte no tiene copias para procesar.');
            }
            if ($copies->contains(fn ($copy) => ! $copy->isComplete())) {
                if (! $support->local_path || ! str_starts_with($support->local_path, 'support_sources/')
                    || ! Storage::disk('local')->exists($support->local_path)) {
                    throw new RuntimeException('No está disponible el archivo original para reintentar las copias pendientes.');
                }
                $path = Storage::disk('local')->path($support->local_path);
                try {
                    // One internal document per original, independent of the SFTP copies.
                    // Reuse any existing upload from groups created before this rule changed.
                    $sharedUrl = $support->file_url ?: $copies->first(fn ($copy) => $copy->file_url !== null)?->file_url;
                    if (! $sharedUrl) {
                        $response = $this->sharePoint->upload($path, $copies->first()->sharepoint_path);
                        $sharedUrl = $response['webUrl'] ?? null;
                        if (! $sharedUrl) {
                            throw new RuntimeException('SharePoint no devolvió un enlace para el soporte.');
                        }
                    }
                    $support->update(['file_url' => $sharedUrl]);
                } catch (\Throwable $exception) {
                    $support->copies()->whereNull('sftp_uploaded_at')->update(['error_message' => $exception->getMessage()]);
                    $support->update(['sftp_error' => 'No se completó el guardado del soporte.']);
                    Log::warning('SupportFileDistributor:sharepoint_failed', ['support_file_id' => $support->id, 'message' => $exception->getMessage()]);

                    return false;
                }
                foreach ($copies as $copy) {
                    if ($copy->isComplete()) {
                        continue;
                    }
                    try {
                        if (! $copy->sharepoint_uploaded_at) {
                            $copy->update(['file_url' => $sharedUrl, 'sharepoint_uploaded_at' => now(), 'error_message' => null]);
                        }
                        if (! $copy->sftp_uploaded_at) {
                            [$uploaded, $error] = $this->sftp->upload('DOCS', $copy->file_name, 'DOCS/'.$copy->file_name, $path, $support->service_id);
                            if (! $uploaded) {
                                throw new RuntimeException($error ?: 'No se confirmó la subida al SFTP.');
                            }
                            $copy->update(['sftp_uploaded_at' => now(), 'error_message' => null]);
                        }
                    } catch (\Throwable $exception) {
                        $copy->update(['error_message' => $exception->getMessage()]);
                        Log::warning('SupportFileDistributor:copy_failed', ['support_file_id' => $support->id, 'copy_id' => $copy->id, 'message' => $exception->getMessage()]);
                    }
                }
            }

            $complete = $copies->every(fn ($copy) => $copy->isComplete());
            $support->update([
                'uploaded_sftp' => $complete,
                'sftp_error' => $complete ? null : 'No se completó el guardado del soporte.',
            ]);
            if ($complete && $support->local_path && Storage::disk('local')->delete($support->local_path)) {
                $support->update(['local_path' => null]);
            }

            return $complete;
        });
    }

    public function deleteGroup(SupportFile $support): void
    {
        Cache::lock('support-file:'.$support->id, 600)->block(1, function () use ($support) {
            $support->refresh();
            if ($support->trashed()) {
                return;
            }
            $support->update(['deletion_started_at' => $support->deletion_started_at ?? now()]);
            $copies = $support->copies()->orderBy('id')->get();
            // Include the parent URL in case the process stopped before linking the copies.
            $urls = $copies->pluck('file_url')->push($support->file_url)->filter()->unique();
            foreach ($urls as $url) {
                $linked = $copies->where('file_url', $url);
                if ($linked->isNotEmpty() && $linked->every(fn ($copy) => $copy->sharepoint_deleted_at !== null)) {
                    continue;
                }
                $this->sharePoint->delete($url);
                $support->copies()->where('file_url', $url)->update(['sharepoint_deleted_at' => now()]);
            }
            if ($support->local_path && ! Storage::disk('local')->delete($support->local_path)) {
                throw new RuntimeException('No se pudo retirar el archivo original local. Reintente la eliminación.');
            }
            $support->delete();
        });
    }
}
