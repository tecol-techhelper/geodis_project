<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'segment_tag',
        'item',
        'consecutive',
        'observation',
        'ttcol_value',
        'cargo_value',
        'driver',
        'advance_payment',
        'third_party_value',
        'manifest_number',
        'possitioning_issue',
        'supplier_invoice_number',
        'remittance_invoice_number',
        'remittance_invoice_value',
        'remittance_value',
        'invoice_date',
        'record_number',
        'payment_approval',
        'odp',
        'odp_value',
        'raw_segment',
        'status_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'ttcol_value' => 'decimal:2',
        'cargo_value' => 'decimal:2',
        'manifest_number' => 'integer',
        'supplier_invoice_number' => 'integer',
        'remittance_invoice_value' => 'decimal:2',
        'invoice_date' => 'date',
        'payment_approval' => 'boolean',
        'odp' => 'integer',
        'odp_value' => 'decimal:2',
        'status_id' => 'integer',
        'possitioning_issue' => 'integer',
        'remittance_invoice_number' => 'integer',
        'advance_payment' => 'decimal:2'
    ];

    protected $hidden = [
        'raw_segment',
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    // Relation (as son) 1-to-1 with statuses table
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    // Relations (as father) 

    // 1-to-N with notiticacions table
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'service_id');
    }

    // 1-to-N with edifact_files table
    public function edifact_files(): HasMany
    {
        return $this->hasMany(EdifactFile::class, 'service_id');
    }

    // 1-to-N with support_files table
    public function support_files(): HasMany
    {
        return $this->hasMany(SupportFile::class, 'service_id');
    }

    //1-to-N with service_contacts table
    public function service_contacts(): HasMany
    {
        return $this->hasMany(ServiceContact::class, 'service_id');
    }

    //1-to-N with service_dates table
    public function service_dates(): HasMany
    {
        return $this->hasMany(ServiceDate::class, 'service_id');
    }

    //1-to-N with purchase_orders table
    public function purchase_orders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class, 'service_id');
    }

    //1-to-N with edi_failed_files table
    public function edi_failed_files(): HasMany
    {
        return $this->hasMany(EdiFailedFile::class, 'service_id');
    }

    // 1-to-N service_measurements table
    public function service_measurements(): HasMany
    {
        return $this->hasMany(ServiceMeasurement::class, 'service_id');
    }

    // 1-to-N parties table
    public function service_parties(): HasMany
    {
        return $this->hasMany(ServiceParty::class, 'service_id');
    }

    // 1-to-N transport_details table
    public function transport_details(): HasMany
    {
        return $this->hasMany(TransportDetail::class, 'service_id');
    }

    // 1-to-N with location_details table
    public function location_details(): HasMany
    {
        return $this->hasMany(LocationDetail::class, 'service_id');
    }

    // 1-to-N with service_equipments table
    public function service_equipments(): HasMany
    {
        return $this->hasMany(ServiceEquipment::class, 'service_id');
    }
}
