<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FileTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $array = array(
            array(
                'file_type' => 'CLI',
                'file_type_full_name' => 'CUMPLIDO',
                'created_at' => now()
            ),
            array(
                'file_type' => 'CLP',
                'file_type_full_name' => 'Check List Preoperacional',
                'created_at' => now()
            ),
            array(
                'file_type' => 'IC',
                'file_type_full_name' => 'Informe de Cargue',
                'created_at' => now()
            ),
            array(
                'file_type' => 'IF',
                'file_type_full_name' => 'Informe final (Registro Fotográfico)',
                'created_at' => now()
            ),
            array(
                'file_type' => 'RO',
                'file_type_full_name' => 'Reporte OnSite',
                'created_at' => now()
            ),
            array(
                'file_type' => 'RT',
                'file_type_full_name' => 'Remesa de Transporte',
                'created_at' => now()
            ),
            array(
                'file_type' => 'ID',
                'file_type_full_name' => 'Informe de Descargue',
                'created_at' => now()
            ),
            array(
                'file_type' => 'TRC',
                'file_type_full_name' => 'Tirilla de Retiro de Contenedores',
                'created_at' => now()
            ),
            array(
                'file_type' => 'TDC',
                'file_type_full_name' => 'Tirilla de Devolución de Contenedores',
                'created_at' => now()
            ),
            array(
                'file_type' => 'GABF301',
                'file_type_full_name' => 'Formato de inspección de contenedores y unidades de carga para importación y exportación',
                'created_at' => now()
            ),
            array(
                'file_type' => 'PDR',
                'file_type_full_name' => 'Plan de Ruta Contenedores – Impo // Expo',
                'created_at' => now()
            ),
            array(
                'file_type' => 'GPS',
                'file_type_full_name' => 'Reporte de GPS – Impo // Expo',
                'created_at' => now()
            ),
            array(
                'file_type' => 'RP',
                'file_type_full_name' => 'Reempaques',
                'created_at' => now()
            )
        );

        DB::table('file_types')->insert($array);
    }
}
