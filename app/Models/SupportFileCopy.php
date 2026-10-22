<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportFileCopy extends Model
{
    protected $fillable = [
        'identifier', 'cni', 'file_name', 'associations', 'sharepoint_path', 'file_url',
        'sharepoint_uploaded_at', 'sftp_uploaded_at', 'sharepoint_deleted_at', 'error_message',
    ];

    protected $casts = [
        'associations' => 'array',
        'sharepoint_uploaded_at' => 'datetime',
        'sftp_uploaded_at' => 'datetime',
        'sharepoint_deleted_at' => 'datetime',
    ];

    public function supportFile(): BelongsTo
    {
        return $this->belongsTo(SupportFile::class);
    }

    public function isComplete(): bool
    {
        return $this->sharepoint_uploaded_at !== null && $this->sftp_uploaded_at !== null;
    }
}
