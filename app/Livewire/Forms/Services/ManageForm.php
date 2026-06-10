<?php

namespace App\Livewire\Forms\Services;

use App\Models\Container;
use App\Models\Operator;
use App\Models\Resource;
use App\Models\Service;
use App\Models\ServiceResourceReport;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Form;

class ManageForm extends Form
{
    public bool $canEdit = false;
    public ?Service $service = null;

    public ?int $id = null;
    public ?string $consecutive = null;

    public ?string $item = null;
    public ?string $observation = null;

    public $ttcol_value = null;
    public $cargo_value = null;
    public ?string $driver = null;
    public $advance_payment = null;

    /**
     * status del Service
     */
    public ?int $service_status_id = null;

    /**
     * Snapshot original para detectar cambios reales
     */
    public ?int $original_service_status_id = null;

    /**
     * Recurso seleccionado (dropdown)
     */
    public ?int $service_resource_id = null;

    /**
     * Filas de recursos del servicio.
     *
     * @var array<int, array{
     *     row_key:string,
     *     pivot_id:int|null,
     *     resource_id:int,
     *     required_report_mask:int,
     *     resource_operation:string
     * }>
     */
    public array $service_resource_rows = [];

    /**
     * Snapshot original de recursos.
     *
     * @var array<int, array{
     *     row_key:string,
     *     pivot_id:int|null,
     *     resource_id:int,
     *     required_report_mask:int,
     *     resource_operation:string
     * }>
     */
    public array $original_service_resource_rows = [];

    /**
     * Borradores de información adicional, aislados por fila de recurso.
     *
     * @var array<string, array<string, mixed>>
     */
    public array $additional_information = [];

    public ?string $active_resource_row_key = null;

    /**
     * Flag para limpiar el dropdown de recursos luego de guardar
     */
    public bool $reset_resource_selection = false;

    /**
     * IDs de Purchase Orders (CNI) que cambiaron en esta edición
     * (lo usas luego en el botón "Enviar" para generar IFTSTA SOLO por esos CNIs)
     *
     * @var array<int, int>
     */
    public array $dirty_purchase_order_ids = [];

    public array $omit = [
        'segment_tag',
        'raw_segment',
        'created_at',
        'updated_at',
        'update_at',
        'deleted_at',
    ];

    public function mount(Service $service): void
    {
        $user = Auth::user();

        // Permitir edición solo a usuarios con rol admin o coord
        $this->canEdit =
            ($user?->rol_key === 'admin' || $user?->rol_key === 'coord')
            || ($user && method_exists($user, 'hasRole') && ($user->hasRole('admin') || $user->hasRole('coord')));

        $this->service = Service::query()
            ->with([
                // Service
                'status',
                'service_parties.party_type',
                'service_contacts.contact_type',
                'service_contacts.service_contact_details',
                'service_dates' => fn($query) => $query->orderBy('service_date'),
                'service_dates.date_type',
                'service_equipments.equipment_type',
                'service_measurements.global_measure_type',
                'support_files.file_type',

                'location_details.location_code',

                'transport_details.transport_stage',
                'transport_details.transport_mode',

                // Service
                'resources',
                'service_resource_rows.report.vehicle',
                'service_resource_rows.report.operator',
                'service_resource_rows.report.container',

                // Purchase Orders (CNIs)
                'purchase_orders',
                'purchase_orders.order_references.reference_type',

                // Delivery terms
                'purchase_orders.delivery_terms.delivery_term_catalog',

                // Parties / contacts
                'purchase_orders.purchase_order_parties.party_type',
                'purchase_orders.purchase_order_contacts.contact_type',
                'purchase_orders.purchase_order_contacts.purchase_order_contact_details',

                // Notes
                'purchase_orders.purchase_order_notes.note_type',

                // Measurements
                'purchase_orders.purchase_order_measurements.global_measure_type',

                // Requirements
                'purchase_orders.purchase_order_requirements',

                // Charges
                'purchase_orders.transport_charges.price_qualifier',

                // Items
                'purchase_orders.purchase_order_items',
                'purchase_orders.purchase_order_items.item_notes.note_type',
                'purchase_orders.purchase_order_items.item_measures.measurement_attribute_code',
                'purchase_orders.purchase_order_items.item_measures.measurement_purpose_code',
                'purchase_orders.purchase_order_items.item_dimensions.dimension_type',
                'purchase_orders.purchase_order_items.item_container_identifiers.identifier_type',
                'purchase_orders.purchase_order_items.item_product_identifiers.product_identifier_role',
                'purchase_orders.purchase_order_items.item_product_identifiers.product_identifier_type',
                'purchase_orders.purchase_order_items.item_unit_identifiers',
            ])
            ->findOrFail($service->id);

        $this->fillFromService($this->service);
    }

    public function fillFromService(Service $s): void
    {
        $this->id = $s->id;
        $this->consecutive = (string) $s->consecutive;

        $item = $s->item;
        if (is_string($item)) {
            $item = preg_replace('/^\d{2}-/u', '', $item);
        }
        $this->item = $item;
        $this->observation = $s->observation;

        $this->ttcol_value = $s->ttcol_value;
        $this->cargo_value = $s->cargo_value;
        $this->driver = $s->driver;
        $this->advance_payment = $s->advance_payment;

        // Estado del servicio
        $this->service_status_id = $s->status_id !== null ? (int) $s->status_id : null;
        $this->original_service_status_id = $this->service_status_id;

        // Recursos a nivel de servicio, preservando filas repetidas del pivote.
        $serviceResourceRows = $s->service_resource_rows?->keyBy('id') ?? collect();
        $additionalInformation = [];

        $rows = $s->resources
            ?->map(function ($resource) use ($serviceResourceRows, &$additionalInformation) {
                $pivotId = $resource->pivot?->id !== null ? (int) $resource->pivot->id : null;
                $rowKey = $pivotId !== null ? "pivot-{$pivotId}" : (string) Str::uuid();
                $report = $pivotId !== null ? $serviceResourceRows->get($pivotId)?->report : null;

                $additionalInformation[$rowKey] = $this->additionalInformationFromReport($report);

                return [
                    'row_key' => $rowKey,
                    'pivot_id' => $pivotId,
                    'resource_id' => (int) $resource->id,
                    'required_report_mask' => (int) $resource->required_report_mask,
                    'resource_operation' => (string) $resource->resource_operation,
                ];
            })
            ->values()
            ->all() ?? [];
        $this->service_resource_rows = $rows;
        $this->original_service_resource_rows = $rows;
        $this->additional_information = $additionalInformation;
        $this->active_resource_row_key = null;
        // Siempre dejar el dropdown limpio al cargar/recargar
        $this->service_resource_id = null;
        $this->reset_resource_selection = false;

        // Reset de "dirty" cuando recargas
        $this->dirty_purchase_order_ids = [];
    }

    public function rules(): array
    {
        return [
            'item' => ['nullable', 'string', 'max:16'],
            'observation' => ['nullable', 'string', 'max:2000'],
            'ttcol_value' => ['nullable', 'numeric', 'min:0'],
            'cargo_value' => ['nullable', 'numeric', 'min:0'],
            'driver' => ['nullable', 'string', 'max:64'],
            'advance_payment' => ['nullable', 'numeric', 'min:0'],

            // Permite "Sin estado" (null). Si eliges un valor, debe existir en statuses.
            'service_status_id' => ['nullable', 'integer', 'exists:statuses,id'],

            'service_resource_id' => ['nullable', 'integer', 'exists:resources,id'],
            'service_resource_rows' => ['array'],
            'service_resource_rows.*.row_key' => ['required', 'string'],
            'service_resource_rows.*.pivot_id' => ['nullable', 'integer'],
            'service_resource_rows.*.resource_id' => ['integer', 'exists:resources,id'],
            'service_resource_rows.*.required_report_mask' => ['integer', 'min:0'],
            'service_resource_rows.*.resource_operation' => ['required', 'string'],
            'additional_information' => ['array'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'string' => 'El campo :attribute debe ser texto.',
            'integer' => 'El campo :attribute debe ser un número entero.',
            'numeric' => 'El campo :attribute debe ser numérico.',
            'array' => 'El campo :attribute debe ser una lista válida.',
            'exists' => 'El valor seleccionado para :attribute no es válido.',
            'max' => 'El campo :attribute no debe superar :max caracteres.',
            'min' => 'El campo :attribute debe ser al menos :min.',
        ];
    }

    public function addServiceResource(): void
    {
        if ($this->service_resource_id === null) {
            return;
        }

        $this->addResourceById((int) $this->service_resource_id);
        $this->service_resource_id = null;
    }

    /**
     * Agrega un recurso por ID pasado directamente como argumento.
     * Usado desde Alpine/JS para evitar race conditions con wire:model.
     */
    public function addResourceById(int $id): void
    {
        if ($id <= 0) {
            return;
        }

        $resource = Resource::query()->find($id);

        if (!$resource) {
            return;
        }

        $rowKey = 'new-' . Str::uuid();

        $this->service_resource_rows[] = [
            'row_key' => $rowKey,
            'pivot_id' => null,
            'resource_id' => $id,
            'required_report_mask' => (int) $resource->required_report_mask,
            'resource_operation' => (string) $resource->resource_operation,
        ];
        $this->additional_information[$rowKey] = $this->emptyAdditionalInformation();
    }

    public function removeServiceResource(int $resourceIndex): void
    {
        if (!array_key_exists($resourceIndex, $this->service_resource_rows)) {
            return;
        }

        $rowKey = (string) data_get($this->service_resource_rows[$resourceIndex], 'row_key', '');

        unset($this->service_resource_rows[$resourceIndex]);
        $this->service_resource_rows = array_values($this->service_resource_rows);

        if ($rowKey !== '') {
            unset($this->additional_information[$rowKey]);
        }

        if ($this->active_resource_row_key === $rowKey) {
            $this->active_resource_row_key = null;
        }
    }

    public function openAdditionalInformation(string $rowKey): void
    {
        if (!$this->requiresAdditionalInformationForRow($rowKey)) {
            return;
        }

        $this->active_resource_row_key = $rowKey;
    }

    public function closeAdditionalInformation(): void
    {
        $this->active_resource_row_key = null;
    }

    public function clearAdditionalInformation(string $rowKey): void
    {
        abort_unless($this->canEdit, 403, 'No tienes permisos para editar este servicio.');

        if (!$this->resourceRow($rowKey)) {
            return;
        }

        $this->additional_information[$rowKey] = $this->emptyAdditionalInformation();
        $this->resetValidation("additional_information.{$rowKey}");
    }

    public function updateAdditionalInformation(string $rowKey): void
    {
        abort_unless($this->canEdit, 403, 'No tienes permisos para editar este servicio.');

        $row = $this->resourceRow($rowKey);
        $reportId = (int) data_get($this->additional_information, "{$rowKey}.report_id", 0);

        abort_unless(
            $row && data_get($row, 'pivot_id') && $reportId > 0,
            404,
            'La información adicional registrada no fue encontrada.',
        );

        $this->validateAdditionalInformation($rowKey);

        DB::transaction(function () use ($row, $rowKey, $reportId) {
            $service = Service::query()->findOrFail($this->id);
            $pivotId = (int) $row['pivot_id'];

            $belongsToService = DB::table('service_resource')
                ->where('id', $pivotId)
                ->where('service_id', $service->id)
                ->where('resource_id', (int) $row['resource_id'])
                ->exists();

            abort_unless($belongsToService, 404, 'El recurso no pertenece a este servicio.');

            $report = ServiceResourceReport::query()
                ->whereKey($reportId)
                ->where('service_resource_id', $pivotId)
                ->where('service_id', $service->id)
                ->where('resource_id', (int) $row['resource_id'])
                ->lockForUpdate()
                ->first();

            abort_unless($report, 404, 'La información adicional registrada no fue encontrada.');

            $report = $this->persistAdditionalInformationRow($service, $row, $report);
            $this->additional_information[$rowKey]['report_id'] = (int) $report->id;
        });
    }

    public function requiresAdditionalInformationForRow(string $rowKey): bool
    {
        return $this->resourceForRow($rowKey)?->requiresAdditionalInformation() ?? false;
    }

    /**
     * @return array{vehicle:bool,operator:bool,remittance:bool,container:bool}
     */
    public function requirementsForRow(string $rowKey): array
    {
        $resource = $this->resourceForRow($rowKey);

        return [
            'vehicle' => $resource?->requiresVehicle() ?? false,
            'operator' => $resource?->requiresOperator() ?? false,
            'remittance' => $resource?->requiresRemittance() ?? false,
            'container' => $resource?->requiresContainer() ?? false,
        ];
    }

    /**
     * @return array{role:string,identification:string,first_name:string,last_name:string}
     */
    public function operatorLabelsForRow(string $rowKey): array
    {
        $isTransport = strtoupper((string) data_get(
            $this->resourceRow($rowKey),
            'resource_operation',
        )) === 'TRANSPORTE';
        $role = $isTransport ? 'Conductor' : 'Operador';

        return [
            'role' => $role,
            'identification' => "Identificación del {$role}",
            'first_name' => "Nombre(s) del {$role}",
            'last_name' => "Apellido(s) del {$role}",
        ];
    }

    public function additionalInformationStatus(string $rowKey): string
    {
        if (!$this->requiresAdditionalInformationForRow($rowKey)) {
            return 'not_applicable';
        }

        if (!$this->isAdditionalInformationComplete($rowKey)) {
            return 'pending';
        }

        return filled(data_get($this->additional_information, "{$rowKey}.report_id"))
            ? 'registered'
            : 'complete';
    }

    public function isAdditionalInformationComplete(string $rowKey): bool
    {
        $requirements = $this->requirementsForRow($rowKey);
        $draft = $this->additional_information[$rowKey] ?? [];

        if ($requirements['vehicle'] && blank(data_get($draft, 'vehicle_plate'))) {
            return false;
        }

        if ($requirements['operator']) {
            foreach (['operator_identification', 'operator_first_name', 'operator_last_name'] as $field) {
                if (blank(data_get($draft, $field))) {
                    return false;
                }
            }
        }

        if ($requirements['remittance'] && blank(data_get($draft, 'remesa_transporte'))) {
            return false;
        }

        if ($requirements['container'] && blank(data_get($draft, 'container_number'))) {
            return false;
        }

        return true;
    }

    /**
     * Si quieres marcar "dirty" desde la vista con wire:change, usa esto.
     * Igual update() detecta cambios por sí solo, pero esto ayuda si quieres UI más reactiva.
     */
    public function markPurchaseOrderDirty(int $purchaseOrderId): void
    {
        if (!in_array($purchaseOrderId, $this->dirty_purchase_order_ids, true)) {
            $this->dirty_purchase_order_ids[] = $purchaseOrderId;
        }
    }

    public function update(): array
    {
        abort_unless($this->canEdit, 403, 'No tienes permisos para editar este servicio.');

        $this->validate();
        $this->validateAdditionalInformation();

        $dirtyIds = [];

        DB::transaction(function () use (&$dirtyIds) {
            $s = Service::query()->findOrFail($this->id);

            $rawItem = trim((string) ($this->item ?? ''));
            if ($rawItem !== '') {
                $rawItem = preg_replace('/^\d{2}-/u', '', $rawItem);
                $rawItem = preg_replace('/^mes-/i', '', $rawItem);
                $rawItem = ltrim($rawItem, '-');
            }
            $monthPrefix = $s->created_at?->format('m') ?? now()->format('m');
            $normalizedItem = $rawItem !== '' ? ($monthPrefix . '-' . $rawItem) : null;

            $s->fill([
                'item' => $normalizedItem,
                'observation' => $this->observation,
                'ttcol_value' => $this->ttcol_value,
                'cargo_value' => $this->cargo_value,
                'driver' => $this->driver,
                'advance_payment' => $this->advance_payment,
            ]);

            $s->save();

            // Cambio de estado a nivel de servicio
            $newStatusId = $this->service_status_id !== null ? (int) $this->service_status_id : null;
            $oldStatusId = $this->original_service_status_id !== null ? (int) $this->original_service_status_id : null;

            if ($newStatusId !== $oldStatusId) {
                $s->status_id = $newStatusId;
                $s->save();

                // Si cambia el estado del servicio, marcar todos los CNI para IFTSTA
                $dirtyIds = array_merge(
                    $dirtyIds,
                    $s->purchase_orders?->pluck('id')->map(fn($id) => (int) $id)->all() ?? []
                );
            }

            // Detectar cambios reales de recurso a nivel de servicio, preservando duplicados.
            $normalizeResourceRows = function (array $rows): array {
                return array_values(array_filter(array_map(function ($row) {
                    $resourceId = (int) data_get($row, 'resource_id', 0);

                    if ($resourceId <= 0) {
                        return null;
                    }

                    $pivotId = data_get($row, 'pivot_id');

                    return [
                        'row_key' => (string) data_get($row, 'row_key'),
                        'pivot_id' => $pivotId !== null ? (int) $pivotId : null,
                        'resource_id' => $resourceId,
                        'required_report_mask' => (int) data_get($row, 'required_report_mask', 0),
                        'resource_operation' => (string) data_get($row, 'resource_operation', ''),
                    ];
                }, $rows)));
            };

            $newResourceRows = $normalizeResourceRows($this->service_resource_rows);
            $oldResourceRows = $normalizeResourceRows($this->original_service_resource_rows);
            $comparableRows = fn(array $rows) => array_map(
                fn($row) => [
                    'pivot_id' => $row['pivot_id'],
                    'resource_id' => $row['resource_id'],
                ],
                $rows,
            );

            if ($comparableRows($newResourceRows) !== $comparableRows($oldResourceRows)) {
                $pivotIdsToKeep = array_values(array_filter(array_map(
                    fn($row) => $row['pivot_id'],
                    $newResourceRows
                )));

                $pivotIdsToDelete = array_values(array_filter(array_map(
                    fn($row) => $row['pivot_id'],
                    $oldResourceRows
                ), fn($pivotId) => !in_array($pivotId, $pivotIdsToKeep, true)));

                if ($pivotIdsToDelete !== []) {
                    DB::table('service_resource')
                        ->where('service_id', $s->id)
                        ->whereIn('id', $pivotIdsToDelete)
                        ->delete();
                }

                foreach ($newResourceRows as &$row) {
                    if ($row['pivot_id'] !== null) {
                        continue;
                    }

                    $row['pivot_id'] = (int) DB::table('service_resource')->insertGetId([
                        'service_id' => $s->id,
                        'resource_id' => $row['resource_id'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                unset($row);

                // Si cambia recurso, marcar todos los CNI para IFTSTA
                $dirtyIds = array_merge(
                    $dirtyIds,
                    $s->purchase_orders?->pluck('id')->map(fn($id) => (int) $id)->all() ?? []
                );
            }

            $this->persistAdditionalInformation($s, $newResourceRows);

            // Guarda “dirty” para que el botón Enviar sepa qué CNIs incluir en IFTSTA
            $dirtyIds = array_values(array_unique($dirtyIds));
            $this->dirty_purchase_order_ids = $dirtyIds;

            // Recarga todo (y refresca snapshot)
            $this->reset_resource_selection = true;
            $this->mount($s);
            $this->dirty_purchase_order_ids = $dirtyIds;
        });

        return $dirtyIds;
    }

    private function validateAdditionalInformation(?string $onlyRowKey = null): void
    {
        $rules = [];
        $attributes = [];

        foreach ($this->service_resource_rows as $row) {
            $rowKey = (string) data_get($row, 'row_key');

            if ($onlyRowKey !== null && $rowKey !== $onlyRowKey) {
                continue;
            }

            $requirements = $this->requirementsForRow($rowKey);
            $operatorLabels = $this->operatorLabelsForRow($rowKey);
            $prefix = "additional_information.{$rowKey}";

            if ($requirements['vehicle']) {
                $rules["{$prefix}.vehicle_plate"] = ['required', 'string', 'max:32'];
                $attributes["{$prefix}.vehicle_plate"] = 'placa del vehículo';
            }

            if ($requirements['operator']) {
                $rules["{$prefix}.operator_identification"] = ['required', 'string', 'max:64'];
                $rules["{$prefix}.operator_first_name"] = ['required', 'string', 'max:128'];
                $rules["{$prefix}.operator_last_name"] = ['required', 'string', 'max:128'];
                $attributes["{$prefix}.operator_identification"] = strtolower($operatorLabels['identification']);
                $attributes["{$prefix}.operator_first_name"] = strtolower($operatorLabels['first_name']);
                $attributes["{$prefix}.operator_last_name"] = strtolower($operatorLabels['last_name']);
            }

            if ($requirements['remittance']) {
                $rules["{$prefix}.remesa_transporte"] = ['required', 'string', 'max:128'];
                $attributes["{$prefix}.remesa_transporte"] = 'remesa de transporte';
            }

            if ($requirements['container']) {
                $rules["{$prefix}.container_number"] = ['required', 'string', 'max:64'];
                $attributes["{$prefix}.container_number"] = 'número de contenedor';
            }

        }

        if ($rules !== []) {
            $this->validate($rules, $this->messages(), $attributes);
        }
    }

    /**
     * @param array<int, array{
     *     row_key:string,
     *     pivot_id:int,
     *     resource_id:int,
     *     required_report_mask:int,
     *     resource_operation:string
     * }> $resourceRows
     */
    private function persistAdditionalInformation(Service $service, array $resourceRows): void
    {
        foreach ($resourceRows as $row) {
            $resource = new Resource([
                'required_report_mask' => $row['required_report_mask'],
            ]);

            if (!$resource->requiresAdditionalInformation()) {
                ServiceResourceReport::query()
                    ->where('service_resource_id', $row['pivot_id'])
                    ->delete();
                continue;
            }

            $this->persistAdditionalInformationRow($service, $row);
        }
    }

    /**
     * @param array{
     *     row_key:string,
     *     pivot_id:int,
     *     resource_id:int,
     *     required_report_mask:int,
     *     resource_operation:string
     * } $row
     */
    private function persistAdditionalInformationRow(
        Service $service,
        array $row,
        ?ServiceResourceReport $existingReport = null,
    ): ServiceResourceReport
    {
        $resource = new Resource([
            'required_report_mask' => $row['required_report_mask'],
        ]);
        $draft = $this->additional_information[$row['row_key']] ?? [];
        $vehicleId = $resource->requiresVehicle()
            ? $this->persistVehicle((string) data_get($draft, 'vehicle_plate'))
            : null;
        $operatorId = $resource->requiresOperator()
            ? $this->persistOperator($draft)
            : null;
        $containerId = $resource->requiresContainer()
            ? $this->persistContainer((string) data_get($draft, 'container_number'))
            : null;

        $report = $existingReport ?? ServiceResourceReport::query()->firstOrNew([
            'service_resource_id' => $row['pivot_id'],
        ]);

        if (!$report->exists) {
            $report->created_by = Auth::id();
        }

        $report->fill([
            'service_id' => $service->id,
            'resource_id' => $row['resource_id'],
            'vehicle_id' => $vehicleId,
            'operator_id' => $operatorId,
            'container_id' => $containerId,
            'remesa_transporte' => $resource->requiresRemittance()
                ? trim((string) data_get($draft, 'remesa_transporte'))
                : null,
            'updated_by' => Auth::id(),
        ]);
        $report->save();

        return $report;
    }

    private function persistVehicle(string $plate): int
    {
        $plate = Str::upper(trim($plate));
        $vehicle = Vehicle::withTrashed()->firstOrNew(['plate' => $plate]);

        if ($vehicle->trashed()) {
            $vehicle->restore();
        }

        $vehicle->save();

        return (int) $vehicle->id;
    }

    /**
     * @param array<string, mixed> $draft
     */
    private function persistOperator(array $draft): int
    {
        $identification = Str::upper(trim((string) data_get($draft, 'operator_identification')));
        $operator = Operator::withTrashed()->firstOrNew([
            'identification' => $identification,
        ]);

        if ($operator->trashed()) {
            $operator->restore();
        }

        $operator->fill([
            'first_name' => trim((string) data_get($draft, 'operator_first_name')),
            'last_name' => trim((string) data_get($draft, 'operator_last_name')),
        ]);
        $operator->save();

        return (int) $operator->id;
    }

    private function persistContainer(string $containerNumber): int
    {
        $containerNumber = Str::upper(trim($containerNumber));
        $container = Container::withTrashed()->firstOrNew([
            'container_number' => $containerNumber,
        ]);

        if ($container->trashed()) {
            $container->restore();
        }

        $container->save();

        return (int) $container->id;
    }

    /**
     * @return array<string, mixed>
     */
    private function additionalInformationFromReport(?ServiceResourceReport $report): array
    {
        if (!$report) {
            return $this->emptyAdditionalInformation();
        }

        return [
            'report_id' => (int) $report->id,
            'vehicle_plate' => $report->vehicle?->plate,
            'operator_identification' => $report->operator?->identification,
            'operator_first_name' => $report->operator?->first_name,
            'operator_last_name' => $report->operator?->last_name,
            'remesa_transporte' => $report->remesa_transporte,
            'container_number' => $report->container?->container_number,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function emptyAdditionalInformation(): array
    {
        return [
            'report_id' => null,
            'vehicle_plate' => null,
            'operator_identification' => null,
            'operator_first_name' => null,
            'operator_last_name' => null,
            'remesa_transporte' => null,
            'container_number' => null,
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    private function resourceRow(string $rowKey): ?array
    {
        foreach ($this->service_resource_rows as $row) {
            if ((string) data_get($row, 'row_key') === $rowKey) {
                return $row;
            }
        }

        return null;
    }

    private function resourceForRow(string $rowKey): ?Resource
    {
        $row = $this->resourceRow($rowKey);

        if (!$row) {
            return null;
        }

        return new Resource([
            'required_report_mask' => (int) data_get($row, 'required_report_mask', 0),
        ]);
    }

    public function visibleAttributes(Model $m): array
    {
        $attrs = $m->getAttributes();
        foreach ($this->omit as $bad) {
            unset($attrs[$bad]);
        }
        return $attrs;
    }

    public function catalogLabel(Model $catalog): string
    {
        $attrs = $catalog->getAttributes();

        if (array_key_exists('type_name', $attrs) && filled($catalog->type_name)) return (string) $catalog->type_name;
        if (array_key_exists('type_description', $attrs) && filled($catalog->type_description)) return (string) $catalog->type_description;

        foreach ($attrs as $k => $v) if (Str::endsWith($k, '_name') && filled($v)) return (string) $v;
        foreach ($attrs as $k => $v) if (Str::endsWith($k, '_description') && filled($v)) return (string) $v;

        return (string) ($catalog->id ?? '-');
    }

    public function displayValue(Model $m, string $key, mixed $value): string
    {
        if (Str::endsWith($key, '_id')) {
            $base = Str::beforeLast($key, '_id');
            foreach ([$base, Str::camel($base)] as $rel) {
                $rels = $m->getRelations();
                if (array_key_exists($rel, $rels) && $rels[$rel] instanceof Model) {
                    return $this->catalogLabel($rels[$rel]);
                }
            }
        }

        if (is_bool($value)) return $value ? 'Sí' : 'No';
        if ($value === null) return '-';

        return (string) $value;
    }

    public function columnsFor($collection): array
    {
        $first = $collection?->first();
        if (!$first instanceof Model) return [];
        return array_keys($this->visibleAttributes($first));
    }

    public function items()
    {
        return $this->service?->purchase_orders?->flatMap(fn($po) => $po->purchase_order_items ?? collect()) ?? collect();
    }
}
