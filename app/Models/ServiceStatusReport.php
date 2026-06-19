<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceStatusReport extends Model
{
    protected $fillable = [
        'service_id',
        'status_id',
        'reported_at',
        'status_name_snapshot',
        'edifact_code_snapshot',
    ];

    protected $casts = [
        'service_id' => 'integer',
        'status_id' => 'integer',
        'reported_at' => 'datetime',
        'edifact_code_snapshot' => 'integer',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function resourceStatusReports(): HasMany
    {
        return $this->hasMany(ServiceResourceStatusReport::class);
    }
}
