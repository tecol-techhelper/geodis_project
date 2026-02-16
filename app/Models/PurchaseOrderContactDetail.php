<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrderContactDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'segment_tag',
        'channel_contact',
        'contact_information',
        'raw_segment',
        'purchase_contact_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'purchase_contact_id' => 'integer'
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
        'deleted_at'
    ];

    // Relation (as son)

    // 1-to-N with service_contacts table
    public function service_contact(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrderContact::class, 'purchase_contact_id');
    }
}
