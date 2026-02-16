<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleKeys = ['admin', 'coord', 'account', 'od', 'spec', 'sec'];
        $roles = Role::query()->whereIn('rol_key', $roleKeys)->get()->keyBy('rol_key');

        $usersByRole = [
            'admin' => [
                'email' => 'auxiliarnomina@transtecol.com.co',
                'first_name' => 'Ernesto',
                'last_name' => 'Cortes',
                'username' => 'admin_pruebas',
                'user_area' => 'Administracion',
            ],
            'coord' => [
                'email' => 'nomina@transtecol.com.co',
                'first_name' => 'Jeimi',
                'last_name' => 'Leal',
                'username' => 'coord_pruebas',
                'user_area' => 'Nomina',
            ],
            'account' => [
                'email' => 'contabilidad@transtecol.com.co',
                'first_name' => 'Paula',
                'last_name' => 'Contable',
                'username' => 'account_pruebas',
                'user_area' => 'Contabilidad',
            ],
            'od' => [
                'email' => 'directora.operativa@transtecol.com.co',
                'first_name' => 'Andrea',
                'last_name' => 'Operativa',
                'username' => 'od_pruebas',
                'user_area' => 'Direccion Operativa',
            ],
            'spec' => [
                'email' => 'especifico@transtecol.com.co',
                'first_name' => 'Carlos',
                'last_name' => 'Especifico',
                'username' => 'spec_pruebas',
                'user_area' => 'Operaciones',
            ],
            'sec' => [
                'email' => 'seguridad@transtecol.com.co',
                'first_name' => 'Laura',
                'last_name' => 'Seguridad',
                'username' => 'sec_pruebas',
                'user_area' => 'Seguridad',
            ],
        ];

        foreach ($usersByRole as $rolKey => $payload) {
            $user = User::updateOrCreate(
                ['email' => $payload['email']],
                [
                    'first_name' => $payload['first_name'],
                    'last_name' => $payload['last_name'],
                    'username' => $payload['username'],
                    'user_area' => $payload['user_area'],
                    'password' => 'admin1234*',
                    'is_active' => 1,
                ]
            );

            $role = $roles->get($rolKey);
            if ($role) {
                $user->roles()->syncWithoutDetaching([$role->id]);
            }
        }
    }
}
