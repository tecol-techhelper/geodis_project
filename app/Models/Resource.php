<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model
{
    use SoftDeletes;

    public const REQUIRES_VEHICLE = 1;
    public const REQUIRES_PERSONNEL = 2;
    public const REQUIRES_REMITTANCE = 4;
    public const REQUIRES_CONTAINER = 8;

    protected $fillable = [
        'resource_id',
        'resource_name',
        'resource_operation',
        'required_report_mask',
    ];

    protected $casts = [
        'id' => 'integer',
        'required_report_mask' => 'integer',
    ];

    public function requiresVehicle(): bool
    {
        return $this->requires(self::REQUIRES_VEHICLE);
    }

    public function requiresPersonnel(): bool
    {
        return $this->requires(self::REQUIRES_PERSONNEL);
    }

    public function requiresRemittance(): bool
    {
        return $this->requires(self::REQUIRES_REMITTANCE);
    }

    public function requiresContainer(): bool
    {
        return $this->requires(self::REQUIRES_CONTAINER);
    }

    public function requiresAdditionalInformation(): bool
    {
        return (int) $this->required_report_mask !== 0;
    }

    private function requires(int $requirement): bool
    {
        return (((int) $this->required_report_mask) & $requirement) === $requirement;
    }

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

    public function serviceResourceReports(): HasMany
    {
        return $this->hasMany(ServiceResourceReport::class);
    }

    public function personnelRequirements(): HasMany
    {
        return $this->hasMany(ResourcePersonnelRequirement::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }
}
