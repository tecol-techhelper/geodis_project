<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        DB::table('product_identifier_roles')->insert([
            [
                'role_code' => 5,
                'role_name' => 'Product identification',
                'role_description' => 'Identificación principal del producto.',
                'created_at' => $now,
            ],
            [
                'role_code' => 1,
                'role_name' => 'Additional identification',
                'role_description' => 'Identificación adicional del producto.',
                'created_at' => $now,
            ],
            [
                'role_code' => 2,
                'role_name' => 'Identification for potential substitution',
                'role_description' => 'Identificación usada para una posible sustitución del producto.',
                'created_at' => $now,
            ],
            [
                'role_code' => 3,
                'role_name' => 'Substituted by',
                'role_description' => 'Indica el identificador del producto por el cual fue sustituido.',
                'created_at' => $now,
            ],
        ]);
    }
}
