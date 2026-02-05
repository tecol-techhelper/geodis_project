<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'segment_tag',
        'status_name',
        'status_description',
        'status_be'
    ];

    protected $casts = [
        'id' => 'integer'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relation (as father) 1-to-1 with services table
    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'status_id');
    }
}
