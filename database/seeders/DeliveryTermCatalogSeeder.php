<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeliveryTermCatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        DB::table('delivery_term_catalogs')->insert([
            [
                'term_code' => 'FAS',
                'term_name' => 'Free Alongside Ship',
                'term_description' => 'El vendedor entrega la mercancía al costado del buque en el puerto de embarque designado.',
                'created_at' => $now,
            ],
            [
                'term_code' => 'FOB',
                'term_name' => 'Free On Board',
                'term_description' => 'El vendedor entrega la mercancía a bordo del buque en el puerto de embarque acordado.',
                'created_at' => $now,
            ],
            [
                'term_code' => 'CFR',
                'term_name' => 'Cost and Freight',
                'term_description' => 'El vendedor asume los costos y el flete necesarios para llevar la mercancía hasta el puerto de destino.',
                'created_at' => $now,
            ],
            [
                'term_code' => 'CIF',
                'term_name' => 'Cost, Insurance and Freight',
                'term_description' => 'El vendedor asume costos, seguro y flete hasta el puerto de destino.',
                'created_at' => $now,
            ],
            [
                'term_code' => 'EXW',
                'term_name' => 'Ex Works',
                'term_description' => 'El vendedor pone la mercancía a disposición del comprador en sus propias instalaciones.',
                'created_at' => $now,
            ],
            [
                'term_code' => 'FCA',
                'term_name' => 'Free Carrier',
                'term_description' => 'El vendedor entrega la mercancía al transportista designado por el comprador en el lugar acordado.',
                'created_at' => $now,
            ],
            [
                'term_code' => 'CPT',
                'term_name' => 'Carriage Paid To',
                'term_description' => 'El vendedor paga el transporte hasta el lugar de destino acordado.',
                'created_at' => $now,
            ],
            [
                'term_code' => 'CIP',
                'term_name' => 'Carriage and Insurance Paid To',
                'term_description' => 'El vendedor paga el transporte y el seguro hasta el lugar de destino acordado.',
                'created_at' => $now,
            ],
            [
                'term_code' => 'DDP',
                'term_name' => 'Delivered Duty Paid',
                'term_description' => 'El vendedor entrega la mercancía en destino asumiendo todos los costos, impuestos y derechos.',
                'created_at' => $now,
            ],
            [
                'term_code' => 'DAT',
                'term_name' => 'Delivered At Terminal',
                'term_description' => 'El vendedor entrega la mercancía descargada en una terminal designada en el lugar de destino.',
                'created_at' => $now,
            ],
            [
                'term_code' => 'DAF',
                'term_name' => 'Delivered At Frontier',
                'term_description' => 'El vendedor entrega la mercancía en la frontera antes de la aduana del país de destino.',
                'created_at' => $now,
            ],
            [
                'term_code' => 'DES',
                'term_name' => 'Delivered Ex Ship',
                'term_description' => 'El vendedor entrega la mercancía a bordo del buque en el puerto de destino.',
                'created_at' => $now,
            ],
            [
                'term_code' => 'DDU',
                'term_name' => 'Delivered Duty Unpaid',
                'term_description' => 'El vendedor entrega la mercancía en destino sin asumir derechos ni impuestos de importación.',
                'created_at' => $now,
            ],
            [
                'term_code' => 'DAP',
                'term_name' => 'Delivered At Place',
                'term_description' => 'El vendedor entrega la mercancía en el lugar acordado, lista para ser descargada.',
                'created_at' => $now,
            ],
        ]);
    }
}
