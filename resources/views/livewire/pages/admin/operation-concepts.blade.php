<?php

use App\Models\OperationConcept;
use App\Models\ResourceOperation;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    public ?int $selectedOperationId = null;
    public ?int $conceptId = null;
    public string $conceptName = '';

    public function mount(): void
    {
        $this->ensureAdministrator();

        $firstOperationId = ResourceOperation::query()->orderBy('name')->value('id');

        if ($firstOperationId !== null) {
            $this->selectOperation((int) $firstOperationId);
        }
    }

    /** @return EloquentCollection<int, ResourceOperation> */
    public function operations(): EloquentCollection
    {
        return ResourceOperation::query()
            ->withCount('concepts')
            ->orderBy('name')
            ->get();
    }

    /** @return EloquentCollection<int, OperationConcept> */
    public function concepts(): EloquentCollection
    {
        if ($this->selectedOperationId === null) {
            return new EloquentCollection();
        }

        return OperationConcept::query()
            ->where('resource_operation_id', $this->selectedOperationId)
            ->orderBy('name')
            ->get();
    }

    public function selectOperation(int $operationId): void
    {
        $this->ensureAdministrator();

        $this->selectedOperationId = (int) ResourceOperation::query()->findOrFail($operationId)->id;
        $this->resetConceptForm();
        $this->resetValidation();
    }

    public function updatedSelectedOperationId(): void
    {
        $this->resetConceptForm();
        $this->resetValidation();
    }

    public function newConcept(): void
    {
        $this->ensureAdministrator();
        abort_unless($this->selectedOperationId !== null, 422, 'Seleccione una operación antes de agregar un concepto.');

        $this->resetConceptForm();
        $this->resetValidation();
    }

    public function editConcept(int $conceptId): void
    {
        $this->ensureAdministrator();

        $concept = OperationConcept::query()
            ->where('resource_operation_id', $this->selectedOperationId)
            ->findOrFail($conceptId);

        $this->conceptId = (int) $concept->id;
        $this->conceptName = $concept->name;
        $this->resetValidation();
    }

    public function saveConcept(): void
    {
        $this->ensureAdministrator();

        $this->validate([
            'selectedOperationId' => ['required', 'integer', 'exists:resource_operations,id'],
            'conceptName' => ['required', 'string', 'max:191'],
        ], [
            'selectedOperationId.required' => 'Seleccione una operación antes de guardar el concepto.',
            'selectedOperationId.exists' => 'La operación seleccionada no existe.',
            'conceptName.required' => 'El nombre del concepto es obligatorio.',
            'conceptName.max' => 'El concepto no puede superar 191 caracteres.',
        ]);

        $name = preg_replace('/\s+/u', ' ', trim($this->conceptName));

        if ($name === null || $name === '') {
            throw ValidationException::withMessages(['conceptName' => 'El nombre del concepto es obligatorio.']);
        }

        $duplicate = OperationConcept::query()
            ->where('resource_operation_id', $this->selectedOperationId)
            ->whereRaw('LOWER(name) = ?', [Str::lower($name)])
            ->when($this->conceptId !== null, fn ($query) => $query->whereKeyNot($this->conceptId))
            ->exists();

        if ($duplicate) {
            throw ValidationException::withMessages([
                'conceptName' => 'Ya existe un concepto con ese nombre para la operación seleccionada.',
            ]);
        }

        ResourceOperation::query()->findOrFail($this->selectedOperationId);

        if ($this->conceptId === null) {
            OperationConcept::query()->create([
                'resource_operation_id' => $this->selectedOperationId,
                'name' => $name,
            ]);
        } else {
            $concept = OperationConcept::query()
                ->where('resource_operation_id', $this->selectedOperationId)
                ->findOrFail($this->conceptId);

            $concept->update(['name' => $name]);
        }

        $this->resetConceptForm();
        flash()->title('Concepto guardado')->success('El concepto se guardó correctamente.');
    }

    private function resetConceptForm(): void
    {
        $this->conceptId = null;
        $this->conceptName = '';
    }

    private function ensureAdministrator(): void
    {
        abort_unless(auth()->user()?->hasRole('admin'), 403, 'No tienes permisos para administrar conceptos.');
    }
};
?>

@section('title', 'Conceptos')

<div class="space-y-6">
    <x-breadcrums :items="[
        ['label' => 'Inicio', 'url' => route('dashboard'), 'icon' => 'home'],
        ['label' => 'Configuración de servicios', 'url' => route('services.configuration'), 'icon' => 'settings'],
        ['label' => 'Conceptos', 'icon' => 'list-tree'],
    ]" />

    <section class="rounded-lg border-2 bg-white p-6 shadow-lg">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Conceptos por operación</h1>
                <p class="mt-1 text-sm text-gray-600">Agregue o edite los conceptos disponibles para cada tipo de operación.</p>
            </div>
            <button type="button" wire:click="newConcept"
                class="rounded-md border border-blue-700 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-blue-700 transition hover:bg-blue-700 hover:text-white">
                Nuevo concepto
            </button>
        </div>

        <div class="mt-6 grid grid-cols-1 items-start gap-6 xl:grid-cols-[minmax(0,0.8fr)_minmax(0,1.2fr)_minmax(280px,0.7fr)]">
            <section class="overflow-hidden rounded-xl border border-gray-200">
                <div class="border-b border-gray-200 bg-gray-50 px-4 py-3">
                    <h2 class="font-semibold text-gray-900">Operaciones</h2>
                </div>
                <div>
                    @forelse ($this->operations() as $operation)
                        <button type="button" wire:click="selectOperation({{ $operation->id }})"
                            @class([
                                'flex w-full items-center justify-between gap-3 border-b border-gray-100 px-4 py-3 text-left text-sm transition last:border-b-0',
                                'bg-blue-50 text-blue-900' => $selectedOperationId === $operation->id,
                                'hover:bg-gray-50' => $selectedOperationId !== $operation->id,
                            ])>
                            <span class="font-medium">{{ $operation->name }}</span>
                            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-600">{{ $operation->concepts_count }}</span>
                        </button>
                    @empty
                        <p class="px-4 py-6 text-center text-sm text-gray-500">No hay operaciones registradas.</p>
                    @endforelse
                </div>
            </section>

            <section class="overflow-hidden rounded-xl border border-gray-200">
                <div class="border-b border-gray-200 bg-gray-50 px-4 py-3">
                    <h2 class="font-semibold text-gray-900">Conceptos asociados</h2>
                </div>
                <div>
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="sticky top-0 bg-white text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <tr><th class="px-4 py-3">Concepto</th><th class="px-4 py-3"></th></tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($this->concepts() as $concept)
                                <tr @class(['bg-blue-50' => $conceptId === $concept->id])>
                                    <td class="px-4 py-3 font-medium text-gray-800">{{ $concept->name }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <button type="button" wire:click="editConcept({{ $concept->id }})" class="text-xs font-medium text-blue-700 hover:underline">Editar</button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="px-4 py-8 text-center text-sm text-gray-500">Seleccione una operación con conceptos registrados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="rounded-xl border border-gray-200 bg-gray-50 p-5">
                <h2 class="font-semibold text-gray-900">Adición o Edición de Concepto</h2>
                <form wire:submit="saveConcept" class="mt-5 space-y-4">
                    <div>
                        <x-input-label for="concept_name" value="Concepto" />
                        <x-text-input id="concept_name" type="text" wire:model="conceptName" class="mt-1 w-full" maxlength="191" autofocus />
                        @error('conceptName')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="w-full rounded-md bg-emerald-700 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-emerald-800">
                        Guardar concepto
                    </button>
                </form>
            </section>
        </div>
    </section>
</div>
