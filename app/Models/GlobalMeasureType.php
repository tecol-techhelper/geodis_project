<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GlobalMeasureType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type_qualifier',
        'type_description'
    ];

    protected $casts = [
        'id' => 'integer',
        'type_qualifier' => 'integer'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relation (as father)

    // 1-to-1 with service_measurements table
    public function service_measurement(): HasMany
    {
        return $this->hasMany(ServiceMeasurement::class, 'global_measure_type_id');
    }
}
