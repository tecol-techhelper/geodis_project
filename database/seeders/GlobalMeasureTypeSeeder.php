<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GlobalMeasureTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $rows = [
            ['type_qualifier' => 7, 'type_description' => 'Peso bruto total'],
            ['type_qualifier' => 8, 'type_description' => 'Cantidad total de piezas'],
            ['type_qualifier' => 11, 'type_description' => 'Número total de paquetes'],
            ['type_qualifier' => 15, 'type_description' => 'Volumen total'],
            ['type_qualifier' => 26, 'type_description' => 'Volumen bruto total'],
            ['type_qualifier' => 29, 'type_description' => 'Peso neto total'],
        ];

        foreach ($rows as $row) {
            $qualifier = (string) $row['type_qualifier'];

            $existingId = DB::table('global_measure_types')
                ->where('type_qualifier', $qualifier)
                ->value('id');

            if ($existingId) {
                DB::table('global_measure_types')
                    ->where('id', $existingId)
                    ->update([
                        'type_description' => $row['type_description'],
                        'updated_at' => $now,
                    ]);
            } else {
                DB::table('global_measure_types')->insert([
                    'type_qualifier' => $qualifier,
                    'type_description' => $row['type_description'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
