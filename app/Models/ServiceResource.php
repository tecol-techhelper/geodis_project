<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ServiceResource extends Model
{
    protected $table = 'service_resource';

    protected $fillable = [
        'service_id',
        'resource_id',
        'last_reported_at',
        'status_name',
    ];

    protected $casts = [
        'last_reported_at' => 'datetime',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function report(): HasOne
    {
        return $this->hasOne(ServiceResourceReport::class);
    }

    public function statusReports(): HasMany
    {
        return $this->hasMany(ServiceResourceStatusReport::class);
    }
}
