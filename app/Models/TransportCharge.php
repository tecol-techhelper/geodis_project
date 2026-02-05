<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PowerComponents\LivewirePowerGrid\Concerns\SoftDeletes;

class TransportCharge extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'charge_code',
        'rate_class_code',
        'price_amount',
        'unit_price_basis',
        'measure_unit_code',
        'pri_segment_raw',
        'tcc_segment_raw',
        'pruchase_order_id',
        'price_qualifier_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'price_amount' => 'decimal:2',
        'unit_price_basis' => 'decimal:2',
        'purchase_order_id' => 'integer',
        'price_qualifier_id' => 'integer'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relation (as son)

    // 1-to-1 with purchase_order table
    public function purchase_order(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    // 1-to-N with price_qualifier table
    public function price_qualifier(): BelongsTo
    {
        return $this->belongsTo(PriceQualifier::class, 'price_qualifier_id');
    }
}
