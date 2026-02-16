<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $rows = [
            ['type_tag' => 'AAO', 'type_description' => 'Contacto administrativo'],
            ['type_tag' => 'ABE', 'type_description' => 'Contacto de contabilidad'],
            ['type_tag' => 'DL',  'type_description' => 'Contacto de destino del servicio'],
            ['type_tag' => 'IC',  'type_description' => 'Contacto de información general'],
            ['type_tag' => 'PD',  'type_description' => 'Contacto de compras'],
            ['type_tag' => 'PE',  'type_description' => 'Contacto de ventas'],
            ['type_tag' => 'QA',  'type_description' => 'Contacto de calidad'],
            ['type_tag' => 'SD',  'type_description' => 'Contacto de origen del servicio'],
            ['type_tag' => 'SU',  'type_description' => 'Proveedor'],
            ['type_tag' => 'TR',  'type_description' => 'Transportista'],
            ['type_tag' => 'UC',  'type_description' => 'Contacto de soporte técnico'],
            ['type_tag' => 'ZZZ', 'type_description' => 'Otro contacto específico'],
        ];

        foreach ($rows as $row) {
            $tag = strtoupper(trim($row['type_tag']));

            $existingId = DB::table('contact_types')
                ->where('type_tag', $tag)
                ->value('id');

            if ($existingId) {
                DB::table('contact_types')
                    ->where('id', $existingId)
                    ->update([
                        'type_description' => $row['type_description'],
                        'updated_at' => $now,
                    ]);
            } else {
                DB::table('contact_types')->insert([
                    'type_tag' => $tag,
                    'type_description' => $row['type_description'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
