<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceTariffDistanceRange extends Model
{
    protected $fillable = [
        'resource_tariff_id',
        'minimum_km',
        'maximum_km',
        'is_minimum_inclusive',
        'is_maximum_inclusive',
        'unit_price',
        'is_active',
    ];

    protected $casts = [
        'id' => 'integer',
        'resource_tariff_id' => 'integer',
        'minimum_km' => 'decimal:2',
        'maximum_km' => 'decimal:2',
        'is_minimum_inclusive' => 'boolean',
        'is_maximum_inclusive' => 'boolean',
        'unit_price' => 'decimal:6',
        'is_active' => 'boolean',
    ];

    public function tariff(): BelongsTo
    {
        return $this->belongsTo(ResourceTariff::class, 'resource_tariff_id');
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
