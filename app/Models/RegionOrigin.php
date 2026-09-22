<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegionOrigin extends Model
{
    protected $fillable = [
        'region_id',
        'origin_id',
        'is_active',
    ];

    protected $casts = [
        'id' => 'integer',
        'region_id' => 'integer',
        'origin_id' => 'integer',
        'is_active' => 'boolean',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function catalogueOrigin(): BelongsTo
    {
        return $this->belongsTo(Origin::class, 'origin_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
