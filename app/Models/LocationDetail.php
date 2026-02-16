<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PowerComponents\LivewirePowerGrid\Concerns\SoftDeletes;

class LocationDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'segment_tag',
        'raw_segment',
        'location_details',
        'location_code_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'location_code_id' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relation (as son)

    // 1-to-1 with location_code table
    public function location_code(): BelongsTo
    {
        return $this->belongsTo(LocationCode::class, 'location_code_id');
    }

    // 1-to-N with service table
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
