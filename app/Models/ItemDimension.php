<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PowerComponents\LivewirePowerGrid\Concerns\SoftDeletes;

class ItemDimension extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'segment_tag',
        'dimension_unit',
        'length',
        'width',
        'heigth',
        'raw_segment',
        'purchase_order_item_id',
        'dimension_type_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'heigth' => 'decimal:2',
        'purchase_order_item_id' => 'integer',
        'dimension_type_id' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    // Relation (as son)

    // 1-to-N with dimension_type table
    public function dimension_type(): BelongsTo
    {
        return $this->belongsTo(DimensionType::class, 'dimension_type_id');
    }

    // 1-to-N with purchase_order_item table
    public function purchase_order_item(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrderItem::class, 'purchase_order_item_id');
    }
}
