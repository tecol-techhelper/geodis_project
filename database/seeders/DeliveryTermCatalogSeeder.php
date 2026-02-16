<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeliveryTermCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $rows = [
            [
                'term_code' => 'FAS',
                'term_name' => 'Franco al costado del buque',
                'term_description' => 'El vendedor entrega la mercancía al costado del buque en el puerto de embarque designado.',
            ],
            [
                'term_code' => 'FOB',
                'term_name' => 'Franco a bordo',
                'term_description' => 'El vendedor entrega la mercancía a bordo del buque en el puerto de embarque acordado.',
            ],
            [
                'term_code' => 'CFR',
                'term_name' => 'Costo y flete',
                'term_description' => 'El vendedor asume los costos y el flete necesarios para llevar la mercancía hasta el puerto de destino.',
            ],
            [
                'term_code' => 'CIF',
                'term_name' => 'Costo, seguro y flete',
                'term_description' => 'El vendedor asume los costos, el seguro y el flete hasta el puerto de destino.',
            ],
            [
                'term_code' => 'EXW',
                'term_name' => 'En fábrica',
                'term_description' => 'El vendedor pone la mercancía a disposición del comprador en sus propias instalaciones.',
            ],
            [
                'term_code' => 'FCA',
                'term_name' => 'Franco transportista',
                'term_description' => 'El vendedor entrega la mercancía al transportista designado por el comprador en el lugar acordado.',
            ],
            [
                'term_code' => 'CPT',
                'term_name' => 'Transporte pagado hasta',
                'term_description' => 'El vendedor paga el transporte hasta el lugar de destino acordado.',
            ],
            [
                'term_code' => 'CIP',
                'term_name' => 'Transp. y seguro pagados hasta',
                'term_description' => 'El vendedor paga el transporte y el seguro hasta el lugar de destino acordado.',
            ],
            [
                'term_code' => 'DDP',
                'term_name' => 'Entregado con derechos pagados',
                'term_description' => 'El vendedor entrega la mercancía en destino asumiendo todos los costos, impuestos y derechos.',
            ],
            [
                'term_code' => 'DAT',
                'term_name' => 'Entregado en terminal',
                'term_description' => 'El vendedor entrega la mercancía descargada en una terminal designada en el lugar de destino.',
            ],
            [
                'term_code' => 'DAF',
                'term_name' => 'Entregado en frontera',
                'term_description' => 'El vendedor entrega la mercancía en la frontera antes de la aduana del país de destino.',
            ],
            [
                'term_code' => 'DES',
                'term_name' => 'Entregado sobre buque',
                'term_description' => 'El vendedor entrega la mercancía a bordo del buque en el puerto de destino.',
            ],
            [
                'term_code' => 'DDU',
                'term_name' => 'Entregado sin derechos pagados',
                'term_description' => 'El vendedor entrega la mercancía en destino sin asumir derechos ni impuestos de importación.',
            ],
            [
                'term_code' => 'DAP',
                'term_name' => 'Entregado en lugar',
                'term_description' => 'El vendedor entrega la mercancía en el lugar acordado, lista para ser descargada.',
            ],
        ];

        $rows = array_map(function (array $row) use ($now) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
            return $row;
        }, $rows);

        DB::table('delivery_term_catalogs')->upsert(
            $rows,
            ['term_code'],
            ['term_name', 'term_description', 'updated_at']
        );
    }
}
