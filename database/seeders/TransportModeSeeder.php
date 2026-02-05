<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransportModeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        DB::table('transport_modes')->insert([
            [
                'transport_mode_code' => 1,
                'transport_mode_name' => 'Maritime Transport',
                'transport_mode_description' => 'Transporte de carga realizado por vía marítima.',
                'created_at' => $now,
            ],
            [
                'transport_mode_code' => 2,
                'transport_mode_name' => 'Rail Transport',
                'transport_mode_description' => 'Transporte de carga realizado mediante red ferroviaria.',
                'created_at' => $now,
            ],
            [
                'transport_mode_code' => 3,
                'transport_mode_name' => 'Road Transport',
                'transport_mode_description' => 'Transporte de carga realizado por carretera.',
                'created_at' => $now,
            ],
            [
                'transport_mode_code' => 4,
                'transport_mode_name' => 'Air Transport',
                'transport_mode_description' => 'Transporte de carga realizado por vía aérea.',
                'created_at' => $now,
            ],
            [
                'transport_mode_code' => 6,
                'transport_mode_name' => 'Multimodal',
                'transport_mode_description' => 'Transporte que combina dos o más modos de transporte bajo un mismo servicio.',
                'created_at' => $now,
            ],
        ]);
    }
}
