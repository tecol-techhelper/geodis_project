<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OperationConcept extends Model
{
    protected $fillable = [
        'resource_operation_id',
        'name',
    ];

    protected $casts = [
        'id' => 'integer',
        'resource_operation_id' => 'integer',
    ];

    public function operation(): BelongsTo
    {
        return $this->belongsTo(ResourceOperation::class, 'resource_operation_id');
    }

    public function reportLines(): HasMany
    {
        return $this->hasMany(ServiceResourceReportLine::class);
    }

    public function tariffs(): HasMany
    {
        return $this->hasMany(ResourceTariff::class);
    }
}
