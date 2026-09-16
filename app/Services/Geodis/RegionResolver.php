<?php

namespace App\Services\Geodis;

use App\Models\Region;
use App\Models\RegionOrigin;
use Illuminate\Support\Collection;

class RegionResolver
{
    public function __construct(
        private readonly OriginNormalizer $normalizer,
    ) {}

    public function resolve(?string $origin): ?Region
    {
        $normalizedOrigin = $this->normalizer->normalize($origin);

        if ($normalizedOrigin === null) {
            return null;
        }

        return RegionOrigin::query()
            ->active()
            ->where('normalized_origin', $normalizedOrigin)
            ->whereHas('region', fn ($query) => $query->active())
            ->with('region')
            ->first()?->region;
    }

    /**
     * @param  iterable<?string>  $origins
     * @return Collection<string, Region>
     */
    public function resolveMany(iterable $origins): Collection
    {
        $normalizedOrigins = collect($origins)
            ->map(fn (?string $origin) => $this->normalizer->normalize($origin))
            ->filter()
            ->unique()
            ->values();

        if ($normalizedOrigins->isEmpty()) {
            return collect();
        }

        return RegionOrigin::query()
            ->active()
            ->whereIn('normalized_origin', $normalizedOrigins)
            ->whereHas('region', fn ($query) => $query->active())
            ->with('region:id,name,is_active')
            ->get()
            ->mapWithKeys(fn (RegionOrigin $regionOrigin) => [
                $regionOrigin->normalized_origin => $regionOrigin->region,
            ]);
    }
}
