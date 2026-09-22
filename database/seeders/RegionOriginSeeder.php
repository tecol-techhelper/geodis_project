<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\RegionOrigin;
use App\Models\Origin;
use App\Services\Geodis\OriginNormalizer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionOriginSeeder extends Seeder
{
    /**
     * Regional / Origen extracted from regionales.xlsx, Hoja1.
     *
     * @var array<string, array<int, string>>
     */
    private const REGIONS = [
        'CAÑO LIMON' => [
            'ARAUCA (GILBRALTAR)', 'ARAUCA (SARAVENA)', 'BANADÍA', 'CAMPO RONDON',
            'CUCUTA - VILLA DEL ROSARIO', 'ORIPAYA', 'ORU', 'SAMORE', 'TIBU', 'TOLEDO',
        ],
        'CENTRAL' => [
            'ALBAN', 'BOGOTA', 'FRESNO', 'GUADUERO', 'GUALANDAY', 'HERVEO', 'MADRID',
            'MANSILLA', 'MARIQUITA', 'MIRAFLORES', 'MOSQUERA', 'MUNICIPIOS DE BOYACA',
            'MUNICIPIOS DE CUNDINAMARCA', 'MUNICIPIOS DE TOLIMA', 'PUENTE ARANDA',
            'PUERTO SALGAR', 'SANTA ROSA', 'SUTAMARCHAN', 'TOCANCIPA', 'VILLETA',
        ],
        'GRC' => [
            'BARANOA', 'BARRANQUILLA', 'CARTAGENA', 'COPEY', 'COVEÑAS', 'POZOS COLORADOS',
            'RETIRO', 'SANTA MARTA', 'TOLU - COVEÑAS',
        ],
        'OCCIDENTE' => [
            'BUENAVENTURA', 'BUGA', 'CALI', 'CARTAGO', 'CISNEROS', 'DAGUA', 'MANIZALEZ',
            'MEDELLÍN', 'MULALO', 'PEREIRA', 'PINTADA', 'TUMACO', 'YUMBO',
        ],
        'ORITO' => ['ALISALES', 'GUAMUEZ', 'LA HORMIGA', 'ORITO', 'PARAMO'],
        'VAO GOR' => ['CAÑO SUR', 'RUBIALES'],
        'VAO GPA' => ['CAMPO DINA (NEIVA)', 'NEIVA', 'SAN FRANCISCO', 'YAGUARÁ'],
        'VPI' => ['CUPIAGUA', 'CUSIANA', 'FLOREÑA', 'YOPAL'],
        'VRC' => [
            'AYACUCHO', 'BARRANCABERMEJA GRB', 'BASE TTCOL LA CIRA', 'CAMPO JAZMIN',
            'CAMPO MORICHE', 'CANTAGALLO', 'CASABE', 'CHIMITA', 'EL CENTRO', 'EL LLANITO',
            'GALAN', 'ICP', 'LA CIRA', 'LIZAMA', 'NARE', 'PROVINCIA', 'PUERTO BOYACA',
            'PUERTO WILCHES', 'SEBASTOPOL', 'TECA', 'VASCONIA',
        ],
        'VRO' => [
            'ACACIAS', 'APIAY', 'CAMPO TEJON', 'CAMPO TINAMU', 'CASTILLA', 'CASTILLA 3',
            'CHICHIMENE', 'CPF SANTA MONICA', 'CPO-09', 'EL JARDIN', 'ESTACION SURIA',
            'LA VARA', 'SAN FERNANDO', 'VILLAVICENCIO',
        ],
    ];

    public function run(): void
    {
        $normalizer = app(OriginNormalizer::class);
        DB::transaction(function () use ($normalizer): void {
            foreach (self::REGIONS as $regionName => $origins) {
                $region = Region::query()->updateOrCreate(
                    ['name' => $regionName],
                    ['is_active' => true],
                );

                foreach ($origins as $origin) {
                    $normalizedOrigin = $normalizer->normalize($origin);

                    if ($normalizedOrigin === null) {
                        continue;
                    }

                    $originModel = Origin::query()->updateOrCreate(
                        ['normalized_origin' => $normalizedOrigin],
                        ['origin' => $normalizedOrigin],
                    );

                    RegionOrigin::query()->updateOrCreate(
                        ['origin_id' => $originModel->id],
                        [
                            'region_id' => $region->id,
                            'is_active' => true,
                        ],
                    );
                }
            }
        });
    }
}
