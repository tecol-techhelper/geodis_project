<?php

namespace App\Services\Tariffs;

use App\Models\OperationConcept;
use App\Models\Region;
use App\Models\Resource;
use App\Models\ResourceTariff;
use App\Models\ResourceTariffDistanceRange;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class TransportTariffSpreadsheetImporter
{
    private const OPERATION = 'TRANSPORTE';

    /** @var array<int, string> */
    private const FIXED_CONCEPTS = [
        'Km Adicional',
        'Mes Operativo',
        'Día Operativo',
        'Día Standby',
        'Día Disponibilidad',
        'Consolidación',
    ];

    public function __construct(private readonly InitialTransportTariffCatalogue $catalogue)
    {
    }

    /** @return array{tariffs_created: int, ranges_created: int, skipped: int} */
    public function import(): array
    {
        $records = $this->resolveRecords();

        return DB::transaction(function () use ($records): array {
            $tariffsCreated = 0;
            $rangesCreated = 0;
            $skipped = 0;

            foreach ($records as $record) {
                $tariff = $this->findTariff($record['resource_id'], $record['region_id'], $record['viajes_concept_id']);

                if ($tariff === null) {
                    $tariff = ResourceTariff::query()->create([
                        'resource_id' => $record['resource_id'],
                        'region_id' => $record['region_id'],
                        'operation_concept_id' => $record['viajes_concept_id'],
                        'unit_price' => null,
                        'is_active' => true,
                    ]);
                    $tariffsCreated++;
                }

                foreach (TransportDistanceRanges::definitions() as $index => $rangeDefinition) {
                    $range = ResourceTariffDistanceRange::query()
                        ->where('resource_tariff_id', $tariff->id)
                        ->where($rangeDefinition['minimum_km'] === null ? fn ($query) => $query->whereNull('minimum_km') : fn ($query) => $query->where('minimum_km', $rangeDefinition['minimum_km']))
                        ->where($rangeDefinition['maximum_km'] === null ? fn ($query) => $query->whereNull('maximum_km') : fn ($query) => $query->where('maximum_km', $rangeDefinition['maximum_km']))
                        ->where('is_minimum_inclusive', $rangeDefinition['is_minimum_inclusive'])
                        ->where('is_maximum_inclusive', $rangeDefinition['is_maximum_inclusive'])
                        ->first();

                    if ($range !== null) {
                        $skipped++;
                        continue;
                    }

                    ResourceTariffDistanceRange::query()->create([
                        'resource_tariff_id' => $tariff->id,
                        ...array_diff_key($rangeDefinition, ['label' => true]),
                        'unit_price' => $record['prices'][$index],
                        'is_active' => true,
                    ]);
                    $rangesCreated++;
                }

                foreach (self::FIXED_CONCEPTS as $conceptIndex => $conceptName) {
                    $conceptId = $record['fixed_concept_ids'][$conceptName];
                    $existingTariff = $this->findTariff($record['resource_id'], $record['region_id'], $conceptId);

                    if ($existingTariff !== null) {
                        $skipped++;
                        continue;
                    }

                    ResourceTariff::query()->create([
                        'resource_id' => $record['resource_id'],
                        'region_id' => $record['region_id'],
                        'operation_concept_id' => $conceptId,
                        'unit_price' => $record['prices'][$conceptIndex + 11],
                        'is_active' => true,
                    ]);
                    $tariffsCreated++;
                }
            }

            return [
                'tariffs_created' => $tariffsCreated,
                'ranges_created' => $rangesCreated,
                'skipped' => $skipped,
            ];
        });
    }

    /** @return array<int, array{resource_id:int, region_id:?int, viajes_concept_id:int, fixed_concept_ids:array<string, int>, prices:array<int, ?string>}> */
    private function resolveRecords(): array
    {
        $resources = Resource::query()
            ->with('operation:id,name')
            ->whereHas('operation', fn ($query) => $query->where('name', self::OPERATION))
            ->get(['id', 'resource_id', 'resource_name', 'resource_operation_id']);
        $regionsByName = Region::query()
            ->pluck('id', 'name')
            ->mapWithKeys(fn ($id, $name) => [$this->normalize($name) => (int) $id]);
        $concepts = OperationConcept::query()
            ->whereHas('operation', fn ($query) => $query->where('name', self::OPERATION))
            ->get(['id', 'name']);

        $resourceLookup = [];
        foreach ($resources as $resource) {
            $resourceLookup[$this->resourceKey($resource->resource_name)] = (int) $resource->id;
            $resourceLookup[$this->resourceKey($resource->resource_id)] = (int) $resource->id;
        }

        $conceptIds = $concepts->mapWithKeys(fn (OperationConcept $concept) => [$this->normalize($concept->name) => (int) $concept->id]);
        $viajesConceptId = $conceptIds->get($this->normalize('Viajes'));
        $fixedConceptIds = collect(self::FIXED_CONCEPTS)
            ->mapWithKeys(fn (string $concept) => [$concept => $conceptIds->get($this->normalize($concept))])
            ->all();

        $errors = [];
        if ($viajesConceptId === null) {
            $errors[] = 'Concepto no encontrado para TRANSPORTE: Viajes';
        }
        foreach ($fixedConceptIds as $conceptName => $conceptId) {
            if ($conceptId === null) {
                $errors[] = "Concepto no encontrado para TRANSPORTE: {$conceptName}";
            }
        }

        $records = [];
        foreach ($this->catalogue->rows() as $row) {
            $service = trim($row['service']);
            $prices = $row['prices'];
            if ($service === '' || count($prices) !== 17) {
                $errors[] = "Fila de Transporte inválida: {$service}";
                continue;
            }

            foreach ($prices as $price) {
                if ($price !== null && trim($price) !== '' && !is_numeric($price)) {
                    $errors[] = "El valor de Transporte para {$service} no es numérico.";
                }
            }

            [$regionId, $resourceLabel] = $this->splitServiceName($service, $regionsByName);
            $resourceId = $resourceLookup[$this->resourceKey($resourceLabel)] ?? null;
            if ($resourceId === null) {
                $errors[] = "Recurso no encontrado para TRANSPORTE: {$resourceLabel}";
                continue;
            }

            $key = implode(':', [$resourceId, $regionId ?? 'general']);
            if (isset($records[$key])) {
                $errors[] = "Combinación duplicada en Transporte: {$service}";
                continue;
            }

            $records[$key] = [
                'resource_id' => $resourceId,
                'region_id' => $regionId,
                'viajes_concept_id' => (int) $viajesConceptId,
                'fixed_concept_ids' => array_map(static fn ($id) => (int) $id, $fixedConceptIds),
                'prices' => array_map(static fn ($price) => $price === null || trim($price) === '' ? null : trim($price), $prices),
            ];
        }

        if ($errors !== []) {
            throw new RuntimeException("Las tarifas de Transporte no se importaron.\n" . implode("\n", array_values(array_unique($errors))));
        }

        return array_values($records);
    }

    private function findTariff(int $resourceId, ?int $regionId, int $conceptId): ?ResourceTariff
    {
        return ResourceTariff::query()
            ->where('resource_id', $resourceId)
            ->where('operation_concept_id', $conceptId)
            ->when($regionId === null, fn ($query) => $query->whereNull('region_id'), fn ($query) => $query->where('region_id', $regionId))
            ->first();
    }

    /** @param \Illuminate\Support\Collection<string, int> $regionsByName
     *  @return array{0:?int, 1:string}
     */
    private function splitServiceName(string $service, $regionsByName): array
    {
        [$prefix, $rest] = array_pad(explode('_', $service, 2), 2, null);
        $regionId = $rest === null ? null : $regionsByName->get($this->normalize($prefix));

        return $regionId === null ? [null, $service] : [$regionId, trim($rest)];
    }

    private function resourceKey(string $value): string
    {
        $normalized = str_replace('HIDRAULICO', '', $this->normalize($value));

        return preg_replace('/[^A-Z0-9]+/', '', $normalized) ?? '';
    }

    private function normalize(string $value): string
    {
        $normalized = preg_replace('/\s+/u', ' ', trim($value)) ?? '';

        return Str::upper(Str::ascii($normalized));
    }
}
