<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrderContact extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'segment_tag',
        'contact_name',
        'raw_segment',
        'purchase_order_id',
        'service_id',
        'contact_type_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'service_id' => 'integer',
        'purchase_order_id' => 'integer',
        'contact_type_id' => 'integer'
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
        'deleted_at'
    ];

    // Relation (as father)

    // 1-to-N with purchase_order_contact_details table
    public function purchase_order_contact_details(): HasMany
    {
        return $this->hasMany(PurchaseOrderContactDetail::class, 'purchase_order_contact_id', 'id');
    }

    // Relation (as son)

    // 1-to-1 with contact_types table
    public function contact_type(): BelongsTo
    {
        return $this->belongsTo(ContactType::class, 'contact_type_id', 'id');
    }

    // 1-to-N with purchase_order table
    public function purchase_order(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id', 'id');
    }
}
