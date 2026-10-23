<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupportFile extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'file_name',
        'file_url',
        'file_size',
        'file_extension',
        'uploaded_at',
        'service_id',
        'user_id',
        'uploaded_sftp',
        'sftp_error',
        'file_type_id',
        'purchase_order_id',
        'purchase_order_number',
        'order_reference_id',
        'order_reference_value',
        'local_path',
        'original_name',
        'observations',
        'deletion_started_at',
        'processing_status',
        'processing_token',
        'processing_heartbeat_at',
    ];

    protected $casts = ['deletion_started_at' => 'datetime', 'processing_heartbeat_at' => 'datetime'];

    public function copies(): HasMany
    {
        return $this->hasMany(SupportFileCopy::class);
    }

    public function copyProgress(): string
    {
        if ($this->deletion_started_at) {
            return 'Eliminación pendiente';
        }
        [$complete, $total] = $this->copyCounts();
        if ($total === 0 || $complete === $total) {
            return 'Guardado';
        }

        return match ($this->processing_status) {
            'queued' => $this->distributionIsActive() ? 'En espera' : 'Requiere reintento',
            'processing' => $this->distributionIsActive() ? 'Procesando' : 'Requiere reintento',
            'failed' => 'Requiere reintento',
            default => 'Pendiente de guardar',
        };
    }

    public function copyCounts(): array
    {
        if (array_key_exists('completed_copies_count', $this->getAttributes())) {
            return [(int) $this->completed_copies_count, (int) $this->copies_count];
        }

        return [$this->copies->filter(fn ($copy) => $copy->isComplete())->count(), $this->copies->count()];
    }

    public function distributionIsActive(): bool
    {
        return in_array($this->processing_status, ['queued', 'processing'], true)
            && $this->processing_heartbeat_at?->gt(now()->subMinutes(10));
    }

    // Relation(as son)

    // 1-to-1 with file_type table
    public function file_type(): BelongsTo
    {
        return $this->belongsTo(FileType::class, 'file_type_id');
    }

    // 1-to-N with services table
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function purchase_order(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    public function order_reference(): BelongsTo
    {
        return $this->belongsTo(OrderReference::class, 'order_reference_id');
    }
}
