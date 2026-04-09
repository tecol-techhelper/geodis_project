<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $rows = [
            // Izajes
            ['resource_operation' => 'Izajes', 'resource_name' => 'Montacarga 3.5 Ton (Con Operador)', 'resource_id' => 'MTC35'],
            ['resource_operation' => 'Izajes', 'resource_name' => 'Montacarga 5 Ton (Con Operador)', 'resource_id' => 'MTC5'],
            ['resource_operation' => 'Izajes', 'resource_name' => 'Montacarga 7 Ton (Con Operador)', 'resource_id' => 'MTC7'],
            ['resource_operation' => 'Izajes', 'resource_name' => 'Montacarga 10 Ton (Con Operador)', 'resource_id' => 'MTC10'],
            ['resource_operation' => 'Izajes', 'resource_name' => 'Telehandler (Con Operador)', 'resource_id' => 'TLH'],
            ['resource_operation' => 'Izajes', 'resource_name' => 'Cargador 7 Ton (Con Operador)', 'resource_id' => 'CRG7'],
            ['resource_operation' => 'Izajes', 'resource_name' => 'Cargador 10 Ton (Con Operador)', 'resource_id' => 'CRG10'],
            ['resource_operation' => 'Izajes', 'resource_name' => 'Camion Grua 10 Ton (Con Operador y Aparejador)', 'resource_id' => 'CG10'],
            ['resource_operation' => 'Izajes', 'resource_name' => 'Camion Grua 15 Ton (Con Operador y Aparejador)', 'resource_id' => 'CG15'],
            ['resource_operation' => 'Izajes', 'resource_name' => 'Camion Grua 20 Ton (Con Operador y Aparejador)', 'resource_id' => 'CG20'],
            ['resource_operation' => 'Izajes', 'resource_name' => 'Grua 20 Ton (Con Operador y Aparejador)', 'resource_id' => 'GR20'],
            ['resource_operation' => 'Izajes', 'resource_name' => 'Grua tipo Pulpo 3.5 Ton (Con Operador y Aparejador)', 'resource_id' => 'GRP35'],
            ['resource_operation' => 'Izajes', 'resource_name' => 'Grua 40 Ton (Con Operador y Aparejador)', 'resource_id' => 'GR40'],
            ['resource_operation' => 'Izajes', 'resource_name' => 'Grua 70 Ton (Con Operador y Aparejador)', 'resource_id' => 'GR70'],
            ['resource_operation' => 'Izajes', 'resource_name' => 'Grua 90 Ton (Con Operador y Aparejador)', 'resource_id' => 'GR90'],
            ['resource_operation' => 'Izajes', 'resource_name' => 'Grua 120 Ton (Con Operador y Aparejador)', 'resource_id' => 'GR120'],
            ['resource_operation' => 'Izajes', 'resource_name' => 'Grua 200 Ton (Con Operador y Aparejador)', 'resource_id' => 'GR200'],
            ['resource_operation' => 'Izajes', 'resource_name' => 'Cilindros Hidráulicos 100 Ton', 'resource_id' => 'CH100'],
            ['resource_operation' => 'Izajes', 'resource_name' => 'Cilindros Hidráulicos 200 Ton', 'resource_id' => 'CH200'],

            // Transporte
            ['resource_operation' => 'Transporte', 'resource_name' => 'TURBO - 1 Ton', 'resource_id' => 'TB1'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'TURBO - 2 Ton', 'resource_id' => 'TB2'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'Turbo Con elevador y Gato Hidraulico - 2 Ton', 'resource_id' => 'TBE2'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'TURBO - 3 Ton', 'resource_id' => 'TB3'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'TURBO - 4 Ton', 'resource_id' => 'TB4'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'Turbo Con elevador y Gato Hidraulico - 4 Ton', 'resource_id' => 'TBE4'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'SENCILLO - 8 Ton', 'resource_id' => 'SC8'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'Sencillo con Elevador y Gato Hidraulico - 8 Ton', 'resource_id' => 'SCE8'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'CABEZOTE TRACTO CAMION - MINI MULA - 22 Ton', 'resource_id' => 'CTM22'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'CABEZOTE TRACTO CAMION - 35 Ton', 'resource_id' => 'CTC35'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'TRACTO CAMION MINIMULA - 17 Ton', 'resource_id' => 'TCM17'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'TRACTO CAMION MINIMULA - 22 Ton', 'resource_id' => 'TCM22'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'TRACTO CAMION PLANCHA - 30 Ton', 'resource_id' => 'TCP30'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'TRACTO CAMION CARROZADO - 30 Ton', 'resource_id' => 'TCC30'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'TRACTO CAMION PLANCHA - 35 Ton', 'resource_id' => 'TCP35'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'TRACTO CAMION CARROZADO - 35 Ton', 'resource_id' => 'TCC35'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'CAMA BAJA TIPO II - 40 Ton', 'resource_id' => 'CB340'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'CAMA BAJA TIPO IV - 40 Ton', 'resource_id' => 'CB440'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'CAMA BAJA TIPO V - 40 Ton', 'resource_id' => 'CB540'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'CAMA BAJA TIPO VI - Extensible - 50 Ton', 'resource_id' => 'CBE50'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'Camion Grua 10 Ton (Con Operador y Aparejador) - 10 Ton', 'resource_id' => 'CG10'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'Camion Grua 15 Ton (Con Operador y Aparejador) - 15 Ton', 'resource_id' => 'CG15'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'Camion Grua 20 Ton (Con Operador y Aparejador) - 20 Ton', 'resource_id' => 'CG20'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'Camion Grua Tipo Planchon Vehicular', 'resource_id' => 'CGPV'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'EQUIPO ESPECIAL - LINEA DE MODULAR', 'resource_id' => 'ELMOD'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'EQUIPO ESPECIAL - VIGAS', 'resource_id' => 'EEVIG'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'TRACTO CAMION CARROTANQUE - 9,000 GLS', 'resource_id' => 'TCC9K'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'TRACTO CAMION CARROTANQUE - 10,500 GLS', 'resource_id' => 'TCC10K'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'CAMIONETA 4x4 CARGA - 700 KG', 'resource_id' => 'C4C700'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'CARRIER CABINADO - 700 KG', 'resource_id' => 'CC700'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'CABEZOTE TRACTO CAMION - Mini Mula', 'resource_id' => 'CTMM'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'TRACTO-CAMION EXTENSIBLE - 25 Ton', 'resource_id' => 'TCE25'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'CAMA BAJA TIPO I - 30 Ton', 'resource_id' => 'CB130'],
            ['resource_operation' => 'Transporte', 'resource_name' => 'CAMA BAJA TIPO II - 30 Ton', 'resource_id' => 'CB230'],

            // OC/CONCEPTOS
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Acompañamiento a la carga (Escolta)', 'resource_id' => 'ESC'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Supervisor o Controlador de Maniobras', 'resource_id' => 'SUP'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Aparejador de Grua / Cargas / Equipos de izaje Adicional', 'resource_id' => 'APA'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Inspección HSE', 'resource_id' => 'HSE'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Inspección de Vías', 'resource_id' => 'INPV'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Ayudante o Auxiliar de Cargue y descargue', 'resource_id' => 'AUXCD'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Ayudante o Auxiliar de Cargue y descargue Perdigüero', 'resource_id' => 'AUXPRD'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Señalizador Vial (Paletero)', 'resource_id' => 'SVIAL'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Conductor adicional', 'resource_id' => 'CONAD'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Camioneta 4 x 4 Operaciones (Sin Conductor)', 'resource_id' => 'C4OSC'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Camioneta 4 x 2 Operaciones (Sin Conductor)', 'resource_id' => 'C2OSC'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Camioneta 4 x 4 Operaciones (Con Conductor)', 'resource_id' => 'C4OCC'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Camioneta 4 x 2 Operaciones (Con Conductor)', 'resource_id' => 'C2OCC'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'POLINES DE MADERA PARA CARGAS Y TUBERIA 4\" X 4\"', 'resource_id' => 'POL44'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'POLINES DE MADERA PARA CARGAS Y TUBERIA 6\" X 6\"', 'resource_id' => 'POL66'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Baños Portátiles (Incluye Transporte y Mantenimiento)', 'resource_id' => 'BPORT'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'CONTENEDOR PARA OFICINA 20\'', 'resource_id' => 'CO20'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'CONTENEDOR PARA OFICINA 40\'', 'resource_id' => 'CO40'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'TRANSPORTE CONTENEDOR VACIO 1X20\'ST, OT, FR VRC - PUERTOS COSTA NORTE o BUENAVENTURA', 'resource_id' => 'TV20V'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'TRANSPORTE CONTENEDOR VACIO 1X40\' ST, HC, OT, FR VRC - PUERTOS COSTA NORTE o BUENAVENTURA', 'resource_id' => 'TV40V'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'TRANSPORTE CONTENEDOR VACIO 1X20\'ST, OT, FR VRO - PUERTOS COSTA NORTE o BUENAVENTURA', 'resource_id' => 'TV200'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'TRANSPORTE CONTENEDOR VACIO 1X40\' ST, HC, OT, FR VRO - PUERTOS COSTA NORTE o BUENAVENTURA', 'resource_id' => 'TV400'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'MOVILIZADOR DE CANECAS DE 55 GALONES', 'resource_id' => 'MCC55'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Estiba Madera 1x1 mts', 'resource_id' => 'EST11'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Estiba Madera 1x1,20 mts', 'resource_id' => 'EST112'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Estiba Madera 1,20x1,20 mts', 'resource_id' => 'EST1212'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Areas de almacenamiento Consolidacion de Carga - Costa Norte - Buenaventura ( Bodega cubierta )', 'resource_id' => 'ACCNB'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Areas de almacenamiento Consolidacion de Carga - Costa Norte - Buenaventura( Patios/Intemperie )', 'resource_id' => 'ACCNP'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Areas de almacenamiento Consolidacion de Carga - Bogotá y Aledaños ( Bodega cubierta )', 'resource_id' => 'ACBOB'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Areas de almacenamiento Consolidacion de Carga - Bogotá y Aledaños ( Patios/Intemperie )', 'resource_id' => 'ACBOA'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'DESCONTENERIZACION EN PUERTO (Carga Especial) CNT 20 - Especial OT/FR', 'resource_id' => 'DPE20'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'DESCONTENERIZACION EN PUERTO (Carga Especial) CNT 40 - Especial OT/FR', 'resource_id' => 'DPE40'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'DESCONTENERIZACION EN PUERTO (Carga General) CNT 20', 'resource_id' => 'DPG20'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'DESCONTENERIZACION EN PUERTO (Carga General) CNT 40 STD/HC', 'resource_id' => 'DPG40'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'SUMINISTRO DE MESAS DE INSPECCION', 'resource_id' => 'SMI'],
        ];

        $rows = array_map(function (array $row) use ($now) {
            $row['created_at'] = $now;
            return $row;
        }, $rows);

        DB::table('resources')->upsert(
            $rows,
            ['resource_id'],
            ['resource_name', 'resource_operation', 'updated_at']
        );
    }
}
