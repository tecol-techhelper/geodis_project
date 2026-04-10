<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusPurposeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $rows = [
            [
                'purpose_code' => 'GEN',
                'purpose_name' => 'General',
                'purpose_subcode' => 'GENERAL',
            ],
            [
                'purpose_code' => 'LOG',
                'purpose_name' => 'Logistica de Entrada',
                'purpose_subcode' => 'POST-CARRIAGE',
            ],
            [
                'purpose_code' => 'LOG',
                'purpose_name' => 'Logistica de Entrada',
                'purpose_subcode' => 'DOM-CONSOL',
            ],
            [
                'purpose_code' => 'LOG',
                'purpose_name' => 'Logistica de Entrada',
                'purpose_subcode' => 'EMPTY-CONTAINER',
            ],
            [
                'purpose_code' => 'TEBS',
                'purpose_name' => 'Transporte entre Bodegas',
                'purpose_subcode' => 'DELIVERY-SO',
            ],
        ];

        $rows = array_map(function (array $row) use ($now) {
            $row['created_at'] = $now;
            return $row;
        }, $rows);

        $allowedKeys = array_map(function (array $row) {
            $code = strtoupper(trim((string) ($row['purpose_code'] ?? '')));
            $sub = strtoupper(trim((string) ($row['purpose_subcode'] ?? '')));
            return $code . '|' . $sub;
        }, $rows);

        DB::table('status_purposes')
            ->whereNotIn(DB::raw("CONCAT(purpose_code,'|',COALESCE(purpose_subcode,''))"), $allowedKeys)
            ->delete();

        DB::table('status_purposes')->upsert(
            $rows,
            ['purpose_code', 'purpose_subcode'],
            ['purpose_name', 'updated_at']
        );
    }
}
