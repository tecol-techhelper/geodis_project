<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransportStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $rows = [
            [
                'transport_stage_code' => 20,
                'transport_stage_name' => 'Etapa principal',
                'transport_stage_description' => 'Etapa principal del transporte del servicio.',
            ],
            [
                'transport_stage_code' => 21,
                'transport_stage_name' => 'Tramo principal - 1er transp.',
                'transport_stage_description' => 'Primer transportista que participa en la ejecución del tramo principal del transporte.',
            ],
            [
                'transport_stage_code' => 22,
                'transport_stage_name' => 'Tramo principal - 2do transp.',
                'transport_stage_description' => 'Segundo transportista que participa en la ejecución del mismo tramo principal del transporte.',
            ],
            [
                'transport_stage_code' => 23,
                'transport_stage_name' => 'Tramo principal - 3er transp.',
                'transport_stage_description' => 'Tercer transportista involucrado en la ejecución del tramo principal del transporte.',
            ],
            [
                'transport_stage_code' => 24,
                'transport_stage_name' => 'Tramo principal - 4to transp.',
                'transport_stage_description' => 'Cuarto transportista involucrado en la ejecución del tramo principal del transporte.',
            ],
            [
                'transport_stage_code' => 28,
                'transport_stage_name' => 'Etapa secundaria',
                'transport_stage_description' => 'Etapa secundaria del transporte, asociada a tramos complementarios al transporte principal.',
            ],
        ];

        $rows = array_map(function (array $row) use ($now) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
            return $row;
        }, $rows);

        DB::table('transport_stages')->upsert(
            $rows,
            ['transport_stage_code'],
            ['transport_stage_name', 'transport_stage_description', 'updated_at']
        );
    }
}
