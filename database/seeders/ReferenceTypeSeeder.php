<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReferenceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        DB::table('reference_types')->insert([
            [
                'reference_type_code' => 'ACD',
                'reference_type_name' => 'Service order type',
                'reference_type_description' => 'Tipo de orden de servicio asociada a la operación.',
                'created_at' => $now,
            ],
            [
                'reference_type_code' => 'BN',
                'reference_type_name' => 'Booking reference number',
                'reference_type_description' => 'Número de reserva (booking) asignado por el transportista o proveedor logístico.',
                'created_at' => $now,
            ],
            [
                'reference_type_code' => 'ACL',
                'reference_type_name' => 'Principal reference number',
                'reference_type_description' => 'Referencia principal utilizada para identificar la operación de transporte.',
                'created_at' => $now,
            ],
            [
                'reference_type_code' => 'AGW',
                'reference_type_name' => 'Service level',
                'reference_type_description' => 'Nivel de servicio acordado para la operación de transporte.',
                'created_at' => $now,
            ],
            [
                'reference_type_code' => 'CU',
                'reference_type_name' => 'Consignor reference number',
                'reference_type_description' => 'Referencia asignada por el remitente de la mercancía.',
                'created_at' => $now,
            ],
            [
                'reference_type_code' => 'CN',
                'reference_type_name' => 'Air Waybill',
                'reference_type_description' => 'Número de guía aérea (AWB) asociada al transporte aéreo.',
                'created_at' => $now,
            ],
            [
                'reference_type_code' => 'ABT',
                'reference_type_name' => 'Customs Declaration Number',
                'reference_type_description' => 'Número de declaración aduanera asociada a la operación.',
                'created_at' => $now,
            ],
            [
                'reference_type_code' => 'AVY',
                'reference_type_name' => 'Order lines ID',
                'reference_type_description' => 'Identificador de las líneas de la orden asociadas al servicio.',
                'created_at' => $now,
            ],
            [
                'reference_type_code' => 'COI',
                'reference_type_name' => 'EORI / Customs ID',
                'reference_type_description' => 'Número de registro e identificación del operador económico o identificación aduanera (EORI).',
                'created_at' => $now,
            ],
            [
                'reference_type_code' => 'DP',
                'reference_type_name' => 'Customs Declaration Number (alternative)',
                'reference_type_description' => 'Número alternativo de declaración aduanera utilizado para fines de control o trazabilidad.',
                'created_at' => $now,
            ],
            [
                'reference_type_code' => 'ADE',
                'reference_type_name' => 'Account number',
                'reference_type_description' => 'Número de cuenta que debe ser retornado a GEODIS en cualquier mensaje IFSTA relacionado.',
                'created_at' => $now,
            ],
            [
                'reference_type_code' => 'IT',
                'reference_type_name' => 'Internal customer number',
                'reference_type_description' => 'Número interno del cliente utilizado para control y trazabilidad interna.',
                'created_at' => $now,
            ],
        ]);
    }
}
