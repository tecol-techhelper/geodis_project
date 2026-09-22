<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResourceTariff extends Model
{
    protected $fillable = [
        'resource_id',
        'region_id',
        'operation_concept_id',
        'unit_price',
        'is_active',
    ];

    protected $casts = [
        'id' => 'integer',
        'resource_id' => 'integer',
        'region_id' => 'integer',
        'region_scope_key' => 'integer',
        'operation_concept_id' => 'integer',
        'unit_price' => 'decimal:6',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $tariff): void {
            $tariff->region_scope_key = $tariff->region_id ?? 0;
        });
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function operationConcept(): BelongsTo
    {
        return $this->belongsTo(OperationConcept::class);
    }

    public function distanceRanges(): HasMany
    {
        return $this->hasMany(ResourceTariffDistanceRange::class)
            ->orderBy('minimum_km')
            ->orderBy('maximum_km');
    }

    public function hasConfiguredPrice(): bool
    {
        return $this->unit_price !== null;
    }

    public function isAvailableForFutureUse(): bool
    {
        return $this->is_active
            && $this->unit_price !== null
            && (float) $this->unit_price > 0;
    }
}
