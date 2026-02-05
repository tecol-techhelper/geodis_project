<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'segment_tag',
        'channel_contact',
        'contact_information',
        'raw_segment',
        'service_contact_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'service_contact_id' => 'integer'
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
        'deleted_at'
    ];

    // Relation (as son)

    // 1-to-N with servicecontacts table
    public function service_contact(): BelongsTo
    {
        return $this->belongsTo(ServiceContact::class, 'service_contact_id');
    }
}
