<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $array = array(
            array(
                'rol_key'=>'admin',
                'rol_name' => 'Administrador',
                'rol_description' => 'Usuario con control total sobre el sistema. Tiene acceso a todas las funcionalidades, puede crear editar y eliminar usuarios, servicios, archivos y gestionar configuraciones criticas',
                'created_at' => now()
            ),
            array(
                'rol_key'=>'coord',
                'rol_name' => 'Coordinador',
                'rol_description' => 'Encargado de gestionar operativamente los servicios. Puede editar campos especificos relacionados con la ejecucion del servicio y hacer cargue de archivos',
                'created_at' => now()
            ),
            array(
                'rol_key'=>'acct',
                'rol_name' => 'Contador',
                'rol_description' => 'Usuario responsable de los datos contables. Tiene acceso a los campos relacionados con pagos y facturación',
                'created_at' => now()
            ),
            array(
                'rol_key'=>'od',
                'rol_name' => 'Directora Operativa',
                'rol_description' => 'Rol encargado del campo que da autorizacion para pago',
                'created_at' => now()
            ),
            array(
                'rol_key'=>'spec',
                'rol_name' => 'Especifico',
                'rol_description' => 'Rol con acceso a un unico campo del sistema. Su funcion es puntual y se basa en el diligenciamiento del numero de acta',
                'created_at' => now()
            ),
            array(
                'rol_key'=>'sec',
                'rol_name' => 'Seguridad',
                'rol_description' => 'Tipo de usuario con acceso unico al campo de seguimiento del servicio',
                'created_at' => now()
            ),
        );

        DB::table('roles')->insert($array);
    }
}
