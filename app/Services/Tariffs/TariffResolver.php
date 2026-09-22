<?php

namespace App\Services\Tariffs;

use App\Models\OperationConcept;
use App\Models\ResourceTariff;
use Brick\Math\BigDecimal;

class TariffResolver
{
    public function resolve(?int $resourceId, ?int $regionId, ?int $conceptId, ?string $kilometres = null): ?string
    {
        if ($resourceId === null || $conceptId === null) {
            return null;
        }

        if ($this->usesDistanceRanges($conceptId)) {
            return $this->resolveDistancePrice($resourceId, $regionId, $conceptId, $kilometres);
        }

        $baseQuery = ResourceTariff::query()
            ->where('resource_id', $resourceId)
            ->where('operation_concept_id', $conceptId)
            ->where('is_active', true)
            ->whereNotNull('unit_price')
            ->where('unit_price', '>', 0);

        if ($regionId !== null) {
            $regionalPrice = (clone $baseQuery)->where('region_id', $regionId)->value('unit_price');

            if ($regionalPrice !== null) {
                return (string) $regionalPrice;
            }
        }

        $generalPrice = $baseQuery->whereNull('region_id')->value('unit_price');

        return $generalPrice === null ? null : (string) $generalPrice;
    }

    public function usesDistanceRanges(?int $conceptId): bool
    {
        return $conceptId !== null && OperationConcept::query()
            ->whereKey($conceptId)
            ->where('name', 'Viajes')
            ->whereHas('operation', fn ($query) => $query->where('name', 'TRANSPORTE'))
            ->exists();
    }

    private function resolveDistancePrice(int $resourceId, ?int $regionId, int $conceptId, ?string $kilometres): ?string
    {
        if ($kilometres === null || ! is_numeric($kilometres)) {
            return null;
        }
        $distance = BigDecimal::of($kilometres);
        if ($distance->isLessThanOrEqualTo(0)) {
            return null;
        }

        // Keep the existing priority: regional tariff, then general tariff.
        foreach ($regionId !== null ? [$regionId, null] : [null] as $candidateRegion) {
            $tariffs = ResourceTariff::query()
                ->where('resource_id', $resourceId)
                ->where('operation_concept_id', $conceptId)
                ->where('region_id', $candidateRegion)
                ->where('is_active', true)
                ->with(['distanceRanges' => fn ($query) => $query
                    ->where('is_active', true)->whereNotNull('unit_price')->where('unit_price', '>', 0)])
                ->get();

            $matches = $tariffs->flatMap->distanceRanges->filter(function ($range) use ($distance): bool {
                $minimum = $range->minimum_km;
                $maximum = $range->maximum_km;

                return ($minimum === null || ($range->is_minimum_inclusive
                    ? $distance->isGreaterThanOrEqualTo($minimum) : $distance->isGreaterThan($minimum)))
                    && ($maximum === null || ($range->is_maximum_inclusive
                        ? $distance->isLessThanOrEqualTo($maximum) : $distance->isLessThan($maximum)));
            });

            if ($matches->count() > 1) {
                return null; // An overlapping configuration must not select an arbitrary price.
            }
            if ($matches->count() === 1) {
                return (string) $matches->first()->unit_price;
            }
        }

        return null;
    }
}
