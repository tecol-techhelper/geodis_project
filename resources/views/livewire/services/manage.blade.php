<?php

use App\Models\Service;
use App\Models\Status;
use App\Models\EdifactFile;
use App\Jobs\UploadEdifactToSftpJob;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Services\Edi\GenerateIftstaPayloadService;
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

        $this->statuses = Status::query()
            ->select(['id', 'status_name', 'status_be', 'status_description'])
            ->orderBy('id')
            ->get();
    }

    public function save(): void
    {
        $dirtyPurchaseOrderIds = $this->form->update();

        try {
            $service = Service::query()->findOrFail((int) $this->form->id);

            if (count($dirtyPurchaseOrderIds) > 0) {
                /** @var GenerateIftstaPayloadService $iftsta */
                $iftsta = app(GenerateIftstaPayloadService::class);
                $result = $iftsta->generate($service, $dirtyPurchaseOrderIds);

                $payload = (string) ($result['payload'] ?? '');
                if ($payload !== '') {
                    $meta = (array) ($result['meta'] ?? []);
                    $interchangeRef = (string) ($meta['interchange_ref'] ?? now()->format('YmdHis'));

                    $transmissionId = $this->uniqueOutgoingTransmissionId($interchangeRef);
                    $fileName = $this->buildIftstaFileName($service, $interchangeRef);

                    $edifactFile = EdifactFile::query()->create([
                        'transmission_id' => $transmissionId,
                        'message_type' => EdifactFile::TYPE_IFTSTA,
                        'direction' => EdifactFile::DIRECTION_OUT,
                        'status' => EdifactFile::STATUS_PENDING,
                        'file_name' => $fileName,
                        'purchase_order' => $service->purchase_orders()
                            ->whereIn('id', $dirtyPurchaseOrderIds)
                            ->pluck('purchase_order_number')
                            ->filter()
                            ->join(' '),
                        'service_id' => $service->id,
                    ]);

                    UploadEdifactToSftpJob::dispatchSync(
                        edifactFileId: (int) $edifactFile->id,
                        payload: $payload
                    );

                    Log::info('IFTSTA generado y job de envio despachado', [
                        'service_id' => $service->id,
                        'edifact_file_id' => $edifactFile->id,
                        'transmission_id' => $transmissionId,
                        'purchase_orders' => $dirtyPurchaseOrderIds,
                    ]);
                } else {
                    Log::warning('No se genero payload IFTSTA luego de cambiar estados', [
                        'service_id' => $service->id,
                        'purchase_orders' => $dirtyPurchaseOrderIds,
                    ]);
                }
            } else {
                Log::info('Servicio actualizado sin cambios de estado en purchase_orders', [
                    'service_id' => $service->id,
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Error al generar/despachar IFTSTA desde manage.save', [
                'service_id' => $this->form->id,
                'error' => $e->getMessage(),
            ]);

            flash()->title('Actualizacion parcial')->warning('Servicio actualizado, pero no se pudo enviar IFTSTA.');
            return;
        }

        flash()->title('Servicio actualizado')->success('Servicio actualizado exitosamente.');
    }

    private function uniqueOutgoingTransmissionId(string $seed): string
    {
        $base = 'OUT-' . preg_replace('/[^A-Za-z0-9_-]/', '', $seed);
        $candidate = $base;

        while (EdifactFile::query()->where('transmission_id', $candidate)->exists()) {
            $candidate = $base . '-' . Str::upper(Str::random(4));
        }

        return $candidate;
    }

    private function buildIftstaFileName(Service $service, string $interchangeRef): string
    {
        $timestamp = now()->format('YmdHis');
        return "ECOPETROL_TRANSTECOL_IFTSTA_{$timestamp}.edi";
    }

    public function back(): void
    {
        $this->redirect(route('services.index'));
    }

    // Diccionario para unit_identifier_type
    public function getUnitIdentifierLabel($type): string
    {
        $dictionary = [
            'BJ' => 'Número de serie de envío / Código de contenedor SSCC EAN/GENCOD',
            'BN' => 'Número de serie',
            'BX' => 'Número de lote',
        ];

        return $dictionary[$type] ?? $type;
    }

    public function getContactChannelLabel($channel): string
    {
        $dictionary = [
            'FX' => 'Fax',
            'TE' => 'Telefono',
            'EM' => 'Correo electronico',
            'TL' => 'Telex',
            'MA' => 'Direccion Fisica',
        ];

        $code = strtoupper(trim((string) $channel));

        return $dictionary[$code] ?? ($code !== '' ? $code : 'Canal');
    }
};
?>
@section('title', 'Servicio')
<div class="space-y-6 pb-8" x-data x-on:support-files-saved.window="setTimeout(() => window.location.reload(), 3200)">
    <x-breadcrums :items="[
        ['label' => 'Inicio', 'url' => route('dashboard'), 'icon' => 'home'],
        ['label' => 'Servicios', 'url' => route('services.index'), 'icon' => 'package'],
        ['label' => 'Servicio # ' . ($form->consecutive ?? 'N/A'), 'icon' => 'package-search'],
    ]" />

    @php
        $statusLabel = function ($st) {
            return $st->status_name ?? ($st->status_be ?? ($st->status_description ?? 'Estado #' . $st->id));
        };
    @endphp

    <form wire:submit.prevent="save"
        class="px-4 sm:px-6 py-6 space-y-8 border-2 border-gray-200 bg-white shadow-2xl rounded-2xl">

        {{-- HEADER DEL SERVICIO --}}
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 pb-6 border-b-2 border-gray-200">
            <div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900">
                    Servicio N° {{ $form->consecutive ?? '-' }}
                </h1>
                @if ($form->item)
                    <p class="text-sm text-gray-600 mt-2">Item: <span class="font-semibold">{{ $form->item }}</span>
                    </p>
                @endif
            </div>

        </div>

        {{-- BOTONES DE ACCIÓN --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <x-danger-button type="button" wire:click="back" x-on:click="setTimeout(() => $el.blur(), 100)"
                class="flex-1 sm:flex-none">
                Volver
            </x-danger-button>

            @if ($form->service && $form->canEdit)
                <livewire:services.upload-file-modal :service="$form->service" :key="'upload-files-' . $form->service->id" />
            @endif

            @if ($form->canEdit)
                <x-success-button type="submit" class="flex-1 sm:flex-none">
                    Enviar
                </x-success-button>
            @endif
        </div>

        {{-- INFORMACIÓN PRINCIPAL DEL SERVICIO (SOLO ITEM Y CONSECUTIVO) --}}
        <div>
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Información General
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="item" class="block text-sm font-medium text-gray-700 mb-1">Item</label>
                    <input type="text" id="item" wire:model.defer="form.item"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-50 disabled:text-gray-500"
                        @disabled(!$form->canEdit) />
                    @error('form.item')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="consecutive" class="block text-sm font-medium text-gray-700 mb-1">Consecutivo</label>
                    <input type="text" id="consecutive" wire:model.defer="form.consecutive"
                        class="w-full rounded-lg border-gray-300 shadow-sm bg-gray-50 text-gray-500 cursor-not-allowed"
                        disabled />
                </div>
            </div>
        </div>

        {{-- SERVICE PARTIES --}}
        @if ($form->service?->service_parties?->isNotEmpty())
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Partes del Servicio
                </h2>

                <div class="overflow-x-auto border rounded-xl shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tipo</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nombre</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Dirección</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ciudad</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Código Postal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($form->service->service_parties as $party)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        {{ $party->party_type ? $form->catalogLabel($party->party_type) : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">{{ $party->party_name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $party->party_street ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $party->party_city ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $party->party_region ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- SERVICE CONTACTS --}}
        @if ($form->service?->service_contacts?->isNotEmpty())
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Contactos del Servicio
                </h2>

                <div class="overflow-x-auto border rounded-xl shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tipo</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nombre</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Detalles</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($form->service->service_contacts as $contact)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        {{ $contact->contact_type ? $form->catalogLabel($contact->contact_type) : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">{{ $contact->contact_name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        @if ($contact->service_contact_details?->isNotEmpty())
                                            <div class="space-y-1">
                                                @foreach ($contact->service_contact_details as $detail)
                                                    <div class="text-xs">
                                                        <span
                                                            class="font-medium">{{ $this->getContactChannelLabel($detail->channel_contact) }}:</span>
                                                        {{ $detail->contact_information ?? '-' }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-gray-400">Sin detalles</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- SERVICE DATES --}}
        @if ($form->service?->service_dates?->isNotEmpty())
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Fechas del Servicio
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($form->service->service_dates as $date)
                        <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                            <div class="text-sm font-medium text-gray-700 mb-1">
                                {{ $date->date_type ? $form->catalogLabel($date->date_type) : 'Fecha' }}
                            </div>
                            <div class="text-lg font-semibold text-gray-900">
                                {{ $date->service_date ? \Carbon\Carbon::parse($date->service_date)->format('d/m/Y') : '-' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- PURCHASE ORDERS --}}
        @if ($form->service?->purchase_orders?->isNotEmpty())
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Órdenes de Compra
                </h2>

                @foreach ($form->service->purchase_orders as $index => $po)
                    <div class="mb-6 p-4 border-2 border-indigo-200 rounded-xl bg-indigo-50/30">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">
                            Orden #{{ $index + 1 }} - {{ $po->purchase_order_number ?? 'N/A' }}
                        </h3>

                        {{-- DATOS DE LA ORDEN --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                            <div class="text-sm">
                                <span class="font-medium text-gray-700">Número de orden:</span>
                                <span class="text-gray-900">{{ $po->purchase_order_number ?? '-' }}</span>
                            </div>
                            <div class="text-sm">
                                <span class="font-medium text-gray-700">Secuencia:</span>
                                <span class="text-gray-900">{{ $po->purchase_order_secuence ?? '-' }}</span>
                            </div>
                        </div>


                        <div class="mb-4">
                            <label for="po_status_{{ $po->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                Estado de la Orden
                            </label>
                            <select id="po_status_{{ $po->id }}"
                                wire:model.defer="form.purchase_order_statuses.{{ $po->id }}"
                                class="w-full md:w-96 rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed"
                                @disabled(!$form->canEdit)>
                                @foreach ($statuses as $st)
                                    <option value="{{ $st->id }}">{{ $statusLabel($st) }}</option>
                                @endforeach
                            </select>
                            @error('form.purchase_order_statuses.' . $po->id)
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- PO PARTIES --}}
                        @if ($po->purchase_order_parties?->isNotEmpty())
                            <div class="mt-4">
                                <h4 class="text-md font-semibold text-gray-800 mb-2">Partes de la Orden</h4>
                                <div class="overflow-x-auto border rounded-lg shadow-sm">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Tipo</th>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Ubicación</th>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Dirección</th>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Ciudad</th>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Código Postal</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($po->purchase_order_parties as $party)
                                                <tr class="hover:bg-gray-50 transition-colors">
                                                    <td class="px-3 py-2 text-sm">
                                                        {{ $party->party_type?->party_type_name ?? '-' }}
                                                    </td>
                                                    <td class="px-3 py-2 text-sm">{{ $party->party_name ?? '-' }}</td>
                                                    <td class="px-3 py-2 text-sm">{{ $party->party_street ?? '-' }}
                                                    </td>
                                                    <td class="px-3 py-2 text-sm">{{ $party->party_city ?? '-' }}</td>
                                                    <td class="px-3 py-2 text-sm">{{ $party->party_region ?? '-' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        {{-- PO CONTACTS --}}
                        @if ($po->purchase_order_contacts?->isNotEmpty())
                            <div class="mt-4">
                                <h4 class="text-md font-semibold text-gray-800 mb-2">Contactos de la Orden</h4>
                                <div class="overflow-x-auto border rounded-lg shadow-sm">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Tipo</th>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Nombre</th>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Detalles</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($po->purchase_order_contacts as $contact)
                                                <tr class="hover:bg-gray-50 transition-colors">
                                                    <td class="px-3 py-2 text-sm">
                                                        {{ $contact->contact_type ? $form->catalogLabel($contact->contact_type) : '-' }}
                                                    </td>
                                                    <td class="px-3 py-2 text-sm">{{ $contact->contact_name ?? '-' }}
                                                    </td>
                                                    <td class="px-3 py-2 text-sm">
                                                        @if ($contact->purchase_order_contact_details?->isNotEmpty())
                                                            <div class="space-y-1">
                                                                @foreach ($contact->purchase_order_contact_details as $detail)
                                                                    <div class="text-xs">
                                                                        <span
                                                                            class="font-medium">{{ $this->getContactChannelLabel($detail->channel_contact) }}:</span>
                                                                        {{ $detail->contact_information ?? '-' }}
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <span class="text-gray-400">Sin detalles</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        {{-- PO NOTES (ESPECIFICACIONES) --}}
                        @if ($po->purchase_order_notes?->isNotEmpty())
                            <div class="mt-4">
                                <h4 class="text-md font-semibold text-gray-800 mb-2">Especificaciones de la Orden</h4>
                                <div class="space-y-2">
                                    @foreach ($po->purchase_order_notes as $note)
                                        <div class="p-3 border border-gray-200 rounded-lg bg-white">
                                            <div class="text-sm text-gray-900">{{ $note->note_text ?? '-' }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- PO MEASUREMENTS --}}
                        @if ($po->purchase_order_measurements?->isNotEmpty())
                            <div class="mt-4">
                                <h4 class="text-md font-semibold text-gray-800 mb-2">Mediciones de la Orden</h4>
                                <div class="overflow-x-auto border rounded-lg shadow-sm">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Tipo</th>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Valor/Unidad</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($po->purchase_order_measurements as $measurement)
                                                <tr class="hover:bg-gray-50 transition-colors">
                                                    <td class="px-3 py-2 text-sm">
                                                        {{ $measurement->global_measure_type ? $form->catalogLabel($measurement->global_measure_type) : '-' }}
                                                    </td>
                                                    <td class="px-3 py-2 text-sm">
                                                        {{ $measurement->measure_value ?? ($measurement->measurement_value ?? '-') }}
                                                        {{ $measurement->measure_unit ?? ($measurement->measurement_unit ?? '') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        {{-- PO REQUIREMENTS --}}
                        @if ($po->purchase_order_requirements?->isNotEmpty())
                            <div class="mt-4">
                                <h4 class="text-md font-semibold text-gray-800 mb-2">Requerimientos de la Orden</h4>
                                <div class="space-y-2">
                                    @foreach ($po->purchase_order_requirements as $requirement)
                                        <div class="p-3 border border-gray-200 rounded-lg bg-white">
                                            @if ($requirement->requirement_type)
                                                <div class="text-xs font-medium text-gray-500 mb-1">
                                                    Tipo: {{ $requirement->requirement_type }}
                                                </div>
                                            @endif
                                            <div class="text-sm text-gray-900">
                                                {{ $requirement->requirement_value ?? '-' }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- DELIVERY TERMS --}}
                        @if ($po->delivery_terms?->isNotEmpty())
                            <div class="mt-4">
                                <h4 class="text-md font-semibold text-gray-800 mb-2">Términos de Entrega</h4>
                                <div class="space-y-2">
                                    @foreach ($po->delivery_terms as $term)
                                        <div class="p-3 border border-gray-200 rounded-lg bg-white">
                                            <div class="text-sm">
                                                <span class="font-medium text-gray-700">
                                                    {{ $term->delivery_term_catalog ? $form->catalogLabel($term->delivery_term_catalog) : 'Término' }}:
                                                </span>
                                                <span
                                                    class="text-gray-900">{{ $term->delivery_location ?? '-' }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- TRANSPORT CHARGES --}}
                        @if ($po->transport_charges?->isNotEmpty())
                            <div class="mt-4">
                                <h4 class="text-md font-semibold text-gray-800 mb-2">Cargos de Transporte</h4>
                                <div class="overflow-x-auto border rounded-lg shadow-sm">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Tipo de Cargo</th>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Precio Declarado</th>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Precio Unitario</th>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Base</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($po->transport_charges as $charge)
                                                <tr class="hover:bg-gray-50 transition-colors">
                                                    <td class="px-3 py-2 text-sm">
                                                        {{ $charge->price_qualifier ? $form->catalogLabel($charge->price_qualifier) : '-' }}
                                                    </td>
                                                    <td class="px-3 py-2 text-sm">
                                                        {{ $charge->price_amount !== null ? number_format((float) $charge->price_amount, 2, ',', '.') : '-' }}
                                                        {{ $charge->currency_code ? ' ' . $charge->currency_code : '' }}
                                                    </td>
                                                    <td class="px-3 py-2 text-sm">
                                                        {{ $charge->unit_price_basis !== null ? number_format((float) $charge->unit_price_basis, 2, ',', '.') : '-' }}
                                                    </td>
                                                    <td class="px-3 py-2 text-sm">
                                                        {{ $charge->measure_unit_code ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        {{-- PO ITEMS --}}
                        @if ($po->purchase_order_items?->isNotEmpty())
                            <div class="mt-4">
                                <h4 class="text-md font-semibold text-gray-800 mb-2">Items de la Orden</h4>

                                @foreach ($po->purchase_order_items as $itemIndex => $item)
                                    <div class="mb-4 p-4 border-2 border-purple-200 rounded-lg bg-purple-50/20">
                                        <h5 class="text-sm font-bold text-gray-900 mb-3">
                                            Item #{{ $item->line_item_number ?? $itemIndex + 1 }}
                                        </h5>

                                        {{-- Item Principal Info --}}
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 mb-3">
                                            @if ($item->item_description)
                                                <div class="col-span-2">
                                                    <div class="text-xs text-gray-600">Descripción</div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $item->item_description }}</div>
                                                </div>
                                            @endif
                                            @if ($item->quantity)
                                                <div>
                                                    <div class="text-xs text-gray-600">Cantidad</div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $item->quantity }} {{ $item->quantity_unit ?? '' }}
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($item->gross_weight)
                                                <div>
                                                    <div class="text-xs text-gray-600">Peso Bruto</div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ number_format($item->gross_weight, 2) }}
                                                        {{ $item->gross_weight_unit ?? '' }}
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($item->net_weight)
                                                <div>
                                                    <div class="text-xs text-gray-600">Peso Neto</div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ number_format($item->net_weight, 2) }}
                                                        {{ $item->net_weight_unit ?? '' }}
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($item->volume)
                                                <div>
                                                    <div class="text-xs text-gray-600">Volumen</div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ number_format($item->volume, 2) }}
                                                        {{ $item->volume_unit ?? '' }}
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($item->package_count)
                                                <div>
                                                    <div class="text-xs text-gray-600">Paquetes</div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $item->package_count }}</div>
                                                </div>
                                            @endif
                                            @if ($item->package_type)
                                                <div>
                                                    <div class="text-xs text-gray-600">Tipo Paquete</div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $item->package_type }}</div>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Item Notes (DESCRIPCIÓN) --}}
                                        @if ($item->item_notes?->isNotEmpty())
                                            <div class="mt-3">
                                                <div class="text-xs font-semibold text-gray-700 mb-1">Descripción</div>
                                                <div class="space-y-1">
                                                    @foreach ($item->item_notes as $itemNote)
                                                        <div
                                                            class="p-2 bg-white border border-gray-200 rounded text-xs">
                                                            <span
                                                                class="text-gray-900">{{ $itemNote->note_text ?? '-' }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Item Measures (MEDIDAS) --}}
                                        @if ($item->item_measures?->isNotEmpty())
                                            <div class="mt-3">
                                                <div class="text-xs font-semibold text-gray-700 mb-1">Medidas</div>
                                                <div class="overflow-x-auto border rounded-lg">
                                                    <table class="min-w-full text-xs">
                                                        <thead class="bg-gray-100">
                                                            <tr>
                                                                <th class="px-2 py-1 text-left">Tipo de Medida</th>
                                                                <th class="px-2 py-1 text-left">Valor/Unidad</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="bg-white divide-y divide-gray-200">
                                                            @foreach ($item->item_measures as $measure)
                                                                <tr>
                                                                    <td class="px-2 py-1">
                                                                        {{ $measure->measurement_attribute_code ? $form->catalogLabel($measure->measurement_attribute_code) : '-' }}
                                                                    </td>
                                                                    <td class="px-2 py-1">
                                                                        @php
                                                                            $rawValue =
                                                                                $measure->measurement_value ??
                                                                                $measure->measure_value;
                                                                            $formattedValue =
                                                                                $rawValue !== null
                                                                                    ? rtrim(
                                                                                        rtrim(
                                                                                            number_format(
                                                                                                (float) $rawValue,
                                                                                                6,
                                                                                                ',',
                                                                                                '.',
                                                                                            ),
                                                                                            '0',
                                                                                        ),
                                                                                        ',',
                                                                                    )
                                                                                    : '-';
                                                                        @endphp
                                                                        {{ $formattedValue }}
                                                                        {{ $measure->measure_unit_code ?? ($measure->measurement_unit ?? ($measure->measure_unit ?? '')) }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Item Dimensions (DIMENSIONES) --}}
                                        @if ($item->item_dimensions?->isNotEmpty())
                                            <div class="mt-3">
                                                <div class="text-xs font-semibold text-gray-700 mb-1">Dimensiones</div>
                                                <div class="overflow-x-auto border rounded-lg">
                                                    <table class="min-w-full text-xs">
                                                        <thead class="bg-gray-100">
                                                            <tr>
                                                                <th class="px-2 py-1 text-left">Tipo</th>
                                                                <th class="px-2 py-1 text-left">Largo</th>
                                                                <th class="px-2 py-1 text-left">Ancho</th>
                                                                <th class="px-2 py-1 text-left">Alto</th>
                                                                <th class="px-2 py-1 text-left">Unidad</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="bg-white divide-y divide-gray-200">
                                                            @foreach ($item->item_dimensions as $dimension)
                                                                <tr>
                                                                    <td class="px-2 py-1">
                                                                        {{ $dimension->dimension_type ? $form->catalogLabel($dimension->dimension_type) : '-' }}
                                                                    </td>
                                                                    <td class="px-2 py-1">
                                                                        {{ $dimension->length ?? '-' }}</td>
                                                                    <td class="px-2 py-1">
                                                                        {{ $dimension->width ?? '-' }}</td>
                                                                    <td class="px-2 py-1">
                                                                        {{ $dimension->height ?? '-' }}</td>
                                                                    <td class="px-2 py-1">
                                                                        {{ $dimension->dimension_unit ?? '-' }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Item Container Identifiers --}}
                                        @if ($item->item_container_identifiers?->isNotEmpty())
                                            <div class="mt-3">
                                                <div class="text-xs font-semibold text-gray-700 mb-1">Identificadores
                                                    de Contenedor</div>
                                                <div class="space-y-1">
                                                    @foreach ($item->item_container_identifiers as $container)
                                                        <div
                                                            class="p-2 bg-white border border-gray-200 rounded text-xs">
                                                            <span class="font-medium text-gray-600">
                                                                {{ $container->identifier_type ? $form->catalogLabel($container->identifier_type) : 'Tipo' }}:
                                                            </span>
                                                            <span
                                                                class="text-gray-900">{{ $container->package_identifier_value ?? '-' }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Item Product Identifiers --}}
                                        @if ($item->item_product_identifiers?->isNotEmpty())
                                            <div class="mt-3">
                                                <div class="text-xs font-semibold text-gray-700 mb-1">Identificadores
                                                    de Producto</div>
                                                <div class="overflow-x-auto border rounded-lg">
                                                    <table class="min-w-full text-xs">
                                                        <thead class="bg-gray-100">
                                                            <tr>
                                                                <th class="px-2 py-1 text-left">Rol</th>
                                                                <th class="px-2 py-1 text-left">Tipo</th>
                                                                <th class="px-2 py-1 text-left">Identificador</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="bg-white divide-y divide-gray-200">
                                                            @foreach ($item->item_product_identifiers as $productId)
                                                                <tr>
                                                                    <td class="px-2 py-1">
                                                                        {{ $productId->product_identifier_role ? $form->catalogLabel($productId->product_identifier_role) : '-' }}
                                                                    </td>
                                                                    <td class="px-2 py-1">
                                                                        {{ $productId->product_identifier_type ? $form->catalogLabel($productId->product_identifier_type) : '-' }}
                                                                    </td>
                                                                    <td class="px-2 py-1">
                                                                        {{ $productId->identifier_value ?? ($productId->product_identifier_value ?? '-') }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Item Unit Identifiers --}}
                                        @if ($item->item_unit_identifiers?->isNotEmpty())
                                            <div class="mt-3">
                                                <div class="text-xs font-semibold text-gray-700 mb-1">Identificadores
                                                    de Unidad</div>
                                                <div class="space-y-1">
                                                    @foreach ($item->item_unit_identifiers as $unitId)
                                                        <div
                                                            class="p-2 bg-white border border-gray-200 rounded text-xs">
                                                            <span class="font-medium text-gray-600">
                                                                {{ $this->getUnitIdentifierLabel($unitId->unit_identifier_type ?? '') }}:
                                                            </span>
                                                            <span
                                                                class="text-gray-900">{{ $unitId->unit_identifier_value ?? '-' }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        {{-- LOCATION DETAILS --}}
        @if ($form->service?->location_details?->isNotEmpty())
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Detalles de Ubicación
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($form->service->location_details as $location)
                        <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                            <div class="text-sm font-medium text-gray-700 mb-2">
                                {{ $location->location_code ? $form->catalogLabel($location->location_code) : 'Ubicación' }}
                            </div>
                            <div class="text-base font-semibold text-gray-900">
                                {{ $location->location_details ?? '-' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- TRANSPORT DETAILS --}}
        @if ($form->service?->transport_details?->isNotEmpty())
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Detalles de Transporte
                </h2>

                <div class="overflow-x-auto border rounded-xl shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Etapa</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Modo</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Detalles del Vehículo</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($form->service->transport_details as $transport)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 text-sm">
                                        {{ $transport->transport_stage ? $form->catalogLabel($transport->transport_stage) : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ $transport->transport_mode ? $form->catalogLabel($transport->transport_mode) : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ $transport->vehicle_details ?? ($transport->vehicule_details ?? '-') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- EQUIPMENTS --}}
        @if ($form->service?->service_equipments?->isNotEmpty())
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Equipos
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($form->service->service_equipments as $equipment)
                        <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                            <div class="text-sm font-medium text-gray-700 mb-1">
                                {{ $equipment->equipment_type ? $form->catalogLabel($equipment->equipment_type) : 'Equipo' }}
                            </div>
                            <div class="text-base font-semibold text-gray-900">
                                {{ $equipment->equipment_identification ?? '-' }}
                            </div>
                            @if ($equipment->equipment_size_type)
                                <div class="text-xs text-gray-600 mt-1">Tamaño: {{ $equipment->equipment_size_type }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ARCHIVOS CARGADOS DEL SERVICIO --}}
        @if ($form->service?->support_files?->isNotEmpty())
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Soportes Cargados
                </h2>

                <div class="overflow-x-auto border rounded-xl shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nombre del Archivo
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tipo
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($form->service->support_files as $file)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 text-sm">
                                        @if ($file->file_url)
                                            <a href="{{ $file->file_url }}" target="_blank"
                                                rel="noopener noreferrer" class="text-blue-600 hover:underline">
                                                {{ $file->file_name ?? '-' }}
                                            </a>
                                        @else
                                            {{ $file->file_name ?? '-' }}
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm">{{ $file->file_type?->file_type_full_name ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </form>
</div>
