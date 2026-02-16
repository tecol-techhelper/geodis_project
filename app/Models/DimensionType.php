<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PowerComponents\LivewirePowerGrid\Concerns\SoftDeletes;

class DimensionType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'dimension_type_code',
        'dimension_type_name',
        'dimension_type_description',
    ];

    protected $casts = [
        'id' => 'integer'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    // Relation (as father)

    // 1-to-1 with item_dimension table
    public function item_dimension(): HasMany
    {
        return $this->hasMany(ItemDimension::class, 'dimension_type_id');
    }
}
