<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IdentifierTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        DB::table('identifier_types')->insert([
            [
                'identifier_type_code' => 18,
                'identifier_type_name' => 'Carrier instructions',
                'identifier_type_description' => 'Instrucciones proporcionadas por el transportista para la ejecución del servicio.',
                'created_at' => $now,
            ],
            [
                'identifier_type_code' => 24,
                'identifier_type_name' => 'Shipper assigned',
                'identifier_type_description' => 'Identificador asignado por el remitente (shipper) para la identificación de la mercancía o del envío.',
                'created_at' => $now,
            ],
            [
                'identifier_type_code' => 30,
                'identifier_type_name' => 'Mark serial shipping container code',
                'identifier_type_description' => 'Código serial de marcación del contenedor de envío utilizado para identificación y trazabilidad.',
                'created_at' => $now,
            ],
        ]);
    }
}
