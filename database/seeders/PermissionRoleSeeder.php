<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $attach = function (?Role $role, array $keys) use ($now) {
            if (!$role || empty($keys)) {
                return;
            }

            $permissions = Permission::whereIn('permission_key', $keys)
                ->pluck('id');

            $pivot = $permissions->mapWithKeys(fn (int $id) => [
                $id => ['created_at' => $now, 'updated_at' => $now],
            ]);

            $role->permissions()->syncWithoutDetaching($pivot);
        };

        $attach(Role::where('rol_key', 'admin')->first(), Permission::pluck('permission_key')->toArray());
        $attach(Role::where('rol_key', 'coord')->first(), ['view_services', 'edit_transport_block', 'upload_files']);
        $attach(Role::where('rol_key', 'account')->first(), ['view_services', 'edit_accounting_block']);
        $attach(Role::where('rol_key', 'od')->first(), ['view_services', 'edit_payment_block']);
        $attach(Role::where('rol_key', 'sec')->first(), ['view_services', 'edit_tracking_block']);
        $attach(Role::where('rol_key', 'spec')->first(), ['view_services', 'edit_record_block']);
    }
}
