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
                'measurement_attribute_name' => 'Total net weight',
                'measurement_attribute_description' => 'Peso neto total.',
            ],
            [
                'measurement_attribute_code' => 'AAD',
                'measurement_attribute_name' => 'Total gross weight',
                'measurement_attribute_description' => 'Peso bruto total.',
            ],
            [
                'measurement_attribute_code' => 'AAW',
                'measurement_attribute_name' => 'Gross volume',
                'measurement_attribute_description' => 'Volumen bruto.',
            ],
            [
                'measurement_attribute_code' => 'ACV',
                'measurement_attribute_name' => 'Loading meters',
                'measurement_attribute_description' => 'Metros lineales de carga utilizados en el vehículo.',
            ],

            // ✅ FALTANTE
            [
                'measurement_attribute_code' => 'AAJ',
                'measurement_attribute_name' => 'Units per pallet',
                'measurement_attribute_description' => 'Número de unidades contenidas por cada pallet.',
            ],
        ];

        foreach ($rows as $row) {

            $code = strtoupper(trim($row['measurement_attribute_code']));

            $existingId = DB::table('measurement_attribute_codes')
                ->where('measurement_attribute_code', $code)
                ->value('id');

            if ($existingId) {
                DB::table('measurement_attribute_codes')
                    ->where('id', $existingId)
                    ->update([
                        'measurement_attribute_name' => $row['measurement_attribute_name'],
                        'measurement_attribute_description' => $row['measurement_attribute_description'],
                    ]);
            } else {
                DB::table('measurement_attribute_codes')->insert([
                    'measurement_attribute_code' => $code,
                    'measurement_attribute_name' => $row['measurement_attribute_name'],
                    'measurement_attribute_description' => $row['measurement_attribute_description'],
                    'created_at' => $now,
                ]);
            }
        }
    }
}
