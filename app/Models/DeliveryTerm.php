<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PowerComponents\LivewirePowerGrid\Concerns\SoftDeletes;

class DeliveryTerm extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'segment_tag',
        'freight_payment_code',
        'delivery_term_function',
        'raw_segment',
        'purchase_order_id',
        'delivery_term_catalog_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'delivery_term_function_id' => 'integer',
        'delivery_term_catalog_id' => 'integer',
        'purchase_order_id' => 'integer'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relation (as son)

    // 1-to-1 with delivery_term_catalog table
    public function delivery_term_catalog(): BelongsTo
    {
        return $this->belongsTo(DeliveryTermCatalog::class, 'delivery_term_catalog_id');
    }

    // 1-to-N with purchase_order table
    public function purchase_order(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }
}
