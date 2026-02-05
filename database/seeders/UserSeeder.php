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
        $adminRole = Role::where('rol_key', 'admin')->first();
        $coordRole = Role::where('rol_key', 'coord')->first();

        $userAdmin = User::create([
            'first_name' => 'Ernesto',
            'last_name' => 'Cortés',
            'username' => 'askmeee12',
            'user_area' => 'Ernesto',
            'email' => 'auxiliarnomina@transtecol.com.co',
            'password' =>'admin1234*',
            'is_active' => 1,
            'created_at' => now(),
        ]);

        $userCoord = User::create([
            'first_name' => 'Jeimi',
            'last_name' => 'Leal',
            'username' => 'nomina2024',
            'user_area' => 'Nomina',
            'email' => 'nomina@transtecol.com.co',
            'password' =>'admin1234*',
            'is_active' => 1,
            'created_at' => now(),
        ]);

        $userAdmin->roles()->attach($adminRole->id);
        $userCoord->roles()->attach($coordRole->id);
    }
}
