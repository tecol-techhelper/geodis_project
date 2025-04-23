<?php

namespace App\Enums;

enum Rol: int
{
    case Administrator = 1;
    case Coordinator = 2;
    case Accountant = 3;
    case OD = 4;
    case Specific = 5;
    case Security = 6;

    public function label(): string
    {
        return match($this){
            self::Administrator => 'Administrador',
            self::Coordinator => 'Coordinador',
            self::Accountant => 'Contador',
            self::OD => 'Directora Operativa',
            self::Specific => 'Especifico',
            self::Security => 'Seguridad',
        };
    }
}
