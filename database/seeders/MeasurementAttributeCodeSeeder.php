<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MeasurementAttributeCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        DB::table('measurement_attribute_codes')->insert([
            ['measurement_attribute_code' => 'AAC', 'measurement_attribute_name' => 'Total net weight',   'measurement_attribute_description' => 'Peso neto total.', 'created_at' => $now],
            ['measurement_attribute_code' => 'AAD', 'measurement_attribute_name' => 'Total gross weight', 'measurement_attribute_description' => 'Peso bruto total.', 'created_at' => $now],
            ['measurement_attribute_code' => 'AAW', 'measurement_attribute_name' => 'Gross volume',       'measurement_attribute_description' => 'Volumen bruto.', 'created_at' => $now],
            ['measurement_attribute_code' => 'ACV', 'measurement_attribute_name' => 'Loading meters',     'measurement_attribute_description' => 'Metros de carga (loading meters).', 'created_at' => $now],
        ]);
    }
}
