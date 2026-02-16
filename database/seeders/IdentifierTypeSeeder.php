<?php

namespace Database\Seeders;

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

        $rows = [
            [
                'identifier_type_code' => 18,
                'identifier_type_name' => 'Instrucciones del transportista',
                'identifier_type_description' => 'Instrucciones proporcionadas por el transportista para la ejecución del servicio.',
            ],
            [
                'identifier_type_code' => 24,
                'identifier_type_name' => 'Asignado por el remitente',
                'identifier_type_description' => 'Identificador asignado por el remitente (shipper) para la identificación de la mercancía o del envío.',
            ],
            [
                'identifier_type_code' => 30,
                'identifier_type_name' => 'Código serial de marcación del contenedor',
                'identifier_type_description' => 'Código serial de marcación del contenedor de envío utilizado para identificación y trazabilidad.',
            ],
        ];

        $rows = array_map(function (array $row) use ($now) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
            return $row;
        }, $rows);

        DB::table('identifier_types')->upsert(
            $rows,
            ['identifier_type_code'],
            ['identifier_type_name', 'identifier_type_description', 'updated_at']
        );
    }
}
