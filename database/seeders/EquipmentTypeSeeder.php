<?php

namespace Database\Seeders;

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

        $rows = [
            [
                'equipment_type_code' => 'CN',
                'equipment_type_name' => 'Contenedor',
                'equipment_type_description' => 'Contenedor utilizado para el transporte de mercancías en distintos modos de transporte.',
            ],
            [
                'equipment_type_code' => 'TE',
                'equipment_type_name' => 'Remolque',
                'equipment_type_description' => 'Remolque utilizado para el transporte terrestre de mercancías.',
            ],
            [
                'equipment_type_code' => 'UL',
                'equipment_type_name' => 'Dispositivo de carga unitaria',
                'equipment_type_description' => 'Dispositivo de carga unitaria utilizado principalmente en el transporte aéreo.',
            ],
            [
                'equipment_type_code' => 'RR',
                'equipment_type_name' => 'Vagón ferroviario',
                'equipment_type_description' => 'Vagón ferroviario utilizado para el transporte de mercancías por ferrocarril.',
            ],
        ];

        $rows = array_map(function (array $row) use ($now) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
            return $row;
        }, $rows);

        DB::table('equipment_types')->upsert(
            $rows,
            ['equipment_type_code'],
            ['equipment_type_name', 'equipment_type_description', 'updated_at']
        );
    }
}
