<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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

    public function origins(): HasMany
    {
        return $this->hasMany(RegionOrigin::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
