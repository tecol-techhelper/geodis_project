<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DateType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type_qualifier',
        'type_description',
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

    // 1-to-1 with service_dates table
    public function service_date(): HasMany
    {
        return $this->hasMany(ServiceDate::class, 'date_type_id');
    }
}
