<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PowerComponents\LivewirePowerGrid\Concerns\SoftDeletes;

class PartyType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'party_qualifier',
        'party_description'
    ];

    protected $casts = [
        'id' => 'integer'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relation (as father)

    // 1-to-1 with party table
    public function party(): HasMany
    {
        return $this->hasMany(Party::class, 'party_type_id');
    }
}
