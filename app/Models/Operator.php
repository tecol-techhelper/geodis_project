<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operator extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'identification',
        'first_name',
        'last_name',
    ];

    public function serviceResourceReports(): HasMany
    {
        return $this->hasMany(ServiceResourceReport::class);
    }
}
