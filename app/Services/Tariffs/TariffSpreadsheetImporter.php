<?php

namespace App\Services\Tariffs;

use App\Models\OperationConcept;
use App\Models\Region;
use App\Models\Resource;
use App\Models\ResourceTariff;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class TariffSpreadsheetImporter
{
    /** @var array<string, array<string, string>> */
    private const RESOURCE_ALIASES = [
        'OCONCEPTOS' => [
            'APAREJADOR DE GRUA / APAREJADOR DE CARGAS / APAREJADOR DE EQUIPOS DE IZAJE ADICIONAL' => 'O_AGCEIA',
            'POLINES DE MADERA 4" X 4"' => 'O_PMCT44',
            'POLINES DE MADERA 6" X 6"' => 'O_PMCT66',
            'TRANSPORTE CONTENEDOR VACIO 1X20"ST, OT, FR ENTRE OTROS DE 20", EN LA RUTA VRC - PUERTOS - COSTA NORTE O BUENAVENTURA' => 'O_TCV20A',
            'TRANSPORTE CONTENEDOR VACIO 1X40" ST, HC, OT, FR ENTRE OTROS DE 40" EN LA RUTA VRC - PUERTOS COSTA NORTE O BUENAVENTURA' => 'O_TCV40C',
            'TRANSPORTE CONTENEDOR VACIO 1X20"ST, OT, FR ENTRE OTROS DE 20" VRO - PUERTOS COSTA NORTE O BUENAVENTURA' => 'O_TCV20B',
            'TRANSPORTE CONTENEDOR VACIO 1X40" ST, HC, OT, FR ENTRE OTROS DE 40" EN LA RUTA VRO - PUERTOS COSTA NORTE O BUENAVENTURA' => 'O_TCV40A',
        ],
        'IZAJES' => [
            'TELEHANDLER - CAPACIDAD MIN 17MTS (CON OPERADOR)' => 'I_TLHCO0',
            'CILINDROS HIDRAULICOS EQUIPO DE GATEO CAP TOTAL (MIN) 100 TON' => 'I_CH100T',
            'CILINDROS HIDRAULICOS EQUIPO DE GATEO CAP TOTAL (MIN) 200 TON' => 'I_CH200T',
        ],
    ];

    public function __construct(private readonly InitialTariffCatalogue $catalogue)
    {
    }

    /** @return array{created: int, skipped: int} */
    public function import(): array
    {
        $rows = $this->readRows();
        $records = $this->resolveRecords($rows);

        return DB::transaction(function () use ($records): array {
            $created = 0;
            $skipped = 0;

            foreach ($records as $record) {
                $query = ResourceTariff::query()
                    ->where('resource_id', $record['resource_id'])
                    ->where('operation_concept_id', $record['operation_concept_id']);

                $record['region_id'] === null
                    ? $query->whereNull('region_id')
                    : $query->where('region_id', $record['region_id']);

                if ($query->exists()) {
                    $skipped++;
                    continue;
                }

                ResourceTariff::query()->create([
                    ...$record,
                    'is_active' => true,
                ]);

                $created++;
            }

            return compact('created', 'skipped');
        });
    }

    /** @return array<int, array{operation: string, service: string, concept: string, price: ?string}> */
    private function readRows(): array
    {
        $rows = [];

        foreach ($this->catalogue->tables() as $table) {
            foreach ($table['rows'] as $row) {
                $service = trim((string) array_shift($row));

                if ($service === '' || count($row) !== count($table['concepts'])) {
                    continue;
                }

                foreach ($table['concepts'] as $index => $concept) {
                    $price = $row[$index] === null ? '' : trim((string) $row[$index]);

                    if ($price !== '' && !is_numeric($price)) {
                        throw new RuntimeException("El valor de {$concept} para {$service} no es numérico.");
                    }

                    $rows[] = [
                        'operation' => (string) $table['operation'],
                        'service' => $service,
                        'concept' => trim((string) $concept),
                        'price' => $price === '' ? null : $price,
                    ];
                }
            }
        }

        return $rows;
    }

    /** @param array<int, array{operation: string, service: string, concept: string, price: ?string}> $rows
     *  @return array<int, array{resource_id: int, region_id: ?int, operation_concept_id: int, unit_price: ?string}>
     */
    private function resolveRecords(array $rows): array
    {
        $resources = Resource::query()
            ->with('operation:id,name')
            ->get(['id', 'resource_id', 'resource_name', 'resource_operation_id']);
        $regionsByName = Region::query()->pluck('id', 'name')
            ->mapWithKeys(fn ($id, $name) => [$this->normalize($name) => (int) $id]);
        $concepts = OperationConcept::query()
            ->with('operation:id,name')
            ->get(['id', 'resource_operation_id', 'name']);

        $resourceLookup = [];
        foreach ($resources as $resource) {
            $resourceLookup[$resource->operation?->name][$this->normalize($resource->resource_name)] = $resource;
            $resourceLookup[$resource->operation?->name][$this->normalize($resource->resource_id)] = $resource;
        }

        $conceptLookup = [];
        foreach ($concepts as $concept) {
            $conceptLookup[$concept->operation?->name][$this->normalize($concept->name)] = $concept;
        }

        $records = [];
        $errors = [];

        foreach ($rows as $row) {
            [$regionId, $resourceLabel] = $this->splitServiceName($row['service'], $regionsByName);
            $normalizedResourceLabel = $this->normalize($resourceLabel);
            $resourceKey = self::RESOURCE_ALIASES[$row['operation']][$normalizedResourceLabel] ?? $normalizedResourceLabel;
            $resource = $resourceLookup[$row['operation']][$resourceKey] ?? null;
            $concept = $conceptLookup[$row['operation']][$this->normalize($row['concept'])] ?? null;

            if ($resource === null) {
                $errors[] = "Recurso no encontrado para {$row['operation']}: {$resourceLabel}";
                continue;
            }

            if ($concept === null) {
                $errors[] = "Concepto no encontrado para {$row['operation']}: {$row['concept']}";
                continue;
            }

            $key = implode(':', [$resource->id, $regionId ?? 'general', $concept->id]);
            if (isset($records[$key])) {
                $errors[] = "Combinación duplicada en el archivo: {$row['service']} / {$row['concept']}";
                continue;
            }

            $records[$key] = [
                'resource_id' => (int) $resource->id,
                'region_id' => $regionId,
                'operation_concept_id' => (int) $concept->id,
                'unit_price' => $row['price'],
            ];
        }

        if ($errors !== []) {
            throw new RuntimeException("El tarifario no se importó.\n" . implode("\n", array_values(array_unique($errors))));
        }

        return array_values($records);
    }

    /** @param \Illuminate\Support\Collection<string, int> $regionsByName
     *  @return array{0: ?int, 1: string}
     */
    private function splitServiceName(string $service, $regionsByName): array
    {
        [$prefix, $rest] = array_pad(explode('_', $service, 2), 2, null);
        $regionId = $rest === null ? null : $regionsByName->get($this->normalize($prefix));

        return $regionId === null ? [null, $service] : [$regionId, trim($rest)];
    }

    private function normalize(string $value): string
    {
        $normalized = preg_replace('/\s+/u', ' ', trim($value)) ?? '';

        return Str::upper(Str::ascii($normalized));
    }

}
