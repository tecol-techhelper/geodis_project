<?php

namespace Database\Seeders;

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

        $rows = [
            [
                'identifier_type_code' => 'MF',
                'identifier_type_name' => 'Número de artículo del fabricante',
                'identifier_type_description' => 'Número de artículo del fabricante (productor).',
            ],
            [
                'identifier_type_code' => 'EN',
                'identifier_type_name' => 'EAN (Asociación Internacional de Numeración de Artículos)',
                'identifier_type_description' => 'Código EAN (numeración internacional de artículos).',
            ],
            [
                'identifier_type_code' => 'VP',
                'identifier_type_name' => 'Número de parte del vendedor',
                'identifier_type_description' => 'Número de parte del vendedor (proveedor).',
            ],
            [
                'identifier_type_code' => 'MA',
                'identifier_type_name' => 'Número de máquina',
                'identifier_type_description' => 'Número de máquina.',
            ],
            [
                'identifier_type_code' => 'MN',
                'identifier_type_name' => 'Número de modelo',
                'identifier_type_description' => 'Número de modelo.',
            ],
            [
                'identifier_type_code' => 'ON',
                'identifier_type_name' => 'Número de orden',
                'identifier_type_description' => 'Número de orden.',
            ],
        ];

        $rows = array_map(function (array $row) use ($now) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
            return $row;
        }, $rows);

        DB::table('product_identifier_types')->upsert(
            $rows,
            ['identifier_type_code'],
            ['identifier_type_name', 'identifier_type_description', 'updated_at']
        );
    }
}
