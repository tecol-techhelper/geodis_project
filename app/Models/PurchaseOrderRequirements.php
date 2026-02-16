<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PowerComponents\LivewirePowerGrid\Concerns\SoftDeletes;

class PurchaseOrderRequirements extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'segment_tag',
        'raw_segment',
        'contract_carriage_condition_code',
        'po_requirements_code',
        'additional_po_requirement_code',
        'transport_priority',
        'purchase_order_id '
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

    //Relation (as son)

    // 1-to-N with service
    public function purchase_order(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }
}
