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

        $sync = function (?Role $role, array $keys) use ($now): void {
            if (! $role) {
                return;
            }

            $permissions = Permission::whereIn('permission_key', $keys)
                ->pluck('id');

            $pivot = $permissions->mapWithKeys(fn (int $id) => [
                $id => ['created_at' => $now, 'updated_at' => $now],
            ]);

            $role->permissions()->sync($pivot);
        };

        $sync(Role::where('rol_key', 'admin')->first(), Permission::pluck('permission_key')->all());
        $sync(Role::where('rol_key', 'coord')->first(), [
            'view_services',
            'edit_services',
            'upload_files',
            'delete_services',
        ]);
        $sync(Role::where('rol_key', 'account')->first(), ['view_services']);
        $sync(Role::where('rol_key', 'od')->first(), ['view_services']);
        $sync(Role::where('rol_key', 'sec')->first(), ['view_services']);
        $sync(Role::where('rol_key', 'spec')->first(), ['view_services']);
    }
}
