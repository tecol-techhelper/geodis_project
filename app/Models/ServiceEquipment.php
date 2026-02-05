<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PowerComponents\LivewirePowerGrid\Concerns\SoftDeletes;

class ServiceEquipment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'segment_tag',
        'raw_segment',
        'equipment_identifier',
    ];

    protected $casts = [
        'id' => 'integer',
        'equipment_type_id' => 'integer',
        'service_id' => 'integer'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relation (as son)

    // 1-to-1 with equipment_type table
    public function equipment_type(): BelongsTo
    {
        return $this->belongsTo(EquipmentType::class, 'equipment_type_id');
    }

    // 1-to-N with service table
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
