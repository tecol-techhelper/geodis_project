<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PowerComponents\LivewirePowerGrid\Concerns\SoftDeletes;

class DeliveryTermCatalog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'term_code',
        'term_name',
        'term_description',
    ];

    protected $casts = [
        'id' => 'integer'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relation(as father) 1-to-1 with order_reference table
    public function delivery_term(): HasMany
    {
        return $this->hasMany(DeliveryTerm::class, 'delivery_term_catalog_id');
    }
}
