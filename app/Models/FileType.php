<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FileType extends Model
{
    
    public function support_file(): HasOne{
        return $this->hasOne(SupportFile::class);
    }
}
