<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceDate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'segment_tag',
        'raw_segment',
        'service_date',
        'format_date',
        'service_id',
        'date_type_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'service_date' => 'date',
        'format_date' => 'integer',
        'service_id' => 'integer',
        'date_type_id' => 'integer'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relation (as son)

    // 1-to-1 with date_type table
    public function date_type(): BelongsTo
    {
        return $this->belongsTo(DateType::class, 'date_type_id');
    }

    // 1-to-N with service table
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
