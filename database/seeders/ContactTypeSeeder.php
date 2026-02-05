<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB ::table('contact_types')->insert([
            ['type_tag' => 'AAO', 'type_description' => 'Contacto administrativo', 'created_at' => now()],
            ['type_tag' => 'ABE', 'type_description' => 'Contacto de contabilidad', 'created_at' => now()],
            ['type_tag' => 'DL',  'type_description' => 'Contacto de destino del servicio', 'created_at' => now()],
            ['type_tag' => 'IC',  'type_description' => 'Contacto de información general', 'created_at' => now()],
            ['type_tag' => 'PD',  'type_description' => 'Contacto de compras', 'created_at' => now()],
            ['type_tag' => 'PE',  'type_description' => 'Contacto de ventas', 'created_at' => now()],
            ['type_tag' => 'QA',  'type_description' => 'Contacto de calidad', 'created_at' => now()],
            ['type_tag' => 'SD',  'type_description' => 'Contacto de origen del servicio', 'created_at' => now()],
            ['type_tag' => 'SU',  'type_description' => 'Proveedor', 'created_at' => now()],
            ['type_tag' => 'TR',  'type_description' => 'Transportista', 'created_at' => now()],
            ['type_tag' => 'UC',  'type_description' => 'Contacto de soporte técnico', 'created_at' => now()],
            ['type_tag' => 'ZZZ', 'type_description' => 'Otro contacto específico', 'created_at' => now()]]);
    }
}
