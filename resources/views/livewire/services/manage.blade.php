<?php

use App\Models\Service;
use App\Models\Status;
use App\Models\EdifactFile;
use App\Models\Resource;
use App\Models\StatusPurpose;
use App\Jobs\UploadEdifactToSftpJob;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Services\Edi\GenerateIftstaPayloadService;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Livewire\Forms\Services\ManageForm;

new #[Layout('layouts.app')] class extends Component {
    public ManageForm $form;
    public ?string $status_reported_at = null;

    /** @var \Illuminate\Support\Collection<int, \App\Models\Status> */
    public $statuses;
    /** @var \Illuminate\Support\Collection<int, \App\Models\Resource> */
    public $resources;
    /** @var array<string,int> */
    public array $statusPurposeMap = [];
    /** @var array<string,array{name:string,subcode:string}> */
    public array $statusPurposeLookup = [];

    public function mount(Service $service): void
    {
        $this->form->mount($service);
        $this->status_reported_at = null;

        $this->statuses = Status::query()
            ->select(['id', 'status_name', 'status_be', 'status_description', 'status_purpose_id', 'edifact_code'])
            ->orderBy('id')
            ->get();

        $purposes = StatusPurpose::query()
            ->select(['id', 'purpose_subcode', 'purpose_name'])
            ->get();

        $this->statusPurposeMap = $purposes->mapWithKeys(fn($p) => [strtoupper(trim((string) $p->purpose_subcode)) => (int) $p->id])->all();

        $this->statusPurposeLookup = $purposes
            ->mapWithKeys(
                fn($p) => [
                    strtoupper(trim((string) $p->purpose_subcode)) => [
                        'name' => $p->purpose_name,
                        'subcode' => $p->purpose_subcode,
                    ],
                ],
            )
            ->all();

        // Alias de negocio: algunos IFCSUM traen ROAD como ACD para Transporte entre Bodegas.
        if (isset($this->statusPurposeMap['DELIVERY-SO'])) {
            $this->statusPurposeMap['ROAD'] = $this->statusPurposeMap['DELIVERY-SO'];
        }
        if (isset($this->statusPurposeLookup['DELIVERY-SO'])) {
            $this->statusPurposeLookup['ROAD'] = $this->statusPurposeLookup['DELIVERY-SO'];
        }

        $this->resources = Resource::query()
            ->select(['id', 'resource_id', 'resource_name', 'resource_operation'])
            ->orderBy('resource_operation')
            ->orderBy('resource_name')
            ->get();
    }

    public function save(): void
    {
        $dirtyPurchaseOrderIds = $this->form->update();

        try {
            $service = Service::query()
                ->with('status:id,status_name,edifact_code')
                ->findOrFail((int) $this->form->id);

            if (count($dirtyPurchaseOrderIds) > 0) {
                $this->validate([
                    'status_reported_at' => ['required', 'date'],
                ]);

                /** @var GenerateIftstaPayloadService $iftsta */
                $iftsta = app(GenerateIftstaPayloadService::class);

                $resources = $service->resources ?? collect();
                $hasResources = $resources->isNotEmpty();
                $unreportedResources = $resources
                    ->filter(function ($res) {
                        return $res->pivot?->last_reported_at === null;
                    })
                    ->values();

                if ($hasResources && $unreportedResources->isEmpty()) {
                    Log::info('No hay recursos nuevos por reportar en IFTSTA', [
                        'service_id' => $service->id,
                        'purchase_orders' => $dirtyPurchaseOrderIds,
                    ]);
                    $resourceIdList = [];
                } elseif ($hasResources) {
                    $resourceIdList = $unreportedResources->all();
                } else {
                    $resourceIdList = [null];
                }

                $statusReportedAt = $this->status_reported_at ? \Carbon\Carbon::parse($this->status_reported_at) : null;
                $reportedStatusName = $service->status?->status_name;

                foreach ($resourceIdList as $resourceItem) {
                    $resourceId = is_string($resourceItem) ? trim($resourceItem) : (is_object($resourceItem) ? trim((string) ($resourceItem->resource_id ?? '')) : null);

                    $result = $iftsta->generate($service, $dirtyPurchaseOrderIds, $resourceId, $statusReportedAt);

                    $payload = (string) ($result['payload'] ?? '');
                    if ($payload === '') {
                        Log::warning('No se genero payload IFTSTA luego de cambiar estados', [
                            'service_id' => $service->id,
                            'purchase_orders' => $dirtyPurchaseOrderIds,
                            'resource_id' => $resourceId,
                        ]);
                        continue;
                    }

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
                        'purchase_order' => $service->purchase_orders()->whereIn('id', $dirtyPurchaseOrderIds)->pluck('purchase_order_number')->filter()->join(' '),
                        'service_id' => $service->id,
                    ]);

                    UploadEdifactToSftpJob::dispatchSync(edifactFileId: (int) $edifactFile->id, payload: $payload);

                    Log::info('IFTSTA generado y job de envio despachado', [
                        'service_id' => $service->id,
                        'edifact_file_id' => $edifactFile->id,
                        'transmission_id' => $transmissionId,
                        'purchase_orders' => $dirtyPurchaseOrderIds,
                        'resource_id' => $resourceId,
                    ]);

                    if ($resourceItem && is_object($resourceItem) && $resourceItem->pivot?->id) {
                        $reportedAt = $statusReportedAt ?? now();
                        \Illuminate\Support\Facades\DB::table('service_resource')
                            ->where('id', (int) $resourceItem->pivot->id)
                            ->update([
                                'last_reported_at' => $reportedAt,
                                'status_name' => $reportedStatusName,
                                'updated_at' => now(),
                            ]);
                    }
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

        $this->form->mount($service);
        $this->status_reported_at = null;

        flash()->title('Servicio actualizado')->success('Servicio actualizado exitosamente.');
        // Resetear el dropdown del recurso luego de enviar
        $this->form->service_resource_id = null;
        $this->dispatch('service-resource-reset');
    }

    public function addServiceResource(): void
    {
        $this->form->addServiceResource();
    }

    public function addResourceById(int $id): void
    {
        $this->form->addResourceById($id);
    }

    public function removeServiceResource(int $resourceIndex): void
    {
        $this->form->removeServiceResource($resourceIndex);
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
        $consecutive = $service->consecutive ?? 'NA';
        return "ECOPETROL_TRANSTECOL_IFTSTA_{$timestamp}_{$consecutive}.edi";
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
    <div wire:ignore>
        <x-breadcrums :items="[
            ['label' => 'Inicio', 'url' => route('dashboard'), 'icon' => 'home'],
            ['label' => 'Servicios', 'url' => route('services.index'), 'icon' => 'package'],
            ['label' => 'Servicio # ' . ($form->consecutive ?? 'N/A'), 'icon' => 'package-search'],
        ]" />
    </div>

    @php
        $statusLabel = function ($st) {
            return $st->status_name ?? ($st->status_be ?? ($st->status_description ?? 'Estado #' . $st->id));
        };
        $canUploadSupports = function () {
            $user = auth()->user();
            if (!$user) {
                return false;
            }

            $username = strtolower(trim((string) ($user->username ?? '')));
            if ($username === 'administrador') {
                return true;
            }

            return method_exists($user, 'hasRole') && $user->hasRole('admin');
        };
        $isBlank = function ($value) {
            if ($value === null) {
                return true;
            }
            $text = trim((string) $value);
            if ($text === '') {
                return true;
            }
            $upper = strtoupper($text);
            return in_array($upper, ['-', 'UNKNOWN', 'UNKNOW', 'N/A', 'NA', 'NULL'], true);
        };
        $hasData = function ($obj, array $fields) use ($isBlank) {
            foreach ($fields as $field) {
                $value = is_callable($field) ? $field($obj) : data_get($obj, $field);
                if (!$isBlank($value)) {
                    return true;
                }
            }
            return false;
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
            @php
                $currentStatusId = $form->service_status_id !== null ? (int) $form->service_status_id : null;
                $bulkPurposeIds = [];
                foreach ($form->service?->purchase_orders ?? [] as $po) {
                    $acds = $po->order_references?->filter(function ($ref) {
                        $code = strtoupper(trim((string) ($ref->reference_type?->reference_type_code ?? '')));
                        return $code === 'ACD';
                    });
                    $acdValue = strtoupper(trim((string) ($acds?->first()->order_reference_value ?? '')));
                    $acdValue = str_replace([' ', '_'], '-', $acdValue);

                    if ($acdValue !== '') {
                        $subId = $statusPurposeMap[$acdValue] ?? null;
                        if ($subId) {
                            $bulkPurposeIds[] = $subId;
                        }
                    }
                }

                $bulkPurposeIds = array_values(array_unique($bulkPurposeIds));
                $bulkStatuses = $bulkPurposeIds
                    ? $statuses->filter(
                        fn($st) => $st->status_be === 'ASIG' || in_array($st->status_purpose_id, $bulkPurposeIds, true),
                    )
                    : $statuses->filter(fn($st) => $st->status_be === 'ASIG');

                if ($currentStatusId !== null) {
                    $currentStatus = $statuses->firstWhere('id', $currentStatusId);
                    if ($currentStatus) {
                        $bulkStatuses = $bulkStatuses->concat([$currentStatus])->unique('id');
                    }
                }

                $bulkStatuses = $bulkStatuses
                    ->sortBy(function ($st) {
                        $code = is_numeric($st->edifact_code ?? null) ? (int) $st->edifact_code : PHP_INT_MAX;
                        $zeroFirst = $code === 0 ? 0 : 1;

                        return [$zeroFirst, $code, (int) $st->id];
                    })
                    ->values();
            @endphp

            <div class="w-full lg:w-96" wire:ignore>
                <label for="bulk_status" class="block text-sm font-medium text-gray-700 mb-1">
                    Estado de la Orden
                </label>
                <select id="bulk_status" wire:model.defer="form.service_status_id"
                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed whitespace-normal js-status-select"
                    data-placeholder="" data-current-value="{{ $form->service_status_id ?? '' }}"
                    data-livewire-model="form.service_status_id" style="white-space: normal;"
                    @disabled(!$form->canEdit)>
                    @foreach ($bulkStatuses as $st)
                        @php
                            $stateCode = is_numeric($st->edifact_code ?? null) ? (int) $st->edifact_code : null;
                            $stateCodeLabel = ($stateCode !== null && $stateCode !== 0) ? " ({$stateCode})" : '';
                        @endphp
                        <option value="{{ $st->id }}" style="white-space: normal;" @selected((int) $st->id === (int) ($form->service_status_id ?? 0))>
                            {{ $stateCodeLabel . ' ' . $statusLabel($st) . ' - ' . $st->status_description }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- BOTONES DE ACCIÓN --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <x-danger-button type="button" wire:click="back" x-on:click="setTimeout(() => $el.blur(), 100)"
                class="w-full sm:w-auto">
                Volver
            </x-danger-button>

            @if ($form->service)
                <div class="w-full sm:w-auto">
                    @if ($canUploadSupports())
                        <livewire:services.upload-file-modal :service="$form->service" :key="'upload-files-' . $form->service->id" />
                    @else
                        <x-primary-button type="button" disabled
                            class="w-full sm:w-auto opacity-60 cursor-not-allowed">
                            Cargar Soportes
                        </x-primary-button>
                    @endif
                </div>
            @endif

            @if ($form->canEdit)
                <x-success-button type="submit" class="w-full sm:w-auto">
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

            @php
                $agwPriority = null;
                $agwPriorityLabel = null;
                $agwConsolidatedNumber = null;
                $agwPriorityClass = 'text-gray-600';

                foreach ($form->service?->purchase_orders ?? [] as $_po) {
                    $agwRef = $_po->order_references?->first(function ($_ref) {
                        return strtoupper(trim((string) ($_ref->reference_type?->reference_type_code ?? ''))) ===
                            'AGW';
                    });

                    if (!$agwRef) {
                        continue;
                    }

                    $agwValue = trim((string) ($agwRef->order_reference_value ?? ''));
                    if ($agwValue === '') {
                        continue;
                    }

                    $agwParts = array_map('trim', explode('/', $agwValue, 2));
                    $agwPriority = $agwParts[0] !== '' ? $agwParts[0] : null;
                    $agwConsolidatedNumber = ($agwParts[1] ?? '') !== '' ? $agwParts[1] : null;

                    $priorityMap = [
                        'CRITICAL' => 'Crítico',
                        'STANDARD' => 'Estándar',
                    ];
                    $agwPriorityLabel = $agwPriority !== null
                        ? ($priorityMap[strtoupper($agwPriority)] ?? $agwPriority)
                        : null;

                    $agwPriorityClass = match (strtoupper((string) $agwPriority)) {
                        'CRITICAL' => 'text-red-600',
                        'STANDARD' => 'text-blue-600',
                        default => 'text-gray-600',
                    };
                    break;
                }
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="item" class="block text-sm font-medium text-gray-700 mb-1">Item</label>
                    <div
                        class="relative flex h-12 items-stretch rounded-lg border border-gray-300 shadow-sm focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500">
                        <span
                            class="flex items-center px-4 bg-gray-100 text-gray-900 font-semibold text-sm rounded-l-lg">
                            {{ $form->service?->created_at?->format('m') ?? now()->format('m') }}-
                        </span>
                        <input type="text" id="item" wire:model.defer="form.item"
                            class="w-full h-full rounded-r-lg rounded-l-none border-0 bg-transparent focus:ring-0 disabled:bg-gray-50 disabled:text-gray-500 px-4"
                            @disabled(!$form->canEdit) />
                    </div>
                    @error('form.item')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror

                    <div class="mt-3">
                        <label for="service_priority" class="block text-sm font-medium text-gray-700 mb-1">
                            Prioridad
                        </label>
                        <div id="service_priority"
                            class="w-full h-12 px-4 rounded-lg border border-gray-300 shadow-sm bg-gray-50 flex items-center font-bold {{ $agwPriorityClass }}">
                            {{ $agwPriorityLabel ?? '-' }}
                        </div>
                    </div>
                </div>

                <div>
                    <label for="consecutive" class="block text-sm font-medium text-gray-700 mb-1">Consecutivo</label>
                    <input type="text" id="consecutive" wire:model.defer="form.consecutive"
                        class="w-full h-12 rounded-lg border-gray-300 shadow-sm bg-gray-50 text-gray-500 cursor-not-allowed"
                        disabled />

                    <div class="mt-3">
                        <label for="consolidated_number" class="block text-sm font-medium text-gray-700 mb-1">
                            N&uacute;mero de Consolidado
                        </label>
                        <input type="text" id="consolidated_number"
                            value="{{ $agwConsolidatedNumber ?? '-' }}"
                            class="w-full h-12 rounded-lg border-gray-300 shadow-sm bg-gray-50 text-gray-600 cursor-not-allowed"
                            disabled />
                    </div>
                </div>

                @php
                    // Resolver el Tipo de Servicio a partir del RFF+ACD de la primera purchase order
                    $serviceTypePurpose = null;
                    foreach ($form->service?->purchase_orders ?? [] as $_po) {
                        $acdRef = $_po->order_references?->first(function ($_ref) {
                            return strtoupper(trim((string) ($_ref->reference_type?->reference_type_code ?? ''))) ===
                                'ACD';
                        });
                        if ($acdRef) {
                            $acdKey = strtoupper(trim((string) ($acdRef->order_reference_value ?? '')));
                            $acdKey = str_replace([' ', '_'], '-', $acdKey);
                            if ($acdKey !== '' && isset($statusPurposeLookup[$acdKey])) {
                                $serviceTypePurpose = $statusPurposeLookup[$acdKey];
                            }
                            break;
                        }
                    }
                @endphp

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Servicio</label>
                    <div
                        class="w-full h-12 flex items-center px-4 rounded-lg border border-gray-300 shadow-sm bg-gray-50 text-gray-700 text-sm">
                        @if ($serviceTypePurpose)
                            <span class="font-semibold text-indigo-700">{{ $serviceTypePurpose['name'] }}</span>
                            <span class="ml-1 text-gray-500">({{ $serviceTypePurpose['subcode'] }})</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </div>
                </div>

                <div class="md:col-span-3 mt-2">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7h18M5 7v10a2 2 0 002 2h10a2 2 0 002-2V7" />
                        </svg>
                        Recurso(s) Utilizado(s)
                    </h3>
                </div>

                @php
                    $resourceGroups = $resources?->groupBy('resource_operation') ?? collect();
                    $resourceLookup = $resources?->keyBy('id') ?? collect();
                    $serviceResourcePivotLookup = collect($form->service?->resources ?? [])
                        ->filter(fn($resource) => (int) data_get($resource, 'pivot.id', 0) > 0)
                        ->keyBy(fn($resource) => (int) data_get($resource, 'pivot.id'));

                    $formatReportedAt = function ($value): string {
                        if ($value === null || trim((string) $value) === '') {
                            return '-';
                        }

                        try {
                            return \Illuminate\Support\Carbon::parse($value)->format('d/m/Y H:i');
                        } catch (\Throwable $e) {
                            return (string) $value;
                        }
                    };

                    $selectedResources = collect($form->service_resource_rows ?? [])
                        ->values()
                        ->map(function ($row, $index) use ($resourceLookup, $serviceResourcePivotLookup, $formatReportedAt) {
                            $resource = $resourceLookup->get((int) data_get($row, 'resource_id'));
                            $pivotId = (int) data_get($row, 'pivot_id', 0);
                            $pivotResource = $pivotId > 0 ? $serviceResourcePivotLookup->get($pivotId) : null;
                            $lastReportedAt = $pivotResource?->pivot?->last_reported_at;
                            $statusName = $pivotResource?->pivot?->status_name;

                            if (!$resource) {
                                return null;
                            }

                            return [
                                'index' => $index,
                                'resource' => $resource,
                                'last_reported_at' => $formatReportedAt($lastReportedAt),
                                'status_name' => (is_string($statusName) && trim($statusName) !== '') ? trim($statusName) : '-',
                            ];
                        })
                        ->filter()
                        ->values();
                @endphp
                <div class="md:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="service_resource" class="block text-sm font-medium text-gray-700 mb-1">
                            Recurso(s)
                        </label>
                        <div class="flex items-stretch gap-2">
                            <div class="flex-1 min-w-0" wire:ignore>
                                <select id="service_resource" wire:model="form.service_resource_id"
                                    class="w-full h-12 rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed whitespace-normal js-status-select"
                                    data-placeholder="Seleccione un recurso"
                                    data-current-value=""
                                    data-livewire-model="form.service_resource_id"
                                    style="white-space: normal;"
                                    @disabled(!$form->canEdit)>
                                    <option value="">Seleccione un recurso</option>
                                    @foreach ($resourceGroups as $operation => $items)
                                        <optgroup label="{{ $operation }}">
                                            @foreach ($items as $res)
                                                <option value="{{ $res->id }}" style="white-space: normal;">
                                                    {{ $res->resource_name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                            <x-primary-button type="button"
                                x-on:click="
                                    const sel = document.getElementById('service_resource');
                                    const id = sel && sel.tomselect
                                        ? parseInt(sel.tomselect.getValue())
                                        : parseInt(sel ? sel.value : 0);
                                    if (!id || id <= 0) return;
                                    $wire.call('addResourceById', id).then(() => {
                                        if (sel && sel.tomselect) {
                                            sel.tomselect.clear(true);
                                            sel.dataset.currentValue = '';
                                        } else if (sel) {
                                            sel.value = '';
                                        }
                                    });
                                "
                                class="flex items-center justify-center gap-2 w-12 h-12 shrink-0 focus:outline-none focus:ring-0">
                                <span class="text-lg leading-none">+</span>
                            </x-primary-button>
                        </div>
                    </div>

                    <div>
                        <label for="status_reported_at" class="block text-sm font-medium text-gray-700 mb-1">
                            Fecha de Reporte
                        </label>
                        <input type="datetime-local" id="status_reported_at" wire:model.defer="status_reported_at"
                            class="w-full h-12 rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required @disabled(!$form->canEdit) />
                    </div>

                    @error('form.service_resource_id')
                        <p class="text-sm text-red-600 mt-1 md:col-span-2">{{ $message }}</p>
                    @enderror
                </div>

                @if ($selectedResources->isNotEmpty())
                    <div class="md:col-span-3 mt-1 overflow-x-auto border border-gray-200 rounded-lg bg-white">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Recurso
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha Reporte
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acción
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($selectedResources as $selectedResource)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ $selectedResource['resource']->resource_name }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-700">
                                            {{ $selectedResource['last_reported_at'] }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-700">
                                            {{ $selectedResource['status_name'] }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if ($form->canEdit)
                                                <button type="button"
                                                    wire:click="removeServiceResource({{ $selectedResource['index'] }})"
                                                    class="text-red-600 hover:text-red-700 text-sm font-semibold">
                                                    X
                                                </button>
                                            @else
                                                <span class="text-sm text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- SERVICE PARTIES --}}
        @php
            $serviceParties = $form->service?->service_parties?->filter(function ($party) use ($isBlank) {
                // Mostrar solo si hay datos reales (no solo el cualificador)
                return !(
                    $isBlank($party->party_name ?? null) &&
                    $isBlank($party->party_street ?? null) &&
                    $isBlank($party->party_city ?? null) &&
                    $isBlank($party->party_region ?? null)
                );
            });
        @endphp
        @if ($serviceParties?->isNotEmpty())
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
                            @foreach ($serviceParties as $party)
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
        @php
            $serviceContacts = $form->service?->service_contacts?->filter(function ($contact) use ($isBlank) {
                $details = $contact->service_contact_details?->filter(function ($detail) use ($isBlank) {
                    return !(
                        $isBlank($detail->channel_contact ?? null) && $isBlank($detail->contact_information ?? null)
                    );
                });
                return !($isBlank($contact->contact_name ?? null) && ($details?->isEmpty() ?? true));
            });
        @endphp
        @if ($serviceContacts?->isNotEmpty())
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
                            @foreach ($serviceContacts as $contact)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        {{ $contact->contact_type ? $form->catalogLabel($contact->contact_type) : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">{{ $contact->contact_name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        @php
                                            $contactDetails = $contact->service_contact_details?->filter(function (
                                                $detail,
                                            ) use ($isBlank) {
                                                return !(
                                                    $isBlank($detail->channel_contact ?? null) &&
                                                    $isBlank($detail->contact_information ?? null)
                                                );
                                            });
                                        @endphp
                                        @if ($contactDetails?->isNotEmpty())
                                            <div class="space-y-1">
                                                @foreach ($contactDetails as $detail)
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
        @php
            $serviceDates = $form->service?->service_dates?->filter(
                fn($date) => !$isBlank($date->service_date ?? null),
            );
        @endphp
        @if ($serviceDates?->isNotEmpty())
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Fechas del Servicio
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($serviceDates as $date)
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
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-3">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Orden #{{ $index + 1 }} - {{ $po->purchase_order_number ?? 'N/A' }}
                            </h3>

                            <div class="text-sm text-gray-700">
                                <span class="font-medium">Estado:</span>
                                {{ $form->service?->status ? $statusLabel($form->service->status) . ' - ' . ($form->service->status->status_description ?? '') : '-' }}
                            </div>
                        </div>



                        {{-- DATOS DE LA ORDEN (oculto) --}}
                        {{--
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
                        --}}




                        {{-- PO PARTIES --}}
                        @php
                            $poParties = $po->purchase_order_parties?->filter(function ($party) use ($isBlank) {
                                // Mostrar solo si hay datos reales (no solo el cualificador)
                                return !(
                                    $isBlank($party->party_name ?? null) &&
                                    $isBlank($party->party_street ?? null) &&
                                    $isBlank($party->party_city ?? null) &&
                                    $isBlank($party->party_region ?? null)
                                );
                            });
                        @endphp
                        @if ($poParties?->isNotEmpty())
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
                                            @foreach ($poParties as $party)
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
                        @php
                            $poContacts = $po->purchase_order_contacts?->filter(function ($contact) use ($isBlank) {
                                $details = $contact->purchase_order_contact_details?->filter(function ($detail) use (
                                    $isBlank,
                                ) {
                                    return !(
                                        $isBlank($detail->channel_contact ?? null) &&
                                        $isBlank($detail->contact_information ?? null)
                                    );
                                });
                                return !($isBlank($contact->contact_name ?? null) && ($details?->isEmpty() ?? true));
                            });
                        @endphp
                        @if ($poContacts?->isNotEmpty())
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
                                            @foreach ($poContacts as $contact)
                                                <tr class="hover:bg-gray-50 transition-colors">
                                                    <td class="px-3 py-2 text-sm">
                                                        {{ $contact->contact_type ? $form->catalogLabel($contact->contact_type) : '-' }}
                                                    </td>
                                                    <td class="px-3 py-2 text-sm">{{ $contact->contact_name ?? '-' }}
                                                    </td>
                                                    <td class="px-3 py-2 text-sm">
                                                        @php
                                                            $poContactDetails = $contact->purchase_order_contact_details?->filter(
                                                                function ($detail) use ($isBlank) {
                                                                    return !(
                                                                        $isBlank($detail->channel_contact ?? null) &&
                                                                        $isBlank($detail->contact_information ?? null)
                                                                    );
                                                                },
                                                            );
                                                        @endphp
                                                        @if ($poContactDetails?->isNotEmpty())
                                                            <div class="space-y-1">
                                                                @foreach ($poContactDetails as $detail)
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
                        @php
                            $poNotes = $po->purchase_order_notes?->filter(function ($note) {
                                $raw = trim((string) ($note->raw_segment ?? ''));
                                $text = trim((string) ($note->note_text ?? ''));
                                $junkRaw = "FTX+ABA+++ :;:;;:;'";

                                if ($raw === $junkRaw) {
                                    return false;
                                }

                                if ($text === '') {
                                    return false;
                                }

                                // Si solo trae separadores (ej ":;:;;:;"), no mostrar
                                if (preg_match('/^[\\s:;]+$/', $text)) {
                                    return false;
                                }

                                return true;
                            });
                        @endphp
                        @if ($poNotes?->isNotEmpty())
                            <div class="mt-4">
                                <h4 class="text-md font-semibold text-gray-800 mb-2">Especificaciones de la Orden</h4>
                                <div class="space-y-2">
                                    @foreach ($poNotes as $note)
                                        <div class="p-3 border border-gray-200 rounded-lg bg-white">
                                            <div class="text-sm text-gray-900">{{ $note->note_text ?? '-' }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- PO MEASUREMENTS --}}
                        @php
                            $poMeasurements = $po->purchase_order_measurements?->filter(function ($measurement) use (
                                $isBlank,
                            ) {
                                return !(
                                    $isBlank(
                                        $measurement->measure_value ?? ($measurement->measurement_value ?? null),
                                    ) &&
                                    $isBlank($measurement->measure_unit ?? ($measurement->measurement_unit ?? null))
                                );
                            });
                        @endphp
                        @if ($poMeasurements?->isNotEmpty())
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
                                            @foreach ($poMeasurements as $measurement)
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
                        @php
                            $poRequirements = $po->purchase_order_requirements?->filter(function ($requirement) use (
                                $isBlank,
                            ) {
                                return !(
                                    $isBlank($requirement->requirement_type ?? null) &&
                                    $isBlank($requirement->requirement_value ?? null)
                                );
                            });
                        @endphp
                        @if ($poRequirements?->isNotEmpty())
                            <div class="mt-4">
                                <h4 class="text-md font-semibold text-gray-800 mb-2">Requerimientos de la Orden</h4>
                                <div class="space-y-2">
                                    @foreach ($poRequirements as $requirement)
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
                        @php
                            $poDeliveryTerms = $po->delivery_terms?->filter(function ($term) use ($isBlank) {
                                return !$isBlank($term->delivery_location ?? null);
                            });
                        @endphp
                        @if ($poDeliveryTerms?->isNotEmpty())
                            <div class="mt-4">
                                <h4 class="text-md font-semibold text-gray-800 mb-2">Términos de Entrega</h4>
                                <div class="space-y-2">
                                    @foreach ($poDeliveryTerms as $term)
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
                        @php
                            $poTransportCharges = $po->transport_charges?->filter(function ($charge) use ($isBlank) {
                                return !(
                                    $isBlank($charge->charge_code ?? null) &&
                                    $isBlank($charge->rate_class_code ?? null) &&
                                    $isBlank($charge->price_amount ?? null) &&
                                    $isBlank($charge->unit_price_basis ?? null) &&
                                    $isBlank($charge->measure_unit_code ?? null) &&
                                    $isBlank($charge->currency_code ?? null)
                                );
                            });
                        @endphp
                        @if ($poTransportCharges?->isNotEmpty())
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
                                            @foreach ($poTransportCharges as $charge)
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
                        @php
                            $poItems = $po->purchase_order_items?->filter(function ($item) use ($isBlank) {
                                $hasMain = !(
                                    $isBlank($item->item_description ?? null) &&
                                    $isBlank($item->quantity ?? null) &&
                                    $isBlank($item->gross_weight ?? null) &&
                                    $isBlank($item->net_weight ?? null) &&
                                    $isBlank($item->volume ?? null) &&
                                    $isBlank($item->package_count ?? null) &&
                                    $isBlank($item->package_type ?? null)
                                );

                                $hasNotes =
                                    $item->item_notes
                                        ?->filter(fn($n) => !$isBlank($n->note_text ?? null))
                                        ->isNotEmpty() ?? false;
                                $hasMeasures =
                                    $item->item_measures
                                        ?->filter(
                                            fn($m) => !$isBlank($m->measurement_value ?? ($m->measure_value ?? null)),
                                        )
                                        ->isNotEmpty() ?? false;
                                $hasDims =
                                    $item->item_dimensions
                                        ?->filter(
                                            fn($d) => !$isBlank($d->length ?? null) ||
                                                !$isBlank($d->width ?? null) ||
                                                !$isBlank($d->height ?? null),
                                        )
                                        ->isNotEmpty() ?? false;
                                $hasContainers =
                                    $item->item_container_identifiers
                                        ?->filter(fn($c) => !$isBlank($c->package_identifier_value ?? null))
                                        ->isNotEmpty() ?? false;
                                $hasProducts =
                                    $item->item_product_identifiers
                                        ?->filter(
                                            fn($p) => !$isBlank(
                                                $p->identifier_value ?? ($p->product_identifier_value ?? null),
                                            ),
                                        )
                                        ->isNotEmpty() ?? false;
                                $hasUnits =
                                    $item->item_unit_identifiers
                                        ?->filter(fn($u) => !$isBlank($u->unit_identifier_value ?? null))
                                        ->isNotEmpty() ?? false;

                                return $hasMain ||
                                    $hasNotes ||
                                    $hasMeasures ||
                                    $hasDims ||
                                    $hasContainers ||
                                    $hasProducts ||
                                    $hasUnits;
                            });
                        @endphp
                        @if ($poItems?->isNotEmpty())
                            <div class="mt-4">
                                <h4 class="text-md font-semibold text-gray-800 mb-2">Items de la Orden</h4>

                                @foreach ($poItems as $itemIndex => $item)
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
                                        @php
                                            $itemNotes = $item->item_notes?->filter(function ($itemNote) use (
                                                $isBlank,
                                            ) {
                                                return !$isBlank($itemNote->note_text ?? null);
                                            });
                                        @endphp
                                        @if ($itemNotes?->isNotEmpty())
                                            <div class="mt-3">
                                                <div class="text-xs font-semibold text-gray-700 mb-1">Descripción</div>
                                                <div class="space-y-1">
                                                    @foreach ($itemNotes as $itemNote)
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
                                        @php
                                            $itemMeasures = $item->item_measures?->filter(function ($measure) use (
                                                $isBlank,
                                            ) {
                                                return !(
                                                    $isBlank(
                                                        $measure->measurement_value ??
                                                            ($measure->measure_value ?? null),
                                                    ) &&
                                                    $isBlank(
                                                        $measure->measure_unit_code ??
                                                            ($measure->measurement_unit ??
                                                                ($measure->measure_unit ?? null)),
                                                    )
                                                );
                                            });
                                        @endphp
                                        @if ($itemMeasures?->isNotEmpty())
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
                                                            @foreach ($itemMeasures as $measure)
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
                                        @php
                                            $itemDimensions = $item->item_dimensions?->filter(function (
                                                $dimension,
                                            ) use ($isBlank) {
                                                return !(
                                                    $isBlank($dimension->length ?? null) &&
                                                    $isBlank($dimension->width ?? null) &&
                                                    $isBlank($dimension->height ?? null) &&
                                                    $isBlank($dimension->dimension_unit ?? null)
                                                );
                                            });
                                        @endphp
                                        @if ($itemDimensions?->isNotEmpty())
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
                                                            @foreach ($itemDimensions as $dimension)
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
                                        @php
                                            $itemContainers = $item->item_container_identifiers?->filter(function (
                                                $container,
                                            ) use ($isBlank) {
                                                return !$isBlank($container->package_identifier_value ?? null);
                                            });
                                        @endphp
                                        @if ($itemContainers?->isNotEmpty())
                                            <div class="mt-3">
                                                <div class="text-xs font-semibold text-gray-700 mb-1">Identificadores
                                                    de Contenedor</div>
                                                <div class="space-y-1">
                                                    @foreach ($itemContainers as $container)
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
                                        @php
                                            $itemProducts = $item->item_product_identifiers?->filter(function (
                                                $productId,
                                            ) use ($isBlank) {
                                                return !$isBlank(
                                                    $productId->identifier_value ??
                                                        ($productId->product_identifier_value ?? null),
                                                );
                                            });
                                        @endphp
                                        @if ($itemProducts?->isNotEmpty())
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
                                                            @foreach ($itemProducts as $productId)
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
                                        @php
                                            $itemUnitIds = $item->item_unit_identifiers?->filter(function (
                                                $unitId,
                                            ) use ($isBlank) {
                                                return !$isBlank($unitId->unit_identifier_value ?? null);
                                            });
                                        @endphp
                                        @if ($itemUnitIds?->isNotEmpty())
                                            <div class="mt-3">
                                                <div class="text-xs font-semibold text-gray-700 mb-1">Identificadores
                                                    de Unidad</div>
                                                <div class="space-y-1">
                                                    @foreach ($itemUnitIds as $unitId)
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
        @php
            $locationDetails = $form->service?->location_details?->filter(function ($location) use ($isBlank) {
                return !$isBlank($location->location_details ?? null);
            });
        @endphp
        @if ($locationDetails?->isNotEmpty())
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
                    @foreach ($locationDetails as $location)
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
        @php
            $transportDetails = $form->service?->transport_details?->filter(function ($transport) use ($isBlank) {
                $stage = $transport->transport_stage?->transport_stage_name ?? null;
                $mode = $transport->transport_mode?->transport_mode_name ?? null;
                $vehicle = $transport->vehicle_details ?? ($transport->vehicule_details ?? null);
                return !($isBlank($stage) && $isBlank($mode) && $isBlank($vehicle));
            });
        @endphp
        @if ($transportDetails?->isNotEmpty())
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
                            @foreach ($transportDetails as $transport)
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
        @php
            $serviceEquipments = $form->service?->service_equipments?->filter(function ($equipment) use ($isBlank) {
                return !(
                    $isBlank($equipment->equipment_identification ?? null) &&
                    $isBlank($equipment->equipment_size_type ?? null)
                );
            });
        @endphp
        @if ($serviceEquipments?->isNotEmpty())
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Equipos
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($serviceEquipments as $equipment)
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
