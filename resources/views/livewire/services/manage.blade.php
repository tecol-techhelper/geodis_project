<?php

use App\Models\Service;
use App\Models\Status;
use App\Models\EdifactFile;
use App\Models\Resource;
use App\Models\ServiceStatusReport;
use App\Models\StatusPurpose;
use App\Jobs\UploadEdifactToSftpJob;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Services\Edi\GenerateIftstaPayloadService;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Livewire\Forms\Services\ManageForm;

new #[Layout('layouts.app')] class extends Component {
    public ManageForm $form;
    public ?string $status_reported_at = null;
    /** @var array<int, int|string> */
    public array $resource_status_update_pivot_ids = [];

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
            ->select(['id', 'resource_id', 'resource_name', 'resource_operation', 'required_report_mask'])
            ->orderBy('resource_operation')
            ->orderBy('resource_name')
            ->get();
    }

    public function save(): void
    {
        $this->validateStatusReportedAtIfIftstaRequired();

        try {
            $dirtyPurchaseOrderIds = $this->form->update();
        } catch (ValidationException $exception) {
            foreach (array_keys($exception->errors()) as $errorKey) {
                if (preg_match('/additional_information\.([^\.]+)\./', $errorKey, $matches)) {
                    $this->form->active_resource_row_key = $matches[1];
                    break;
                }
            }

            throw $exception;
        }

        $updateChanges = $this->form->last_update_changes;
        $onlyRemovedResources = ($updateChanges['resource_removed'] ?? false)
            && !($updateChanges['resource_added'] ?? false)
            && !($updateChanges['status_changed'] ?? false);
        $selectedStatusUpdatePivotIds = $this->selectedResourceStatusUpdatePivotIds();

        try {
            $service = Service::query()
                ->with(['status:id,status_name,edifact_code', 'resources', 'purchase_orders:id,service_id'])
                ->findOrFail((int) $this->form->id);

            $resources = $service->resources ?? collect();
            $newlyAddedPivotIds = collect($this->form->last_added_service_resource_ids)
                ->map(fn($id) => (int) $id)
                ->filter()
                ->unique()
                ->values()
                ->all();

            $newResourceReports = $resources
                ->filter(function ($res) use ($newlyAddedPivotIds) {
                    $pivotId = $res->pivot?->id !== null ? (int) $res->pivot->id : null;

                    return $pivotId !== null && in_array($pivotId, $newlyAddedPivotIds, true);
                })
                ->values();

            $selectedExistingResourceReports = $resources
                ->filter(function ($res) use ($selectedStatusUpdatePivotIds, $newlyAddedPivotIds) {
                    $pivotId = $res->pivot?->id !== null ? (int) $res->pivot->id : null;

                    return $pivotId !== null
                        && in_array($pivotId, $selectedStatusUpdatePivotIds, true)
                        && !in_array($pivotId, $newlyAddedPivotIds, true);
                })
                ->values();

            $statusReportedAt = $this->status_reported_at ? \Carbon\Carbon::parse($this->status_reported_at) : null;
            $reportedStatusName = $service->status?->status_name;
            $statusReport = null;

            if (count($dirtyPurchaseOrderIds) > 0) {
                $this->validate([
                    'status_reported_at' => ['required', 'date'],
                ], [
                    'status_reported_at.required' => 'La fecha de reporte es obligatoria.',
                    'status_reported_at.date' => 'La fecha de reporte no tiene un formato válido.',
                ]);

                /** @var GenerateIftstaPayloadService $iftsta */
                $iftsta = app(GenerateIftstaPayloadService::class);

                $reportTasks = $newResourceReports
                    ->map(fn($resourceItem) => [
                        'resource' => $resourceItem,
                        'resource_id' => trim((string) ($resourceItem->resource_id ?? '')),
                    ])
                    ->values()
                    ->all();

                $statusOnlyRequired = ($updateChanges['status_changed'] ?? false) && $newResourceReports->isEmpty();

                if ($statusOnlyRequired) {
                    $reportTasks[] = [
                        'resource' => null,
                        'resource_id' => null,
                    ];
                }

                if ($reportTasks === []) {
                    Log::info('No hay recursos nuevos ni actualizaciones de recurso por reportar en IFTSTA', [
                        'service_id' => $service->id,
                        'purchase_orders' => $dirtyPurchaseOrderIds,
                    ]);
                }

                foreach ($reportTasks as $reportTask) {
                    $resourceId = $reportTask['resource_id'];

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
                    $edifactFile->refresh();

                    if ($edifactFile->status !== EdifactFile::STATUS_SENT) {
                        throw new \RuntimeException("El IFTSTA #{$edifactFile->id} no quedo enviado por SFTP.");
                    }

                    Log::info('IFTSTA generado y job de envio despachado', [
                        'service_id' => $service->id,
                        'edifact_file_id' => $edifactFile->id,
                        'transmission_id' => $transmissionId,
                        'purchase_orders' => $dirtyPurchaseOrderIds,
                        'resource_id' => $resourceId,
                    ]);

                    if (!$statusReport) {
                        $statusReport = $this->persistServiceStatusReport($service, $statusReportedAt);
                    }

                }

                if ($statusReport) {
                    $this->markResourcesWithStatus($statusReport, $newResourceReports, $statusReportedAt, $reportedStatusName);
                }
            } else {
                Log::info('Servicio actualizado sin cambios de estado en purchase_orders', [
                    'service_id' => $service->id,
                ]);
            }

            if ($selectedExistingResourceReports->isNotEmpty()) {
                $statusReport ??= $this->persistServiceStatusReport($service, $statusReportedAt);

                if ($statusReport) {
                    $this->markResourcesWithStatus($statusReport, $selectedExistingResourceReports, $statusReportedAt, $reportedStatusName);
                }
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
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
        $this->resource_status_update_pivot_ids = [];

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
        $pivotId = data_get($this->form->service_resource_rows, "{$resourceIndex}.pivot_id");
        $this->form->removeServiceResource($resourceIndex);

        if ($pivotId !== null) {
            $pivotId = (int) $pivotId;
            $this->resource_status_update_pivot_ids = array_values(array_filter(
                $this->resource_status_update_pivot_ids,
                fn($selectedPivotId) => (int) $selectedPivotId !== $pivotId,
            ));
        }
    }

    public function updateAdditionalInformation(): void
    {
        $rowKey = $this->form->active_resource_row_key;

        abort_unless($rowKey, 404, 'No hay un recurso seleccionado.');

        $this->form->updateAdditionalInformation($rowKey);

        flash()
            ->title('Información actualizada')
            ->success('La información adicional se actualizó correctamente.');
    }

    public function searchPersonnel(string $lookupKey): void
    {
        $parts = explode(':', $lookupKey);
        abort_unless(count($parts) === 3, 404, 'Búsqueda de persona inválida.');

        [$rowKey, $roleId, $personnelIndex] = $parts;
        $roleId = (int) $roleId;
        $personnelIndex = (int) $personnelIndex;
        $found = $this->form->fillPersonnelFromOperator($rowKey, $roleId, $personnelIndex);

        $this->dispatch(
            'personnel-lookup-result',
            lookupKey: $lookupKey,
            rowKey: $rowKey,
            roleId: $roleId,
            personnelIndex: $personnelIndex,
            found: $found,
        );
    }

    public function openAdditionalInformation(string $rowKey): void
    {
        $this->form->openAdditionalInformation($rowKey);
    }

    public function closeAdditionalInformation(): void
    {
        $this->form->closeAdditionalInformation();
    }

    public function clearAdditionalInformation(string $rowKey): void
    {
        $this->form->clearAdditionalInformation($rowKey);
    }

    private function validateStatusReportedAtIfIftstaRequired(): void
    {
        if (!$this->iftstaRequiresStatusReportedAt()) {
            return;
        }

        $rules = [
            'status_reported_at' => ['required', 'date'],
        ];

        if ($this->selectedResourceStatusUpdatePivotIds() !== []) {
            $rules['form.service_status_id'] = ['required', 'integer', 'exists:statuses,id'];
        }

        $this->validate(
            $rules,
            [
                'status_reported_at.required' => 'La fecha de reporta es obligatoria cuando se reporta un estado.',
                'form.service_status_id.required' => 'El estado del servicio es obligatorio para actualizar recursos.',
                'form.service_status_id.exists' => 'El estado del servicio seleccionado no es valido.',
                'status_reported_at.date' => 'La fecha de reporte debe ser una fecha válida.',
            ],
            [
                'status_reported_at' => 'fecha de reporte',
                'form.service_status_id' => 'estado del servicio',
            ],
        );
    }

    private function iftstaRequiresStatusReportedAt(): bool
    {
        if (!$this->form->canEdit || !$this->form->service?->purchase_orders?->isNotEmpty()) {
            return false;
        }

        $newStatusId = $this->form->service_status_id !== null ? (int) $this->form->service_status_id : null;
        $oldStatusId = $this->form->original_service_status_id !== null ? (int) $this->form->original_service_status_id : null;
        $statusChanged = $newStatusId !== $oldStatusId;

        $newResourceRows = $this->normalizedResourceRows($this->form->service_resource_rows);
        $oldResourceRows = $this->normalizedResourceRows($this->form->original_service_resource_rows);

        $pivotIdsToKeep = array_values(array_filter(array_map(
            fn($row) => $row['pivot_id'],
            $newResourceRows,
        )));

        $pivotIdsToDelete = array_values(array_filter(array_map(
            fn($row) => $row['pivot_id'],
            $oldResourceRows,
        ), fn($pivotId) => !in_array($pivotId, $pivotIdsToKeep, true)));

        $hasRemovedResources = $pivotIdsToDelete !== [];
        $hasAddedResources = collect($newResourceRows)->contains(fn($row) => $row['pivot_id'] === null);
        $onlyRemovedResources = $hasRemovedResources && !$hasAddedResources && !$statusChanged;

        if ($onlyRemovedResources) {
            return false;
        }

        if ($this->selectedResourceStatusUpdatePivotIds() !== []) {
            return true;
        }

        if ($statusChanged || $hasAddedResources) {
            return true;
        }

        return false;
    }

    private function selectedResourceStatusUpdatePivotIds(): array
    {
        $currentPivotIds = collect($this->form->service_resource_rows)
            ->pluck('pivot_id')
            ->filter()
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();

        return collect($this->resource_status_update_pivot_ids)
            ->map(fn($id) => (int) $id)
            ->filter(fn($id) => $id > 0 && in_array($id, $currentPivotIds, true))
            ->unique()
            ->values()
            ->all();
    }

    private function normalizedResourceRows(array $rows): array
    {
        return array_values(array_filter(array_map(function ($row) {
            $resourceId = (int) data_get($row, 'resource_id', 0);

            if ($resourceId <= 0) {
                return null;
            }

            $pivotId = data_get($row, 'pivot_id');

            return [
                'pivot_id' => $pivotId !== null ? (int) $pivotId : null,
                'resource_id' => $resourceId,
            ];
        }, $rows)));
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

    private function markResourcesWithStatus(
        ServiceStatusReport $statusReport,
        \Illuminate\Support\Collection $resources,
        ?\Carbon\Carbon $reportedAt,
        ?string $reportedStatusName,
    ): void {
        $reportedAt ??= now();

        $resources
            ->filter(fn($resourceItem) => $resourceItem->pivot?->id)
            ->unique(fn($resourceItem) => (int) $resourceItem->pivot->id)
            ->each(function ($resourceItem) use ($statusReport, $reportedAt, $reportedStatusName) {
                DB::table('service_resource_status_reports')->insertOrIgnore([
                    'service_resource_id' => (int) $resourceItem->pivot->id,
                    'service_status_report_id' => $statusReport->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('service_resource')
                    ->where('id', (int) $resourceItem->pivot->id)
                    ->update([
                        'last_reported_at' => $reportedAt,
                        'status_name' => $reportedStatusName,
                        'updated_at' => now(),
                    ]);
            });
    }

    public function back(): void
    {
        $this->redirect(route('services.index'));
    }

    private function persistServiceStatusReport(Service $service, ?\Carbon\Carbon $reportedAt): ?ServiceStatusReport
    {
        if ($service->status_id === null || !$service->status) {
            return null;
        }

        return ServiceStatusReport::query()->updateOrCreate(
            [
                'service_id' => $service->id,
                'status_id' => $service->status_id,
            ],
            [
                'reported_at' => $reportedAt ?? now(),
                'status_name_snapshot' => $service->status->status_name,
                'edifact_code_snapshot' => $service->status->edifact_code,
            ],
        );
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
<div class="space-y-6 pb-8" x-data="{ resourceToRemove: null }"
    x-on:support-files-saved.window="setTimeout(() => window.location.reload(), 3200)">
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
        class="space-y-6 rounded-xl border border-gray-200 bg-white px-4 py-6 shadow-sm sm:px-6">

        {{-- HEADER DEL SERVICIO --}}
        <div class="flex flex-col gap-5 border-b border-gray-200 pb-6 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Detalle operativo</p>
                <h1 class="mt-1 text-2xl font-bold text-gray-900 sm:text-3xl">
                    Servicio N° {{ $form->consecutive ?? '-' }}
                </h1>
                @if ($form->item)
                    <p class="mt-2 text-sm text-gray-600">Item: <span class="font-semibold">{{ $form->item }}</span>
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

            <div class="w-full lg:max-w-xl">
                <label for="bulk_status" class="mb-1 block text-sm font-medium text-gray-700">
                    Estado del Servicio
                </label>
                <div wire:ignore>
                    <select id="bulk_status" wire:model.defer="form.service_status_id"
                        class="js-status-select w-full whitespace-normal rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-500"
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
                @error('form.service_status_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- BOTONES DE ACCIÓN --}}
        <div class="flex flex-col gap-3 rounded-lg border border-gray-200 bg-gray-50 p-3 sm:flex-row sm:items-center">
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
                            class="w-full cursor-not-allowed opacity-60 sm:w-auto">
                            Cargar soportes
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
            <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-gray-900">
                <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Información general
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

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <label for="item" class="mb-1 block text-sm font-medium text-gray-700">Item</label>
                    <div
                        class="relative flex h-11 items-stretch rounded-md border border-gray-300 bg-white shadow-sm focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500">
                        <span
                            class="flex items-center rounded-l-md bg-gray-100 px-4 text-sm font-semibold text-gray-900">
                            {{ $form->service?->created_at?->format('m') ?? now()->format('m') }}-
                        </span>
                        <input type="text" id="item" wire:model.defer="form.item"
                            class="h-full w-full rounded-l-none rounded-r-md border-0 bg-transparent px-4 focus:ring-0 disabled:bg-gray-50 disabled:text-gray-500"
                            @disabled(!$form->canEdit) />
                    </div>
                    @error('form.item')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div class="mt-3">
                        <label for="service_priority" class="mb-1 block text-sm font-medium text-gray-700">
                            Prioridad
                        </label>
                        <div id="service_priority"
                            class="flex h-11 w-full items-center rounded-md border border-gray-300 bg-gray-50 px-4 font-semibold shadow-sm {{ $agwPriorityClass }}">
                            {{ $agwPriorityLabel ?? '-' }}
                        </div>
                    </div>
                </div>

                <div>
                    <label for="consecutive" class="mb-1 block text-sm font-medium text-gray-700">Consecutivo</label>
                    <input type="text" id="consecutive" wire:model.defer="form.consecutive"
                        class="h-11 w-full cursor-not-allowed rounded-md border-gray-300 bg-gray-50 text-gray-500 shadow-sm"
                        disabled />

                    <div class="mt-3">
                        <label for="consolidated_number" class="mb-1 block text-sm font-medium text-gray-700">
                            N&uacute;mero de Consolidado
                        </label>
                        <input type="text" id="consolidated_number"
                            value="{{ $agwConsolidatedNumber ?? '-' }}"
                            class="h-11 w-full cursor-not-allowed rounded-md border-gray-300 bg-gray-50 text-gray-600 shadow-sm"
                            disabled />
                    </div>
                </div>

                @php
                    // Resolver el Tipo de servicio a partir del RFF+ACD de la primera purchase order
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
                    <label class="mb-1 block text-sm font-medium text-gray-700">Tipo de servicio</label>
                    <div
                        class="flex h-11 w-full items-center rounded-md border border-gray-300 bg-gray-50 px-4 text-sm text-gray-700 shadow-sm">
                        @if ($serviceTypePurpose)
                            <span class="font-semibold text-indigo-700">{{ $serviceTypePurpose['name'] }}</span>
                            <span class="ml-1 text-gray-500">({{ $serviceTypePurpose['subcode'] }})</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </div>
                </div>

                <div class="mt-2 md:col-span-3">
                    <h3 class="flex items-center gap-2 text-base font-semibold text-gray-900">
                        <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z" />
                        </svg>
                        Fechas Estimadas del Servicio
                    </h3>
                </div>

                <div class="grid grid-cols-1 gap-4 md:col-span-3 md:grid-cols-2">
                    <div>
                        <label for="positioning_date" class="mb-1 block text-sm font-medium text-gray-700">
                            Fecha estimada de cargue (Posicionamiento)
                        </label>
                        <input type="datetime-local" id="positioning_date" wire:model.defer="form.positioning_date"
                            class="h-11 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-500"
                            @disabled(!$form->canEdit) />
                        @error('form.positioning_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="arrival_date" class="mb-1 block text-sm font-medium text-gray-700">
                            Fecha estimada de descargue (Arribo)
                        </label>
                        <input type="datetime-local" id="arrival_date" wire:model.defer="form.arrival_date"
                            class="h-11 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-500"
                            @disabled(!$form->canEdit) />
                        @error('form.arrival_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-2 md:col-span-3">
                    <h3 class="flex items-center gap-2 text-base font-semibold text-gray-900">
                        <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7h18M5 7v10a2 2 0 002 2h10a2 2 0 002-2V7" />
                        </svg>
                        Recurso(s) utilizado(s)
                    </h3>
                </div>

                @php
                    $resourceGroups = $resources?->groupBy('resource_operation') ?? collect();
                    $resourceLookup = $resources?->keyBy('id') ?? collect();
                    $serviceResourcePivotLookup = collect($form->service?->resources ?? [])
                        ->filter(fn($resource) => (int) data_get($resource, 'pivot.id', 0) > 0)
                        ->keyBy(fn($resource) => (int) data_get($resource, 'pivot.id'));
                    $serviceStatusReports = collect($form->service?->service_status_reports ?? [])
                        ->sortBy(function ($statusReport) {
                            $edifactCode = $statusReport->status?->edifact_code
                                ?? $statusReport->edifact_code_snapshot
                                ?? null;

                            return [
                                is_numeric($edifactCode) ? (int) $edifactCode : PHP_INT_MAX,
                                (int) ($statusReport->status_id ?? PHP_INT_MAX),
                            ];
                        })
                        ->values();

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

                    $resourceStatusLinks = $serviceStatusReports
                        ->flatMap(function ($statusReport) {
                            $statusName = $statusReport->status?->status_name
                                ?? $statusReport->status_name_snapshot
                                ?? ('Estado #' . $statusReport->status_id);

                            return collect($statusReport->resourceStatusReports ?? [])->map(fn($resourceStatusReport) => [
                                'service_resource_id' => (int) $resourceStatusReport->service_resource_id,
                                'status_name' => $statusName,
                            ]);
                        })
                        ->values();

                    $reportedStatusesByPivotId = $resourceStatusLinks
                        ->groupBy('service_resource_id')
                        ->map(fn($rows) => $rows->pluck('status_name')->filter()->unique()->implode(', '));

                    $statusReportRows = $serviceStatusReports
                        ->map(function ($statusReport) use ($formatReportedAt) {
                            $resourceReports = collect($statusReport->resourceStatusReports ?? []);
                            $resourceNames = $resourceReports
                                ->map(fn($resourceStatusReport) => $resourceStatusReport->serviceResource?->resource?->resource_name)
                                ->filter()
                                ->unique()
                                ->values();

                            return [
                                'status_name' => collect([
                                    $statusReport->status?->status_name
                                    ?? $statusReport->status_name_snapshot
                                    ?? ('Estado #' . $statusReport->status_id),
                                    $statusReport->status?->status_description,
                                ])->filter(fn($value) => trim((string) $value) !== '')->implode(' - '),
                                'reported_at' => $formatReportedAt($statusReport->reported_at),
                                'resource_count' => $resourceReports->pluck('service_resource_id')->unique()->count(),
                                'resource_names' => $resourceNames->isNotEmpty() ? $resourceNames->implode(', ') : '-',
                            ];
                        })
                        ->values();

                    $selectedResources = collect($form->service_resource_rows ?? [])
                        ->values()
                        ->map(function ($row, $index) use ($resourceLookup, $serviceResourcePivotLookup, $formatReportedAt, $form, $reportedStatusesByPivotId) {
                            $resource = $resourceLookup->get((int) data_get($row, 'resource_id'));
                            $pivotId = (int) data_get($row, 'pivot_id', 0);
                            $rowKey = (string) data_get($row, 'row_key');
                            $pivotResource = $pivotId > 0 ? $serviceResourcePivotLookup->get($pivotId) : null;
                            $lastReportedAt = $pivotResource?->pivot?->last_reported_at;
                            $statusName = $pivotResource?->pivot?->status_name;

                            if (!$resource) {
                                return null;
                            }

                            return [
                                'index' => $index,
                                'row_key' => $rowKey,
                                'pivot_id' => $pivotId > 0 ? $pivotId : null,
                                'resource' => $resource,
                                'last_reported_at' => $formatReportedAt($lastReportedAt),
                                'status_name' => (is_string($statusName) && trim($statusName) !== '') ? trim($statusName) : '-',
                                'status_names' => $reportedStatusesByPivotId->get($pivotId, '-'),
                                'additional_status' => $form->additionalInformationStatus($rowKey),
                            ];
                        })
                        ->filter()
                        ->values();
                @endphp
                <div class="grid grid-cols-1 gap-4 md:col-span-3 md:grid-cols-2">
                    <div>
                        <label for="service_resource" class="mb-1 block text-sm font-medium text-gray-700">
                            Recurso(s)
                        </label>
                        <div class="flex items-stretch gap-2">
                            <div class="min-w-0 flex-1" wire:ignore>
                                <select id="service_resource" wire:model="form.service_resource_id"
                                    class="js-status-select h-11 w-full whitespace-normal rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-500"
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
                                class="flex h-11 w-11 shrink-0 items-center justify-center gap-2 focus:outline-none focus:ring-0">
                                <span class="text-lg leading-none">+</span>
                            </x-primary-button>
                        </div>
                    </div>

                    <div>
                        <label for="status_reported_at" class="mb-1 block text-sm font-medium text-gray-700">
                            Fecha de reporte
                        </label>
                        <input type="datetime-local" id="status_reported_at" wire:model.defer="status_reported_at"
                            class="h-11 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-500"
                            @disabled(!$form->canEdit) />
                        @error('status_reported_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    @error('form.service_resource_id')
                        <p class="text-sm text-red-600 mt-1 md:col-span-2">{{ $message }}</p>
                    @enderror
                </div>

                @if ($selectedResources->isNotEmpty() || $statusReportRows->isNotEmpty())
                    <div class="mt-1 grid grid-cols-1 items-start gap-4 md:col-span-3 lg:grid-cols-2">
                    <div class="min-w-0 overflow-hidden rounded-lg border border-gray-200 bg-white">
                        <div class="border-b border-gray-200 bg-gray-50 px-4 py-2">
                            <h4 class="text-sm font-semibold text-gray-900">Recursos asignados</h4>
                        </div>
                        <div class="max-h-80 overflow-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50/80">
                                <tr>
                                    <th class="w-10 px-3 py-2 text-center">
                                        <span class="sr-only">Actualizar estado</span>
                                    </th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Recurso
                                    </th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estados reportados
                                    </th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Último estado
                                    </th>
                                    <th
                                        class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Información adicional
                                    </th>
                                    <th
                                        class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acción
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($selectedResources as $selectedResource)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-3 py-2 text-center">
                                            @if ($selectedResource['pivot_id'])
                                                <input type="checkbox"
                                                    value="{{ $selectedResource['pivot_id'] }}"
                                                    wire:model.defer="resource_status_update_pivot_ids"
                                                    title="Actualizar este recurso al estado seleccionado del servicio"
                                                    aria-label="Actualizar {{ $selectedResource['resource']->resource_name }} al estado seleccionado del servicio"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 disabled:cursor-not-allowed disabled:opacity-60"
                                                    @disabled(!$form->canEdit) />
                                            @else
                                                <span class="text-sm text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-900">
                                            {{ $selectedResource['resource']->resource_name }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-700">
                                            {{ $selectedResource['status_names'] }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-700">
                                            {{ $selectedResource['status_name'] }}
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            @php
                                                $additionalStatus = $selectedResource['additional_status'];
                                                $additionalStatusIcon = match ($additionalStatus) {
                                                    'registered', 'complete' => 'circle-check',
                                                    'pending' => 'circle-plus',
                                                    default => 'circle-minus',
                                                };
                                                $additionalStatusClass = match ($additionalStatus) {
                                                    'registered', 'complete' => 'border-green-300 bg-green-50 text-green-700 hover:bg-green-100',
                                                    'pending' => 'border-amber-300 bg-amber-50 text-amber-700 hover:bg-amber-100',
                                                    default => 'cursor-not-allowed border-gray-200 bg-gray-100 text-gray-400',
                                                };
                                                $additionalStatusTitle = match ($additionalStatus) {
                                                    'registered' => 'Información adicional registrada.',
                                                    'complete' => 'Información adicional completa, pendiente de guardar.',
                                                    'pending' => 'Información adicional pendiente.',
                                                    default => 'Este recurso no requiere información adicional para ser reportado.',
                                                };
                                                $requiresAdditionalInformation = $form->requiresAdditionalInformationForRow(
                                                    $selectedResource['row_key'],
                                                );
                                            @endphp

                                            <button type="button"
                                                @if ($requiresAdditionalInformation)
                                                    wire:click="openAdditionalInformation('{{ $selectedResource['row_key'] }}')"
                                                @else
                                                    disabled
                                                @endif
                                                title="{{ $additionalStatusTitle }}"
                                                aria-label="{{ $additionalStatusTitle }}"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border transition {{ $additionalStatusClass }}">
                                                @switch($additionalStatusIcon)
                                                    @case('circle-check')
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                            class="h-5 w-5" aria-hidden="true">
                                                            <circle cx="12" cy="12" r="10" />
                                                            <path d="m9 12 2 2 4-4" />
                                                        </svg>
                                                    @break

                                                    @case('circle-plus')
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                            class="h-5 w-5" aria-hidden="true">
                                                            <circle cx="12" cy="12" r="10" />
                                                            <path d="M8 12h8" />
                                                            <path d="M12 8v8" />
                                                        </svg>
                                                    @break

                                                    @default
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                            class="h-5 w-5" aria-hidden="true">
                                                            <circle cx="12" cy="12" r="10" />
                                                            <path d="M8 12h8" />
                                                        </svg>
                                                @endswitch
                                            </button>
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            @if ($form->canEdit)
                                                <button type="button"
                                                    x-on:click="resourceToRemove = {{ $selectedResource['index'] }}"
                                                    title="Eliminar recurso"
                                                    aria-label="Eliminar {{ $selectedResource['resource']->resource_name }}"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-600 transition hover:bg-red-50 hover:text-red-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="h-4 w-4" aria-hidden="true">
                                                        <path d="M3 6h18" />
                                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                        <path d="M19 6l-1 14c0 1-1 2-2 2H8c-1 0-2-1-2-2L5 6" />
                                                        <path d="M10 11v6" />
                                                        <path d="M14 11v6" />
                                                    </svg>
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
                    </div>
                    <div class="min-w-0 overflow-hidden rounded-lg border border-gray-200 bg-white">
                        <div class="border-b border-gray-200 bg-gray-50 px-4 py-2">
                            <h4 class="text-sm font-semibold text-gray-900">Estados Reportados</h4>
                        </div>
                        <div class="max-h-80 overflow-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50/80">
                                <tr>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha de reporte
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($statusReportRows as $statusReportRow)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-2 text-sm text-gray-900">
                                            {{ $statusReportRow['status_name'] }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-700">
                                            {{ $statusReportRow['reported_at'] }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                    </div>
                @endif

                <div x-cloak x-show="resourceToRemove !== null" x-transition.opacity
                    x-on:keydown.escape.window="resourceToRemove = null"
                    class="fixed inset-0 z-[60] flex items-center justify-center bg-black/40 p-4 backdrop-blur-sm"
                    role="dialog" aria-modal="true" aria-labelledby="removeResourceTitle">
                    <div x-on:click.outside="resourceToRemove = null"
                        class="w-full max-w-md rounded-xl bg-white p-6 shadow-2xl">
                        <h3 id="removeResourceTitle" class="text-lg font-semibold text-gray-900">
                            Eliminar recurso del servicio
                        </h3>
                        <p class="mt-2 text-sm text-gray-600">
                            Esta acción también eliminará información asociada al recurso. El cambio será definitivo
                            al pulsar Enviar.
                        </p>
                        <div class="mt-6 flex justify-end gap-3">
                            <x-secondary-button type="button" x-on:click="resourceToRemove = null">
                                Cancelar
                            </x-secondary-button>
                            <x-danger-button type="button"
                                x-on:click="$wire.removeServiceResource(resourceToRemove); resourceToRemove = null">
                                Sí, eliminar
                            </x-danger-button>
                        </div>
                    </div>
                </div>

                @php
                    $activeResourceRow = $selectedResources->firstWhere('row_key', $form->active_resource_row_key);
                    $activeRowKey = $activeResourceRow['row_key'] ?? null;
                    $activeRequirements = $activeRowKey ? $form->requirementsForRow($activeRowKey) : [];
                    $activePersonnelRequirements = $activeRowKey ? $form->personnelRequirementsForRow($activeRowKey) : [];
                    $activeHasRegisteredInformation = $activeRowKey
                        && filled(data_get($form->additional_information, "{$activeRowKey}.report_id"));
                @endphp

                @if ($activeResourceRow && $activeRowKey)
                    <div x-data="{ confirmClear: false }" x-on:keydown.escape.window="$wire.closeAdditionalInformation()"
                        class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto bg-black/40 p-4 pt-8 backdrop-blur-sm"
                        role="dialog" aria-modal="true" aria-labelledby="additionalInformationTitle">
                        <div class="my-4 flex max-h-[90vh] w-full max-w-2xl flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-2xl">
                            <div class="flex items-start justify-between gap-4 border-b border-gray-200 bg-gray-50 px-5 py-4">
                                <div>
                                    <h3 id="additionalInformationTitle" class="font-semibold text-gray-900">
                                        Información adicional
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-600">
                                        {{ $activeResourceRow['resource']->resource_name }}
                                    </p>
                                </div>
                                <button type="button" wire:click="closeAdditionalInformation"
                                    class="rounded-md p-1 text-gray-500 hover:bg-gray-200 hover:text-gray-700"
                                    aria-label="Cerrar modal">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="h-5 w-5" aria-hidden="true">
                                        <path d="M18 6 6 18" />
                                        <path d="m6 6 12 12" />
                                    </svg>
                                </button>
                            </div>

                            <div class="space-y-5 overflow-y-auto px-5 py-5">
                                @if (!$form->canEdit)
                                    <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
                                        Tienes acceso de solo lectura a esta información.
                                    </div>
                                @else
                                    <p class="text-sm text-gray-600">
                                        Los campos marcados con <span class="font-semibold text-red-600">*</span> son
                                        obligatorios. Cerrar este modal conserva la información.
                                        @if ($activeHasRegisteredInformation)
                                            Usa Actualizar para guardar únicamente los cambios de este recurso.
                                        @else
                                            El registro inicial se guarda al pulsar Enviar en el servicio.
                                        @endif
                                    </p>
                                @endif
                                @if (($activeRequirements['vehicle'] ?? false) || ($activeRequirements['remittance'] ?? false) || ($activeRequirements['container'] ?? false))
                                    <section class="rounded-xl border border-gray-200 bg-white p-4">
                                        <div class="space-y-4">
                                            @if ($activeRequirements['vehicle'] ?? false)
                                                <div>
                                                    <x-input-label for="additional_vehicle_plate">
                                                        Placa del vehículo <span class="text-red-600">*</span>
                                                    </x-input-label>
                                                    <x-text-input id="additional_vehicle_plate" type="text"
                                                        wire:model.defer="form.additional_information.{{ $activeRowKey }}.vehicle_plate"
                                                        class="mt-1 w-full uppercase" maxlength="32"
                                                        placeholder="Ej. ABC123" :disabled="!$form->canEdit" />
                                                    @error('form.additional_information.' . $activeRowKey . '.vehicle_plate')
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            @endif

                                            @if ($activeRequirements['remittance'] ?? false)
                                                <div>
                                                    <x-input-label for="additional_remittance">
                                                        Remesa de transporte <span class="text-red-600">*</span>
                                                    </x-input-label>
                                                    <x-text-input id="additional_remittance" type="text"
                                                        wire:model.defer="form.additional_information.{{ $activeRowKey }}.remesa_transporte"
                                                        class="mt-1 w-full" maxlength="128" :disabled="!$form->canEdit" />
                                                    @error('form.additional_information.' . $activeRowKey . '.remesa_transporte')
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            @endif

                                            @if ($activeRequirements['container'] ?? false)
                                                <div>
                                                    <x-input-label for="additional_container_number">
                                                        Número de contenedor <span class="text-red-600">*</span>
                                                    </x-input-label>
                                                    <x-text-input id="additional_container_number" type="text"
                                                        wire:model.defer="form.additional_information.{{ $activeRowKey }}.container_number"
                                                        class="mt-1 w-full uppercase" maxlength="64"
                                                        :disabled="!$form->canEdit" />
                                                    @error('form.additional_information.' . $activeRowKey . '.container_number')
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            @endif
                                        </div>
                                    </section>
                                @endif

                                @if ($activeRequirements['personnel'] ?? false)
                                    <section class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                        <div class="space-y-4">
                                            @foreach ($activePersonnelRequirements as $personnelRequirement)
                                                @php
                                                    $roleId = (int) $personnelRequirement['role_id'];
                                                    $roleName = (string) $personnelRequirement['role_name'];
                                                    $quantityRequired = (int) $personnelRequirement['quantity_required'];
                                                @endphp

                                                @for ($personnelIndex = 0; $personnelIndex < $quantityRequired; $personnelIndex++)
                                                    @php
                                                        $entryLabel = $quantityRequired > 1 ? ' ' . ($personnelIndex + 1) : '';
                                                        $fieldIdPrefix = "additional_personnel_{$roleId}_{$personnelIndex}";
                                                        $fieldPrefix = 'form.additional_information.' . $activeRowKey . '.personnel.' . $roleId . '.' . $personnelIndex;
                                                        $lookupKey = "{$activeRowKey}:{$roleId}:{$personnelIndex}";
                                                        $searchPersonnelAction = "searchPersonnel('{$lookupKey}')";
                                                    @endphp

                                                    <fieldset class="space-y-4 rounded-xl border border-gray-200 bg-white p-4">
                                                        <legend class="px-2 text-sm font-semibold text-gray-800">
                                                            {{ $roleName }}{{ $entryLabel }}
                                                        </legend>

                                                        <div x-data="{ lookupStatus: 'idle', lookupKey: @js($lookupKey) }"
                                                            x-on:personnel-lookup-result.window="
                                                                if ($event.detail.lookupKey === lookupKey) {
                                                                    lookupStatus = $event.detail.found ? 'found' : 'not_found';
                                                                    setTimeout(() => lookupStatus = 'idle', 2500);
                                                                }
                                                            ">
                                                            <x-input-label for="{{ $fieldIdPrefix }}_identification">
                                                                Identificación del {{ $roleName }} <span class="text-red-600">*</span>
                                                            </x-input-label>
                                                            <div class="mt-1 flex">
                                                                <x-text-input id="{{ $fieldIdPrefix }}_identification" type="text"
                                                                    wire:model.defer="{{ $fieldPrefix }}.identification"
                                                                    class="w-full rounded-r-none" maxlength="64" :disabled="!$form->canEdit" />
                                                                @if ($form->canEdit)
                                                                    <button type="button"
                                                                        wire:click="{{ $searchPersonnelAction }}"
                                                                        wire:loading.attr="disabled" wire:target="{{ $searchPersonnelAction }}"
                                                                        x-bind:title="lookupStatus === 'not_found'
                                                                            ? 'No existe una persona con esta identificación.'
                                                                            : (lookupStatus === 'found'
                                                                                ? 'Persona encontrada.'
                                                                                : 'Buscar persona por identificación.')"
                                                                        x-bind:aria-label="lookupStatus === 'not_found'
                                                                            ? 'No existe una persona con esta identificación.'
                                                                            : (lookupStatus === 'found'
                                                                                ? 'Persona encontrada.'
                                                                                : 'Buscar persona por identificación.')"
                                                                        x-bind:class="{
                                                                            'border-red-700 bg-red-700 hover:bg-red-800': lookupStatus === 'not_found',
                                                                            'border-green-700 bg-green-700 hover:bg-green-800': lookupStatus === 'found',
                                                                            'border-blue-700 bg-blue-700 hover:bg-blue-800': lookupStatus === 'idle'
                                                                        }"
                                                                        class="-ml-px inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-r-xl border text-white transition ease-in-out duration-150 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                            class="h-5 w-5" aria-hidden="true">
                                                                            <circle cx="11" cy="11" r="8" />
                                                                            <path d="m21 21-4.3-4.3" />
                                                                        </svg>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                            <p x-cloak x-show="lookupStatus === 'not_found'"
                                                                x-transition.opacity
                                                                class="mt-1 text-sm text-red-600">
                                                                No existe una persona con esta identificación.
                                                            </p>
                                                            @error($fieldPrefix . '.identification')
                                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                                            <div>
                                                                <x-input-label for="{{ $fieldIdPrefix }}_first_name">
                                                                    Nombre(s) del {{ $roleName }} <span class="text-red-600">*</span>
                                                                </x-input-label>
                                                                <x-text-input id="{{ $fieldIdPrefix }}_first_name" type="text"
                                                                    wire:model.defer="{{ $fieldPrefix }}.first_name"
                                                                    class="mt-1 w-full" maxlength="128" :disabled="!$form->canEdit" />
                                                                @error($fieldPrefix . '.first_name')
                                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                                @enderror
                                                            </div>
                                                            <div>
                                                                <x-input-label for="{{ $fieldIdPrefix }}_last_name">
                                                                    Apellido(s) del {{ $roleName }} <span class="text-red-600">*</span>
                                                                </x-input-label>
                                                                <x-text-input id="{{ $fieldIdPrefix }}_last_name" type="text"
                                                                    wire:model.defer="{{ $fieldPrefix }}.last_name"
                                                                    class="mt-1 w-full" maxlength="128" :disabled="!$form->canEdit" />
                                                                @error($fieldPrefix . '.last_name')
                                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                @endfor
                                            @endforeach
                                        </div>
                                    </section>
                                @endif

                            </div>

                            <div class="flex flex-col-reverse gap-3 border-t border-gray-200 bg-gray-50 px-5 py-4 sm:flex-row sm:justify-end">
                                @if ($form->canEdit)
                                    <x-danger-button type="button" x-on:click="confirmClear = true"
                                        title="Eliminará toda la información registrada o relacionada con este recurso.">
                                        Limpiar
                                    </x-danger-button>

                                    @if ($activeHasRegisteredInformation)
                                        <x-success-button type="button" wire:click="updateAdditionalInformation"
                                            wire:loading.attr="disabled" wire:target="updateAdditionalInformation">
                                            Actualizar
                                        </x-success-button>
                                    @endif
                                @endif

                                <x-primary-button type="button" wire:click="closeAdditionalInformation">
                                    Cerrar
                                </x-primary-button>
                            </div>
                        </div>

                        <div x-cloak x-show="confirmClear" x-transition.opacity
                            class="fixed inset-0 z-[70] flex items-center justify-center bg-black/40 p-4">
                            <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-2xl">
                                <h4 class="text-lg font-semibold text-gray-900">Eliminar Datos</h4>
                                <p class="mt-2 text-sm text-gray-600">
                                    ¿Está seguro de eliminar la información registrada? Estos datos son de obligatorio
                                    diligenciamiento; si no se completan nuevamente, será necesario eliminar el recurso
                                    antes de enviar el servicio.
                                </p>
                                <div class="mt-6 flex justify-end gap-3">
                                    <x-secondary-button type="button" x-on:click="confirmClear = false">
                                        Cancelar
                                    </x-secondary-button>
                                    <x-danger-button type="button"
                                        x-on:click="$wire.clearAdditionalInformation('{{ $activeRowKey }}'); confirmClear = false">
                                        Sí, eliminar
                                    </x-danger-button>
                                </div>
                            </div>
                        </div>
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
                <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-gray-900">
                    <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Partes del servicio
                </h2>

                <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tipo</th>
                                <th
                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nombre</th>
                                <th
                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Dirección</th>
                                <th
                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ciudad</th>
                                <th
                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Código postal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($serviceParties as $party)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-2 whitespace-nowrap text-sm">
                                        {{ $party->party_type ? $form->catalogLabel($party->party_type) : '-' }}
                                    </td>
                                    <td class="px-4 py-2 text-sm">{{ $party->party_name ?? '-' }}</td>
                                    <td class="px-4 py-2 text-sm">{{ $party->party_street ?? '-' }}</td>
                                    <td class="px-4 py-2 text-sm">{{ $party->party_city ?? '-' }}</td>
                                    <td class="px-4 py-2 text-sm">{{ $party->party_region ?? '-' }}</td>
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
                <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-gray-900">
                    <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Contactos del servicio
                </h2>

                <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tipo</th>
                                <th
                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nombre</th>
                                <th
                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Detalles</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($serviceContacts as $contact)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-2 whitespace-nowrap text-sm">
                                        {{ $contact->contact_type ? $form->catalogLabel($contact->contact_type) : '-' }}
                                    </td>
                                    <td class="px-4 py-2 text-sm">{{ $contact->contact_name ?? '-' }}</td>
                                    <td class="px-4 py-2 text-sm">
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
                <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-gray-900">
                    <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Fechas del servicio
                </h2>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($serviceDates as $date)
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                            <div class="mb-1 text-sm font-medium text-gray-700">
                                {{ $date->date_type ? $form->catalogLabel($date->date_type) : 'Fecha' }}
                            </div>
                            <div class="text-base font-semibold text-gray-900">
                                {{ $date->service_date ? \Carbon\Carbon::parse($date->service_date)->format('d/m/Y') : '-' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- PURCHASE ORDERS --}}
        @if ($form->service?->purchase_orders?->isNotEmpty())
            <section
                x-data="{ selectedPurchaseOrder: {{ (int) $form->service->purchase_orders->first()->id }} }"
                aria-labelledby="purchase-orders-title">
                <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                    <h2 id="purchase-orders-title"
                        class="flex items-center gap-2 text-lg font-semibold text-gray-900">
                        <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Órdenes de Compra
                    </h2>
                    <p class="text-sm text-gray-500">
                        {{ $form->service->purchase_orders->count() }}
                        {{ $form->service->purchase_orders->count() === 1 ? 'orden disponible' : 'órdenes disponibles' }}
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[17rem_minmax(0,1fr)] lg:items-start">
                    <nav class="rounded-xl border border-gray-200 bg-white shadow-sm"
                        aria-label="Órdenes de compra del servicio">
                        <div class="border-b border-gray-200 px-4 py-3">
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Seleccionar orden</p>
                        </div>

                        <div class="flex gap-2 overflow-x-auto p-3 lg:max-h-[70vh] lg:flex-col lg:overflow-y-auto">
                            @foreach ($form->service->purchase_orders as $index => $po)
                                <button type="button"
                                    x-on:click="selectedPurchaseOrder = {{ (int) $po->id }}"
                                    x-bind:aria-current="selectedPurchaseOrder === {{ (int) $po->id }} ? 'true' : 'false'"
                                    x-bind:class="selectedPurchaseOrder === {{ (int) $po->id }}
                                        ? 'border-indigo-600 bg-indigo-50 text-indigo-900 shadow-sm'
                                        : 'border-gray-200 bg-white text-gray-700 hover:border-indigo-300 hover:bg-gray-50'"
                                    class="min-w-[14rem] rounded-lg border px-4 py-3 text-left transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 lg:min-w-0 lg:w-full">
                                    <span class="block truncate text-sm font-semibold">
                                        Orden {{ $index + 1 }} # {{ $po->purchase_order_number ?? 'Sin número' }}
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    </nav>

                    <div class="min-w-0">
                        @foreach ($form->service->purchase_orders as $index => $po)
                            <article x-show="selectedPurchaseOrder === {{ (int) $po->id }}" x-cloak
                                x-transition.opacity.duration.150ms
                                aria-labelledby="purchase-order-{{ $po->id }}-title"
                                style="display: none;"
                                class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-5">
                        <div class="mb-4 flex flex-col gap-3 border-b border-gray-200 pb-4 lg:flex-row lg:items-center lg:justify-between">
                            <h3 id="purchase-order-{{ $po->id }}-title"
                                class="text-lg font-semibold text-gray-900">
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
                                <h4 class="mb-2 text-sm font-semibold text-gray-900">Partes de la Orden</h4>
                                <div class="overflow-x-auto rounded-lg border border-gray-200">
                                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                    Tipo</th>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                    Ubicación</th>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                    Dirección</th>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                    Ciudad</th>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
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
                                <h4 class="mb-2 text-sm font-semibold text-gray-900">Contactos de la Orden</h4>
                                <div class="overflow-x-auto rounded-lg border border-gray-200">
                                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                    Tipo</th>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                    Nombre</th>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
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
                                <h4 class="mb-2 text-sm font-semibold text-gray-900">Especificaciones de la Orden</h4>
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
                                <h4 class="mb-2 text-sm font-semibold text-gray-900">Mediciones de la Orden</h4>
                                <div class="overflow-x-auto rounded-lg border border-gray-200">
                                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                    Tipo</th>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
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
                                <h4 class="mb-2 text-sm font-semibold text-gray-900">Requerimientos de la Orden</h4>
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
                                <h4 class="mb-2 text-sm font-semibold text-gray-900">Términos de Entrega</h4>
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
                                <h4 class="mb-2 text-sm font-semibold text-gray-900">Cargos de Transporte</h4>
                                <div class="overflow-x-auto rounded-lg border border-gray-200">
                                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                    Tipo de Cargo</th>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                    Precio Declarado</th>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                    Precio Unitario</th>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
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
                            <div class="mt-4" x-data="{ itemSearch: '', expandedItem: null }">
                                <div class="mb-3 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <h4 class="text-sm font-semibold text-gray-900">Items de la Orden</h4>

                                    <label class="relative block w-full sm:max-w-xs">
                                        <span class="sr-only">Buscar Item</span>
                                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="m21 21-4.35-4.35m1.35-5.65a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                                        </svg>
                                        <input type="search" x-model.debounce.200ms="itemSearch"
                                            placeholder="Buscar Item, descripción o contenedor"
                                            class="w-full rounded-lg border-gray-300 py-2 pl-9 pr-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </label>
                                </div>

                                <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                                    <table class="min-w-[72rem] w-full divide-y divide-gray-200 text-sm">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                    Item
                                                </th>
                                                <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                    Descripción
                                                </th>
                                                <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                    Peso
                                                </th>
                                                <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                    Volumen
                                                </th>
                                                <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                    Unidades
                                                </th>
                                                <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                    Dimensiones (L/A/A)
                                                </th>
                                                <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                    Contenedor
                                                </th>
                                                <th class="px-3 py-2 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                    Acción
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 bg-white">
                                            @foreach ($poItems as $itemIndex => $item)
                                                @php
                                                    $itemNumber = $item->item_number ?? $itemIndex + 1;
                                                    $itemNotes = $item->item_notes
                                                        ?->filter(fn($note) => !$isBlank($note->note_text ?? null))
                                                        ->values() ?? collect();
                                                    $itemDescription = trim((string) ($itemNotes->first()?->note_text ?? ''));

                                                    $summaryMeasures = $item->item_measures
                                                        ?->filter(
                                                            fn($measure) => !$isBlank(
                                                                $measure->measurement_value ?? ($measure->measure_value ?? null),
                                                            ),
                                                        )
                                                        ->values() ?? collect();
                                                    $weightMeasures = $summaryMeasures->filter(function ($measure) {
                                                        $attributeCode = strtoupper(
                                                            trim(
                                                                (string) ($measure->measurement_attribute_code
                                                                    ?->measurement_attribute_code ?? ''),
                                                            ),
                                                        );
                                                        $purposeCode = strtoupper(
                                                            trim(
                                                                (string) ($measure->measurement_purpose_code
                                                                    ?->measurement_purpose_code ?? ''),
                                                            ),
                                                        );

                                                        return in_array($attributeCode, ['AAC', 'AAD'], true) ||
                                                            $purposeCode === 'WT';
                                                    });
                                                    $volumeMeasures = $summaryMeasures->filter(function ($measure) {
                                                        $attributeCode = strtoupper(
                                                            trim(
                                                                (string) ($measure->measurement_attribute_code
                                                                    ?->measurement_attribute_code ?? ''),
                                                            ),
                                                        );
                                                        $purposeCode = strtoupper(
                                                            trim(
                                                                (string) ($measure->measurement_purpose_code
                                                                    ?->measurement_purpose_code ?? ''),
                                                            ),
                                                        );

                                                        return $attributeCode === 'AAW' || $purposeCode === 'VOL';
                                                    });
                                                    $formatSummaryMeasure = function ($measure): string {
                                                        if (!$measure) {
                                                            return '-';
                                                        }

                                                        $value = $measure->measurement_value ?? ($measure->measure_value ?? null);
                                                        $formattedValue =
                                                            $value !== null
                                                                ? rtrim(
                                                                    rtrim(number_format((float) $value, 3, ',', '.'), '0'),
                                                                    ',',
                                                                )
                                                                : '-';
                                                        $unit =
                                                            $measure->measure_unit_code ??
                                                            ($measure->measurement_unit ?? ($measure->measure_unit ?? ''));

                                                        return trim($formattedValue . ' ' . $unit);
                                                    };
                                                    $weightSummary = $formatSummaryMeasure($weightMeasures->first());
                                                    if ($weightMeasures->count() > 1) {
                                                        $weightSummary .= ' +' . ($weightMeasures->count() - 1);
                                                    }
                                                    $volumeSummary = $formatSummaryMeasure($volumeMeasures->first());
                                                    if ($volumeMeasures->count() > 1) {
                                                        $volumeSummary .= ' +' . ($volumeMeasures->count() - 1);
                                                    }

                                                    $summaryDimensions = $item->item_dimensions
                                                        ?->filter(
                                                            fn($dimension) => !$isBlank($dimension->length ?? null) ||
                                                                !$isBlank($dimension->width ?? null) ||
                                                                !$isBlank($dimension->height ?? null),
                                                        )
                                                        ->values() ?? collect();
                                                    $firstDimension = $summaryDimensions->first();
                                                    $dimensionSummary = $firstDimension
                                                        ? implode(' / ', [
                                                            $firstDimension->length ?? '-',
                                                            $firstDimension->width ?? '-',
                                                            $firstDimension->height ?? '-',
                                                        ]) .
                                                            ($firstDimension->dimension_unit
                                                                ? ' ' . $firstDimension->dimension_unit
                                                                : '')
                                                        : '-';
                                                    if ($summaryDimensions->count() > 1) {
                                                        $dimensionSummary .= ' +' . ($summaryDimensions->count() - 1);
                                                    }

                                                    $summaryContainers = $item->item_container_identifiers
                                                        ?->filter(
                                                            fn($container) => !$isBlank(
                                                                $container->package_identifier_value ?? null,
                                                            ),
                                                        )
                                                        ->values() ?? collect();
                                                    $containerSummary =
                                                        $summaryContainers->first()?->package_identifier_value ?? '-';
                                                    if ($summaryContainers->count() > 1) {
                                                        $containerSummary .= ' +' . ($summaryContainers->count() - 1);
                                                    }

                                                    $unitsSummary = trim(
                                                        (string) ($item->item_count ?? '-') .
                                                            ' ' .
                                                            (string) ($item->item_type ?? ''),
                                                    );
                                                    $itemSearchText = strtolower(
                                                        trim(
                                                            implode(' ', [
                                                                $itemNumber,
                                                                $itemDescription,
                                                                $containerSummary,
                                                            ]),
                                                        ),
                                                    );
                                                @endphp

                                                <tr x-show="itemSearch === '' || @js($itemSearchText).includes(itemSearch.toLowerCase())"
                                                    class="hover:bg-gray-50">
                                                    <td class="whitespace-nowrap px-3 py-3 font-semibold text-gray-900">
                                                        {{ $itemNumber }}
                                                    </td>
                                                    <td class="max-w-xs px-3 py-3 text-gray-700">
                                                        <span class="block truncate" title="{{ $itemDescription }}">
                                                            {{ $itemDescription !== '' ? $itemDescription : '-' }}
                                                        </span>
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-3 text-gray-700">
                                                        {{ $weightSummary }}
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-3 text-gray-700">
                                                        {{ $volumeSummary }}
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-3 text-gray-700">
                                                        {{ $unitsSummary }}
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-3 text-gray-700">
                                                        {{ $dimensionSummary }}
                                                    </td>
                                                    <td class="max-w-xs px-3 py-3 text-gray-700">
                                                        <span class="block truncate" title="{{ $containerSummary }}">
                                                            {{ $containerSummary }}
                                                        </span>
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-3 text-right">
                                                        <button type="button"
                                                            x-on:click="expandedItem = expandedItem === {{ (int) $item->id }} ? null : {{ (int) $item->id }}"
                                                            x-bind:aria-expanded="expandedItem === {{ (int) $item->id }}"
                                                            class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 shadow-sm transition hover:border-indigo-300 hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                                            <span x-text="expandedItem === {{ (int) $item->id }} ? 'Ocultar' : 'Ver detalle'"></span>
                                                        </button>
                                                    </td>
                                                </tr>

                                                <tr x-show="expandedItem === {{ (int) $item->id }} && (itemSearch === '' || @js($itemSearchText).includes(itemSearch.toLowerCase()))"
                                                    x-cloak style="display: none;">
                                                    <td colspan="8" class="bg-gray-50 p-4 sm:p-5">
                                                        <div class="rounded-lg border border-gray-200 bg-white p-4">
                                                            <h5 class="mb-3 text-sm font-semibold text-gray-900">
                                                                Detalle del Item #{{ $itemNumber }}
                                                            </h5>

                                                            {{-- Item Principal Info --}}
                                                            <div class="mb-3">
                                                                <div class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                                    Información general
                                                                </div>
                                                                <dl class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                                                                    <div>
                                                                        <dt class="text-xs text-gray-600">Número de Item</dt>
                                                                        <dd class="text-sm font-medium text-gray-900">{{ $itemNumber }}</dd>
                                                                    </div>
                                                                    <div>
                                                                        <dt class="text-xs text-gray-600">Unidades</dt>
                                                                        <dd class="text-sm font-medium text-gray-900">{{ $unitsSummary }}</dd>
                                                                    </div>
                                                                    <div class="sm:col-span-3">
                                                                        <dt class="text-xs text-gray-600">Descripción</dt>
                                                                        <dd class="text-sm font-medium text-gray-900">
                                                                            {{ $itemDescription !== '' ? $itemDescription : '-' }}
                                                                        </dd>
                                                                    </div>
                                                                </dl>
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
                                                <div class="mb-1 text-xs font-semibold text-gray-700">Descripción</div>
                                                <div class="space-y-1">
                                                    @foreach ($itemNotes as $itemNote)
                                                        <div
                                                            class="rounded border border-gray-200 bg-white p-2 text-xs">
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
                                                <div class="mb-1 text-xs font-semibold text-gray-700">Medidas</div>
                                                <div class="overflow-x-auto rounded-lg border border-gray-200">
                                                    <table class="min-w-full text-xs">
                                                        <thead class="bg-gray-50">
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
                                                <div class="mb-1 text-xs font-semibold text-gray-700">Dimensiones</div>
                                                <div class="overflow-x-auto rounded-lg border border-gray-200">
                                                    <table class="min-w-full text-xs">
                                                        <thead class="bg-gray-50">
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
                                                            class="rounded border border-gray-200 bg-white p-2 text-xs">
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
                                                <div class="overflow-x-auto rounded-lg border border-gray-200">
                                                    <table class="min-w-full text-xs">
                                                        <thead class="bg-gray-50">
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
                                                return !$isBlank(
                                                    $unitId->unit_identifier_value ??
                                                        ($unitId->identifier_value_from ?? null),
                                                );
                                            });
                                        @endphp
                                        @if ($itemUnitIds?->isNotEmpty())
                                            <div class="mt-3">
                                                <div class="text-xs font-semibold text-gray-700 mb-1">Identificadores
                                                    de Unidad</div>
                                                <div class="space-y-1">
                                                    @foreach ($itemUnitIds as $unitId)
                                                        <div
                                                            class="rounded border border-gray-200 bg-white p-2 text-xs">
                                                            <span class="font-medium text-gray-600">
                                                                {{ $this->getUnitIdentifierLabel($unitId->unit_identifier_type ?? '') }}:
                                                            </span>
                                                            <span
                                                                class="text-gray-900">{{ $unitId->unit_identifier_value ?? ($unitId->identifier_value_from ?? '-') }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- LOCATION DETAILS --}}
        @php
            $locationDetails = $form->service?->location_details?->filter(function ($location) use ($isBlank) {
                return !$isBlank($location->location_details ?? null);
            });
        @endphp
        @if ($locationDetails?->isNotEmpty())
            <div>
                <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-gray-900">
                    <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Detalles de Ubicación
                </h2>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    @foreach ($locationDetails as $location)
                        <div class="rounded-lg border border-gray-200 bg-white p-4">
                            <div class="mb-2 text-sm font-medium text-gray-700">
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
                <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-gray-900">
                    <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Detalles de Transporte
                </h2>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Etapa</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Modo</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
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
                <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-gray-900">
                    <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Equipos
                </h2>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($serviceEquipments as $equipment)
                        <div class="rounded-lg border border-gray-200 bg-white p-4">
                            <div class="mb-1 text-sm font-medium text-gray-700">
                                {{ $equipment->equipment_type ? $form->catalogLabel($equipment->equipment_type) : 'Equipo' }}
                            </div>
                            <div class="text-base font-semibold text-gray-900">
                                {{ $equipment->equipment_identification ?? '-' }}
                            </div>
                            @if ($equipment->equipment_size_type)
                                <div class="mt-1 text-xs text-gray-600">Tamaño: {{ $equipment->equipment_size_type }}
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
                <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-gray-900">
                    <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Soportes Cargados
                </h2>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Nombre del Archivo
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
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
                                                rel="noopener noreferrer" class="font-medium text-indigo-600 hover:text-indigo-800 hover:underline">
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
