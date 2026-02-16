<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $rows = [
            [
                'location_code' => 9,
                'location_code_name' => 'Lugar/puerto de carga',
                'location_code_description' => 'Lugar o puerto donde la mercancía es cargada para iniciar el transporte.',
            ],
            [
                'location_code' => 11,
                'location_code_name' => 'Lugar/puerto de descarga',
                'location_code_description' => 'Lugar o puerto donde la mercancía es descargada al finalizar el transporte.',
            ],
        ];

        $rows = array_map(function (array $row) use ($now) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
            return $row;
        }, $rows);

        DB::table('location_codes')->upsert(
            $rows,
            ['location_code'],
            ['location_code_name', 'location_code_description', 'updated_at']
        );
    }
}
