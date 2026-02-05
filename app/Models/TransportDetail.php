<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PowerComponents\LivewirePowerGrid\Concerns\SoftDeletes;

class TransportDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'segment_tag',
        'vehicule_details',
        'raw_segment',
        'service_id',
        'transport_stage_id',
        'transport_mode_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'service_id' => 'integer',
        'transport_stage_id' => 'integer',
        'transport_mode_id' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relation (as son)

    // 1-to-1 with transport_mode table
    public function transport_mode(): BelongsTo
    {
        return $this->belongsTo(TransportMode::class, 'transport_mode_id');
    }

    // 1-to-N with transport_stage table
    public function transport_stage(): BelongsTo
    {
        return $this->belongsTo(TransportStage::class, 'transport_stage_id');
    }

    // 1-to-N with service table
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
