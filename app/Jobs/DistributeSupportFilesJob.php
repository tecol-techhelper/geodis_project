<?php

namespace App\Jobs;

use App\Models\SupportFile;
use App\Services\SupportFileDistributor;
use DateTimeInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class DistributeSupportFilesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 300;

    public int $tries = 0;

    public int $maxExceptions = 3;

    public function __construct(public int $supportFileId, public string $token)
    {
        $this->onConnection('support_uploads');
        $this->onQueue('support-files');
        $this->afterCommit();
    }

    public function retryUntil(): DateTimeInterface
    {
        return now()->addMinutes(30);
    }

    public function backoff(): array
    {
        return [15, 60, 180];
    }

    public function handle(SupportFileDistributor $distributor): void
    {
        $support = SupportFile::find($this->supportFileId);
        if (! $support || $support->deletion_started_at || $support->processing_token !== $this->token
            || $support->processing_status === 'completed') {
            return;
        }
        try {
            if ($distributor->distribute($support, 3, $this->token)) {
                return;
            }
            $support->refresh();
            if ($support->processing_token !== $this->token || $support->deletion_started_at) {
                return;
            }
            if ($support->copies()->whereNull('sftp_uploaded_at')->whereNotNull('error_message')->exists()) {
                throw new RuntimeException('No se completó el envío de algunas copias del soporte.');
            }
            // A new job per successful batch resets the exception budget for the next batch.
            Bus::dispatch((new self($this->supportFileId, $this->token))->delay(1));
        } catch (LockTimeoutException $exception) {
            Log::info('SupportUpload:waiting_for_lock', ['support_file_id' => $support->id, 'exception_class' => get_class($exception)]);
            $support->update(['processing_status' => 'queued', 'processing_heartbeat_at' => now()]);
            $this->release(30);
        } catch (Throwable $exception) {
            $support->update(['processing_status' => 'queued', 'processing_heartbeat_at' => now()]);
            Log::warning('SupportUpload:attempt_failed', ['support_file_id' => $support->id, 'exception_class' => get_class($exception), 'message' => $exception->getMessage() ?: 'Excepción sin mensaje']);
            throw $exception;
        }
    }

    public function failed(?Throwable $exception): void
    {
        SupportFile::whereKey($this->supportFileId)->where('processing_token', $this->token)
            ->whereIn('processing_status', ['queued', 'processing'])
            ->update(['processing_status' => 'failed', 'processing_heartbeat_at' => now(), 'sftp_error' => 'El envío no se completó. Puede reintentarlo desde Cargados.']);
        Log::error('SupportUpload:failed', ['support_file_id' => $this->supportFileId, 'exception_class' => $exception ? get_class($exception) : null, 'message' => $exception?->getMessage() ?: 'Trabajo interrumpido o tiempo agotado']);
    }
}
