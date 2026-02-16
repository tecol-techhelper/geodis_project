<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PowerComponents\LivewirePowerGrid\Concerns\SoftDeletes;

class ItemContainerIdentifier extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'segment_tag',
        'identifier_qualifier',
        'identifier_value',
        'raw_segment',
        'purchase_order_item_id',
        'identifier_type_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'purchase_order_item_id' => 'integer',
        'identifier_type_id' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relation (as son)

    // 1-to-N with party_type table
    public function purchase_order(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrderItem::class, 'purchase_order_item_id');
    }

    // 1-to-N with party_type table
    public function identifier_type(): BelongsTo
    {
        return $this->belongsTo(IdentifierType::class, 'identifier_type_id');
    }
}
