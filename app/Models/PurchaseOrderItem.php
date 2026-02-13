<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrderItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'segment_tag',
        'item_number',
        'item_count',
        'raw_segment',
        'purchase_order_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'item_number' => 'integer',
        'item_count' => 'integer',
        'purchase_order_id' => 'integer',

    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    //Relation (as father)

    // 1-to-N with item_notes table
    public function item_notes(): HasMany
    {
        return $this->hasMany(ItemNote::class, 'purchase_order_item_id');
    }

    // 1-to-N with item_measures table
    public function item_measures(): HasMany
    {
        return $this->hasMany(ItemMeasure::class, 'purchase_order_item_id');
    }

    // 1-to-N with item_measures table
    public function item_dimensions(): HasMany
    {
        return $this->hasMany(ItemDimension::class, 'purchase_order_item_id');
    }

    // 1-to-N with item_container_identifiers table
    public function item_container_identifiers(): HasMany
    {
        return $this->hasMany(ItemContainerIdentifier::class, 'purchase_order_item_id');
    }

    // 1-to-N with item_unit_identifier table
    public function item_unit_identifiers(): HasMany
    {
        return $this->hasMany(ItemUnitIdentifier::class, 'purchase_order_item_id');
    }

    // 1-to-N with item_unit_identifier table
    public function item_product_identifiers(): HasMany
    {
        return $this->hasMany(ItemProductIdentifier::class, 'purchase_order_item_id');
    }

    //Relation (as son)

    // 1-to-N with service
    public function purchase_order(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }
}
