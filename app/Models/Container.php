<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Container extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'container_number',
    ];

    public function serviceResourceReports(): HasMany
    {
        return $this->hasMany(ServiceResourceReport::class);
    }
}
