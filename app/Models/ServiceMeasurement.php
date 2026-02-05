<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceMeasurement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'segment_tag',
        'measure_value',
        'measure_unit',
        'raw_segment',
        'service_id',
        'purchase_order_id',
        'global_measure_type_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'service_id' => 'integer',
        'purchase_order_id' => 'integer',
        'global_measure_type_id' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relation (as son)

    // 1-to-1 with global_measure_types table
    public function global_measure_type(): BelongsTo
    {
        return $this->belongsTo(GlobalMeasureType::class);
    }

    // 1-to-N with services table
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    // 1-to-N with purchase_order table
    public function purchase_order(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}
