<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PowerComponents\LivewirePowerGrid\Concerns\SoftDeletes;

class EquipmentType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'equipment_type_code',
        'equipment_type_name',
        'equipment_type_description',
    ];

    protected $casts = [
        'id' => 'integer'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relation(as father) 1-to-1 with service_equipment table
    public function service_equipment(): HasMany
    {
        return $this->hasMany(ServiceEquipment::class, 'equipment_type_id');
    }
}
