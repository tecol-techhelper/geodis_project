<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'segment_tag',
        'raw_segment',
        'contract_carriage_condition_code',
        'po_requirements_code',
        'service_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'purchase_order_secuence' => 'integer',
        'service_id' => 'integer'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relation (as father)


    // 1-to-N service_measurements table
    public function purchase_order_measurements(): HasMany
    {
        return $this->hasMany(PurchaseOrderMeasurement::class, 'purchase_order_id');
    }

    // 1-to-N parties table
    public function parties(): HasMany
    {
        return $this->hasMany(PurchaseOrderParty::class, 'purchase_order_id');
    }

    // 1-to-N order_references table
    public function order_references(): HasMany
    {
        return $this->hasMany(OrderReference::class, 'purchase_order_id');
    }

    // 1-to-N delivery_terms table
    public function delivery_terms(): HasMany
    {
        return $this->hasMany(DeliveryTerm::class, 'purchase_order_id');
    }

    // 1-to-N delivery_terms table
    public function purchase_order_requirements(): HasMany
    {
        return $this->hasMany(PurchaseOrderRequirements::class, 'purchase_order_id');
    }

    // 1-to-N delivery_terms table
    public function transport_charges(): HasMany
    {
        return $this->hasMany(TransportCharge::class, 'purchase_order_id');
    }

    // 1-to-N purchase_order_items table
    public function purchase_order_items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class, 'purchase_order_id');
    }


    // 1-to-N purchase_order_items table
    public function purchase_order_notes(): HasMany
    {
        return $this->hasMany(PurchaseOrderNote::class, 'purchase_order_id');
    }

    // 1-to-N purchase_order_items table
    public function purchase_order_contacts(): HasMany
    {
        return $this->hasMany(PurchaseOrderContact::class, 'purchase_order_id');
    }

    // Relations (as son)

    // 1-to-N with service
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
