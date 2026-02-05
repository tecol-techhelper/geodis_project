<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductIdentifierTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        DB::table('product_identifier_types')->insert([
            [
                'identifier_type_code' => 'MF',
                'identifier_type_name' => "Manufacturer's article number",
                'identifier_type_description' => 'Número de artículo del fabricante (productor).',
                'created_at' => $now,
            ],
            [
                'identifier_type_code' => 'EN',
                'identifier_type_name' => 'EAN (International Article Numbering Association)',
                'identifier_type_description' => 'Código EAN (numeración internacional de artículos).',
                'created_at' => $now,
            ],
            [
                'identifier_type_code' => 'VP',
                'identifier_type_name' => "Vendor's (seller's) part number",
                'identifier_type_description' => 'Número de parte del vendedor (proveedor).',
                'created_at' => $now,
            ],
            [
                'identifier_type_code' => 'MA',
                'identifier_type_name' => 'Machine number',
                'identifier_type_description' => 'Número de máquina.',
                'created_at' => $now,
            ],
            [
                'identifier_type_code' => 'MN',
                'identifier_type_name' => 'Model number',
                'identifier_type_description' => 'Número de modelo.',
                'created_at' => $now,
            ],
            [
                'identifier_type_code' => 'ON',
                'identifier_type_name' => 'Order number',
                'identifier_type_description' => 'Número de orden.',
                'created_at' => $now,
            ],
        ]);
    }
}
