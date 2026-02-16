<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PowerComponents\LivewirePowerGrid\Concerns\SoftDeletes;

class ItemProductIdentifier extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'segment_tag',
        'identifier_value',
        'raw_segment',
        'purchase_order_item_id',
        'product_identifier_role_id',
        'product_identifier_type_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'purchase_order_item_id' => 'integer',
        'product_identifier_role_id' => 'integer',
        'product_identifier_type_id' => 'integer'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relation (as son)

    // 1-to-1 with product_identifier_role table
    public function product_identifier_role(): BelongsTo
    {
        return $this->belongsTo(ProductIdentifierRole::class, 'product_identifier_role_id');
    }

    // 1-to-1 with product_identifier_type table
    public function product_identifier_type(): BelongsTo
    {
        return $this->belongsTo(ProductIdentifierType::class, 'product_identifier_type_id');
    }

    // 1-to-N with service table
    public function purchase_order_item(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrderItem::class, 'purchase_order_item_id');
    }
}
