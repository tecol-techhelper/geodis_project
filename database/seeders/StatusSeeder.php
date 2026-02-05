<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('statuses')->insert([
            [
                'status_name' => 'Solicitud de Geodis al transportador',
                'status_description' => 'Solicitud de Geodis sobre el servicio a la transportadora',
                'status_be' => 'ACK',
                'created_at' => now()
            ],
            [
                'status_name' => 'Confirmación transportista disponibilidad',
                'status_description' => 'Notificación de las transportadoras sobre disponibilidad del servicio',
                'status_be' => 'ACKCONF',
                'created_at' => now()
            ],
            [
                'status_name' => 'Asignación del servicio',
                'status_description' => 'Confirmación de Geodis a la transportadora asignada al servicio con la mejor propuesta y disponibilidad',
                'status_be' => 'APP',
                'created_at' => now()
            ],
            [
                'status_name' => 'Confirmación de recursos asignados para la operación',
                'status_description' => 'Notificación de la transportadora acerca de datos de conductor, operador, vehículo y equipo, posicionamiento de vehículo, si cumple en tiempo y calidad, permisos y demás según aplique para cada origen y destino.',
                'status_be' => 'A71',
                'created_at' => now()
            ],
            [
                'status_name' => 'Novedad posicionamiento correcto',
                'status_description' => 'En caso de aplicar notificación de la transportadora sobre el cambio haciendo énfasis debido al cambio y por qué se presenta',
                'status_be' => 'DESADV',
                'created_at' => now()
            ],
            [
                'status_name' => 'Entrega planillas',
                'status_description' => 'Notificación sobre la entrega de recibo de planillas por parte de la transportadora y cita en puerto',
                'status_be' => 'COW',
                'created_at' => now()
            ],
            [
                'status_name' => 'Inicio actividades de cargue y aseguramiento',
                'status_description' => 'Notificación de la transportadora sobre la fecha de inicio de cargue o desarrollo de la actividad (Para el caso de ITR confirmación en puerto)',
                'status_be' => 'ACT035',
                'created_at' => now()
            ],
            [
                'status_name' => 'Prueba de recibo',
                'status_description' => 'Prueba de recibo sobre la custodia del material - registro fotográfico de como recibió el material la transportadora y como está cargado',
                'status_be' => 'DEV056',
                'created_at' => now()
            ],
            [
                'status_name' => 'Notificación inicio de tránsito',
                'status_description' => 'Confirmación de salida a tránsito nacional con notificación fecha estimada de llegada a destino',
                'status_be' => 'ACT035',
                'created_at' => now()
            ],
            [
                'status_name' => 'Seguimiento y notificación de eventos',
                'status_description' => 'Eventos externos (desastres naturales, no controlables, situaciones orden público, HSE, cambio de vehículo) - Eventos internos (Planeación del servicio, herramientas, bodegas) - Estados de la operación - Estatus del tránsito',
                'status_be' => 'QUA',
                'created_at' => now()
            ],
            [
                'status_name' => 'Notificación con frecuencia',
                'status_description' => 'Notificación para Cargas Críticas o específicas que requieran este seguimiento',
                'status_be' => 'ZS1',
                'created_at' => now()
            ],
            [
                'status_name' => 'Confirmar finalización',
                'status_description' => 'Notificación de arribo y entrega de la carga',
                'status_be' => 'ACT021',
                'created_at' => now()
            ],
            [
                'status_name' => 'Remesa sin comentarios',
                'status_description' => 'Evidencia de satisfacción de guía sin comentarios con evidencia fotográfica de cada remesa que hizo parte de la operación',
                'status_be' => 'POD',
                'created_at' => now()
            ],
        ]);
    }
}
