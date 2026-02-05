<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PowerComponents\LivewirePowerGrid\Concerns\SoftDeletes;

class OrderReference extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'segment_tag',
        'order_reference_value',
        'raw_segment',
    ];

    protected $casts = [
        'id' => 'integer',
        'purchase_order_id' => 'integer',
        'reference_type_id' => 'integer'
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

    // 1-to-N with reference_type table
    public function reference_type(): BelongsTo
    {
        return $this->belongsTo(ReferenceType::class, 'reference_type_id');
    }
}
