<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceResourceStatusReport extends Model
{
    protected $fillable = [
        'service_resource_id',
        'service_status_report_id',
        'reported_at',
    ];

    protected $casts = [
        'service_resource_id' => 'integer',
        'service_status_report_id' => 'integer',
        'reported_at' => 'datetime',
    ];

    public function serviceResource(): BelongsTo
    {
        return $this->belongsTo(ServiceResource::class);
    }

    public function serviceStatusReport(): BelongsTo
    {
        return $this->belongsTo(ServiceStatusReport::class);
    }
}
