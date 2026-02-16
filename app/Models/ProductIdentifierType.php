<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PowerComponents\LivewirePowerGrid\Concerns\SoftDeletes;

class ProductIdentifierType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'identifier_type_code',
        'identifier_type_name',
        'identifier_type_description'
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

    // 1-to-1 with item_product_identifier table
    public function item_product_identifier(): HasMany
    {
        return $this->hasMany(ItemProductIdentifier::class, 'product_identifier_type_id');
    }
}
