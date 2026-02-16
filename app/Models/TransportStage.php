<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PowerComponents\LivewirePowerGrid\Concerns\SoftDeletes;

class TransportStage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'transport_stage_code',
        'transport_stage_name',
        'transport_stage_description',
    ];

    protected $casts = [
        'id' => 'integer',
        'transport_stage_code' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relation (as father)

    // 1-to-1 with service_dates table
    public function transport_detail(): HasMany
    {
        return $this->hasMany(TransportDetail::class, 'transport_stage_id');
    }
}
