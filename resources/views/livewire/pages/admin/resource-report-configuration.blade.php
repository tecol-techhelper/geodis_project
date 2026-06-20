<?php

use App\Models\PersonnelRole;
use App\Models\Resource;
use App\Models\ResourcePersonnelRequirement;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    public string $search = '';
    public string $operationFilter = '';
    public ?int $selectedResourceId = null;

    public bool $requiresVehicle = false;
    public bool $requiresPersonnel = false;
    public bool $requiresRemittance = false;
    public bool $requiresContainer = false;

    /** @var array<int, array{enabled: bool, quantity: int}> */
    public array $personnel = [];

    /** @var EloquentCollection<int, PersonnelRole> */
    public $personnelRoles;

    /** @var array<int, string> */
    public array $operations = [];

    public function mount(): void
    {
        $this->personnelRoles = PersonnelRole::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $this->operations = Resource::query()
            ->select('resource_operation')
            ->distinct()
            ->orderBy('resource_operation')
            ->pluck('resource_operation')
            ->all();

        $firstResourceId = Resource::query()
            ->orderBy('resource_operation')
            ->orderBy('resource_name')
            ->value('id');

        if ($firstResourceId !== null) {
            $this->selectResource((int) $firstResourceId);
        }
    }

    public function resources(): EloquentCollection
    {
        return Resource::query()
            ->select(['id', 'resource_id', 'resource_name', 'resource_operation', 'required_report_mask'])
            ->when($this->operationFilter !== '', fn($query) => $query->where('resource_operation', $this->operationFilter))
            ->when($this->search !== '', function ($query) {
                $search = '%' . trim($this->search) . '%';

                $query->where(function ($query) use ($search) {
                    $query->where('resource_id', 'like', $search)
                        ->orWhere('resource_name', 'like', $search);
                });
            })
            ->orderBy('resource_operation')
            ->orderBy('resource_name')
            ->limit(150)
            ->get();
    }

    public function selectedResource(): ?Resource
    {
        if ($this->selectedResourceId === null) {
            return null;
        }

        return Resource::query()
            ->with([
                'personnelRequirements' => fn($query) => $query
                    ->withTrashed()
                    ->with('personnelRole')
                    ->orderBy('sort_order')
                    ->orderBy('id'),
            ])
            ->find($this->selectedResourceId);
    }

    public function selectResource(int $resourceId): void
    {
        $resource = Resource::query()
            ->with(['personnelRequirements' => fn($query) => $query->withTrashed()])
            ->findOrFail($resourceId);

        $this->selectedResourceId = (int) $resource->id;
        $this->requiresVehicle = $resource->requiresVehicle();
        $this->requiresPersonnel = $resource->requiresPersonnel();
        $this->requiresRemittance = $resource->requiresRemittance();
        $this->requiresContainer = $resource->requiresContainer();

        $requirementsByRole = $resource->personnelRequirements->keyBy('personnel_role_id');
        $this->personnel = [];

        foreach ($this->personnelRoles as $role) {
            $requirement = $requirementsByRole->get($role->id);
            $isEnabled = $requirement !== null
                && $requirement->deleted_at === null
                && (bool) $requirement->is_required;

            $this->personnel[(int) $role->id] = [
                'enabled' => $isEnabled,
                'quantity' => max(1, (int) ($requirement?->quantity_required ?? 1)),
            ];
        }

        $this->resetValidation();
    }

    public function save(): void
    {
        $this->validate([
            'selectedResourceId' => ['required', 'integer', 'exists:resources,id'],
            'requiresVehicle' => ['boolean'],
            'requiresPersonnel' => ['boolean'],
            'requiresRemittance' => ['boolean'],
            'requiresContainer' => ['boolean'],
            'personnel.*.enabled' => ['boolean'],
            'personnel.*.quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ], [
            'selectedResourceId.required' => 'Seleccione un recurso para configurar.',
            'selectedResourceId.exists' => 'El recurso seleccionado no existe.',
            'personnel.*.quantity.required' => 'La cantidad es obligatoria.',
            'personnel.*.quantity.integer' => 'La cantidad debe ser un número entero.',
            'personnel.*.quantity.min' => 'La cantidad mínima es 1.',
            'personnel.*.quantity.max' => 'La cantidad máxima es 99.',
        ]);

        if ($this->requiresPersonnel && !$this->hasEnabledPersonnelRole()) {
            throw ValidationException::withMessages([
                'personnel' => 'Seleccione al menos un rol de personal cuando el recurso requiere personal.',
            ]);
        }

        DB::transaction(function () {
            $resource = Resource::query()->findOrFail((int) $this->selectedResourceId);
            $mask = $this->buildRequiredReportMask();

            $resource->update([
                'required_report_mask' => $mask,
            ]);

            $this->syncPersonnelRequirements($resource);
        });

        $this->selectResource((int) $this->selectedResourceId);

        flash()
            ->title('Configuración actualizada')
            ->success('La configuración del recurso se guardó correctamente.');
    }

    public function requirementLabels(Resource $resource): array
    {
        $labels = [];

        if ($resource->requiresVehicle()) {
            $labels[] = 'Vehículo';
        }

        if ($resource->requiresPersonnel()) {
            $labels[] = 'Personal';
        }

        if ($resource->requiresRemittance()) {
            $labels[] = 'Remesa';
        }

        if ($resource->requiresContainer()) {
            $labels[] = 'Contenedor';
        }

        return $labels;
    }

    private function hasEnabledPersonnelRole(): bool
    {
        foreach ($this->personnel as $configuration) {
            if ((bool) ($configuration['enabled'] ?? false)) {
                return true;
            }
        }

        return false;
    }

    private function buildRequiredReportMask(): int
    {
        $mask = 0;

        if ($this->requiresVehicle) {
            $mask |= Resource::REQUIRES_VEHICLE;
        }

        if ($this->requiresPersonnel) {
            $mask |= Resource::REQUIRES_PERSONNEL;
        }

        if ($this->requiresRemittance) {
            $mask |= Resource::REQUIRES_REMITTANCE;
        }

        if ($this->requiresContainer) {
            $mask |= Resource::REQUIRES_CONTAINER;
        }

        return $mask;
    }

    private function syncPersonnelRequirements(Resource $resource): void
    {
        if (!$this->requiresPersonnel) {
            ResourcePersonnelRequirement::query()
                ->where('resource_id', $resource->id)
                ->whereNull('deleted_at')
                ->update([
                    'is_required' => false,
                    'deleted_at' => now(),
                    'updated_at' => now(),
                ]);

            return;
        }

        foreach ($this->personnelRoles as $role) {
            $configuration = $this->personnel[(int) $role->id] ?? [
                'enabled' => false,
                'quantity' => 1,
            ];

            $isEnabled = (bool) ($configuration['enabled'] ?? false);
            $quantity = max(1, min(99, (int) ($configuration['quantity'] ?? 1)));

            $requirement = ResourcePersonnelRequirement::withTrashed()
                ->where('resource_id', $resource->id)
                ->where('personnel_role_id', $role->id)
                ->first();

            if (!$isEnabled) {
                if ($requirement !== null && $requirement->deleted_at === null) {
                    $requirement->forceFill([
                        'is_required' => false,
                    ])->save();
                    $requirement->delete();
                }

                continue;
            }

            if ($requirement === null) {
                $requirement = new ResourcePersonnelRequirement([
                    'resource_id' => $resource->id,
                    'personnel_role_id' => $role->id,
                ]);
            }

            $requirement->forceFill([
                'quantity_required' => $quantity,
                'is_required' => true,
                'sort_order' => (int) $role->sort_order,
                'deleted_at' => null,
            ])->save();
        }
    }
};
?>

@section('title', 'Configuración de Recursos')

<div class="space-y-6">
    <x-breadcrums :items="[
        ['label' => 'Inicio', 'url' => route('dashboard'), 'icon' => 'home'],
        ['label' => 'Configuración de Recursos', 'icon' => 'settings'],
    ]"></x-breadcrums>

    <div class="rounded-lg border-2 bg-white p-6 shadow-lg">
        <div class="flex flex-col gap-2 md:flex-row md:items-start md:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Configuración de recursos reportables</h2>
                <p class="mt-1 text-sm text-gray-600">
                    Define qué información debe solicitar el modal de servicio para cada recurso.
                </p>
            </div>
            <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900 md:max-w-xl">
                Cambiar esta configuración afecta los campos requeridos en nuevos reportes o ediciones futuras.
                No elimina información ya registrada.
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1fr)_minmax(420px,520px)]">
            <section class="min-w-0 rounded-xl border border-gray-200">
                <div class="border-b border-gray-200 bg-gray-50 p-4">
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-[minmax(0,1fr)_220px]">
                        <div>
                            <x-input-label for="resource_search">Buscar recurso</x-input-label>
                            <x-text-input id="resource_search" type="text" wire:model.live.debounce.350ms="search"
                                class="mt-1 w-full" placeholder="Código o nombre del recurso" />
                        </div>
                        <div>
                            <x-input-label for="operation_filter">Operación</x-input-label>
                            <select id="operation_filter" wire:model.live="operationFilter"
                                class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todas</option>
                                @foreach ($operations as $operation)
                                    <option value="{{ $operation }}">{{ $operation }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="max-h-[640px] overflow-y-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="sticky top-0 z-10 bg-white">
                            <tr class="text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                <th class="px-4 py-3">Recurso</th>
                                <th class="px-4 py-3">Operación</th>
                                <th class="px-4 py-3">Requisitos</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($this->resources() as $resource)
                                @php
                                    $labels = $this->requirementLabels($resource);
                                    $isSelected = (int) $selectedResourceId === (int) $resource->id;
                                @endphp
                                <tr wire:key="resource-row-{{ $resource->id }}"
                                    wire:click="selectResource({{ $resource->id }})"
                                    @class([
                                        'cursor-pointer transition hover:bg-blue-50',
                                        'bg-blue-50' => $isSelected,
                                    ])>
                                    <td class="px-4 py-3 align-top">
                                        <div class="font-semibold text-gray-900">{{ $resource->resource_name }}</div>
                                    </td>
                                    <td class="px-4 py-3 align-top text-gray-700">
                                        {{ $resource->resource_operation }}
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        @if ($labels === [])
                                            <span class="text-xs text-gray-500">No aplica</span>
                                        @else
                                            <div class="flex flex-wrap gap-1.5">
                                                @foreach ($labels as $label)
                                                    <span class="rounded-full border border-gray-200 bg-gray-50 px-2 py-0.5 text-xs font-medium text-gray-700">
                                                        {{ $label }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-sm text-gray-500">
                                        No se encontraron recursos con los filtros aplicados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="rounded-xl border border-gray-200 bg-gray-50">
                @if ($selected = $this->selectedResource())
                    <form wire:submit="save" class="space-y-5 p-5">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                {{ $selected->resource_operation }}
                            </p>
                            <h3 class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $selected->resource_name }}
                            </h3>
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-white p-4">
                            <h4 class="font-semibold text-gray-900">Información requerida</h4>
                            <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <label class="flex items-center gap-3 rounded-lg border border-gray-200 px-3 py-2">
                                    <input type="checkbox" wire:model.live="requiresVehicle"
                                        class="rounded border-gray-300 text-blue-700 focus:ring-blue-600">
                                    <span class="text-sm font-medium text-gray-800">Placa del vehículo</span>
                                </label>
                                <label class="flex items-center gap-3 rounded-lg border border-gray-200 px-3 py-2">
                                    <input type="checkbox" wire:model.live="requiresPersonnel"
                                        class="rounded border-gray-300 text-blue-700 focus:ring-blue-600">
                                    <span class="text-sm font-medium text-gray-800">Personal</span>
                                </label>
                                <label class="flex items-center gap-3 rounded-lg border border-gray-200 px-3 py-2">
                                    <input type="checkbox" wire:model.live="requiresRemittance"
                                        class="rounded border-gray-300 text-blue-700 focus:ring-blue-600">
                                    <span class="text-sm font-medium text-gray-800">Remesa de transporte</span>
                                </label>
                                <label class="flex items-center gap-3 rounded-lg border border-gray-200 px-3 py-2">
                                    <input type="checkbox" wire:model.live="requiresContainer"
                                        class="rounded border-gray-300 text-blue-700 focus:ring-blue-600">
                                    <span class="text-sm font-medium text-gray-800">Número de contenedor</span>
                                </label>
                            </div>
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-white p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h4 class="font-semibold text-gray-900">Personal por recurso</h4>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Disponible solo cuando el recurso requiere personal.
                                    </p>
                                </div>
                            </div>

                            @error('personnel')
                                <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <div class="mt-4 space-y-3">
                                @foreach ($personnelRoles as $role)
                                    <div wire:key="personnel-role-{{ $role->id }}"
                                        @class([
                                            'rounded-lg border border-gray-200 p-3',
                                            'bg-gray-50 opacity-70' => !$requiresPersonnel,
                                            'bg-white' => $requiresPersonnel,
                                        ])>
                                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                            <label class="flex items-center gap-3">
                                                <input type="checkbox"
                                                    wire:model.live="personnel.{{ $role->id }}.enabled"
                                                    class="rounded border-gray-300 text-blue-700 focus:ring-blue-600 disabled:cursor-not-allowed disabled:opacity-50"
                                                    @disabled(!$requiresPersonnel)>
                                                <span class="text-sm font-semibold text-gray-800">{{ $role->name }}</span>
                                            </label>
                                            <div class="w-full sm:w-32">
                                                <x-input-label for="role_quantity_{{ $role->id }}">Cantidad</x-input-label>
                                                <x-text-input id="role_quantity_{{ $role->id }}" type="number"
                                                    min="1" max="99"
                                                    wire:model.defer="personnel.{{ $role->id }}.quantity"
                                                    class="mt-1 w-full"
                                                    :disabled="!$requiresPersonnel || !($personnel[$role->id]['enabled'] ?? false)" />
                                                @error('personnel.' . $role->id . '.quantity')
                                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <x-success-button type="submit" wire:loading.attr="disabled" wire:target="save">
                                Guardar configuración
                            </x-success-button>
                        </div>
                    </form>
                @else
                    <div class="p-8 text-center text-sm text-gray-500">
                        Seleccione un recurso para configurar sus requisitos.
                    </div>
                @endif
            </section>
        </div>
    </div>
</div>
