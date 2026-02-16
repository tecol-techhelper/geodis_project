<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PowerComponents\LivewirePowerGrid\Concerns\SoftDeletes;

class LocationCode extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'location_code',
        'location_code_name',
        'location_code_description',
    ];

    protected $casts = [
        'id' => 'integer',
        'location_code' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relation (as father)

    // 1-to-1 with transport_detail table
    public function transport_detail(): HasMany
    {
        return $this->hasMany(LocationDetail::class, 'location_code_id');
    }
}
