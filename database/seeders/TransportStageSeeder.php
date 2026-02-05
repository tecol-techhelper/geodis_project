<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        DB::table('transport_stages')->insert([
            [
                'transport_stage_code' => 20,
                'transport_stage_name' => 'Main stage',
                'transport_stage_description' => 'Etapa principal del transporte del servicio.',
                'created_at' => $now,
            ],
            [
                'transport_stage_code' => 21,
                'transport_stage_name' => 'Main carriage - first carrier',
                'transport_stage_description' => 'Primer transportista que participa en la ejecución del tramo principal del transporte.',
                'created_at' => $now,
            ],
            [
                'transport_stage_code' => 22,
                'transport_stage_name' => 'Main carriage - second carrier',
                'transport_stage_description' => 'Segundo transportista que participa en la ejecución del mismo tramo principal del transporte.',
                'created_at' => $now,
            ],
            [
                'transport_stage_code' => 23,
                'transport_stage_name' => 'Main carriage - third carrier',
                'transport_stage_description' => 'Tercer transportista involucrado en la ejecución del tramo principal del transporte.',
                'created_at' => $now,
            ],
            [
                'transport_stage_code' => 24,
                'transport_stage_name' => 'Main carriage - fourth carrier',
                'transport_stage_description' => 'Cuarto transportista involucrado en la ejecución del tramo principal del transporte.',
                'created_at' => $now,
            ],
            [
                'transport_stage_code' => 28,
                'transport_stage_name' => 'Secondary stage',
                'transport_stage_description' => 'Etapa secundaria del transporte, asociada a tramos complementarios al transporte principal.',
                'created_at' => $now,
            ],
        ]);
    }
}
