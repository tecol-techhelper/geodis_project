<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EdifactFile extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'transmission_id',
        'message_type',
        'direction',
        'status',
        'file_name',
        'purchase_order',
        'received_at',
        'sent_at',
        'file_url',
        'file_path',
        'remote_disk',
        'remote_path',
        'uploaded_at',
        'attempts',
        'last_attempt_at',
        'error_message',
        'payload_hash',
        'service_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'service_id' => 'integer',
        'received_at' => 'datetime',
        'sent_at' => 'datetime',
        'uploaded_at' => 'datetime',
        'last_attempt_at' => 'datetime',
        'attempts' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Enums “de facto” (alineados a tu tabla)
    public const DIRECTION_IN  = 'IN';
    public const DIRECTION_OUT = 'OUT';

    public const TYPE_IFTSTA = 'IFTSTA';
    public const TYPE_IFCSUM = 'IFCSUM';

    public const STATUS_RECEIVED  = 'RECEIVED';
    public const STATUS_PENDING   = 'PENDING';
    public const STATUS_PROCESSED = 'PROCESSED';
    public const STATUS_SENT      = 'SENT';
    public const STATUS_FAILED    = 'FAILED';

    // Relation (as son) 1-to-N with services table
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
