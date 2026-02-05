<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        DB::table('dimension_types')->insert([
            [
                'dimension_type_code' => 1,
                'dimension_type_name' => 'Gross dimensions',
                'dimension_type_description' => 'Dimensiones brutas de la mercancía.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'dimension_type_code' => 2,
                'dimension_type_name' => 'Package dimensions',
                'dimension_type_description' => 'Dimensiones del paquete, incluyendo la mercancía.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'dimension_type_code' => 10,
                'dimension_type_name' => 'External equipment dimension',
                'dimension_type_description' => 'Dimensiones externas del equipo de transporte.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
