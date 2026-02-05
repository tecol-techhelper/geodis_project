<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        DB::table('location_codes')->insert([
            [
                'location_code' => 9,
                'location_code_name' => 'Place/port of loading',
                'location_code_description' => 'Lugar o puerto donde la mercancía es cargada para iniciar el transporte.',
                'created_at' => $now,
            ],
            [
                'location_code' => 11,
                'location_code_name' => 'Place/port of discharge',
                'location_code_description' => 'Lugar o puerto donde la mercancía es descargada al finalizar el transporte.',
                'created_at' => $now,
            ],
        ]);
    }
}
