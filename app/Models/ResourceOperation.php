<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResourceOperation extends Model
{
    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }

    public function concepts(): HasMany
    {
        return $this->hasMany(OperationConcept::class);
    }
}
