<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceResourceReportPersonnel extends Model
{
    use SoftDeletes;

    protected $table = 'service_resource_report_personnel';

    protected $fillable = [
        'service_resource_report_id',
        'operator_id',
        'personnel_role_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'service_resource_report_id' => 'integer',
        'operator_id' => 'integer',
        'personnel_role_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    public function serviceResourceReport(): BelongsTo
    {
        return $this->belongsTo(ServiceResourceReport::class);
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class);
    }

    public function personnelRole(): BelongsTo
    {
        return $this->belongsTo(PersonnelRole::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
