<?php

use App\Models\Service;
use App\Models\Status;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Livewire\Forms\Services\ManageForm;

new #[Layout('layouts.app')] class extends Component {
    public ManageForm $form;

    /** @var \Illuminate\Support\Collection<int, \App\Models\Status> */
    public $statuses;

    public function mount(Service $service): void
    {
        $this->form->mount($service);

        // Traemos varias columnas posibles para evitar "options vacías" por mismatch de nombre
        $this->statuses = Status::query()
            ->select(['id', 'status_name', 'status_be', 'status_description'])
            ->orderByRaw('COALESCE(status_name, status_be, status_description, id) asc')
            ->get();
    }

    public function save(): void
    {
        $this->form->update();
        session()->flash('success', 'Servicio actualizado.');
    }
};
?>

<div class="space-y-6">
    <x-breadcrums :items="[
        ['label' => 'Inicio', 'url' => route('dashboard'), 'icon' => 'home'],
        ['label' => 'Servicios', 'url' => route('services.index'), 'icon' => 'package'],
        ['label' => 'Servicio', 'icon' => 'package-search'],
    ]" />

    @if (session('success'))
        <div class="p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @php
        // Función local para mostrar SIEMPRE algún texto
        $statusLabel = function ($st) {
            return $st->status_name ?? ($st->status_be ?? ($st->status_description ?? 'Estado #' . $st->id));
        };

    @endphp

    <form wire:submit.prevent="save" class="px-6 py-6 space-y-6 border-2 border-gray-200 bg-white shadow-2xl rounded-2xl">
        <div class="flex items-start justify-between gap-6">
            <h1 class="text-4xl font-extrabold">
                Servicio N° {{ $form->consecutive ?? '-' }}
            </h1>

            {{-- ESTADO EN EL HEADER (LO QUE SEÑALASTE) --}}
            <div class="w-full max-w-xs text-right">
                <div class="text-sm text-gray-500 mb-1">Estado</div>

                <select id="status_id_header" wire:model.defer="form.status_id"
                    class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100 disabled:text-gray-500"
                    @disabled(!$form->canEdit)>
                    <option value="">Sin estado</option>

                    @foreach ($statuses as $st)
                        <option value="{{ $st->id }}">
                            {{ $statusLabel($st) }}
                        </option>
                    @endforeach
                </select>
                @error('form.status_id')
                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- FORMULARIO PRINCIPAL (SIN ESTADO ABAJO) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="item">Item</x-input-label>
                <x-text-input id="item" wire:model.defer="form.item" class="mt-1 block w-full" :disabled="!$form->canEdit" />
                @error('form.item')
                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <x-input-label for="consecutive">Consecutivo</x-input-label>
                <x-text-input id="consecutive" wire:model.defer="form.consecutive" class="mt-1 block w-full" disabled />
            </div>
        </div>

        <div>
            <x-input-label for="observation">Observación</x-input-label>
            <x-textare-input id="observation" wire:model.defer="form.observation" rows="4"
                class="mt-1 block w-full" @disabled(!$form->canEdit) />
            @error('form.observation')
                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="flex justify-end">
            @if ($form->canEdit)
                <x-success-button type="submit">Guardar</x-success-button>
            @else
                <span class="text-sm text-gray-500">Solo admin puede editar.</span>
            @endif
        </div>
    </form>

    {{-- Service Parties --}}
    @php
        $collection = $form->service?->service_parties ?? collect();
        $cols = $form->columnsFor($collection);
    @endphp
    <div class="px-6 py-6 space-y-3 border-2 border-gray-200 bg-white shadow rounded-2xl">
        <h2 class="text-xl font-bold">Service Parties</h2>

        @if (!$collection || $collection->isEmpty())
            <div class="text-gray-500 text-sm">Sin registros.</div>
        @else
            <div class="overflow-x-auto border rounded-xl">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr class="text-left">
                            @foreach ($cols as $c)
                                <th class="px-4 py-2">{{ $c }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($collection as $row)
                            <tr class="border-t align-top">
                                @foreach ($cols as $c)
                                    <td class="px-4 py-2 break-words">
                                        {{ $form->displayValue($row, $c, $row->$c) }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Service Contacts --}}
    @php
        $collection = $form->service?->service_contacts ?? collect();
        $cols = $form->columnsFor($collection);
    @endphp
    <div class="px-6 py-6 space-y-3 border-2 border-gray-200 bg-white shadow rounded-2xl">
        <h2 class="text-xl font-bold">Service Contacts</h2>

        @if (!$collection || $collection->isEmpty())
            <div class="text-gray-500 text-sm">Sin registros.</div>
        @else
            <div class="overflow-x-auto border rounded-xl">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr class="text-left">
                            @foreach ($cols as $c)
                                <th class="px-4 py-2">{{ $c }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($collection as $row)
                            <tr class="border-t align-top">
                                @foreach ($cols as $c)
                                    <td class="px-4 py-2 break-words">
                                        {{ $form->displayValue($row, $c, $row->$c) }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Service Dates --}}
    @php
        $collection = $form->service?->service_dates ?? collect();
        $cols = $form->columnsFor($collection);
    @endphp
    <div class="px-6 py-6 space-y-3 border-2 border-gray-200 bg-white shadow rounded-2xl">
        <h2 class="text-xl font-bold">Service Dates</h2>

        @if (!$collection || $collection->isEmpty())
            <div class="text-gray-500 text-sm">Sin registros.</div>
        @else
            <div class="overflow-x-auto border rounded-xl">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr class="text-left">
                            @foreach ($cols as $c)
                                <th class="px-4 py-2">{{ $c }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($collection as $row)
                            <tr class="border-t align-top">
                                @foreach ($cols as $c)
                                    <td class="px-4 py-2 break-words">
                                        {{ $form->displayValue($row, $c, $row->$c) }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Purchase Orders --}}
    @php
        $collection = $form->service?->purchase_orders ?? collect();
        $cols = $form->columnsFor($collection);
    @endphp
    <div class="px-6 py-6 space-y-3 border-2 border-gray-200 bg-white shadow rounded-2xl">
        <h2 class="text-xl font-bold">Purchase Orders</h2>

        @if (!$collection || $collection->isEmpty())
            <div class="text-gray-500 text-sm">Sin registros.</div>
        @else
            <div class="overflow-x-auto border rounded-xl">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr class="text-left">
                            @foreach ($cols as $c)
                                <th class="px-4 py-2">{{ $c }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($collection as $row)
                            <tr class="border-t align-top">
                                @foreach ($cols as $c)
                                    <td class="px-4 py-2 break-words">
                                        {{ $form->displayValue($row, $c, $row->$c) }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- PO Items --}}
    @php
        $collection = $form->items();
        $cols = $form->columnsFor($collection);
    @endphp
    <div class="px-6 py-6 space-y-3 border-2 border-gray-200 bg-white shadow rounded-2xl">
        <h2 class="text-xl font-bold">PO Items</h2>

        @if (!$collection || $collection->isEmpty())
            <div class="text-gray-500 text-sm">Sin registros.</div>
        @else
            <div class="overflow-x-auto border rounded-xl">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr class="text-left">
                            @foreach ($cols as $c)
                                <th class="px-4 py-2">{{ $c }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($collection as $row)
                            <tr class="border-t align-top">
                                @foreach ($cols as $c)
                                    <td class="px-4 py-2 break-words">
                                        {{ $form->displayValue($row, $c, $row->$c) }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
