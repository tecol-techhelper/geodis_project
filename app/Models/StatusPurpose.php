<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StatusPurpose extends Model
{
    public function statuses(): HasMany
    {
        return $this->hasMany(Status::class, 'status_purpose_id');
    }
}
