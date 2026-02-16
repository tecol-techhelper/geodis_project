<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PowerComponents\LivewirePowerGrid\Concerns\SoftDeletes;

class ProductIdentifierRole extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'role_code',
        'role_name',
        'role_description'
    ];

    protected $casts = [
        'id' => 'integer',
        'role_code' => 'integer'
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
        return $this->hasMany(ItemProductIdentifier::class, 'product_identifier_role_id');
    }
}
