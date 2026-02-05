<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        DB::table('price_qualifiers')->insert([
            [
                'price_qualifier_code' => 'AAB',
                'price_qualifier_name' => 'Calculation gross',
                'price_qualifier_description' => 'Cálculo bruto: valor antes de deducciones o descuentos.',
                'created_at' => $now
            ],
            [
                'price_qualifier_code' => 'INV',
                'price_qualifier_name' => 'Invoice Price',
                'price_qualifier_description' => 'Precio de factura: valor facturado al cliente.',
                'created_at' => $now
            ],
            [
                'price_qualifier_code' => 'AAA',
                'price_qualifier_name' => 'Calculation net',
                'price_qualifier_description' => 'Cálculo neto: valor después de deducciones o descuentos.',
                'created_at' => $now
            ],
        ]);
    }
}
