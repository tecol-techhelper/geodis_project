<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PowerComponents\LivewirePowerGrid\Concerns\SoftDeletes;

class IdentifierType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'identifier_type_code',
        'identifier_type_name',
        'identifier_type_description'
    ];

    protected $casts = [
        'id' => 'integer',
        'identifier_type_code' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    // Relation (as father)

    // 1-to-1 with service_dates table
    public function service_date(): HasMany
    {
        return $this->hasMany(ItemContainerIdentifier::class, 'identifier_type_id');
    }
}
