<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $roleId = DB::table('roles')->where('rol_key', 'coord')->value('id');
        $permissionId = DB::table('permissions')->where('permission_key', 'delete_services')->value('id');

        if (! $roleId || ! $permissionId) {
            return;
        }

        DB::table('permission_roles')->updateOrInsert(
            [
                'permission_id' => $permissionId,
                'role_id' => $roleId,
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $roleId = DB::table('roles')->where('rol_key', 'coord')->value('id');
        $permissionId = DB::table('permissions')->where('permission_key', 'delete_services')->value('id');

        if (! $roleId || ! $permissionId) {
            return;
        }

        DB::table('permission_roles')
            ->where('permission_id', $permissionId)
            ->where('role_id', $roleId)
            ->delete();
    }
};
