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
            // COSTO_AUTORIZADO
            ['resource_operation' => 'COSTO_AUTORIZADO', 'resource_name' => 'Empaque, embalaje, preservación de materiales a transportar', 'resource_id' => 'CA_EEPMT'],
            ['resource_operation' => 'COSTO_AUTORIZADO', 'resource_name' => 'Ayudas o elementos para el transporte diferentes a los tarifados en el contrato', 'resource_id' => 'CA_AETTC'],
            ['resource_operation' => 'COSTO_AUTORIZADO', 'resource_name' => 'equipos de cargue y descargue no incluidos en las tarifas iniciales del contrato', 'resource_id' => 'CA_ECDTC'],
            ['resource_operation' => 'COSTO_AUTORIZADO', 'resource_name' => 'inspecciones en origen y/o destino por compañía especializada diferente al contratista', 'resource_id' => 'CA_IODCC'],
            ['resource_operation' => 'COSTO_AUTORIZADO', 'resource_name' => 'Reaseguros', 'resource_id' => 'CA_REASS'],
            ['resource_operation' => 'COSTO_AUTORIZADO', 'resource_name' => 'Estudios y trámites documentales inherentes a la operación logística de transporte', 'resource_id' => 'CA_ETIOT'],
            ['resource_operation' => 'COSTO_AUTORIZADO', 'resource_name' => 'Derechos especiales para permisos de tránsito de cargas especiales', 'resource_id' => 'CA_DETCE'],
            ['resource_operation' => 'COSTO_AUTORIZADO', 'resource_name' => 'Transporte Fluvial, Férreo o multimodal nacional.', 'resource_id' => 'CA_TFFMN'],
            ['resource_operation' => 'COSTO_AUTORIZADO', 'resource_name' => 'Servicios de transporte, cargues y descargues y otros conceptos de suministro regional o local en zonas o locaciones no contempladas en la oferta económica', 'resource_id' => 'CA_STCSR'],
            ['resource_operation' => 'COSTO_AUTORIZADO', 'resource_name' => 'Servicio de Courier y paqueteo', 'resource_id' => 'CA_SCPTO'],
            ['resource_operation' => 'COSTO_AUTORIZADO', 'resource_name' => 'Escoltas o Acompañamientos por organismos de seguridad o fuerza pública', 'resource_id' => 'CA_EAOSF'],
            ['resource_operation' => 'COSTO_AUTORIZADO', 'resource_name' => 'Transporte Aéreo de Carga en Colombia', 'resource_id' => 'CA_TACDC'],
            ['resource_operation' => 'COSTO_AUTORIZADO', 'resource_name' => 'Costos de cargue, descargue en sitios como puerto y aeropuerto no incluidos en la oferta económica', 'resource_id' => 'CA_EADPO'],
            ['resource_operation' => 'COSTO_AUTORIZADO', 'resource_name' => 'Equipos y accesorios para el izamiento de capacidad superior a los establecidos en el anexo de ofrecimiento económico Costos de cargue y descargues no contemplados en la oferta económica', 'resource_id' => 'CA_EACEO'],
            ['resource_operation' => 'COSTO_AUTORIZADO', 'resource_name' => 'Pesaje de vehículos cuando por solicitud de Ecopetrol se requiera el servicio de pesaje de los vehículos.', 'resource_id' => 'CA_PVESV'],

            // IZAJES
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Camion Grua 10 Ton (Con Operador y Aparejador)', 'resource_id' => 'I_CGTP10'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Camion Grua 15 Ton (Con Operador y Aparejador)', 'resource_id' => 'I_CGTP15'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Camion Grua 20 Ton (Con Operador y Aparejador)', 'resource_id' => 'I_CGTP20'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Cargador 10 Ton (Con Operador)', 'resource_id' => 'I_CTC10T'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Cargador 7 Ton (Con Operador)', 'resource_id' => 'I_CTC7TO'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Cilindros Hidráulicos 100 Ton', 'resource_id' => 'I_CH100T'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Cilindros Hidráulicos 200 Ton', 'resource_id' => 'I_CH200T'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Grua tipo Pulpo 3.5 Ton (Con Operador y Aparejador)', 'resource_id' => 'I_GTP35T'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Grua 120 Ton (Con Operador y Aparejador)', 'resource_id' => 'I_GO120T'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Grua 20 Ton (Con Operador y Aparejador)', 'resource_id' => 'I_GOA20T'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Grua 200 Ton (Con Operador y Aparejador)', 'resource_id' => 'I_GO200T'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Grua 40 Ton (Con Operador y Aparejador)', 'resource_id' => 'I_GOA40T'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Grua 70 Ton (Con Operador y Aparejador)', 'resource_id' => 'I_GOA70T'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Grua 90 Ton (Con Operador y Aparejador)', 'resource_id' => 'I_GOA90T'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Montacarga 10 Ton (Con Operador)', 'resource_id' => 'I_MCO10T'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Montacarga 3,5 Ton (Con Operador)', 'resource_id' => 'I_MCO35T'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Montacarga 5 Ton (Con Operador)', 'resource_id' => 'I_MCO05T'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Montacarga 7 Ton (Con Operador)', 'resource_id' => 'I_MCO07T'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Telehandler (Con Operador)', 'resource_id' => 'I_TLHCO0'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Camion Grua 10 Ton (Con Operador y Aparejador) - MOV', 'resource_id' => 'I_CGT10M'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Camion Grua 15 Ton (Con Operador y Aparejador) - MOV', 'resource_id' => 'I_CGT15M'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Camion Grua 20 Ton (Con Operador y Aparejador) - MOV', 'resource_id' => 'I_CGT20M'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Cargador 10 Ton (Con Operador) - MOV', 'resource_id' => 'I_CT10TM'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Cargador 7 Ton (Con Operador) - MOV', 'resource_id' => 'I_CTC7TM'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Cilindros Hidráulicos 100 Ton - MOV', 'resource_id' => 'I_C100TM'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Cilindros Hidráulicos 200 Ton - MOV', 'resource_id' => 'I_C200TM'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Grua tipo Pulpo 3.5 Ton (Con Operador y Aparejador) - MOV', 'resource_id' => 'I_GT35TM'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Grua 120 Ton (Con Operador y Aparejador) - MOV', 'resource_id' => 'I_G120TM'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Grua 20 Ton (Con Operador y Aparejador) - MOV', 'resource_id' => 'I_GA20TM'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Grua 200 Ton (Con Operador y Aparejador) - MOV', 'resource_id' => 'I_G200TM'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Grua 40 Ton (Con Operador y Aparejador) - MOV', 'resource_id' => 'I_GA40TM'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Grua 70 Ton (Con Operador y Aparejador) - MOV', 'resource_id' => 'I_GA70TM'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Grua 90 Ton (Con Operador y Aparejador) - MOV', 'resource_id' => 'I_GA90TM'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Montacarga 10 Ton (Con Operador) - MOV', 'resource_id' => 'I_MC10TM'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Montacarga 3,5 Ton (Con Operador) - MOV', 'resource_id' => 'I_MC35TM'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Montacarga 5 Ton (Con Operador) - MOV', 'resource_id' => 'I_MC05TM'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Montacarga 7 Ton (Con Operador) - MOV', 'resource_id' => 'I_MC07TM'],
            ['resource_operation' => 'IZAJES', 'resource_name' => 'Telehandler (Con Operador) - MOV', 'resource_id' => 'I_TLHCOM'],

            // OCONCEPTOS
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'SUMINISTRO DE MESAS DE INSPECCION', 'resource_id' => 'O_SDMI00'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Acompañamiento a la carga (Escolta)', 'resource_id' => 'O_ACOME0'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Aparejador de Grúa / Cargas / Equipos de izaje Adicional', 'resource_id' => 'O_AGCEIA'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Areas de almacenamiento Consolidacion de Carga - Costa Norte - Buenaventura ( Bodega cubierta)', 'resource_id' => 'O_AACCCB'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Areas de almacenamiento Consolidacion de Carga - Costa Norte - Buenaventura( Patios/Intemperie)', 'resource_id' => 'O_AACCCI'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Areas de almacenamiento Consolidacion de Carga - Bogotá y Aledaños  ( Bodega cubierta)', 'resource_id' => 'O_AACCCA'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Areas de almacenamiento Consolidacion de Carga - Bogotá y Aledaños ( Patios/Intemperie)', 'resource_id' => 'O_AACCBI'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Ayudante o Auxiliar de Cargue y descargue', 'resource_id' => 'O_AADCYD'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Baños Portatiles (Incluye Transporte y Mantenimiento)', 'resource_id' => 'O_BPITYM'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Camioneta 4 x 2 Operaciones (Con Conductor)', 'resource_id' => 'O_COC4X2'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Camioneta 4 x 2 Operaciones (Sin Conductor)', 'resource_id' => 'O_COS4X2'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Camioneta 4 x 4 Operaciones (Con Conductor)', 'resource_id' => 'O_COC4X4'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Camioneta 4 x 4 Operaciones (Sin Conductor)', 'resource_id' => 'O_COS4X4'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Conductor adicional', 'resource_id' => 'O_CDAD00'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'CONTENEDOR PARA OFICINA 20"', 'resource_id' => 'O_CPO20P'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'CONTENEDOR PARA OFICINA 40"', 'resource_id' => 'O_CPO40P'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'DESCONTENERIZACIÓN EN PUERTO (Carga Especial) CNT 20 - Especial OT/FR', 'resource_id' => 'O_DCNT20'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'DESCONTENERIZACIÓN EN PUERTO (Carga Especial) CNT 40 - Especial OT/FR', 'resource_id' => 'O_DCNT40'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'DESCONTENERIZACIÓN EN PUERTO (Carga General) CNT 20', 'resource_id' => 'O_GCNT20'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'DESCONTENERIZACIÓN EN PUERTO (Carga General) CNT 40STD/ HC', 'resource_id' => 'O_GSDT40'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Estiba  Madera 1X1,20 mts', 'resource_id' => 'O_EM1X1M'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Estiba Madera 1,20X1,20 mts', 'resource_id' => 'O_EM1X1N'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Estiba Madera 1X1 mts', 'resource_id' => 'O_EM1X1B'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Inpeccion de Vías', 'resource_id' => 'O_IPCDV0'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Inspección HSE', 'resource_id' => 'O_IHSE00'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'MOVILIZADOR DE CANECAS DE 55 GALONES', 'resource_id' => 'O_MDC55G'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Ayudante o Auxiliar de Cargue y descargue Perdiguero', 'resource_id' => 'O_AACDP0'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'POLINES DE MADERA PARA CARGAS Y TUBERIA 4" X 4"', 'resource_id' => 'O_PMCT44'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'POLINES DE MADERA PARA CARGAS Y TUBERIA 6" X 6"', 'resource_id' => 'O_PMCT66'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Señalizador Vial (Paletero)', 'resource_id' => 'O_SLZVP0'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'Supervisor o Controlador de Maniobras', 'resource_id' => 'O_SPVCM0'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'TRANSPORTE CONTENEDOR VACIO 1X20"ST, OT, FR VRC - PUERTOS - COSTA NORTE o BUENAVENTURA', 'resource_id' => 'O_TCV20A'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'TRANSPORTE CONTENEDOR VACIO 1X20"ST, OT, FR VRO - PUERTOS COSTA NORTE o BUENAVENTURA', 'resource_id' => 'O_TCV20B'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'TRANSPORTE CONTENEDOR VACIO 1X40" ST, HC, OT, FR VRC - PUERTOS COSTA NORTE o BUENAVENTURA', 'resource_id' => 'O_TCV40C'],
            ['resource_operation' => 'OCONCEPTOS', 'resource_name' => 'TRANSPORTE CONTENEDOR VACIO 1X40" ST, HC, OT, FR VRO - PUERTOS COSTA NORTE o BUENAVENTURA', 'resource_id' => 'O_TCV40A'],

            // TRANSPORTE
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'CABEZOTE TRACTO CAMION - MINI MULA - 22 Ton', 'resource_id' => 'T_CTCM22'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'CABEZOTE TRACTO CAMION - 35 Ton', 'resource_id' => 'T_CTCM35'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'CABEZOTE TRACTO CAMION - Mini Mula', 'resource_id' => 'T_CTCMMM'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'CAMIONETA 4x4 CARGA - 700 KG', 'resource_id' => 'T_CC4700'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'CARRIER CABINADO - 700 KG', 'resource_id' => 'T_CC700K'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'CAMA BAJA TIPO I - 30 Ton', 'resource_id' => 'T_CBTI30'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'CAMA BAJA TIPO II - 30 Ton', 'resource_id' => 'T_CBT230'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'CAMA BAJA  TIPO III - 40 Ton', 'resource_id' => 'T_CBT340'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'CAMA BAJA  TIPO IV - 40 Ton', 'resource_id' => 'T_CBT440'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'CAMA BAJA  TIPO V - 40 Ton', 'resource_id' => 'T_CBT540'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'CAMA BAJA  TIPO VI - Extensible - 50 Ton', 'resource_id' => 'T_CBT450'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'Camion Grua Tipo Planchon Vehícular', 'resource_id' => 'T_CGTPV0'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'Camion Grua 10 Ton (Con Operador y Aparejador) - 10 Ton', 'resource_id' => 'T_CGTC10'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'Camion Grua 15 Ton (Con Operador y Aparejador) - 15 Ton', 'resource_id' => 'T_CGTC15'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'Camion Grua 20 Ton (Con Operador y Aparejador) - 20 Ton', 'resource_id' => 'T_CGTC20'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'EQUIPO ESPECIAL - LINEA DE MODULAR', 'resource_id' => 'T_EELDM0'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'EQUIPO ESPECIAL - VIGAS', 'resource_id' => 'T_EQEV00'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'SENCILLO Con elevador y Gato Hidraulico - 8 Ton', 'resource_id' => 'T_SEGH8T'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'SENCILLO - 8 Ton', 'resource_id' => 'T_SEN8T0'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'TRACTO CAMION CARROTANQUE - 10,500 GLS', 'resource_id' => 'T_TCC10G'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'TRACTO CAMION CARROTANQUE - 9,000 GLS', 'resource_id' => 'T_TCC9GL'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'TRACTO CAMION CARROZADO - 30 Ton', 'resource_id' => 'T_TCC30T'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'TRACTO CAMION CARROZADO - 35 Ton', 'resource_id' => 'T_TCC35T'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'TRACTO-CAMION Extensible - 25 Ton', 'resource_id' => 'T_TCE25T'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'TRACTO CAMION MINIMULA - 17 Ton', 'resource_id' => 'T_TCM17T'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'TRACTO CAMION MINIMULA - 22 Ton', 'resource_id' => 'T_TCM22T'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'TRACTO CAMION PLANCHA - 30 Ton', 'resource_id' => 'T_TCP30T'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'TRACTO CAMION PLANCHA - 35 Ton', 'resource_id' => 'T_TCP35T'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'TURBO Con elevador y Gato Hidraulico - 2 Ton', 'resource_id' => 'T_TEGH2T'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'TURBO Con elevador y Gato Hidraulico - 4 Ton', 'resource_id' => 'T_TEGH4T'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'TURBO - 1 Ton', 'resource_id' => 'T_TUR01T'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'TURBO - 2 Ton', 'resource_id' => 'T_TUR02T'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'TURBO - 3 Ton', 'resource_id' => 'T_TUR03T'],
            ['resource_operation' => 'TRANSPORTE', 'resource_name' => 'TURBO - 4 Ton', 'resource_id' => 'T_TUR04T'],
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
