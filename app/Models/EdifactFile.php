<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EdifactFile extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'transmission_id',
        'message_type',
        'file_name',
        'purchase_order',
        'recived_at',
        'sended_at',
        'file_url',
        'file_path'
    ];

    protected $casts = [
        'id' => 'integer',
        'service_id' => 'integer'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relation (as son) 1-to-N with services table
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
