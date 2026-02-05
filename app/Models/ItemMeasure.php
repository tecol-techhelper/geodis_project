<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PowerComponents\LivewirePowerGrid\Concerns\SoftDeletes;

class ItemMeasure extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'segment_tag',
        'measure_value',
        'measure_unit',
        'raw_segment',
        'purchase_order_item_id',
        'measurement_purpose_code_id',
        'measurement_attribute_code_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'purchase_order_item_id' => 'integer',
        'measurement_purpose_code_id' => 'integer',
        'measurement_attribute_code_id' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // 1-to-1 with measurement_attribute_code table
    public function measurement_attribute_code(): BelongsTo
    {
        return $this->belongsTo(MeasurementAttributeCode::class, 'measurement_attribute_code_id');
    }

    // 1-to-N with measurement_purpose_code table
    public function measurement_purpose_code(): BelongsTo
    {
        return $this->belongsTo(MeasurementPurposeCode::class, 'measurement_purpose_code_id');
    }

    // 1-to-N with purchase_order_item table
    public function purchase_order_item(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrderItem::class, 'purchase_order_item_id');
    }
}
