<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonnelRoleSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $roles = [
            [
                'code' => 'operator',
                'name' => 'Operador',
                'sort_order' => 10,
            ],
            [
                'code' => 'rigger',
                'name' => 'Aparejador',
                'sort_order' => 20,
            ],
            [
                'code' => 'driver',
                'name' => 'Conductor',
                'sort_order' => 30,
            ],
        ];

        foreach ($roles as $role) {
            DB::table('personnel_roles')->updateOrInsert(
                ['code' => $role['code']],
                [
                    'name' => $role['name'],
                    'sort_order' => $role['sort_order'],
                    'deleted_at' => null,
                    'updated_at' => $now,
                    'created_at' => $now,
                ],
            );
        }
    }
}
