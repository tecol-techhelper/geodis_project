<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DateTypeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $rows = [
            [
                'type_qualifier' => 2,
                'type_description' => 'Fecha/hora de entrega solicitada.',
            ],
            [
                'type_qualifier' => 11,
                'type_description' => 'Fecha/hora de despacho o envío.',
            ],
            [
                'type_qualifier' => 63,
                'type_description' => 'Fecha/hora de entrega, límite máximo (la más tardía permitida).',
            ],
            [
                'type_qualifier' => 64,
                'type_description' => 'Fecha/hora de entrega, límite mínimo (la más temprana posible).',
            ],
            [
                'type_qualifier' => 81,
                'type_description' => 'Fecha/hora de envío solicitada (a partir de esta fecha, inclusive).',
            ],
            [
                'type_qualifier' => 84,
                'type_description' => 'Fecha/hora de envío solicitada (hasta esta fecha, inclusive).',
            ],
        ];

        foreach ($rows as $row) {
            $qualifier = (int) $row['type_qualifier'];

            $existingId = DB::table('date_types')
                ->where('type_qualifier', $qualifier)
                ->value('id');

            if ($existingId) {
                DB::table('date_types')
                    ->where('id', $existingId)
                    ->update([
                        'type_description' => $row['type_description'],
                        'updated_at' => $now,
                    ]);
            } else {
                DB::table('date_types')->insert([
                    'type_qualifier' => $qualifier,
                    'type_description' => $row['type_description'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
