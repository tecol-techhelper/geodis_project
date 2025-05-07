<?php

namespace App\Enums;

enum UserStatus: int
{
    case Unactive = 0;
    case Active = 1;

    public function label(): string
    {
        return match ($this){
            self::Unactive => 'Inactivo',
            self::Active => 'Activo',
        };
    }
}
