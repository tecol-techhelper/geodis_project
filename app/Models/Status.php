<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'status_purpose_id',
        'edifact_code',
        'segment_tag',
        'status_name',
        'status_description',
        'status_be'
    ];

    protected $casts = [
        'id' => 'integer',
        'status_purpose_id' => 'integer',
        'edifact_code' => 'integer'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relation (as father) 1-to-N with services table
    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'status_id');
    }

    public function status_purpose(): BelongsTo
    {
        return $this->belongsTo(StatusPurpose::class, 'status_purpose_id');
    }
}
