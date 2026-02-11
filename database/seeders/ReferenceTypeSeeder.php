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
                'reference_type_name' => 'Service order type',
                'reference_type_description' => 'Tipo de orden de servicio asociada a la operación.',
            ],
            [
                'reference_type_code' => 'BN',
                'reference_type_name' => 'Booking reference number',
                'reference_type_description' => 'Número de reserva (booking) asignado por el transportista o proveedor logístico.',
            ],
            [
                'reference_type_code' => 'ACL',
                'reference_type_name' => 'Principal reference number',
                'reference_type_description' => 'Referencia principal utilizada para identificar la operación de transporte.',
            ],
            [
                'reference_type_code' => 'AGW',
                'reference_type_name' => 'Service level',
                'reference_type_description' => 'Nivel de servicio acordado para la operación de transporte.',
            ],
            [
                'reference_type_code' => 'CU',
                'reference_type_name' => 'Consignor reference number',
                'reference_type_description' => 'Referencia asignada por el remitente de la mercancía.',
            ],
            [
                'reference_type_code' => 'CN',
                'reference_type_name' => 'Air Waybill',
                'reference_type_description' => 'Número de guía aérea (AWB) asociada al transporte aéreo.',
            ],
            [
                'reference_type_code' => 'ABT',
                'reference_type_name' => 'Customs Declaration Number',
                'reference_type_description' => 'Número de declaración aduanera asociada a la operación.',
            ],
            [
                'reference_type_code' => 'AVY',
                'reference_type_name' => 'Order lines ID',
                'reference_type_description' => 'Identificador de las líneas de la orden asociadas al servicio.',
            ],
            [
                'reference_type_code' => 'COI',
                'reference_type_name' => 'EORI / Customs ID',
                'reference_type_description' => 'Número de registro e identificación del operador económico o identificación aduanera (EORI).',
            ],
            [
                'reference_type_code' => 'DP',
                'reference_type_name' => 'Customs Declaration Number (alternative)',
                'reference_type_description' => 'Número alternativo de declaración aduanera utilizado para fines de control o trazabilidad.',
            ],
            [
                'reference_type_code' => 'ADE',
                'reference_type_name' => 'Account number',
                'reference_type_description' => 'Número de cuenta que debe ser retornado a GEODIS en cualquier mensaje IFSTA relacionado.',
            ],
            [
                'reference_type_code' => 'IT',
                'reference_type_name' => 'Internal customer number',
                'reference_type_description' => 'Número interno del cliente utilizado para control y trazabilidad interna.',
            ],

            // ✅ FALTANTE: SRN
            [
                'reference_type_code' => 'SRN',
                'reference_type_name' => 'Shipment reference number',
                'reference_type_description' => 'Número de referencia del embarque (Geodis Consignment Id).',
            ],
        ];

        foreach ($rows as $row) {
            $code = strtoupper(trim($row['reference_type_code']));

            $existingId = DB::table('reference_types')
                ->where('reference_type_code', $code)
                ->value('id');

            if ($existingId) {
                // UPDATE (sin updated_at)
                DB::table('reference_types')
                    ->where('id', $existingId)
                    ->update([
                        'reference_type_name' => $row['reference_type_name'],
                        'reference_type_description' => $row['reference_type_description'],
                    ]);
            } else {
                // INSERT (sin updated_at / deleted_at)
                DB::table('reference_types')->insert([
                    'reference_type_code' => $code,
                    'reference_type_name' => $row['reference_type_name'],
                    'reference_type_description' => $row['reference_type_description'],
                    'created_at' => $now,
                ]);
            }
        }
    }
}
