<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NoteTypesSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('note_types')->insert([
            [
                'note_type_code' => 'LOI',
                'note_type_name' => 'Loading instructions',
                'note_type_description' => 'Instrucciones de cargue: indicaciones específicas para el proceso de carga.',
                'created_at' => $now,
            ],
            [
                'note_type_code' => 'DIN',
                'note_type_name' => 'Delivery instructions',
                'note_type_description' => 'Instrucciones de entrega: indicaciones específicas para la entrega.',
                'created_at' => $now,
            ],
            [
                'note_type_code' => 'ICN',
                'note_type_name' => 'Information for consignee',
                'note_type_description' => 'Información para el consignatario: datos relevantes para el destinatario.',
                'created_at' => $now,
            ],
            [
                'note_type_code' => 'SIN',
                'note_type_name' => 'Special instructions',
                'note_type_description' => 'Instrucciones especiales: indicaciones adicionales o excepciones para la operación.',
                'created_at' => $now,
            ],
            [
                'note_type_code' => 'HAN',
                'note_type_name' => 'Handling instructions',
                'note_type_description' => 'Instrucciones de manipulación: condiciones de manejo, estiba, cuidado o restricción.',
                'created_at' => $now,
            ],
            [
                'note_type_code' => 'DEL',
                'note_type_name' => 'Delivery information',
                'note_type_description' => 'Información sobre la entrega: datos generales asociados al proceso de entrega.',
                'created_at' => $now,
            ],
            [
                'note_type_code' => 'AAA',
                'note_type_name' => 'Goods description',
                'note_type_description' => 'Descripción de la mercancía: texto libre describiendo los bienes.',
                'created_at' => $now,
            ],
            [
                'note_type_code' => 'AAI',
                'note_type_name' => 'General information',
                'note_type_description' => 'Información general: notas generales relacionadas con la operación o el servicio.',
                'created_at' => $now,
            ],
            [
                'note_type_code' => 'AUY',
                'note_type_name' => 'Service characteristic',
                'note_type_description' => 'Característica del servicio: atributo o tipo de servicio (p. ej., Service Type).',
                'created_at' => $now,
            ],
            [
                'note_type_code' => 'COI',
                'note_type_name' => 'Order information',
                'note_type_description' => 'Información de la orden: datos asociados a la orden (p. ej., tipo de orden).',
                'created_at' => $now,
            ],
        ]);
    }
}