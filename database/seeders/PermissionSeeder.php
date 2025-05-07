<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $array = array(
            // Users management permissions
            array(
                'permission_key'=>'create_users',
                'permission_name' => 'Crear usuarios',
                'permission_description' => 'Permite la creacion de nuevos usuarios en el sistema',
                'created_at' => now()
            ),
            array(
                'permission_key'=>'view_users',
                'permission_name' => 'Consultar usuarios',
                'permission_description' => 'Permite consultar el listado de usuarios en el sistema',
                'created_at' => now()
            ),
            array(
                'permission_key'=>'view_user_details',
                'permission_name' => 'Consultar usuario',
                'permission_description' => 'Permite consultar detalles de usuario de manera individual',
                'created_at' => now()
            ),
            array(
                'permission_key'=>'update_users',
                'permission_name' => 'Editar usuarios',
                'permission_description' => 'Permite la edicion de informacion del usuario',
                'created_at' => now()
            ),
            array(
                'permission_key'=>'update_user_status',
                'permission_name' => 'Estados de estado de usuarios',
                'permission_description' => 'Permite la modificacion del estado de los usuarios, sea habilitado o deshabilitado',
                'created_at' => now()
            ),
            array(
                'permission_key'=>'delete_users',
                'permission_name' => 'Eliminar usuarios',
                'permission_description' => 'Permite eliminar usuarios del sistema',
                'created_at' => now()
            ),
            // Service management permissions
            array(
                'permission_key'=>'view_services',
                'permission_name' => 'Consultar servicios',
                'permission_description' => 'Permite consultar el listado de servicios',
                'created_at' => now()
            ),
            array(
                'permission_key'=>'edit_transport_block',
                'permission_name' => 'Editar bloque de transporte',
                'permission_description' => 'Permite editar los campos relacionados a la operatividad del servicio',
                'created_at' => now()
            ),
            array(
                'permission_key'=>'edit_accounting_block',
                'permission_name' => 'Editar bloque de contabilidad',
                'permission_description' => 'Permite editar campos relacionados a pagos y facturacion',
                'created_at' => now()
            ),
            array(
                'permission_key'=>'edit_tracking_block',
                'permission_name' => 'Editar bloque de seguimiento',
                'permission_description' => 'Acceso exclusivo a campo de seguimiento o reporte de novedades del servicio',
                'created_at' => now()
            ),
            array(
                'permission_key'=>'edit_payment_block',
                'permission_name' => 'Editar bloque de pago',
                'permission_description' => 'Acceso exclusivo a campo de aprobacion de pago, solo una persona puede tener este permiso fuera del administrador',
                'created_at' => now()
            ),
            array(
                'permission_key'=>'edit_record_block',
                'permission_name' => 'Editar bloque de acta',
                'permission_description' => 'Acceso unico a campo de acta',
                'created_at' => now()
            ),
            array(
                'permission_key'=>'upload_files',
                'permission_name' => 'Cargue de archivos',
                'permission_description' => 'Permite cargar archivos o soportes en el sistema',
                'created_at' => now()
            ),
            array(
                'permission_key'=>'delete_services',
                'permission_name' => 'Eliminar servicios',
                'permission_description' => 'Permite eliminar los servicios',
                'created_at' => now()
            )
        );

        DB::table('permissions')->insert($array);
    }
}
