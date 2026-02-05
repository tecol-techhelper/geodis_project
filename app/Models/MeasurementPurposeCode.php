<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PowerComponents\LivewirePowerGrid\Concerns\SoftDeletes;

class MeasurementPurposeCode extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'measuremente_purpose_code',
        'measuremente_purpose_name',
        'measuremente_purpose_description',
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

    // 1-to-1 with item_measure table
    public function item_measure(): HasMany
    {
        return $this->hasMany(ItemMeasure::class, 'measuremente_purpose_code_id');
    }
}
