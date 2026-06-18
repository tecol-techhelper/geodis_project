<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonnelRole extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function resourcePersonnelRequirements(): HasMany
    {
        return $this->hasMany(ResourcePersonnelRequirement::class);
    }

    public function serviceResourceReportPersonnel(): HasMany
    {
        return $this->hasMany(ServiceResourceReportPersonnel::class);
    }
}
