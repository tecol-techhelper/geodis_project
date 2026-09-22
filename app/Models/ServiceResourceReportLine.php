<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceResourceReportLine extends Model
{
    protected $fillable = [
        'service_resource_report_id',
        'origin_id',
        'destination_id',
        'operation_concept_id',
        'quantity',
        'unit_price',
        'total_price',
        'remesa_transporte',
    ];

    protected $casts = [
        'id' => 'integer',
        'service_resource_report_id' => 'integer',
        'origin_id' => 'integer',
        'destination_id' => 'integer',
        'operation_concept_id' => 'integer',
        'quantity' => 'decimal:6',
        'unit_price' => 'decimal:6',
        'total_price' => 'decimal:6',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(ServiceResourceReport::class, 'service_resource_report_id');
    }

    public function origin(): BelongsTo
    {
        return $this->belongsTo(Origin::class, 'origin_id');
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Origin::class, 'destination_id');
    }

    public function concept(): BelongsTo
    {
        return $this->belongsTo(OperationConcept::class, 'operation_concept_id');
    }
}
