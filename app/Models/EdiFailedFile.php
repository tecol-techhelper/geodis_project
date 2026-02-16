<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EdiFailedFile extends Model
{
    use SoftDeletes;

    // Relation (as son)

    // 1-to-N with service table
    public function service(): BelongsTo{
        return $this->belongsTo(Service::class);
    }
}
