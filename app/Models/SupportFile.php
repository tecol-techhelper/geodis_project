<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'user_id',
        'file_type_id'
    ];

    // Relation N-to-1 with users table
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function support_file(): HasOne{
        return $this->hasOne(FileType::class);
    }
}
