<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = [
        'id' => 'integer',
        'is_active' => 'boolean',
    ];

    public function regionOrigins(): HasMany
    {
        return $this->hasMany(RegionOrigin::class);
    }

    public function origins(): BelongsToMany
    {
        return $this->belongsToMany(Origin::class, 'region_origins')
            ->withPivot('id', 'is_active')
            ->withTimestamps();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function tariffs(): HasMany
    {
        return $this->hasMany(ResourceTariff::class);
    }
}
