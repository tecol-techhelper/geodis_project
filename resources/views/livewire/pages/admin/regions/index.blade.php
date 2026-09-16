<?php

use App\Models\Region;
use App\Models\RegionOrigin;
use App\Services\Geodis\OriginNormalizer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    public ?int $selectedRegionId = null;
    public ?int $regionId = null;
    public string $regionName = '';
    public bool $regionIsActive = true;

    public ?int $originId = null;
    public string $originName = '';
    public bool $originIsActive = true;

    public function mount(): void
    {
        $this->ensureAdministrator();

        $firstRegionId = Region::query()->orderBy('name')->value('id');

        if ($firstRegionId !== null) {
            $this->selectRegion((int) $firstRegionId);
        }
    }

    /** @return EloquentCollection<int, Region> */
    public function regions(): EloquentCollection
    {
        return Region::query()->withCount('origins')->orderBy('name')->get();
    }

    /** @return EloquentCollection<int, RegionOrigin> */
    public function origins(): EloquentCollection
    {
        if ($this->selectedRegionId === null) {
            return new EloquentCollection();
        }

        return RegionOrigin::query()
            ->where('region_id', $this->selectedRegionId)
            ->orderBy('origin')
            ->get();
    }

    public function selectRegion(int $regionId): void
    {
        $this->ensureAdministrator();

        $region = Region::query()->findOrFail($regionId);
        $this->selectedRegionId = (int) $region->id;
        $this->regionId = (int) $region->id;
        $this->regionName = $region->name;
        $this->regionIsActive = (bool) $region->is_active;
        $this->resetOriginForm();
        $this->resetValidation();
    }

    public function newRegion(): void
    {
        $this->ensureAdministrator();
        $this->selectedRegionId = null;
        $this->regionId = null;
        $this->regionName = '';
        $this->regionIsActive = true;
        $this->resetOriginForm();
        $this->resetValidation();
    }

    public function saveRegion(): void
    {
        $this->ensureAdministrator();
        $this->validate([
            'regionName' => ['required', 'string', 'max:100'],
            'regionIsActive' => ['boolean'],
        ], [
            'regionName.required' => 'El nombre de la regional es obligatorio.',
            'regionName.max' => 'El nombre de la regional no puede superar 100 caracteres.',
        ]);

        $name = $this->normalizer()->normalize($this->regionName);

        if ($name === null) {
            throw ValidationException::withMessages(['regionName' => 'El nombre de la regional es obligatorio.']);
        }

        $duplicate = Region::query()
            ->where('name', $name)
            ->when($this->regionId !== null, fn ($query) => $query->whereKeyNot($this->regionId))
            ->exists();

        if ($duplicate) {
            throw ValidationException::withMessages(['regionName' => 'Ya existe una regional con ese nombre.']);
        }

        if ($this->regionId === null) {
            $region = Region::query()->create(['name' => $name, 'is_active' => $this->regionIsActive]);
        } else {
            $region = Region::query()->findOrFail($this->regionId);
            $region->update(['name' => $name, 'is_active' => $this->regionIsActive]);
        }

        $this->selectRegion((int) $region->id);
        flash()->title('Regional guardada')->success('La regional se guardo correctamente.');
    }

    public function toggleRegion(int $regionId): void
    {
        $this->ensureAdministrator();
        $region = Region::query()->findOrFail($regionId);
        $region->update(['is_active' => ! $region->is_active]);

        if ($this->selectedRegionId === $regionId) {
            $this->regionIsActive = (bool) $region->is_active;
        }
    }

    public function newOrigin(): void
    {
        $this->ensureAdministrator();
        abort_unless($this->selectedRegionId !== null, 422, 'Seleccione una regional antes de agregar un origen.');
        $this->resetOriginForm();
        $this->resetValidation();
    }

    public function editOrigin(int $originId): void
    {
        $this->ensureAdministrator();
        $origin = RegionOrigin::query()->where('region_id', $this->selectedRegionId)->findOrFail($originId);
        $this->originId = (int) $origin->id;
        $this->originName = $origin->origin;
        $this->originIsActive = (bool) $origin->is_active;
        $this->resetValidation();
    }

    public function saveOrigin(): void
    {
        $this->ensureAdministrator();
        $this->validate([
            'selectedRegionId' => ['required', 'integer', 'exists:regions,id'],
            'originName' => ['required', 'string', 'max:191'],
            'originIsActive' => ['boolean'],
        ], [
            'selectedRegionId.required' => 'Seleccione una regional antes de asociar un origen.',
            'selectedRegionId.exists' => 'La regional seleccionada no existe.',
            'originName.required' => 'El origen es obligatorio.',
            'originName.max' => 'El origen no puede superar 191 caracteres.',
        ]);

        $normalizedOrigin = $this->normalizer()->normalize($this->originName);

        if ($normalizedOrigin === null) {
            throw ValidationException::withMessages(['originName' => 'El origen es obligatorio.']);
        }

        $duplicate = RegionOrigin::query()
            ->where('normalized_origin', $normalizedOrigin)
            ->when($this->originId !== null, fn ($query) => $query->whereKeyNot($this->originId))
            ->exists();

        if ($duplicate) {
            throw ValidationException::withMessages(['originName' => 'El origen ya esta asociado a una regional.']);
        }

        Region::query()->findOrFail($this->selectedRegionId);

        if ($this->originId === null) {
            RegionOrigin::query()->create([
                'region_id' => $this->selectedRegionId,
                'origin' => $normalizedOrigin,
                'normalized_origin' => $normalizedOrigin,
                'is_active' => $this->originIsActive,
            ]);
        } else {
            RegionOrigin::query()
                ->where('region_id', $this->selectedRegionId)
                ->findOrFail($this->originId)
                ->update([
                    'origin' => $normalizedOrigin,
                    'normalized_origin' => $normalizedOrigin,
                    'is_active' => $this->originIsActive,
                ]);
        }

        $this->resetOriginForm();
        flash()->title('Origen guardado')->success('El origen se guardo correctamente.');
    }

    public function toggleOrigin(int $originId): void
    {
        $this->ensureAdministrator();
        $origin = RegionOrigin::query()->where('region_id', $this->selectedRegionId)->findOrFail($originId);
        $origin->update(['is_active' => ! $origin->is_active]);

        if ($this->originId === $originId) {
            $this->originIsActive = (bool) $origin->is_active;
        }
    }

    private function resetOriginForm(): void
    {
        $this->originId = null;
        $this->originName = '';
        $this->originIsActive = true;
    }

    private function normalizer(): OriginNormalizer
    {
        return app(OriginNormalizer::class);
    }

    private function ensureAdministrator(): void
    {
        abort_unless(auth()->user()?->hasRole('admin'), 403, 'No tienes permisos para administrar regionales.');
    }
};
?>

@section('title', 'Regionales')

<div class="space-y-6">
    <x-breadcrums :items="[
        ['label' => 'Inicio', 'url' => route('dashboard'), 'icon' => 'home'],
        ['label' => 'Regionales', 'icon' => 'settings'],
    ]" />

    <div class="rounded-lg border-2 bg-white p-6 shadow-lg">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Regionales y origenes</h1>
                <p class="mt-1 text-sm text-gray-600">Administra las regionales y los origenes que resuelve la API.</p>
            </div>
            <button type="button" wire:click="newRegion" class="rounded-md border border-blue-700 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-blue-700 transition hover:bg-blue-700 hover:text-white">Nueva regional</button>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,0.9fr)_minmax(0,1.4fr)]">
            <section class="overflow-hidden rounded-xl border border-gray-200">
                <div class="border-b border-gray-200 bg-gray-50 px-4 py-3"><h2 class="font-semibold text-gray-900">Regionales</h2></div>
                <div class="max-h-[620px] overflow-y-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="sticky top-0 bg-white text-left text-xs font-semibold uppercase tracking-wide text-gray-500"><tr><th class="px-4 py-3">Regional</th><th class="px-4 py-3">Origenes</th><th class="px-4 py-3">Estado</th></tr></thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($this->regions() as $region)
                                <tr wire:key="region-{{ $region->id }}" wire:click="selectRegion({{ $region->id }})" @class(['cursor-pointer transition hover:bg-blue-50', 'bg-blue-50' => $selectedRegionId === $region->id])>
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $region->name }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $region->origins_count }}</td>
                                    <td class="px-4 py-3"><button type="button" wire:click.stop="toggleRegion({{ $region->id }})" @class(['rounded-full px-2.5 py-1 text-xs font-medium', 'bg-emerald-100 text-emerald-800' => $region->is_active, 'bg-gray-200 text-gray-700' => ! $region->is_active])>{{ $region->is_active ? 'Activa' : 'Inactiva' }}</button></td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-4 py-8 text-center text-sm text-gray-500">No hay regionales creadas.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="space-y-6">
                <form wire:submit="saveRegion" class="rounded-xl border border-gray-200 bg-gray-50 p-5">
                    <h2 class="font-semibold text-gray-900">{{ $regionId === null ? 'Nueva regional' : 'Editar regional' }}</h2>
                    <p class="mt-1 text-xs text-gray-500">Este nombre es el valor que retornara la API en regional.</p>
                    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-[minmax(0,1fr)_150px]">
                        <div><x-input-label for="region_name">Nombre de regional</x-input-label><x-text-input id="region_name" type="text" wire:model.defer="regionName" class="mt-1 w-full" placeholder="Ej. VRO" /><x-input-error :messages="$errors->get('regionName')" class="mt-2" /></div>
                        <div><x-input-label for="region_status">Estado</x-input-label><select id="region_status" wire:model.defer="regionIsActive" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"><option value="1">Activa</option><option value="0">Inactiva</option></select></div>
                    </div>
                    <div class="mt-4 flex justify-end"><x-success-button type="submit" wire:loading.attr="disabled" wire:target="saveRegion">Guardar regional</x-success-button></div>
                </form>

                @if ($selectedRegionId !== null)
                    <div class="rounded-xl border border-gray-200 bg-white">
                        <div class="flex flex-col gap-3 border-b border-gray-200 bg-gray-50 p-4 sm:flex-row sm:items-center sm:justify-between">
                            <div><h2 class="font-semibold text-gray-900">Origenes asociados</h2><p class="mt-1 text-xs text-gray-500">Cada origen solo puede pertenecer a una regional.</p></div>
                            <button type="button" wire:click="newOrigin" class="rounded-md border border-blue-700 bg-white px-3 py-2 text-xs font-semibold uppercase tracking-widest text-blue-700 transition hover:bg-blue-700 hover:text-white">Nuevo origen</button>
                        </div>
                        <div class="grid grid-cols-1 gap-5 p-4 lg:grid-cols-[minmax(0,1fr)_280px]">
                            <div class="max-h-[360px] overflow-y-auto rounded-lg border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200 text-sm"><thead class="sticky top-0 bg-white text-left text-xs font-semibold uppercase tracking-wide text-gray-500"><tr><th class="px-3 py-2">Origen</th><th class="px-3 py-2">Estado</th><th class="px-3 py-2"></th></tr></thead><tbody class="divide-y divide-gray-100">
                                    @forelse ($this->origins() as $origin)
                                        <tr wire:key="origin-{{ $origin->id }}" @class(['bg-blue-50' => $originId === $origin->id])><td class="px-3 py-2 font-medium text-gray-800">{{ $origin->origin }}</td><td class="px-3 py-2"><button type="button" wire:click="toggleOrigin({{ $origin->id }})" @class(['rounded-full px-2 py-1 text-xs font-medium', 'bg-emerald-100 text-emerald-800' => $origin->is_active, 'bg-gray-200 text-gray-700' => ! $origin->is_active])>{{ $origin->is_active ? 'Activo' : 'Inactivo' }}</button></td><td class="px-3 py-2 text-right"><button type="button" wire:click="editOrigin({{ $origin->id }})" class="text-xs font-medium text-blue-700 hover:underline">Editar</button></td></tr>
                                    @empty
                                        <tr><td colspan="3" class="px-3 py-6 text-center text-sm text-gray-500">Esta regional no tiene origenes.</td></tr>
                                    @endforelse
                                </tbody></table>
                            </div>
                            <form wire:submit="saveOrigin" class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                <h3 class="font-semibold text-gray-900">{{ $originId === null ? 'Nuevo origen' : 'Editar origen' }}</h3>
                                <div class="mt-4"><x-input-label for="origin_name">Origen</x-input-label><x-text-input id="origin_name" type="text" wire:model.defer="originName" class="mt-1 w-full" placeholder="Ej. PUERTO GAITAN" /><x-input-error :messages="$errors->get('originName')" class="mt-2" /></div>
                                <div class="mt-4"><x-input-label for="origin_status">Estado</x-input-label><select id="origin_status" wire:model.defer="originIsActive" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"><option value="1">Activo</option><option value="0">Inactivo</option></select></div>
                                <div class="mt-4 flex justify-end"><x-success-button type="submit" wire:loading.attr="disabled" wire:target="saveOrigin">Guardar origen</x-success-button></div>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-8 text-center text-sm text-gray-500">Guarda o selecciona una regional para administrar sus origenes.</div>
                @endif
            </section>
        </div>
    </div>
</div>
