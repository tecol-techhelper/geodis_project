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
        $adminRole = Role::query()->where('rol_key', 'admin')->firstOrFail();

        $adminData = [
            'email' => 'administrador@geodis.com',
            'first_name' => 'System',
            'last_name' => 'Administrator',
            'username' => 'admin',
            'user_area' => 'Sistemas',
            'password' => 'ParasiteEve1424*',
            'is_active' => 1,
            'guard_name' => 'web',
        ];

        $admin = User::query()
            ->where('email', $adminData['email'])
            ->orWhere('username', $adminData['username'])
            ->orWhere('username', 'admin_pruebas')
            ->first();

        if ($admin) {
            $admin->fill($adminData);
            $admin->save();
        } else {
            $admin = User::query()->create($adminData);
        }

        $admin->roles()->sync([$adminRole->id]);
    }
}
