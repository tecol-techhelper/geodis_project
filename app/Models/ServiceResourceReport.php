<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceResourceReport extends Model
{
    protected $fillable = [
        'service_resource_id',
        'service_id',
        'resource_id',
        'vehicle_id',
        'operator_id',
        'container_id',
        'remesa_transporte',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'service_resource_id' => 'integer',
        'service_id' => 'integer',
        'resource_id' => 'integer',
        'vehicle_id' => 'integer',
        'operator_id' => 'integer',
        'container_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    public function serviceResource(): BelongsTo
    {
        return $this->belongsTo(ServiceResource::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class);
    }

    public function container(): BelongsTo
    {
        return $this->belongsTo(Container::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
