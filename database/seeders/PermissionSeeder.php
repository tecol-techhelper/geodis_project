<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $rows = [
            [
                'permission_key' => 'create_users',
                'permission_name' => 'Crear usuarios',
                'permission_description' => 'Permite la creación de nuevos usuarios en el sistema.',
            ],
            [
                'permission_key' => 'view_users',
                'permission_name' => 'Consultar usuarios',
                'permission_description' => 'Permite consultar el listado de usuarios en el sistema.',
            ],
            [
                'permission_key' => 'view_user_details',
                'permission_name' => 'Consultar usuario',
                'permission_description' => 'Permite consultar detalles de usuario de manera individual.',
            ],
            [
                'permission_key' => 'update_users',
                'permission_name' => 'Editar usuarios',
                'permission_description' => 'Permite la edición de información del usuario.',
            ],
            [
                'permission_key' => 'update_user_status',
                'permission_name' => 'Estados de usuarios',
                'permission_description' => 'Permite la modificación del estado de los usuarios, sea habilitado o deshabilitado.',
            ],
            [
                'permission_key' => 'delete_users',
                'permission_name' => 'Eliminar usuarios',
                'permission_description' => 'Permite eliminar usuarios del sistema.',
            ],
            [
                'permission_key' => 'view_services',
                'permission_name' => 'Consultar servicios',
                'permission_description' => 'Permite consultar el listado de servicios.',
            ],
            [
                'permission_key' => 'edit_transport_block',
                'permission_name' => 'Editar bloque de transporte',
                'permission_description' => 'Permite editar los campos relacionados a la operatividad del servicio.',
            ],
            [
                'permission_key' => 'edit_accounting_block',
                'permission_name' => 'Editar bloque de contabilidad',
                'permission_description' => 'Permite editar campos relacionados a pagos y facturación.',
            ],
            [
                'permission_key' => 'edit_tracking_block',
                'permission_name' => 'Editar bloque de seguimiento',
                'permission_description' => 'Acceso exclusivo al campo de seguimiento o reporte de novedades del servicio.',
            ],
            [
                'permission_key' => 'edit_payment_block',
                'permission_name' => 'Editar bloque de pago',
                'permission_description' => 'Acceso exclusivo al campo de aprobación de pago, solo una persona puede tener este permiso fuera del administrador.',
            ],
            [
                'permission_key' => 'edit_record_block',
                'permission_name' => 'Editar bloque de acta',
                'permission_description' => 'Acceso único al campo de acta.',
            ],
            [
                'permission_key' => 'upload_files',
                'permission_name' => 'Cargue de archivos',
                'permission_description' => 'Permite cargar archivos o soportes en el sistema.',
            ],
            [
                'permission_key' => 'delete_services',
                'permission_name' => 'Eliminar servicios',
                'permission_description' => 'Permite eliminar los servicios.',
            ],
        ];

        $rows = array_map(function (array $row) use ($now) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
            return $row;
        }, $rows);

        DB::table('permissions')->upsert(
            $rows,
            ['permission_key'],
            ['permission_name', 'permission_description', 'updated_at']
        );
    }
}
