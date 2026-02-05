<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DateTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('date_types')->insert([
            ['type_qualifier'=>1,'type_description'=>'Fecha y Hora de Finalización', 'created_at'=>now()],
            ['type_qualifier'=>17,'type_description'=>'Fecha y Hora de Entrega Estimada', 'created_at'=>now()],
            ['type_qualifier'=>11,'type_description'=>'Fecha y Hora de Inicio', 'created_at'=>now()],
            ['type_qualifier'=>63,'type_description'=>'Fecha y Hora de Entrega', 'created_at'=>now()],
            ['type_qualifier'=>87,'type_description'=>'Fecha y Hora de Envio', 'created_at'=>now()],
        ]);
    }
}
