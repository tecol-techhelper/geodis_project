<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DimensionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $rows = [
            [
                'dimension_type_code' => 1,
                'dimension_type_name' => 'Dimensiones brutas',
                'dimension_type_description' => 'Dimensiones brutas de la mercancía.',
            ],
            [
                'dimension_type_code' => 2,
                'dimension_type_name' => 'Dimensiones del paquete',
                'dimension_type_description' => 'Dimensiones del paquete, incluyendo la mercancía.',
            ],
            [
                'dimension_type_code' => 10,
                'dimension_type_name' => 'Dimensiones externas del equipo',
                'dimension_type_description' => 'Dimensiones externas del equipo de transporte.',
            ],
        ];

        $rows = array_map(function (array $row) use ($now) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
            return $row;
        }, $rows);

        DB::table('dimension_types')->upsert(
            $rows,
            ['dimension_type_code'],
            ['dimension_type_name', 'dimension_type_description', 'updated_at']
        );
    }
}
