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
    }
}
