<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Operator extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'identification',
        'first_name',
        'last_name',
    ];

    public static function normalizeIdentification(?string $value): string
    {
        return preg_replace('/[^A-Z0-9]/', '', Str::upper(trim((string) $value))) ?? '';
    }

    public function setIdentificationAttribute(?string $value): void
    {
        $this->attributes['identification'] = self::normalizeIdentification($value);
    }

    public function serviceResourceReportPersonnel(): HasMany
    {
        return $this->hasMany(ServiceResourceReportPersonnel::class);
    }
}
