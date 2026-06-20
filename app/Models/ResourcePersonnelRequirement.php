<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResourcePersonnelRequirement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'resource_id',
        'personnel_role_id',
        'quantity_required',
        'is_required',
        'sort_order',
    ];

    protected $casts = [
        'resource_id' => 'integer',
        'personnel_role_id' => 'integer',
        'quantity_required' => 'integer',
        'is_required' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function personnelRole(): BelongsTo
    {
        return $this->belongsTo(PersonnelRole::class);
    }
}
