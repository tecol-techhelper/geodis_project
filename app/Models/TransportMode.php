<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PowerComponents\LivewirePowerGrid\Concerns\SoftDeletes;

class TransportMode extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'transport_mode_code',
        'transport_mode_name',
        'transport_mode_description',
    ];

    protected $casts = [
        'id' => 'integer',
        'transport_mode_code' => 'integer',
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
        return $this->hasMany(TransportDetail::class, 'transport_mode_id');
    }
}
