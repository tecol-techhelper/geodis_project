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
                'edifact_code' => '0',
                'status_name' => 'Asignacion',
                'status_description' => 'Estado interno de asignacion',
                'status_be' => 'ASIG',
                'purpose_subcode' => 'GENERAL',
            ],
            [
                'edifact_code' => '13',
                'status_name' => 'Recolectado',
                'status_description' => 'Recoleccion por el transportista nacional en puerto / aeropuerto',
                'status_be' => 'ACT013',
                'purpose_subcode' => 'POST-CARRIAGE',
            ],
            [
                'edifact_code' => '29',
                'status_name' => 'Recibido - Xdock/Bodega',
                'status_description' => 'Entrega en bodega de consolidacion',
                'status_be' => 'WHRECP',
                'purpose_subcode' => 'POST-CARRIAGE',
            ],
            [
                'edifact_code' => '35',
                'status_name' => 'En ruta para entrega',
                'status_description' => 'Inicio de traslado desde bodega de consolidacion hacia destino final',
                'status_be' => 'ACT035',
                'purpose_subcode' => 'DOM-CONSOL',
            ],
            [
                'edifact_code' => '74',
                'status_name' => 'Entrega',
                'status_description' => 'Entrega en Destino final',
                'status_be' => 'ACT021',
                'purpose_subcode' => 'DOM-CONSOL',
            ],
            [
                'edifact_code' => '79',
                'status_name' => 'Contenedor vacio recolectado',
                'status_description' => 'Recoleccion de contenedor vacio por el transportista en destino final',
                'status_be' => 'CNT013',
                'purpose_subcode' => 'EMPTY-CONTAINER',
            ],
            [
                'edifact_code' => '82',
                'status_name' => 'Contenedor vacio entregado',
                'status_description' => 'Entrega del Contenedor por el transportista en puerto origen',
                'status_be' => 'CNT021',
                'purpose_subcode' => 'EMPTY-CONTAINER',
            ],
            [
                'edifact_code' => '13',
                'status_name' => 'Recolectado',
                'status_description' => 'Recoleccion en bodega de Origen',
                'status_be' => 'ACT013',
                'purpose_subcode' => 'DELIVERY-SO',
            ],
            [
                'edifact_code' => '74',
                'status_name' => 'Entregado',
                'status_description' => 'Recoleccion en bodega de Destino',
                'status_be' => 'ACT021',
                'purpose_subcode' => 'DELIVERY-SO',
            ],
        ];


                $purposeMap = DB::table('status_purposes')
            ->get()
            ->mapWithKeys(function ($row) {
                $code = strtoupper(trim((string) $row->purpose_code));
                $sub = strtoupper(trim((string) ($row->purpose_subcode ?? '')));
                return ["{$code}|{$sub}" => (int) $row->id];
            })
            ->all();

        $subcodeToPurpose = [
            'GENERAL' => 'GEN',
            'POST-CARRIAGE' => 'LOG',
            'DOM-CONSOL' => 'LOG',
            'EMPTY-CONTAINER' => 'LOG',
            'DELIVERY-SO' => 'TEBS',
        ];

        foreach ($rows as $row) {
            $code = strtoupper(trim($row['status_be']));
            $edifact = strtoupper(trim($row['edifact_code']));
            $purposeSubcode = strtoupper(trim((string) ($row['purpose_subcode'] ?? '')));
            $purposeCode = $subcodeToPurpose[$purposeSubcode] ?? '';
            $purposeKey = "{$purposeCode}|{$purposeSubcode}";
            $purposeId = $purposeCode !== '' ? ($purposeMap[$purposeKey] ?? null) : null;


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
                        'status_purpose_id' => $purposeId,
                        'updated_at' => $now,
                    ]);
            } else {
                DB::table('statuses')->insert([
                    'segment_tag' => 'STS',
                    'status_name' => $row['status_name'],
                    'status_description' => $row['status_description'],
                    'status_be' => $code,
                    'edifact_code' => $edifact,
                    'status_purpose_id' => $purposeId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}













