<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::where('rol_key', 'admin')->first();
        $now = now();
        $permissionsWithTimestamps = Permission::pluck('id')->mapWithKeys(function ($id) use ($now) {
            return [$id => ['created_at' => $now]];
        });
        $admin->permissions()->attach($permissionsWithTimestamps);


        // Permissions to coordinator user
        $coordinator = Role::where('rol_key','coord')->first();
        $coordinator->permissions()->attach([
            Permission::where('permission_key','view_services')->value('id')=> ['created_at'=>now()],
            Permission::where('permission_key','edit_transport_block')->value('id')=> ['created_at'=>now()],
            Permission::where('permission_key','upload_files')->value('id')=> ['created_at'=>now()],
        ]);

        // Permissions to accountant user
        $accounting = Role::where('rol_key', 'account')->first();
        $accounting->permissions()->attach([
            Permission::where('permission_key','view_services')->value('id')=> ['created_at'=>now()],
            Permission::where('permission_key','edit_accounting_block')->value('id')=> ['created_at'=>now()]
        ]);

        // Permissions to operations director user
        $od = Role::where('rol_key', 'od')->first();
        $od->permissions()->attach([
            Permission::where('permission_key','view_services')->value('id')=> ['created_at'=>now()],
            Permission::where('permission_key','edit_payment_block')->value('id')=> ['created_at'=>now()]
        ]);

        // Permissions to security user
        $security = Role::where('rol_key', 'sec')->first();
        $security->permissions()->attach([
            Permission::where('permission_key','view_services')->value('id')=> ['created_at'=>now()],
            Permission::where('permission_key','edit_tracking_block')->value('id')=> ['created_at'=>now()]
        ]);

        // Permissions to specific user
        $security = Role::where('rol_key', 'spec')->first();
        $security->permissions()->attach([
            Permission::where('permission_key','view_services')->value('id')=> ['created_at'=>now()],
            Permission::where('permission_key','edit_record_block')->value('id')=> ['created_at'=>now()]
        ]);
    }
}
