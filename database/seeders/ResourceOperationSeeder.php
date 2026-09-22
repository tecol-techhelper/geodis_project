<?php

namespace Database\Seeders;

use App\Models\ResourceOperation;
use Illuminate\Database\Seeder;

class ResourceOperationSeeder extends Seeder
{
    private const OPERATIONS = [
        'COSTO_AUTORIZADO',
        'IZAJES',
        'OCONCEPTOS',
        'TRANSPORTE',
    ];

    public function run(): void
    {
        foreach (self::OPERATIONS as $name) {
            ResourceOperation::query()->updateOrCreate(['name' => $name]);
        }
    }
}
