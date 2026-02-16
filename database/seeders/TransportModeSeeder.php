<?php

namespace Database\Seeders;

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

        $rows = [
            [
                'transport_mode_code' => 1,
                'transport_mode_name' => 'Transporte marítimo',
                'transport_mode_description' => 'Transporte de carga realizado por vía marítima.',
            ],
            [
                'transport_mode_code' => 2,
                'transport_mode_name' => 'Transporte ferroviario',
                'transport_mode_description' => 'Transporte de carga realizado mediante red ferroviaria.',
            ],
            [
                'transport_mode_code' => 3,
                'transport_mode_name' => 'Transporte terrestre',
                'transport_mode_description' => 'Transporte de carga realizado por carretera.',
            ],
            [
                'transport_mode_code' => 4,
                'transport_mode_name' => 'Transporte aéreo',
                'transport_mode_description' => 'Transporte de carga realizado por vía aérea.',
            ],
            [
                'transport_mode_code' => 6,
                'transport_mode_name' => 'Multimodal',
                'transport_mode_description' => 'Transporte que combina dos o más modos de transporte bajo un mismo servicio.',
            ],
        ];

        $rows = array_map(function (array $row) use ($now) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
            return $row;
        }, $rows);

        DB::table('transport_modes')->upsert(
            $rows,
            ['transport_mode_code'],
            ['transport_mode_name', 'transport_mode_description', 'updated_at']
        );
    }
}
