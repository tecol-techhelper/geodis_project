<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquipmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        DB::table('equipment_types')->insert([
            [
                'equipment_type_code' => 'CN',
                'equipment_type_name' => 'Container',
                'equipment_type_description' => 'Contenedor utilizado para el transporte de mercancías en distintos modos de transporte.',
                'created_at' => $now,
            ],
            [
                'equipment_type_code' => 'TE',
                'equipment_type_name' => 'Trailer',
                'equipment_type_description' => 'Remolque utilizado para el transporte terrestre de mercancías.',
                'created_at' => $now,
            ],
            [
                'equipment_type_code' => 'UL',
                'equipment_type_name' => 'Unit Load Device',
                'equipment_type_description' => 'Dispositivo de carga unitaria utilizado principalmente en el transporte aéreo.',
                'created_at' => $now,
            ],
            [
                'equipment_type_code' => 'RR',
                'equipment_type_name' => 'Rail Car',
                'equipment_type_description' => 'Vagón ferroviario utilizado para el transporte de mercancías por ferrocarril.',
                'created_at' => $now,
            ],
        ]);
    }
}
