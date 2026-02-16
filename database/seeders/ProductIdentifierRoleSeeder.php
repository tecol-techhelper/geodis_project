<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductIdentifierRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $rows = [
            [
                'role_code' => 5,
                'role_name' => 'Identificación del producto',
                'role_description' => 'Identificación principal del producto.',
            ],
            [
                'role_code' => 1,
                'role_name' => 'Identificación adicional',
                'role_description' => 'Identificación adicional del producto.',
            ],
            [
                'role_code' => 2,
                'role_name' => 'Identificación para posible sustitución',
                'role_description' => 'Identificación usada para una posible sustitución del producto.',
            ],
            [
                'role_code' => 3,
                'role_name' => 'Sustituido por',
                'role_description' => 'Indica el identificador del producto por el cual fue sustituido.',
            ],
        ];

        $rows = array_map(function (array $row) use ($now) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
            return $row;
        }, $rows);

        DB::table('product_identifier_roles')->upsert(
            $rows,
            ['role_code'],
            ['role_name', 'role_description', 'updated_at']
        );
    }
}
