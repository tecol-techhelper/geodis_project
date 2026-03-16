<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $rows = [
            [
                'edifact_code' => '13',
                'status_name' => 'Recolectado',
                'status_description' => 'Recolección por el transportista nacional en puerto / aeropuerto',
                'status_be' => 'ACT013',
            ],
            [
                'edifact_code' => '29',
                'status_name' => 'Recibido - Xdock/Bodega',
                'status_description' => 'Entrega en bodega de consolidación',
                'status_be' => 'WHRECP',
            ],
            [
                'edifact_code' => '35',
                'status_name' => 'En ruta para entrega',
                'status_description' => 'Inicio de traslado desde bodega de consolidación hacia destino final',
                'status_be' => 'ACT035',
            ],
            [
                'edifact_code' => '74',
                'status_name' => 'Entrega',
                'status_description' => 'Entrega en Destino final',
                'status_be' => 'ACT021',
            ],
            [
                'edifact_code' => '79',
                'status_name' => 'Contenedor vacío recolectado',
                'status_description' => 'Recolección de contenedor vacío por el transportista en destino final',
                'status_be' => 'CNT013',
            ],
            [
                'edifact_code' => '82',
                'status_name' => 'Contenedor vacío entregado',
                'status_description' => 'Entrega del Contenedor por el transportista en puerto origen',
                'status_be' => 'CNT021',
            ],
            [
                'edifact_code' => '13',
                'status_name' => 'Recolectado',
                'status_description' => 'Recolección en bodega de Origen',
                'status_be' => 'ACT013',
            ],
            [
                'edifact_code' => '74',
                'status_name' => 'Entregado',
                'status_description' => 'Recolección en bodega de Destino',
                'status_be' => 'ACT021',
            ],
        ];

        foreach ($rows as $row) {
            $code = strtoupper(trim($row['status_be']));
            $edifact = strtoupper(trim($row['edifact_code']));

            $existingId = DB::table('statuses')
                ->where('status_be', $code)
                ->where('edifact_code', $edifact)
                ->where('status_name', $row['status_name'])
                ->where('status_description', $row['status_description'])
                ->value('id');

            if ($existingId) {
                DB::table('statuses')
                    ->where('id', $existingId)
                    ->update([
                        'status_name' => $row['status_name'],
                        'status_description' => $row['status_description'],
                        'edifact_code' => $edifact,
                        'updated_at' => $now,
                    ]);
            } else {
                DB::table('statuses')->insert([
                    'segment_tag' => 'STS',
                    'status_name' => $row['status_name'],
                    'status_description' => $row['status_description'],
                    'status_be' => $code,
                    'edifact_code' => $edifact,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
