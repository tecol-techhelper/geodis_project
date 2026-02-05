<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MeasurementPurposeCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        DB::table('measurement_purpose_codes')->insert([
            ['measurement_purpose_code' => 'AAE', 'measurement_purpose_name' => 'Measurement', 'measurement_purpose_description' => 'Propósito: medición.', 'created_at' => $now],
            ['measurement_purpose_code' => 'CT',  'measurement_purpose_name' => 'Counts',      'measurement_purpose_description' => 'Propósito: conteos (unidades).', 'created_at' => $now],
            ['measurement_purpose_code' => 'VOL', 'measurement_purpose_name' => 'Volume',      'measurement_purpose_description' => 'Propósito: volumen.', 'created_at' => $now],
            ['measurement_purpose_code' => 'WT',  'measurement_purpose_name' => 'Weights',     'measurement_purpose_description' => 'Propósito: peso.', 'created_at' => $now],
        ]);
    }
}
