<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReferenceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $rows = [
            [
                'reference_type_code' => 'ACD',
                'reference_type_name' => 'Tipo de orden de servicio',
                'reference_type_description' => 'Tipo de orden de servicio asociada a la operación.',
            ],
            [
                'reference_type_code' => 'BN',
                'reference_type_name' => 'Número de referencia de reserva',
                'reference_type_description' => 'Número de reserva (booking) asignado por el transportista o proveedor logístico.',
            ],
            [
                'reference_type_code' => 'ACL',
                'reference_type_name' => 'Número de referencia principal',
                'reference_type_description' => 'Referencia principal utilizada para identificar la operación de transporte.',
            ],
            [
                'reference_type_code' => 'AGW',
                'reference_type_name' => 'Nivel de servicio',
                'reference_type_description' => 'Nivel de servicio acordado para la operación de transporte.',
            ],
            [
                'reference_type_code' => 'CU',
                'reference_type_name' => 'Número de referencia del consignador',
                'reference_type_description' => 'Referencia asignada por el remitente de la mercancía.',
            ],
            [
                'reference_type_code' => 'CN',
                'reference_type_name' => 'Guía aérea',
                'reference_type_description' => 'Número de guía aérea (AWB) asociada al transporte aéreo.',
            ],
            [
                'reference_type_code' => 'ABT',
                'reference_type_name' => 'Número de declaración aduanera',
                'reference_type_description' => 'Número de declaración aduanera asociada a la operación.',
            ],
            [
                'reference_type_code' => 'AVY',
                'reference_type_name' => 'ID de líneas de orden',
                'reference_type_description' => 'Identificador de las líneas de la orden asociadas al servicio.',
            ],
            [
                'reference_type_code' => 'COI',
                'reference_type_name' => 'EORI / ID aduanero',
                'reference_type_description' => 'Número de registro e identificación del operador económico o identificación aduanera (EORI).',
            ],
            [
                'reference_type_code' => 'DP',
                'reference_type_name' => 'Número de declaración aduanera (alternativo)',
                'reference_type_description' => 'Número alternativo de declaración aduanera utilizado para fines de control o trazabilidad.',
            ],
            [
                'reference_type_code' => 'ADE',
                'reference_type_name' => 'Número de cuenta',
                'reference_type_description' => 'Número de cuenta que debe ser retornado a GEODIS en cualquier mensaje IFSTA relacionado.',
            ],
            [
                'reference_type_code' => 'IT',
                'reference_type_name' => 'Número interno del cliente',
                'reference_type_description' => 'Número interno del cliente utilizado para control y trazabilidad interna.',
            ],
            [
                'reference_type_code' => 'SRN',
                'reference_type_name' => 'Número de referencia del embarque',
                'reference_type_description' => 'Número de referencia del embarque (Geodis Consignment Id).',
            ],
        ];

        $rows = array_map(function (array $row) use ($now) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
            return $row;
        }, $rows);

        DB::table('reference_types')->upsert(
            $rows,
            ['reference_type_code'],
            ['reference_type_name', 'reference_type_description', 'updated_at']
        );
    }
}
