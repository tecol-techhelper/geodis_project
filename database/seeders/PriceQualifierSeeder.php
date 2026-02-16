<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PriceQualifierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $rows = [
            [
                'price_qualifier_code' => 'AAB',
                'price_qualifier_name' => 'Cálculo bruto',
                'price_qualifier_description' => 'Cálculo bruto: valor antes de deducciones o descuentos.',
            ],
            [
                'price_qualifier_code' => 'INV',
                'price_qualifier_name' => 'Precio de factura',
                'price_qualifier_description' => 'Precio de factura: valor facturado al cliente.',
            ],
            [
                'price_qualifier_code' => 'AAA',
                'price_qualifier_name' => 'Cálculo neto',
                'price_qualifier_description' => 'Cálculo neto: valor después de deducciones o descuentos.',
            ],
        ];

        $rows = array_map(function (array $row) use ($now) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
            return $row;
        }, $rows);

        DB::table('price_qualifiers')->upsert(
            $rows,
            ['price_qualifier_code'],
            ['price_qualifier_name', 'price_qualifier_description', 'updated_at']
        );
    }
}
