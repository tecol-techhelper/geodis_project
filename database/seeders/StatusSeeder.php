<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $rows = [
            [
                'status_name' => 'Solicitud de Geodis al transportador',
                'status_description' => 'Solicitud de Geodis sobre el servicio a la transportadora',
                'status_be' => 'ACK',
            ],
            [
                'status_name' => 'Confirmación transportista disponibilidad',
                'status_description' => 'Notificación de las transportadoras sobre disponibilidad del servicio',
                'status_be' => 'ACKCONF',
            ],
            [
                'status_name' => 'Asignación del servicio',
                'status_description' => 'Confirmación de Geodis a la transportadora asignada al servicio con la mejor propuesta y disponibilidad',
                'status_be' => 'APP',
            ],
            [
                'status_name' => 'Confirmación de recursos asignados para la operación',
                'status_description' => 'Notificación de la transportadora acerca de datos de conductor, operador, vehículo y equipo, posicionamiento de vehículo, si cumple en tiempo y calidad, permisos y demás según aplique para cada origen y destino.',
                'status_be' => 'A71',
            ],
            [
                'status_name' => 'Novedad posicionamiento correcto',
                'status_description' => 'En caso de aplicar notificación de la transportadora sobre el cambio haciendo énfasis debido al cambio y por qué se presenta',
                'status_be' => 'DESADV',
            ],
            [
                'status_name' => 'Entrega planillas',
                'status_description' => 'Notificación sobre la entrega de recibo de planillas por parte de la transportadora y cita en puerto',
                'status_be' => 'COW',
            ],
            [
                'status_name' => 'Inicio actividades de cargue y aseguramiento',
                'status_description' => 'Notificación de la transportadora sobre la fecha de inicio de cargue o desarrollo de la actividad (para el caso de ITR confirmación en puerto)',
                'status_be' => 'ACT035',
            ],
            [
                'status_name' => 'Prueba de recibo',
                'status_description' => 'Prueba de recibo sobre la custodia del material - registro fotográfico de cómo recibió el material la transportadora y cómo está cargado',
                'status_be' => 'DEV056',
            ],
            [
                'status_name' => 'Notificación inicio de tránsito',
                'status_description' => 'Confirmación de salida a tránsito nacional con notificación fecha estimada de llegada a destino',
                'status_be' => 'ACT035',
            ],
            [
                'status_name' => 'Seguimiento y notificación de eventos',
                'status_description' => 'Eventos externos (desastres naturales, no controlables, situaciones orden público, HSE, cambio de vehículo) - Eventos internos (Planeación del servicio, herramientas, bodegas) - Estados de la operación - Estatus del tránsito',
                'status_be' => 'QUA',
            ],
            [
                'status_name' => 'Notificación con frecuencia',
                'status_description' => 'Notificación para cargas críticas o específicas que requieran este seguimiento',
                'status_be' => 'ZS1',
            ],
            [
                'status_name' => 'Confirmar finalización',
                'status_description' => 'Notificación de arribo y entrega de la carga',
                'status_be' => 'ACT021',
            ],
            [
                'status_name' => 'Remesa sin comentarios',
                'status_description' => 'Evidencia de satisfacción de guía sin comentarios con evidencia fotográfica de cada remesa que hizo parte de la operación',
                'status_be' => 'POD',
            ],
        ];

        foreach ($rows as $row) {
            $code = strtoupper(trim($row['status_be']));

            $existingId = DB::table('statuses')
                ->where('status_be', $code)
                ->where('status_name', $row['status_name'])
                ->value('id');

            if ($existingId) {
                DB::table('statuses')
                    ->where('id', $existingId)
                    ->update([
                        'status_name' => $row['status_name'],
                        'status_description' => $row['status_description'],
                        'updated_at' => $now,
                    ]);
            } else {
                DB::table('statuses')->insert([
                    'segment_tag' => 'STS',
                    'status_name' => $row['status_name'],
                    'status_description' => $row['status_description'],
                    'status_be' => $code,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
