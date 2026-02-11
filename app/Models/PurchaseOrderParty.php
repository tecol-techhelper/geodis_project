<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrderParty extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'segment_tag',
        'party_name',
        'party_street',
        'party_city',
        'party_region',
        'party_country_code',
        'raw_segment',
        'purchase_order_id',
        'party_type_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'purchase_order_id' => 'integer',
        'party_type_id' => 'integer'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relation (as son)

    // 1-to-1 with party_type table
    public function party_type(): BelongsTo
    {
        return $this->belongsTo(PartyType::class, 'party_type_id');
    }

    // 1-to-N with party_type table
    public function purchase_order(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }
}
