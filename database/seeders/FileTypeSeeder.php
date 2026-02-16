<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FileTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $rows = [
            ['file_type' => 'CLI', 'file_type_full_name' => 'CUMPLIDO'],
            ['file_type' => 'CLP', 'file_type_full_name' => 'Check List Preoperacional'],
            ['file_type' => 'IC', 'file_type_full_name' => 'Informe de Cargue'],
            ['file_type' => 'IF', 'file_type_full_name' => 'Informe final (Registro Fotográfico)'],
            ['file_type' => 'RO', 'file_type_full_name' => 'Reporte OnSite'],
            ['file_type' => 'RT', 'file_type_full_name' => 'Remesa de Transporte'],
            ['file_type' => 'ID', 'file_type_full_name' => 'Informe de Descargue'],
            ['file_type' => 'TRC', 'file_type_full_name' => 'Tirilla de Retiro de Contenedores'],
            ['file_type' => 'TDC', 'file_type_full_name' => 'Tirilla de Devolución de Contenedores'],
            [
                'file_type' => 'GABF301',
                'file_type_full_name' => 'Formato de inspección de contenedores y unidades de carga para importación y exportación',
            ],
            ['file_type' => 'PDR', 'file_type_full_name' => 'Plan de Ruta Contenedores - Impo // Expo'],
            ['file_type' => 'GPS', 'file_type_full_name' => 'Reporte de GPS - Impo // Expo'],
            ['file_type' => 'RP', 'file_type_full_name' => 'Reempaques'],
        ];

        foreach ($rows as $row) {
            $code = strtoupper(trim($row['file_type']));

            $existingId = DB::table('file_types')
                ->where('file_type', $code)
                ->value('id');

            if ($existingId) {
                DB::table('file_types')
                    ->where('id', $existingId)
                    ->update([
                        'file_type_full_name' => $row['file_type_full_name'],
                        'updated_at' => $now,
                    ]);
            } else {
                DB::table('file_types')->insert([
                    'file_type' => $code,
                    'file_type_full_name' => $row['file_type_full_name'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
