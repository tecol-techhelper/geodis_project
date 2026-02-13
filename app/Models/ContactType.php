<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type_tag',
        'type_description'
    ];

    protected $casts = [
        'id' => 'integer'
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
        'deleted_at'
    ];

    // Relation (as father)

    // 1-to-1 with servicecontacts table
    public function service_contact(): HasMany
    {
        return $this->hasMany(ServiceContact::class, 'contact_type_id');
    }

    // 1-to-1 with servicecontacts table
    public function purchase_order_contact(): HasMany
    {
        return $this->hasMany(PurchaseOrderContact::class, 'contact_type_id');
    }
}
