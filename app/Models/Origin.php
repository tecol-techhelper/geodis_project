<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Origin extends Model
{
    protected $fillable = [
        'origin',
        'normalized_origin',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public function regionOrigin(): HasOne
    {
        return $this->hasOne(RegionOrigin::class);
    }

    public function originReportLines(): HasMany
    {
        return $this->hasMany(ServiceResourceReportLine::class, 'origin_id');
    }

    public function destinationReportLines(): HasMany
    {
        return $this->hasMany(ServiceResourceReportLine::class, 'destination_id');
    }
}
