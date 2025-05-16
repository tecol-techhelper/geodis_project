<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EdifactFile extends Model
{
    public $timestamps = true;
    
    protected $fillable = [
        'transmission_id',
        'message_type',
        'file_name',
        'purchase_order',
        'recived_at',
        'file_url',
        'file_path'
    ];
}
