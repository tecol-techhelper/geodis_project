<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRol = Role::where('rol_key', 'admin')->first();
        $coordRol = Role::where('rol_key', 'coord')->first();

        User::create([
            'first_name' => 'Ernesto',
            'last_name' => 'Cortés',
            'username' => 'askmeee12',
            'user_area' => 'Ernesto',
            'email' => 'auxiliarnomina@transtecol.com.co',
            'password' =>'admin1234*',
            'is_active' => 1,
            'role_id' => $adminRol->id,
            'created_at' => now(),
        ]);

        User::create([
            'first_name' => 'Jeimi',
            'last_name' => 'Leal',
            'username' => 'nomina2024',
            'user_area' => 'Nomina',
            'email' => 'nomina@transtecol.com.co',
            'password' =>'admin1234*',
            'is_active' => 1,
            'role_id' => $coordRol->id,
            'created_at' => now(),
        ]);
    }
}
