<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PowerComponents\LivewirePowerGrid\Concerns\SoftDeletes;

class ReferenceType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reference_type_code',
        'reference_type_name',
        'reference_type_description',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    // Relation(as father) 1-to-1 with order_reference table
    public function order_reference(): HasMany
    {
        return $this->hasMany(OrderReference::class, 'reference_type_id');
    }
}
