<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const OBSOLETE_PERMISSIONS = [
        'edit_transport_block',
        'edit_accounting_block',
        'edit_tracking_block',
        'edit_payment_block',
        'edit_record_block',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::transaction(function (): void {
            $now = now();

            DB::table('permissions')->upsert([
                [
                    'permission_key' => 'edit_services',
                    'permission_name' => 'Editar servicios',
                    'permission_description' => 'Permite editar la informacion operativa de los servicios.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'permission_key' => 'purge_services',
                    'permission_name' => 'Eliminar servicios definitivamente',
                    'permission_description' => 'Permite eliminar servicios de forma permanente.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ], ['permission_key'], ['permission_name', 'permission_description', 'updated_at']);

            DB::table('permissions')
                ->whereIn('permission_key', self::OBSOLETE_PERMISSIONS)
                ->delete();

            $this->syncRolePermissions([
                'admin' => DB::table('permissions')->pluck('permission_key')->all(),
                'coord' => ['view_services', 'edit_services', 'upload_files'],
                'account' => ['view_services'],
                'od' => ['view_services'],
                'sec' => ['view_services'],
                'spec' => ['view_services'],
            ], $now);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::transaction(function (): void {
            $now = now();

            DB::table('permissions')->upsert([
                [
                    'permission_key' => 'edit_transport_block',
                    'permission_name' => 'Editar bloque de transporte',
                    'permission_description' => 'Permite editar los campos operativos del servicio.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'permission_key' => 'edit_accounting_block',
                    'permission_name' => 'Editar bloque de contabilidad',
                    'permission_description' => 'Permite editar campos relacionados con pagos y facturacion.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'permission_key' => 'edit_tracking_block',
                    'permission_name' => 'Editar bloque de seguimiento',
                    'permission_description' => 'Permite editar el seguimiento del servicio.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'permission_key' => 'edit_payment_block',
                    'permission_name' => 'Editar bloque de pago',
                    'permission_description' => 'Permite editar la aprobacion de pago.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'permission_key' => 'edit_record_block',
                    'permission_name' => 'Editar bloque de acta',
                    'permission_description' => 'Permite editar el numero de acta.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ], ['permission_key'], ['permission_name', 'permission_description', 'updated_at']);

            DB::table('permissions')
                ->whereIn('permission_key', ['edit_services', 'purge_services'])
                ->delete();

            $this->syncRolePermissions([
                'admin' => DB::table('permissions')->pluck('permission_key')->all(),
                'coord' => ['view_services', 'edit_transport_block', 'upload_files'],
                'account' => ['view_services', 'edit_accounting_block'],
                'od' => ['view_services', 'edit_payment_block'],
                'sec' => ['view_services', 'edit_tracking_block'],
                'spec' => ['view_services', 'edit_record_block'],
            ], $now);
        });
    }

    /**
     * @param  array<string, array<int, string>>  $permissionsByRole
     */
    private function syncRolePermissions(array $permissionsByRole, mixed $now): void
    {
        foreach ($permissionsByRole as $roleKey => $permissionKeys) {
            $roleId = DB::table('roles')->where('rol_key', $roleKey)->value('id');

            if (! $roleId) {
                continue;
            }

            DB::table('permission_roles')->where('role_id', $roleId)->delete();

            $permissionIds = DB::table('permissions')
                ->whereIn('permission_key', $permissionKeys)
                ->pluck('id');

            $rows = $permissionIds->map(fn ($permissionId): array => [
                'permission_id' => $permissionId,
                'role_id' => $roleId,
                'created_at' => $now,
                'updated_at' => $now,
            ])->all();

            if ($rows !== []) {
                DB::table('permission_roles')->insert($rows);
            }
        }
    }
};
