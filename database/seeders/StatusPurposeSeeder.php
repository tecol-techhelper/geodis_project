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
            ],
            [
                'purpose_code' => 'LOG',
                'purpose_name' => 'Logistica de Entrada',
            ],
            [
                'purpose_code' => 'TEBS',
                'purpose_name' => 'Transporte entre Bodegas',
            ],
        ];

        $rows = array_map(function (array $row) use ($now) {
            $row['created_at'] = $now;
            return $row;
        }, $rows);

        DB::table('status_purposes')->upsert(
            $rows,
            ['purpose_code'],
            ['purpose_name', 'updated_at']
        );
    }
}
