<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileType extends Model
{
    use SoftDeletes;

    // Relation(as father) 1-to-1 with support_file table
    public function support_file(): HasMany
    {
        return $this->HasMany(SupportFile::class, 'file_type_id');
    }
}
