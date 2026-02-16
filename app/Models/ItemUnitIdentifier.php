<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PowerComponents\LivewirePowerGrid\Concerns\SoftDeletes;

class ItemUnitIdentifier extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'segment_tag',
        'unit_identifier_type',
        'identifier_value_from',
        'identifier_value_to',
        'raw_segment',
        'purchase_order_item_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'purchase_order_item_id' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relation (as son)

    // 1-to-N with purchase_order_item table
    public function purchase_order_item(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrderItem::class, 'purchase_order_item_id');
    }
}
