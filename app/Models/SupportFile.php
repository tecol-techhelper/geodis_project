<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupportFile extends Model
{
    use SoftDeletes;
    public $timestamps = false;

    protected $fillable = [
        'file_name',
        'file_url',
        'file_size',
        'file_extension',
        'uploaded_at',
        'service_id',
        'user_id',
        'uploaded_sftp',
        'sftp_error',
        'file_type_id',
    ];

    // Relation(as son) 

    //1-to-1 with file_type table 
    public function file_type(): BelongsTo
    {
        return $this->belongsTo(FileType::class, 'file_type_id');
    }

    //1-to-N with services table
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
