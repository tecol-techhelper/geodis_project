<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PowerComponents\LivewirePowerGrid\Concerns\SoftDeletes;

class PriceQualifier extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'price_qualifier_code',
        'price_qualifier_name',
        'price_qualifier_description'
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relation(as father) 1-to-1 with transport_charge table
    public function transport_charges(): HasMany
    {
        return $this->hasMany(TransportCharge::class, 'price_qualifier_id');
    }
}
