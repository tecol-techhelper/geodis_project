<?php

namespace App\Services\Geodis;

use Illuminate\Support\Str;

class OriginNormalizer
{
    public function normalize(?string $origin): ?string
    {
        if ($origin === null) {
            return null;
        }

        $normalized = preg_replace('/\s+/u', ' ', trim($origin));

        if ($normalized === null || $normalized === '') {
            return null;
        }

        return Str::upper($normalized);
    }
}
