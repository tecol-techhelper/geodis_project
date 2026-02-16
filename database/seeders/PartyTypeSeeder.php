<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartyTypeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $rows = [
            [
                'party_qualifier' => 'CN',
                'party_type_name' => 'Consignatario',
                'party_type_description' => 'Parte que recibe físicamente la mercancía en el destino final.',
            ],
            [
                'party_qualifier' => 'DP',
                'party_type_name' => 'Parte de entrega',
                'party_type_description' => 'Parte responsable de la recepción en el punto de entrega cuando es diferente del consignatario.',
            ],
            [
                'party_qualifier' => 'MA',
                'party_type_name' => 'Destinatario final',
                'party_type_description' => 'Parte para la cual la mercancía está destinada en última instancia.',
            ],
            [
                'party_qualifier' => 'PF',
                'party_type_name' => 'Receptor de factura de flete',
                'party_type_description' => 'Parte responsable de recibir y gestionar la factura correspondiente al transporte.',
            ],
            [
                'party_qualifier' => 'PW',
                'party_type_name' => 'Parte de despacho',
                'party_type_description' => 'Parte responsable del despacho o envío de la mercancía cuando es diferente del consignador.',
            ],
            [
                'party_qualifier' => 'CA',
                'party_type_name' => 'Transportador',
                'party_type_description' => 'Empresa o entidad encargada del transporte de la mercancía.',
            ],
            [
                'party_qualifier' => 'CZ',
                'party_type_name' => 'Consignador',
                'party_type_description' => 'Parte que envía o expide la mercancía desde el punto de origen.',
            ],
        ];

        $rows = array_map(function (array $row) use ($now) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
            return $row;
        }, $rows);

        DB::table('party_types')->upsert(
            $rows,
            ['party_qualifier'],
            ['party_type_name', 'party_type_description', 'updated_at']
        );
    }
}
