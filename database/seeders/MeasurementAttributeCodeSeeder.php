<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MeasurementAttributeCodeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $rows = [
            [
                'measurement_attribute_code' => 'AAC',
                'measurement_attribute_name' => 'Peso neto total',
                'measurement_attribute_description' => 'Peso neto total.',
            ],
            [
                'measurement_attribute_code' => 'AAD',
                'measurement_attribute_name' => 'Peso bruto total',
                'measurement_attribute_description' => 'Peso bruto total.',
            ],
            [
                'measurement_attribute_code' => 'AAW',
                'measurement_attribute_name' => 'Volumen bruto',
                'measurement_attribute_description' => 'Volumen bruto.',
            ],
            [
                'measurement_attribute_code' => 'ACV',
                'measurement_attribute_name' => 'Metros de carga',
                'measurement_attribute_description' => 'Metros lineales de carga utilizados en el vehículo.',
            ],
            [
                'measurement_attribute_code' => 'AAJ',
                'measurement_attribute_name' => 'Unidades por pallet',
                'measurement_attribute_description' => 'Número de unidades contenidas por cada pallet.',
            ],
        ];

        $rows = array_map(function (array $row) use ($now) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
            return $row;
        }, $rows);

        DB::table('measurement_attribute_codes')->upsert(
            $rows,
            ['measurement_attribute_code'],
            ['measurement_attribute_name', 'measurement_attribute_description', 'updated_at']
        );
    }
}
