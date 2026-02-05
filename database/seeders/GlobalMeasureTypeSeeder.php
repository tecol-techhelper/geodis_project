<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GlobalMeasureTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('global_measure_types')->insert([
            ['type_qualifier'=>7, 'type_description'=>'Peso Bruto Total', 'created_at'=>now()],
            ['type_qualifier'=>8, 'type_description'=>'Cantidad Total Piezas', 'created_at'=>now()],
            ['type_qualifier'=>11, 'type_description'=>'Número Total de Paquetes', 'created_at'=>now()],
            ['type_qualifier'=>15, 'type_description'=>'Volumen Total', 'created_at'=>now()],
            ['type_qualifier'=>26, 'type_description'=>'Volumen Bruto Total', 'created_at'=>now()],
            ['type_qualifier'=>29, 'type_description'=>'Peso Neto Total', 'created_at'=>now()],
        ]);
    }
}
