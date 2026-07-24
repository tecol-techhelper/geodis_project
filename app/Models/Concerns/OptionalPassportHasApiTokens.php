<?php

namespace App\Models\Concerns;

if (trait_exists(\Laravel\Passport\HasApiTokens::class)) {
    trait OptionalPassportHasApiTokens
    {
        use \Laravel\Passport\HasApiTokens;
    }
} else {
    trait OptionalPassportHasApiTokens
    {
    }
}
