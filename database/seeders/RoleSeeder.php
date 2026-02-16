<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $rows = [
            [
                'rol_key' => 'admin',
                'rol_name' => 'Administrador',
                'rol_description' => 'Usuario con control total sobre el sistema. Tiene acceso a todas las funcionalidades, puede crear, editar y eliminar usuarios, servicios, archivos y gestionar configuraciones críticas.',
            ],
            [
                'rol_key' => 'coord',
                'rol_name' => 'Coordinador',
                'rol_description' => 'Encargado de gestionar operativamente los servicios. Puede editar campos específicos relacionados con la ejecución del servicio y hacer cargue de archivos.',
            ],
            [
                'rol_key' => 'account',
                'rol_name' => 'Contador',
                'rol_description' => 'Usuario responsable de los datos contables. Tiene acceso a los campos relacionados con pagos y facturación.',
            ],
            [
                'rol_key' => 'od',
                'rol_name' => 'Directora Operativa',
                'rol_description' => 'Rol encargado del campo que da autorización para pago.',
            ],
            [
                'rol_key' => 'spec',
                'rol_name' => 'Específico',
                'rol_description' => 'Rol con acceso a un único campo del sistema. Su función es puntual y se basa en el diligenciamiento del número de acta.',
            ],
            [
                'rol_key' => 'sec',
                'rol_name' => 'Seguridad',
                'rol_description' => 'Tipo de usuario con acceso único al campo de seguimiento del servicio.',
            ],
        ];

        $rows = array_map(function (array $row) use ($now) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
            return $row;
        }, $rows);

        DB::table('roles')->upsert(
            $rows,
            ['rol_key'],
            ['rol_name', 'rol_description', 'updated_at']
        );
    }
}
