<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'resource_id',
        'resource_name',
        'resource_operation',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public function purchase_orders(): BelongsToMany
    {
        return $this->belongsToMany(PurchaseOrder::class, 'purchase_order_resource', 'resource_id', 'purchase_order_id')
            ->withTimestamps();
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'service_resource', 'resource_id', 'service_id')
            ->withPivot('id', 'last_reported_at', 'status_name')
            ->withTimestamps();
    }
}
