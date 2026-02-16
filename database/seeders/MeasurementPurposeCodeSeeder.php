<?php

namespace Database\Seeders;

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

        $rows = [
            [
                'measurement_purpose_code' => 'AAE',
                'measurement_purpose_name' => 'Medición',
                'measurement_purpose_description' => 'Propósito: medición.',
            ],
            [
                'measurement_purpose_code' => 'CT',
                'measurement_purpose_name' => 'Conteos',
                'measurement_purpose_description' => 'Propósito: conteos (unidades).',
            ],
            [
                'measurement_purpose_code' => 'VOL',
                'measurement_purpose_name' => 'Volumen',
                'measurement_purpose_description' => 'Propósito: volumen.',
            ],
            [
                'measurement_purpose_code' => 'WT',
                'measurement_purpose_name' => 'Pesos',
                'measurement_purpose_description' => 'Propósito: peso.',
            ],
        ];

        $rows = array_map(function (array $row) use ($now) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
            return $row;
        }, $rows);

        DB::table('measurement_purpose_codes')->upsert(
            $rows,
            ['measurement_purpose_code'],
            ['measurement_purpose_name', 'measurement_purpose_description', 'updated_at']
        );
    }
}
