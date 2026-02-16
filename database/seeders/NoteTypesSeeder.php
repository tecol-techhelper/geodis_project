<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NoteTypesSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $rows = [
            [
                'note_type_code' => 'LOI',
                'note_type_name' => 'Instrucciones de cargue',
                'note_type_description' => 'Instrucciones de cargue: indicaciones específicas para el proceso de carga.',
            ],
            [
                'note_type_code' => 'DIN',
                'note_type_name' => 'Instrucciones de entrega',
                'note_type_description' => 'Instrucciones de entrega: indicaciones específicas para la entrega.',
            ],
            [
                'note_type_code' => 'ICN',
                'note_type_name' => 'Información para el consignatario',
                'note_type_description' => 'Información para el consignatario: datos relevantes para el destinatario.',
            ],
            [
                'note_type_code' => 'SIN',
                'note_type_name' => 'Instrucciones especiales',
                'note_type_description' => 'Instrucciones especiales: indicaciones adicionales o excepciones para la operación.',
            ],
            [
                'note_type_code' => 'HAN',
                'note_type_name' => 'Instrucciones de manipulación',
                'note_type_description' => 'Instrucciones de manipulación: condiciones de manejo, estiba, cuidado o restricción.',
            ],
            [
                'note_type_code' => 'DEL',
                'note_type_name' => 'Información sobre la entrega',
                'note_type_description' => 'Información sobre la entrega: datos generales asociados al proceso de entrega.',
            ],
            [
                'note_type_code' => 'AAA',
                'note_type_name' => 'Descripción de la mercancía',
                'note_type_description' => 'Descripción de la mercancía: texto libre describiendo los bienes.',
            ],
            [
                'note_type_code' => 'AAI',
                'note_type_name' => 'Información general',
                'note_type_description' => 'Información general: notas generales relacionadas con la operación o el servicio.',
            ],
            [
                'note_type_code' => 'AUY',
                'note_type_name' => 'Característica del servicio',
                'note_type_description' => 'Característica del servicio: atributo o tipo de servicio (p. ej., Tipo de servicio).',
            ],
            [
                'note_type_code' => 'COI',
                'note_type_name' => 'Información de la orden',
                'note_type_description' => 'Información de la orden: datos asociados a la orden (p. ej., tipo de orden).',
            ],
        ];

        $rows = array_map(function (array $row) use ($now) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
            return $row;
        }, $rows);

        DB::table('note_types')->upsert(
            $rows,
            ['note_type_code'],
            ['note_type_name', 'note_type_description', 'updated_at']
        );
    }
}
