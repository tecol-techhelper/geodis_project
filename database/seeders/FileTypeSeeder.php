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
            ['file_type' => 'CLP', 'file_type_full_name' => 'CHECK LIST PREOPERACIONAL'],
            [
                'file_type' => 'GABF301',
                'file_type_full_name' => 'CONTAINER AND CARGO UNIT INSPECTION FORMAT FOR IMPORT AND EXPORT',
            ],
            ['file_type' => 'GPS', 'file_type_full_name' => 'REPORTE DE GPS - IMPO // EXPO'],
            ['file_type' => 'IC', 'file_type_full_name' => 'INFORME DE CARGUE (REGISTRO FOTOGRÁFICO)'],
            ['file_type' => 'ID', 'file_type_full_name' => 'INFORME DE DESCARGUE'],
            ['file_type' => 'IF', 'file_type_full_name' => 'INFORME FINAL'],
            ['file_type' => 'PDR', 'file_type_full_name' => 'PLAN DE RUTA CONTENEDORES - IMPO // EXPO'],
            ['file_type' => 'RP', 'file_type_full_name' => 'REEMPAQUES'],
            ['file_type' => 'RT', 'file_type_full_name' => 'REMESA DE TRANSPORTE'],
            ['file_type' => 'TDC', 'file_type_full_name' => 'TIRILLA DE DEVOLUCION DE CONTENEDORES'],
            ['file_type' => 'TRC', 'file_type_full_name' => 'TIRILLA DE RETIRO DE CONTENEDORES'],
        ];

        $validCodes = array_map(fn($row) => strtoupper(trim($row['file_type'])), $rows);

        DB::table('file_types')
            ->whereNotIn('file_type', $validCodes)
            ->whereNull('deleted_at')
            ->update([
                'deleted_at' => $now,
                'updated_at' => $now,
            ]);

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
                        'deleted_at' => null,
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
