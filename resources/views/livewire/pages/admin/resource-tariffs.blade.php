<?php

use App\Models\OperationConcept;
use App\Models\Region;
use App\Models\Resource;
use App\Models\ResourceOperation;
use App\Models\ResourceTariff;
use App\Models\ResourceTariffDistanceRange;
use App\Services\Tariffs\TransportDistanceRanges;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    private const CONFIGURABLE_OPERATIONS = ['IZAJES', 'OCONCEPTOS', 'TRANSPORTE'];

    public string $operationFilter = '';
    public string $resourceFilter = '';
    public string $regionFilter = '';
    public string $statusFilter = '';
    public ?int $tariffId = null;
    public string $resourceId = '';
    public string $regionId = '';
    public string $operationConceptId = '';
    public string $unitPrice = '';
    public bool $isActive = true;
    /** @var array<int, array{label:string, minimum_km:?string, maximum_km:?string, is_minimum_inclusive:bool, is_maximum_inclusive:bool, unit_price:?string, is_active:bool}> */
    public array $distanceRanges = [];

    public function mount(): void
    {
        $this->ensureAdministrator();
    }

    /** @return EloquentCollection<int, ResourceOperation> */
    public function operations(): EloquentCollection
    {
        return ResourceOperation::query()
            ->whereIn('name', self::CONFIGURABLE_OPERATIONS)
            ->orderBy('name')
            ->get();
    }

    /** @return EloquentCollection<int, Resource> */
    public function resources(): EloquentCollection
    {
        return Resource::query()
            ->with('operation:id,name')
            ->whereHas('operation', fn ($query) => $query->whereIn('name', self::CONFIGURABLE_OPERATIONS))
            ->when($this->operationFilter !== '', fn ($query) => $query->where('resource_operation_id', (int) $this->operationFilter))
            ->orderBy('resource_name')
            ->get(['id', 'resource_id', 'resource_name', 'resource_operation_id']);
    }

    /** @return \Illuminate\Support\Collection<string, EloquentCollection<int, Resource>> */
    public function resourceGroups(): \Illuminate\Support\Collection
    {
        return $this->resources()->groupBy(
            fn (Resource $resource) => $resource->operation?->name ?? 'SIN CLASIFICACIÓN',
        );
    }

    /** @return EloquentCollection<int, Region> */
    public function regions(): EloquentCollection
    {
        return Region::query()->orderBy('name')->get(['id', 'name', 'is_active']);
    }

    /** @return EloquentCollection<int, OperationConcept> */
    public function concepts(): EloquentCollection
    {
        $resource = $this->resourceId === '' ? null : Resource::query()->find((int) $this->resourceId);

        if ($resource === null) {
            return new EloquentCollection();
        }

        return OperationConcept::query()
            ->where('resource_operation_id', $resource->resource_operation_id)
            ->orderBy('name')
            ->get();
    }

    /** @return EloquentCollection<int, ResourceTariff> */
    public function tariffs(): EloquentCollection
    {
        return ResourceTariff::query()
            ->with(['resource:id,resource_id,resource_name,resource_operation_id', 'resource.operation:id,name', 'region:id,name', 'operationConcept:id,name', 'distanceRanges'])
            ->whereHas('resource.operation', fn ($query) => $query->whereIn('name', self::CONFIGURABLE_OPERATIONS))
            ->when($this->operationFilter !== '', fn ($query) => $query->whereHas('resource', fn ($resourceQuery) => $resourceQuery->where('resource_operation_id', (int) $this->operationFilter)))
            ->when($this->resourceFilter !== '', fn ($query) => $query->where('resource_id', (int) $this->resourceFilter))
            ->when($this->regionFilter === 'general', fn ($query) => $query->whereNull('region_id'))
            ->when($this->regionFilter !== '' && $this->regionFilter !== 'general', fn ($query) => $query->where('region_id', (int) $this->regionFilter))
            ->when($this->statusFilter === 'active', fn ($query) => $query->where('is_active', true))
            ->when($this->statusFilter === 'inactive', fn ($query) => $query->where('is_active', false))
            ->when($this->statusFilter === 'pending', fn ($query) => $query
                ->whereNull('unit_price')
                ->whereDoesntHave('distanceRanges', fn ($rangeQuery) => $rangeQuery->whereNotNull('unit_price')))
            ->orderBy('resource_id')
            ->orderBy('region_id')
            ->orderBy('operation_concept_id')
            ->limit(300)
            ->get();
    }

    public function newTariff(): void
    {
        $this->ensureAdministrator();
        $this->resetTariffForm();
        $this->resetValidation();
        $this->dispatch('tariff-resource-selected', resourceId: '');
    }

    public function editTariff(int $tariffId): void
    {
        $this->ensureAdministrator();

        $tariff = $this->configurableTariff($tariffId);
        $this->tariffId = (int) $tariff->id;
        $this->resourceId = (string) $tariff->resource_id;
        $this->regionId = $tariff->region_id === null ? '' : (string) $tariff->region_id;
        $this->operationConceptId = (string) $tariff->operation_concept_id;
        $this->unitPrice = $tariff->unit_price === null ? '' : (string) $tariff->unit_price;
        $this->isActive = (bool) $tariff->is_active;
        $this->loadDistanceRanges();
        $this->resetValidation();
        $this->dispatch('tariff-resource-selected', resourceId: (string) $tariff->resource_id);
    }

    public function updatedResourceId(): void
    {
        $this->operationConceptId = '';
        $this->distanceRanges = [];
        $this->resetValidation('operationConceptId');
    }

    public function updatedRegionId(): void
    {
        $this->loadDistanceRanges();
    }

    public function updatedOperationConceptId(): void
    {
        $this->loadDistanceRanges();
    }

    public function saveTariff(): void
    {
        $this->ensureAdministrator();

        $this->validate([
            'resourceId' => ['required', 'integer', 'exists:resources,id'],
            'regionId' => ['nullable', 'integer', 'exists:regions,id'],
            'operationConceptId' => ['required', 'integer', 'exists:operation_concepts,id'],
            'unitPrice' => ['nullable', 'numeric', 'min:0', 'max:999999999999.999999'],
            'isActive' => ['boolean'],
        ], [
            'resourceId.required' => 'Seleccione un recurso.',
            'operationConceptId.required' => 'Seleccione un concepto.',
            'unitPrice.numeric' => 'El valor unitario debe ser numérico.',
            'unitPrice.min' => 'El valor unitario no puede ser negativo.',
        ]);

        $resource = Resource::query()->with('operation:id,name')->findOrFail((int) $this->resourceId);
        abort_unless(in_array($resource->operation?->name, self::CONFIGURABLE_OPERATIONS, true), 422, 'La operación del recurso no admite tarifario en esta fase.');

        $concept = OperationConcept::query()->findOrFail((int) $this->operationConceptId);
        if ((int) $concept->resource_operation_id !== (int) $resource->resource_operation_id) {
            throw ValidationException::withMessages([
                'operationConceptId' => 'El concepto debe pertenecer a la misma operación del recurso.',
            ]);
        }

        $regionId = $this->regionId === '' ? null : (int) $this->regionId;

        if ($this->isTransportTrips($resource, $concept)) {
            $this->saveTransportTripsTariff($resource, $concept, $regionId);

            return;
        }

        $duplicate = ResourceTariff::query()
            ->where('resource_id', $resource->id)
            ->where('operation_concept_id', $concept->id)
            ->when($regionId === null, fn ($query) => $query->whereNull('region_id'), fn ($query) => $query->where('region_id', $regionId))
            ->when($this->tariffId !== null, fn ($query) => $query->whereKeyNot($this->tariffId))
            ->exists();

        if ($duplicate) {
            throw ValidationException::withMessages([
                'operationConceptId' => 'Ya existe una tarifa para la combinación seleccionada.',
            ]);
        }

        $values = [
            'resource_id' => $resource->id,
            'region_id' => $regionId,
            'operation_concept_id' => $concept->id,
            'unit_price' => $this->unitPrice === '' ? null : $this->unitPrice,
            'is_active' => $this->isActive,
        ];

        if ($this->tariffId === null) {
            $tariff = ResourceTariff::query()->create($values);
        } else {
            $tariff = $this->configurableTariff($this->tariffId);
            $tariff->update($values);
        }

        $this->editTariff((int) $tariff->id);
        flash()->title('Tarifa guardada')->success('La tarifa se guardó correctamente.');
    }

    public function priceLabel(ResourceTariff $tariff): string
    {
        if ($this->isTransportTrips($tariff->resource, $tariff->operationConcept)) {
            $configured = $tariff->distanceRanges
                ->filter(fn (ResourceTariffDistanceRange $range) => $range->hasConfiguredPrice())
                ->count();

            return "{$configured}/" . count(TransportDistanceRanges::definitions()) . ' rangos';
        }

        return $tariff->unit_price === null
            ? 'Pendiente'
            : '$ ' . number_format((float) $tariff->unit_price, 2, ',', '.');
    }

    private function configurableTariff(int $tariffId): ResourceTariff
    {
        return ResourceTariff::query()
            ->whereHas('resource.operation', fn ($query) => $query->whereIn('name', self::CONFIGURABLE_OPERATIONS))
            ->findOrFail($tariffId);
    }

    private function resetTariffForm(): void
    {
        $this->tariffId = null;
        $this->resourceId = '';
        $this->regionId = '';
        $this->operationConceptId = '';
        $this->unitPrice = '';
        $this->isActive = true;
        $this->distanceRanges = [];
    }

    public function isTransportTrips(?Resource $resource = null, ?OperationConcept $concept = null): bool
    {
        $resource ??= $this->resourceId === ''
            ? null
            : Resource::query()->with('operation:id,name')->find((int) $this->resourceId);
        $concept ??= $this->operationConceptId === ''
            ? null
            : OperationConcept::query()->find((int) $this->operationConceptId);

        return $resource?->operation?->name === 'TRANSPORTE'
            && $concept?->name === 'Viajes';
    }

    private function loadDistanceRanges(): void
    {
        if (!$this->isTransportTrips()) {
            $this->distanceRanges = [];

            return;
        }

        $regionId = $this->regionId === '' ? null : (int) $this->regionId;
        $tariff = ResourceTariff::query()
            ->with('distanceRanges')
            ->where('resource_id', (int) $this->resourceId)
            ->where('operation_concept_id', (int) $this->operationConceptId)
            ->when($regionId === null, fn ($query) => $query->whereNull('region_id'), fn ($query) => $query->where('region_id', $regionId))
            ->first();

        $this->distanceRanges = collect(TransportDistanceRanges::definitions())
            ->map(function (array $definition) use ($tariff): array {
                $range = $tariff?->distanceRanges
                    ->first(fn (ResourceTariffDistanceRange $range) => $this->sameDistanceBoundary($range->minimum_km, $definition['minimum_km'])
                        && $this->sameDistanceBoundary($range->maximum_km, $definition['maximum_km'])
                        && $range->is_minimum_inclusive === $definition['is_minimum_inclusive']
                        && $range->is_maximum_inclusive === $definition['is_maximum_inclusive']);

                return [
                    ...$definition,
                    'unit_price' => $range?->unit_price,
                    'is_active' => $range?->is_active ?? true,
                ];
            })
            ->all();
    }

    private function sameDistanceBoundary(?string $storedValue, ?string $definitionValue): bool
    {
        if ($storedValue === null || $definitionValue === null) {
            return $storedValue === null && $definitionValue === null;
        }

        return (float) $storedValue === (float) $definitionValue;
    }

    private function saveTransportTripsTariff(Resource $resource, OperationConcept $concept, ?int $regionId): void
    {
        $this->validate([
            'distanceRanges' => ['array', 'size:' . count(TransportDistanceRanges::definitions())],
            'distanceRanges.*.unit_price' => ['nullable', 'numeric', 'min:0', 'max:999999999999.999999'],
            'distanceRanges.*.is_active' => ['boolean'],
        ], [
            'distanceRanges.*.unit_price.numeric' => 'El valor unitario debe ser numérico.',
            'distanceRanges.*.unit_price.min' => 'El valor unitario no puede ser negativo.',
        ]);

        $tariff = ResourceTariff::query()->updateOrCreate(
            [
                'resource_id' => $resource->id,
                'region_id' => $regionId,
                'operation_concept_id' => $concept->id,
            ],
            ['unit_price' => null, 'is_active' => $this->isActive],
        );
        $tariff->update(['unit_price' => null, 'is_active' => $this->isActive]);

        foreach (TransportDistanceRanges::definitions() as $index => $definition) {
            $range = $this->distanceRanges[$index] ?? [];
            ResourceTariffDistanceRange::query()->updateOrCreate(
                [
                    'resource_tariff_id' => $tariff->id,
                    'minimum_km' => $definition['minimum_km'],
                    'maximum_km' => $definition['maximum_km'],
                    'is_minimum_inclusive' => $definition['is_minimum_inclusive'],
                    'is_maximum_inclusive' => $definition['is_maximum_inclusive'],
                ],
                [
                    'unit_price' => blank(data_get($range, 'unit_price')) ? null : data_get($range, 'unit_price'),
                    'is_active' => (bool) data_get($range, 'is_active', true),
                ],
            );
        }

        $this->editTariff((int) $tariff->id);
        flash()->title('Tarifas de Viajes guardadas')->success('Los rangos de kilometraje se guardaron correctamente.');
    }

    private function ensureAdministrator(): void
    {
        abort_unless(auth()->user()?->hasRole('admin'), 403, 'No tienes permisos para administrar tarifas.');
    }
};
?>

@section('title', 'Tarifario')

<div class="space-y-6">
    <x-breadcrums :items="[
        ['label' => 'Inicio', 'url' => route('dashboard'), 'icon' => 'home'],
        ['label' => 'Configuración de servicios', 'url' => route('services.configuration'), 'icon' => 'settings'],
        ['label' => 'Tarifario', 'icon' => 'badge-dollar-sign'],
    ]" />

    <section class="rounded-lg border-2 bg-white p-6 shadow-lg">
        <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Tarifario</h1>
                <p class="mt-1 text-sm text-gray-600">Configure tarifas generales o por regional para Izajes, OConceptos y Transporte.</p>
            </div>
            <button type="button" wire:click="newTariff" class="rounded-md border border-blue-700 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-blue-700 transition hover:bg-blue-700 hover:text-white">
                Nueva tarifa
            </button>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1fr)_minmax(360px,440px)]">
            <section class="min-w-0 overflow-hidden rounded-xl border border-gray-200">
                <div class="grid grid-cols-1 gap-3 border-b border-gray-200 bg-gray-50 p-4 md:grid-cols-2 xl:grid-cols-4">
                    <div>
                        <x-input-label for="tariff_operation">Operación</x-input-label>
                        <select id="tariff_operation" wire:model.live="operationFilter" class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todas</option>
                            @foreach ($this->operations() as $operation)<option value="{{ $operation->id }}">{{ $operation->name }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="tariff_resource">Recurso</x-input-label>
                        <select id="tariff_resource" wire:model.live="resourceFilter" class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todos</option>
                            @foreach ($this->resources() as $resource)<option value="{{ $resource->id }}">{{ $resource->resource_name }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="tariff_region">Regional</x-input-label>
                        <select id="tariff_region" wire:model.live="regionFilter" class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todas</option><option value="general">General</option>
                            @foreach ($this->regions() as $region)<option value="{{ $region->id }}">{{ $region->name }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="tariff_status">Estado</x-input-label>
                        <select id="tariff_status" wire:model.live="statusFilter" class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todos</option><option value="active">Activas</option><option value="inactive">Inactivas</option><option value="pending">Pendientes</option>
                        </select>
                    </div>
                </div>
                <div class="max-h-[640px] overflow-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="sticky top-0 z-10 bg-white text-left text-xs font-semibold uppercase tracking-wide text-gray-500"><tr><th class="px-4 py-3">Recurso</th><th class="px-4 py-3">Regional</th><th class="px-4 py-3">Concepto</th><th class="px-4 py-3">Valor unitario</th><th class="px-4 py-3">Estado</th><th class="px-4 py-3"></th></tr></thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($this->tariffs() as $tariff)
                                <tr wire:key="tariff-{{ $tariff->id }}" @class(['bg-blue-50' => $tariffId === $tariff->id])>
                                    <td class="px-4 py-3"><div class="font-medium text-gray-900">{{ $tariff->resource->resource_name }}</div><div class="text-xs text-gray-500">{{ $tariff->resource->operation?->name }}</div></td>
                                    <td class="px-4 py-3 text-gray-700">{{ $tariff->region?->name ?? 'General' }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $tariff->operationConcept->name }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-800">{{ $this->priceLabel($tariff) }}</td>
                                    <td class="px-4 py-3"><span @class(['rounded-full px-2 py-1 text-xs font-medium', 'bg-emerald-100 text-emerald-800' => $tariff->is_active, 'bg-gray-100 text-gray-700' => !$tariff->is_active])>{{ $tariff->is_active ? 'Activa' : 'Inactiva' }}</span></td>
                                    <td class="px-4 py-3 text-right"><button type="button" wire:click="editTariff({{ $tariff->id }})" class="text-xs font-medium text-blue-700 hover:underline">Editar</button></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-4 py-10 text-center text-sm text-gray-500">No hay tarifas con los filtros seleccionados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="h-fit rounded-xl border border-gray-200 bg-gray-50 p-5">
                <h2 class="font-semibold text-gray-900">{{ $tariffId === null ? 'Nueva tarifa' : 'Edición de tarifa' }}</h2>
                <form wire:submit="saveTariff" class="mt-5 space-y-4">
                    <div>
                        <x-input-label for="form_resource" value="Recurso" />
                        <div class="mt-1" wire:ignore>
                            <select id="form_resource" class="js-status-select w-full whitespace-normal rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                data-placeholder="Seleccione un recurso" data-current-value="{{ $resourceId }}" data-livewire-model="resourceId">
                                <option value="">Seleccione un recurso</option>
                                @foreach ($this->resourceGroups() as $operation => $resources)
                                    <optgroup label="{{ $operation }}">
                                        @foreach ($resources as $resource)
                                            <option value="{{ $resource->id }}">{{ $resource->resource_name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        @error('resourceId')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div><x-input-label for="form_region" value="Regional" /><select id="form_region" wire:model.live="regionId" class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"><option value="">General (sin regional)</option>@foreach ($this->regions() as $region)<option value="{{ $region->id }}">{{ $region->name }}</option>@endforeach</select>@error('regionId')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                    <div><x-input-label for="form_concept" value="Concepto" /><select id="form_concept" wire:model.live="operationConceptId" class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" @disabled($resourceId === '')><option value="">Seleccione un concepto</option>@foreach ($this->concepts() as $concept)<option value="{{ $concept->id }}">{{ $concept->name }}</option>@endforeach</select>@error('operationConceptId')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                    @if ($this->isTransportTrips())
                        <div class="rounded-xl border border-gray-200 bg-white p-4">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">Tarifas por distancia</h3>
                                <p class="mt-1 text-xs text-gray-500">Configure el valor de Viajes para cada rango de kilometraje.</p>
                            </div>
                            <div class="mt-4 space-y-3">
                                @foreach ($distanceRanges as $index => $range)
                                    <div wire:key="distance-range-{{ $index }}" class="grid grid-cols-1 gap-2 rounded-lg border border-gray-100 p-3 sm:grid-cols-[minmax(0,1fr)_150px_auto] sm:items-end">
                                        <div class="text-sm font-medium text-gray-800">{{ $range['label'] }}</div>
                                        <div>
                                            <x-input-label :for="'distance_range_' . $index" value="Valor unitario" />
                                            <x-text-input :id="'distance_range_' . $index" type="number" step="0.000001" min="0" wire:model="distanceRanges.{{ $index }}.unit_price" class="mt-1 w-full" placeholder="Pendiente" />
                                            @error('distanceRanges.' . $index . '.unit_price')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                        </div>
                                        <label class="flex items-center gap-2 pb-2 text-sm text-gray-700"><input type="checkbox" wire:model="distanceRanges.{{ $index }}.is_active" class="rounded border-gray-300 text-blue-700 focus:ring-blue-600"><span>Activa</span></label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @unless ($this->isTransportTrips())
                    <div><x-input-label for="form_unit_price" value="Valor unitario" /><x-text-input id="form_unit_price" type="number" step="0.000001" min="0" wire:model="unitPrice" class="mt-1 w-full" placeholder="Pendiente de configurar" />@error('unitPrice')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror<p class="mt-1 text-xs text-gray-500">Déjelo vacío para marcar la tarifa como pendiente. Cero es un valor válido, pero no habilita uso futuro.</p></div>
                    @endunless
                    <label class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white px-3 py-2"><input type="checkbox" wire:model="isActive" class="rounded border-gray-300 text-blue-700 focus:ring-blue-600"><span class="text-sm font-medium text-gray-800">{{ $this->isTransportTrips() ? 'Combinación de tarifa activa' : 'Tarifa activa' }}</span></label>
                    <div class="flex justify-end"><x-success-button type="submit" wire:loading.attr="disabled" wire:target="saveTariff">Guardar tarifa</x-success-button></div>
                </form>
            </section>
        </div>
    </section>
</div>
