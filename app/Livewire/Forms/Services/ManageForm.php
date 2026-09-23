<?php

namespace App\Livewire\Forms\Services;

use App\Models\Container;
use App\Models\OperationConcept;
use App\Models\Operator;
use App\Models\Origin;
use App\Models\Resource;
use App\Models\Service;
use App\Models\ServiceResourceReport;
use App\Models\ServiceResourceReportLine;
use App\Models\ServiceResourceReportPersonnel;
use App\Models\Vehicle;
use App\Services\Geodis\OriginNormalizer;
use App\Services\Tariffs\TariffResolver;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Form;

class ManageForm extends Form
{
    private const TRANSPORT_OPERATION = 'TRANSPORTE';

    private const AUTHORIZED_COST_OPERATION = 'COSTO_AUTORIZADO';

    private const OPERATIONS_WITH_LINES = [
        self::TRANSPORT_OPERATION,
        'OCONCEPTOS',
        'IZAJES',
        'COSTO_AUTORIZADO',
    ];

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
    public ?string $positioning_date = null;
    public ?string $arrival_date = null;

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
     *     resource_operation:string,
     *     resource_operation_id:int|null,
     *     personnel_requirements:array<int, array<string, mixed>>
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
     *     resource_operation:string,
     *     resource_operation_id:int|null,
     *     personnel_requirements:array<int, array<string, mixed>>
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

    /**
     * Last persisted change summary, used by the parent component to decide
     * whether an IFTSTA report is needed.
     *
     * @var array{status_changed:bool,resource_added:bool,resource_removed:bool}
     */
    public array $last_update_changes = [
        'status_changed' => false,
        'resource_added' => false,
        'resource_removed' => false,
    ];

    /**
     * IDs de la tabla pivote service_resource insertados en el guardado actual.
     *
     * @var array<int, int>
     */
    public array $last_added_service_resource_ids = [];

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
                'resources.operation',
                'resources.personnelRequirements.personnelRole',
                'service_resource_rows.report.vehicle',
                'service_resource_rows.report.container',
                'service_resource_rows.report.personnel.operator',
                'service_resource_rows.report.personnel.personnelRole',
                'service_resource_rows.report.lines.origin.regionOrigin.region',
                'service_resource_rows.report.lines.destination',
                'service_resource_rows.report.lines.concept',
                'service_status_reports.status',
                'service_status_reports.resourceStatusReports.serviceResource.resource',

                // Purchase Orders (CNIs)
                'purchase_orders.resources',
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
        $this->positioning_date = $s->positioning_date?->format('Y-m-d\TH:i');
        $this->arrival_date = $s->arrival_date?->format('Y-m-d\TH:i');

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

                $personnelRequirements = $this->resourcePersonnelRequirementsPayload($resource);
                $additionalInformation[$rowKey] = $this->additionalInformationFromReport($report, $personnelRequirements);

                return [
                    'row_key' => $rowKey,
                    'pivot_id' => $pivotId,
                    'resource_id' => (int) $resource->id,
                    'required_report_mask' => (int) $resource->required_report_mask,
                    'resource_operation' => (string) $resource->operation?->name,
                    'resource_operation_id' => $resource->resource_operation_id !== null
                        ? (int) $resource->resource_operation_id
                        : null,
                    'personnel_requirements' => $personnelRequirements,
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
        $this->last_added_service_resource_ids = [];
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
            'positioning_date' => ['nullable', 'date'],
            'arrival_date' => ['nullable', 'date'],

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

        $resource = Resource::query()
            ->with(['operation', 'personnelRequirements.personnelRole'])
            ->find($id);

        if (!$resource) {
            return;
        }

        $rowKey = 'new-' . Str::uuid();
        $personnelRequirements = $this->resourcePersonnelRequirementsPayload($resource);

        $this->service_resource_rows[] = [
            'row_key' => $rowKey,
            'pivot_id' => null,
            'resource_id' => $id,
            'required_report_mask' => (int) $resource->required_report_mask,
            'resource_operation' => (string) $resource->operation?->name,
            'resource_operation_id' => $resource->resource_operation_id !== null
                ? (int) $resource->resource_operation_id
                : null,
            'personnel_requirements' => $personnelRequirements,
        ];
        $this->additional_information[$rowKey] = $this->emptyAdditionalInformation($personnelRequirements);
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

        $this->ensureOperationLines($rowKey);
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

        $this->additional_information[$rowKey] = $this->emptyAdditionalInformation(
            $this->personnelRequirementsForRow($rowKey),
        );
        $this->ensureOperationLines($rowKey);
        $this->resetValidation("additional_information.{$rowKey}");
    }

    public function addOperationLine(string $rowKey): void
    {
        abort_unless($this->canEdit, 403, 'No tienes permisos para editar este servicio.');

        if (!$this->usesOperationLines($rowKey)) {
            return;
        }

        $lines = (array) data_get($this->additional_information, "{$rowKey}.operation_lines", []);
        $lines[] = $this->newOperationLine($rowKey);
        data_set($this->additional_information, "{$rowKey}.operation_lines", array_values($lines));
    }

    public function updateOperationLineOrigin(string $rowKey, int $lineIndex, mixed $originId): void
    {
        abort_unless($this->canEdit, 403, 'No tienes permisos para editar este servicio.');

        $lines = (array) data_get($this->additional_information, "{$rowKey}.operation_lines", []);

        if (!array_key_exists($lineIndex, $lines)) {
            return;
        }

        $origin = is_numeric($originId) ? $this->originWithRegion((int) $originId) : null;
        $lines[$lineIndex]['origin_id'] = $origin?->id;
        $lines[$lineIndex]['regional'] = $this->regionalNameForOrigin($origin);
        $lines[$lineIndex]['unit_price'] = $this->resolveOperationLineUnitPrice($rowKey, $lines[$lineIndex]);
        $lines[$lineIndex]['total_price'] = $this->calculateOperationLineTotal($lines[$lineIndex]);
        data_set($this->additional_information, "{$rowKey}.operation_lines", array_values($lines));
        $this->resetValidation("additional_information.{$rowKey}.operation_lines.{$lineIndex}.origin_id");
    }

    public function updateOperationLineConcept(string $rowKey, int $lineIndex, mixed $conceptId): void
    {
        abort_unless($this->canEdit, 403, 'No tienes permisos para editar este servicio.');

        $lines = (array) data_get($this->additional_information, "{$rowKey}.operation_lines", []);

        if (!array_key_exists($lineIndex, $lines)) {
            return;
        }

        $lines[$lineIndex]['operation_concept_id'] = is_numeric($conceptId) ? (int) $conceptId : null;
        if (!$this->isAuthorizedCostOperation($rowKey)) {
            $lines[$lineIndex]['unit_price'] = $this->resolveOperationLineUnitPrice($rowKey, $lines[$lineIndex]);
        }
        $lines[$lineIndex]['total_price'] = $this->calculateOperationLineTotal($lines[$lineIndex]);
        data_set($this->additional_information, "{$rowKey}.operation_lines", array_values($lines));
    }

    public function updateOperationLineQuantity(string $rowKey, int $lineIndex, mixed $quantity): void
    {
        $this->updateOperationLineDecimal($rowKey, $lineIndex, 'quantity', $quantity);
    }

    public function updateOperationLineUnitPrice(string $rowKey, int $lineIndex, mixed $unitPrice): void
    {
        abort_unless($this->canEdit, 403, 'No tienes permisos para editar este servicio.');

        if (!$this->isAuthorizedCostOperation($rowKey)) {
            return;
        }

        $this->updateOperationLineDecimal($rowKey, $lineIndex, 'unit_price', $unitPrice);
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
            $report->load([
                'vehicle',
                'container',
                'personnel.operator',
                'personnel.personnelRole',
                'lines.origin.regionOrigin.region',
                'lines.destination',
                'lines.concept',
            ]);
            $this->additional_information[$rowKey] = $this->additionalInformationFromReport(
                $report,
                $this->personnelRequirementsForRow($rowKey),
            );
        });
    }

    public function requiresAdditionalInformationForRow(string $rowKey): bool
    {
        return in_array(true, $this->requirementsForRow($rowKey), true)
            || $this->usesOperationLines($rowKey);
    }

    public function usesOperationLines(string $rowKey): bool
    {
        return in_array($this->operationNameForRow($rowKey), self::OPERATIONS_WITH_LINES, true);
    }

    public function isTransportOperation(string $rowKey): bool
    {
        return $this->operationNameForRow($rowKey) === self::TRANSPORT_OPERATION;
    }

    public function isAuthorizedCostOperation(string $rowKey): bool
    {
        return $this->operationNameForRow($rowKey) === self::AUTHORIZED_COST_OPERATION;
    }

    public function operationNameForRow(string $rowKey): ?string
    {
        $operation = trim((string) data_get($this->resourceRow($rowKey), 'resource_operation'));

        return $operation !== '' ? $operation : null;
    }

    public function availableOrigins()
    {
        return Origin::query()
            ->orderBy('normalized_origin')
            ->get(['id', 'normalized_origin']);
    }

    public function operationConceptsForRow(string $rowKey)
    {
        $operationId = (int) data_get($this->resourceRow($rowKey), 'resource_operation_id', 0);

        if ($operationId <= 0) {
            return collect();
        }

        return OperationConcept::query()
            ->where('resource_operation_id', $operationId)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    private function ensureOperationLines(string $rowKey): void
    {
        if (!$this->usesOperationLines($rowKey)) {
            return;
        }

        $lines = (array) data_get($this->additional_information, "{$rowKey}.operation_lines", []);

        if ($lines === []) {
            $lines[] = $this->newOperationLine($rowKey);

            data_set($this->additional_information, "{$rowKey}.operation_lines", $lines);

            return;
        }

        $origin = $this->originForValue($this->generalOrigin());
        $destination = $this->originForValue($this->generalDestination());

        $lines = array_map(function (array $line) use ($rowKey, $origin, $destination): array {
            if (empty($line['origin_id']) && $origin) {
                $line['origin_id'] = $origin->id;
                $line['regional'] = $this->regionalNameForOrigin($origin);
            }

            if (empty($line['destination_id']) && $destination) {
                $line['destination_id'] = $destination->id;
            }

            if (data_get($line, 'unit_price') === null && filled(data_get($line, 'operation_concept_id'))) {
                $line['unit_price'] = $this->resolveOperationLineUnitPrice($rowKey, $line);
            }

            $line['total_price'] = $this->calculateOperationLineTotal($line);

            return $line;
        }, $lines);

        data_set($this->additional_information, "{$rowKey}.operation_lines", $lines);
    }

    /** @return array<string, int|string|null> */
    private function newOperationLine(string $rowKey): array
    {
        $origin = $this->originForValue($this->generalOrigin());
        $destination = $this->originForValue($this->generalDestination());

        return [
            'line_id' => null,
            'origin_id' => $origin?->id,
            'destination_id' => $destination?->id,
            'regional' => $this->regionalNameForOrigin($origin),
            'operation_concept_id' => null,
            'quantity' => null,
            'unit_price' => null,
            'total_price' => null,
            'remesa_transporte' => null,
        ];
    }

    private function generalOrigin(): ?string
    {
        return $this->service?->service_parties
            ?->first(fn ($party) => strtoupper(trim((string) ($party->party_type?->party_qualifier ?? ''))) === 'PW')
            ?->party_city;
    }

    private function generalDestination(): ?string
    {
        $purchaseOrders = $this->service?->purchase_orders ?? collect();

        $destinations = $purchaseOrders
            ->flatMap(fn ($purchaseOrder) => $purchaseOrder->purchase_order_parties)
            ->filter(fn ($party) => strtoupper(trim((string) ($party->party_type?->party_qualifier ?? ''))) === 'DP')
            ->pluck('party_city')
            ->map(fn ($destination) => app(OriginNormalizer::class)->normalize($destination))
            ->filter()
            ->unique()
            ->values();

        return $destinations->count() === 1 ? $destinations->first() : null;
    }

    private function originForValue(?string $value): ?Origin
    {
        $normalizedOrigin = app(OriginNormalizer::class)->normalize($value);

        if ($normalizedOrigin === null) {
            return null;
        }

        return Origin::query()
            ->with('regionOrigin.region')
            ->where('normalized_origin', $normalizedOrigin)
            ->first();
    }

    private function originWithRegion(int $originId): ?Origin
    {
        return Origin::query()
            ->with('regionOrigin.region')
            ->find($originId);
    }

    private function regionalNameForOrigin(?Origin $origin): ?string
    {
        $regionOrigin = $origin?->regionOrigin;

        return $regionOrigin?->is_active && $regionOrigin->region?->is_active
            ? $regionOrigin->region->name
            : null;
    }

    private function regionalIdForOrigin(?Origin $origin): ?int
    {
        $regionOrigin = $origin?->regionOrigin;

        return $regionOrigin?->is_active && $regionOrigin->region?->is_active
            ? (int) $regionOrigin->region_id
            : null;
    }

    /** @param array<string, mixed> $line */
    private function resolveOperationLineUnitPrice(string $rowKey, array $line): ?string
    {
        $resourceId = data_get($this->resourceRow($rowKey), 'resource_id');
        $conceptId = data_get($line, 'operation_concept_id');
        $originId = data_get($line, 'origin_id');
        $origin = is_numeric($originId)
            ? $this->originWithRegion((int) $originId)
            : $this->originForValue($this->generalOrigin());

        return app(TariffResolver::class)->resolve(
            is_numeric($resourceId) ? (int) $resourceId : null,
            $this->regionalIdForOrigin($origin),
            is_numeric($conceptId) ? (int) $conceptId : null,
            $this->normalizePositiveDecimal(data_get($line, 'quantity')),
        );
    }

    private function updateOperationLineDecimal(string $rowKey, int $lineIndex, string $field, mixed $value): void
    {
        abort_unless($this->canEdit, 403, 'No tienes permisos para editar este servicio.');

        $lines = (array) data_get($this->additional_information, "{$rowKey}.operation_lines", []);

        if (!array_key_exists($lineIndex, $lines)) {
            return;
        }

        $lines[$lineIndex][$field] = $this->normalizeDecimalInput($value);
        $conceptId = data_get($lines[$lineIndex], 'operation_concept_id');
        if ($field === 'quantity' && $this->isTransportOperation($rowKey)
            && app(TariffResolver::class)->usesDistanceRanges(
                is_numeric($conceptId) ? (int) $conceptId : null,
            )) {
            $lines[$lineIndex]['unit_price'] = $this->resolveOperationLineUnitPrice($rowKey, $lines[$lineIndex]);
        }
        $lines[$lineIndex]['total_price'] = $this->calculateOperationLineTotal($lines[$lineIndex]);
        data_set($this->additional_information, "{$rowKey}.operation_lines", array_values($lines));
        $this->resetValidation("additional_information.{$rowKey}.operation_lines.{$lineIndex}.{$field}");

        if ($lines[$lineIndex][$field] !== null && $this->normalizePositiveDecimal($lines[$lineIndex][$field]) === null) {
            $this->addError(
                "additional_information.{$rowKey}.operation_lines.{$lineIndex}.{$field}",
                'El campo debe ser un número decimal mayor a cero.',
            );
        }
    }

    private function normalizePositiveDecimal(mixed $value): ?string
    {
        $value = $this->normalizeDecimalInput($value);

        return is_numeric($value) && (float) $value > 0 ? $value : null;
    }

    private function normalizeDecimalInput(mixed $value): ?string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        $value = str_replace([' ', '$'], '', $value);

        if (str_contains($value, ',') && str_contains($value, '.')) {
            $value = strrpos($value, ',') > strrpos($value, '.')
                ? str_replace(['.', ','], ['', '.'], $value)
                : str_replace(',', '', $value);
        } else {
            $value = str_replace(',', '.', $value);
        }

        return $value !== '' ? $value : null;
    }

    public function formatOperationLineMoney(mixed $value): string
    {
        $decimal = $this->trimStoredDecimal($value);

        return $decimal === null
            ? '—'
            : '$ ' . number_format((float) $decimal, str_contains($decimal, '.') ? 2 : 0, ',', '.');
    }

    private function trimStoredDecimal(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $decimal = (string) $value;

        return preg_match('/\A-?(?:0|[1-9][0-9]*)(?:\.[0-9]+)?\z/', $decimal)
            ? rtrim(rtrim($decimal, '0'), '.')
            : $decimal;
    }

    /** @param array<string, mixed> $line */
    private function calculateOperationLineTotal(array $line): ?string
    {
        $quantity = $this->normalizePositiveDecimal(data_get($line, 'quantity'));
        $unitPrice = $this->normalizePositiveDecimal(data_get($line, 'unit_price'));

        if ($quantity === null || $unitPrice === null) {
            return null;
        }

        $conceptId = data_get($line, 'operation_concept_id');
        if (BigDecimal::of($quantity)->isLessThanOrEqualTo(30)
            && app(TariffResolver::class)->usesDistanceRanges(is_numeric($conceptId) ? (int) $conceptId : null)) {
            // Viajes up to 30 km is charged as the flat amount of that range.
            return (string) BigDecimal::of($unitPrice)->toScale(6, RoundingMode::HALF_UP);
        }

        return number_format((float) $quantity * (float) $unitPrice, 6, '.', '');
    }

    /**
     * @return array{vehicle:bool,personnel:bool,remittance:bool,container:bool}
     */
    public function requirementsForRow(string $rowKey): array
    {
        $resource = $this->resourceForRow($rowKey);

        return [
            'vehicle' => $resource?->requiresVehicle() ?? false,
            'personnel' => ($resource?->requiresPersonnel() ?? false)
                && $this->personnelRequirementsForRow($rowKey) !== [],
            'remittance' => $resource?->requiresRemittance() ?? false,
            'container' => $resource?->requiresContainer() ?? false,
        ];
    }

    /**
     * @return array<int, array{
     *     role_id:int,
     *     role_code:string,
     *     role_name:string,
     *     quantity_required:int,
     *     is_required:bool,
     *     sort_order:int
     * }>
     */
    public function personnelRequirementsForRow(string $rowKey): array
    {
        $row = $this->resourceRow($rowKey);

        if (!$row) {
            return [];
        }

        return array_values(array_filter(
            (array) data_get($row, 'personnel_requirements', []),
            fn($requirement) => (int) data_get($requirement, 'quantity_required', 0) > 0,
        ));
    }

    public function fillPersonnelFromOperator(string $rowKey, int $roleId, int $index): bool
    {
        $identificationPath = "{$rowKey}.personnel.{$roleId}.{$index}.identification";
        $identification = Operator::normalizeIdentification(data_get($this->additional_information, $identificationPath));

        if ($identification === '') {
            return false;
        }

        $operator = Operator::query()
            ->where('identification', $identification)
            ->first(['identification', 'first_name', 'last_name']);

        if (!$operator) {
            return false;
        }

        data_set($this->additional_information, $identificationPath, $operator->identification);
        data_set($this->additional_information, "{$rowKey}.personnel.{$roleId}.{$index}.first_name", $operator->first_name);
        data_set($this->additional_information, "{$rowKey}.personnel.{$roleId}.{$index}.last_name", $operator->last_name);

        $this->resetValidation("additional_information.{$rowKey}.personnel.{$roleId}.{$index}");

        return true;
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

        if ($this->usesOperationLines($rowKey) && (array) data_get($draft, 'operation_lines', []) === []) {
            return false;
        }

        if ($this->usesOperationLines($rowKey)) {
            $requiresConcept = $this->operationConceptsForRow($rowKey)->isNotEmpty();

            foreach ((array) data_get($draft, 'operation_lines', []) as $line) {
                if ($requiresConcept && !filled(data_get($line, 'operation_concept_id'))) {
                    return false;
                }

                if (!filled(data_get($line, 'origin_id')) || !filled(data_get($line, 'destination_id'))) {
                    return false;
                }

                $quantity = $this->normalizePositiveDecimal(data_get($line, 'quantity'));
                $unitPrice = $this->normalizePositiveDecimal(data_get($line, 'unit_price'));
                $totalPrice = $this->normalizePositiveDecimal(data_get($line, 'total_price'));
                $expectedTotal = $this->calculateOperationLineTotal($line);

                if ($quantity === null || $unitPrice === null || $totalPrice === null || $expectedTotal === null
                    || abs((float) $totalPrice - (float) $expectedTotal) > 0.000001) {
                    return false;
                }
            }
        }

        if ($requirements['vehicle'] && blank(data_get($draft, 'vehicle_plate'))) {
            return false;
        }

        if ($requirements['personnel']) {
            foreach ($this->personnelRequirementsForRow($rowKey) as $personnelRequirement) {
                $roleId = (int) $personnelRequirement['role_id'];
                $quantityRequired = (int) $personnelRequirement['quantity_required'];

                for ($index = 0; $index < $quantityRequired; $index++) {
                    foreach (['identification', 'first_name', 'last_name'] as $field) {
                        if (blank(data_get($draft, "personnel.{$roleId}.{$index}.{$field}"))) {
                            return false;
                        }
                    }
                }
            }
        }

        if ($requirements['remittance'] && !$this->isTransportOperation($rowKey)
            && blank(data_get($draft, 'remesa_transporte'))) {
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
        $insertedServiceResourceIds = [];
        $persistedServiceResourceRows = [];
        $this->last_update_changes = [
            'status_changed' => false,
            'resource_added' => false,
            'resource_removed' => false,
        ];
        $this->last_added_service_resource_ids = [];

        DB::transaction(function () use (
            &$dirtyIds,
            &$insertedServiceResourceIds,
            &$persistedServiceResourceRows,
        ) {
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
                'positioning_date' => $this->positioning_date,
                'arrival_date' => $this->arrival_date,
            ]);

            $s->save();

            // Cambio de estado a nivel de servicio
            $newStatusId = $this->service_status_id !== null ? (int) $this->service_status_id : null;
            $oldStatusId = $this->original_service_status_id !== null ? (int) $this->original_service_status_id : null;

            if ($newStatusId !== $oldStatusId) {
                $this->last_update_changes['status_changed'] = true;

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
                        'resource_operation_id' => data_get($row, 'resource_operation_id') !== null
                            ? (int) data_get($row, 'resource_operation_id')
                            : null,
                        'personnel_requirements' => (array) data_get($row, 'personnel_requirements', []),
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
                    $this->last_update_changes['resource_removed'] = true;

                    DB::table('service_resource')
                        ->where('service_id', $s->id)
                        ->whereIn('id', $pivotIdsToDelete)
                        ->delete();
                }

                $insertedResource = false;

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
                    $insertedServiceResourceIds[] = $row['pivot_id'];
                    $insertedResource = true;
                }
                unset($row);

                if ($insertedResource) {
                    $this->last_update_changes['resource_added'] = true;

                    // Solo los recursos nuevos requieren reporte IFTSTA; eliminar es independiente.
                    $dirtyIds = array_merge(
                        $dirtyIds,
                        $s->purchase_orders?->pluck('id')->map(fn($id) => (int) $id)->all() ?? []
                    );
                }
            }

            $this->persistAdditionalInformation($s, $newResourceRows);

            // Guarda “dirty” para que el botón Enviar sepa qué CNIs incluir en IFTSTA
            $dirtyIds = array_values(array_unique($dirtyIds));
            $persistedServiceResourceRows = $newResourceRows;
        });

        $this->service_resource_rows = $persistedServiceResourceRows;
        $this->original_service_resource_rows = $persistedServiceResourceRows;
        $this->dirty_purchase_order_ids = $dirtyIds;
        $this->last_added_service_resource_ids = array_values(array_unique($insertedServiceResourceIds));
        $this->reset_resource_selection = true;

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
            $prefix = "additional_information.{$rowKey}";

            if ($requirements['vehicle']) {
                $rules["{$prefix}.vehicle_plate"] = ['required', 'string', 'max:32'];
                $attributes["{$prefix}.vehicle_plate"] = 'placa del vehículo';
            }

            if ($requirements['personnel']) {
                foreach ($this->personnelRequirementsForRow($rowKey) as $personnelRequirement) {
                    $roleId = (int) $personnelRequirement['role_id'];
                    $roleName = strtolower((string) $personnelRequirement['role_name']);
                    $quantityRequired = (int) $personnelRequirement['quantity_required'];

                    for ($index = 0; $index < $quantityRequired; $index++) {
                        $entryPrefix = "{$prefix}.personnel.{$roleId}.{$index}";
                        $entrySuffix = $quantityRequired > 1 ? ' ' . ($index + 1) : '';

                        $rules["{$entryPrefix}.identification"] = [
                            'required',
                            'string',
                            'max:64',
                            function (string $attribute, mixed $value, \Closure $fail): void {
                                if (Operator::normalizeIdentification($value) === '') {
                                    $fail('El campo :attribute debe contener al menos un número o letra.');
                                }
                            },
                        ];
                        $rules["{$entryPrefix}.first_name"] = ['required', 'string', 'max:128'];
                        $rules["{$entryPrefix}.last_name"] = ['required', 'string', 'max:128'];
                        $attributes["{$entryPrefix}.identification"] = "identificación del {$roleName}{$entrySuffix}";
                        $attributes["{$entryPrefix}.first_name"] = "nombre(s) del {$roleName}{$entrySuffix}";
                        $attributes["{$entryPrefix}.last_name"] = "apellido(s) del {$roleName}{$entrySuffix}";
                    }
                }
            }

            if ($requirements['remittance'] && !$this->isTransportOperation($rowKey)) {
                $rules["{$prefix}.remesa_transporte"] = ['required', 'string', 'max:128'];
                $attributes["{$prefix}.remesa_transporte"] = 'remesa de transporte';
            }

            if ($this->usesOperationLines($rowKey)) {
                $rules["{$prefix}.operation_lines"] = ['array', 'min:1'];
                $operationId = (int) data_get($row, 'resource_operation_id', 0);
                $requiresConcept = $this->operationConceptsForRow($rowKey)->isNotEmpty();

                foreach ((array) data_get($this->additional_information, "{$rowKey}.operation_lines", []) as $lineIndex => $line) {
                    $linePrefix = "{$prefix}.operation_lines.{$lineIndex}";
                    $rules["{$linePrefix}.line_id"] = ['nullable', 'integer'];
                    $rules["{$linePrefix}.operation_concept_id"] = [
                        $requiresConcept ? 'required' : 'nullable',
                        'integer',
                        'exists:operation_concepts,id',
                        function (string $attribute, mixed $value, \Closure $fail) use ($operationId): void {
                            if ($value === null || $value === '') {
                                return;
                            }

                            $belongsToOperation = $operationId > 0
                                && OperationConcept::query()
                                    ->whereKey((int) $value)
                                    ->where('resource_operation_id', $operationId)
                                    ->exists();

                            if (!$belongsToOperation) {
                                $fail('El concepto seleccionado no corresponde a la operación del recurso.');
                            }
                        },
                    ];
                    $attributes["{$linePrefix}.operation_concept_id"] = 'concepto';
                    $rules["{$linePrefix}.quantity"] = ['required', 'numeric', 'gt:0'];
                    $attributes["{$linePrefix}.quantity"] = 'cantidad';

                    $rules["{$linePrefix}.origin_id"] = ['required', 'integer', 'exists:origins,id'];
                    $rules["{$linePrefix}.destination_id"] = ['required', 'integer', 'exists:origins,id'];
                    $attributes["{$linePrefix}.origin_id"] = 'origen';
                    $attributes["{$linePrefix}.destination_id"] = 'destino';

                    if ($this->isAuthorizedCostOperation($rowKey)) {
                        $rules["{$linePrefix}.unit_price"] = ['required', 'numeric', 'gt:0'];
                        $attributes["{$linePrefix}.unit_price"] = 'valor unitario';
                    }

                    if ($this->isTransportOperation($rowKey)) {
                        $rules["{$linePrefix}.remesa_transporte"] = ['nullable', 'string', 'max:128'];
                        $attributes["{$linePrefix}.remesa_transporte"] = 'remesa de transporte';
                    }
                }
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
     *     resource_operation:string,
     *     resource_operation_id:int|null,
     *     personnel_requirements:array<int, array<string, mixed>>
     * }> $resourceRows
     */
    private function persistAdditionalInformation(Service $service, array $resourceRows): void
    {
        foreach ($resourceRows as $row) {
            $requirements = $this->requirementsForRow($row['row_key']);
            $hasOperationLines = $this->hasOperationLinesToPersist($row['row_key']);

            if (!in_array(true, $requirements, true) && !$hasOperationLines) {
                ServiceResourceReport::withTrashed()
                    ->where('service_resource_id', $row['pivot_id'])
                    ->delete();
                $this->additional_information[$row['row_key']]['report_id'] = null;
                continue;
            }

            $report = $this->persistAdditionalInformationRow($service, $row);
            $this->additional_information[$row['row_key']]['report_id'] = (int) $report->id;
        }
    }

    /** @return array<int, array{line_id:int|null,origin_id:int|null,destination_id:int|null,operation_concept_id:int|null,remesa_transporte:string|null}> */
    private function operationLinesForPersistence(string $rowKey): array
    {
        if (!$this->usesOperationLines($rowKey)) {
            return [];
        }

        return collect((array) data_get($this->additional_information, "{$rowKey}.operation_lines", []))
            ->map(function ($line) use ($rowKey): array {
                $originId = data_get($line, 'origin_id');
                $destinationId = data_get($line, 'destination_id');
                $conceptId = data_get($line, 'operation_concept_id');
                $remittance = trim((string) data_get($line, 'remesa_transporte'));

                return [
                    'line_id' => filled(data_get($line, 'line_id')) ? (int) data_get($line, 'line_id') : null,
                    'origin_id' => is_numeric($originId) ? (int) $originId : null,
                    'destination_id' => is_numeric($destinationId) ? (int) $destinationId : null,
                    'operation_concept_id' => is_numeric($conceptId) ? (int) $conceptId : null,
                    'quantity' => $this->normalizePositiveDecimal(data_get($line, 'quantity')),
                    'unit_price' => $this->isAuthorizedCostOperation($rowKey)
                        ? $this->normalizePositiveDecimal(data_get($line, 'unit_price'))
                        : $this->resolveOperationLineUnitPrice($rowKey, $line),
                    'total_price' => null,
                    'remesa_transporte' => $remittance !== '' ? $remittance : null,
                ];
            })
            ->map(function (array $line, int $lineIndex) use ($rowKey): array {
                $line['total_price'] = $this->calculateOperationLineTotal($line);

                foreach (['quantity' => 'La cantidad', 'unit_price' => 'El valor unitario', 'total_price' => 'El valor total'] as $field => $label) {
                    if ($line[$field] !== null && BigDecimal::of($line[$field])
                        ->toScale(6, RoundingMode::HALF_UP)->isGreaterThan('99999999999999.999999')) {
                        throw ValidationException::withMessages([
                            "{$this->getPropertyName()}.additional_information.{$rowKey}.operation_lines.{$lineIndex}.{$field}"
                                => "{$label} supera el máximo permitido de 99.999.999.999.999,999999.",
                        ]);
                    }
                }

                return $line;
            })
            ->filter(fn (array $line) => $line['origin_id'] !== null
                || $line['destination_id'] !== null
                || $line['operation_concept_id'] !== null
                || $line['quantity'] !== null
                || $line['unit_price'] !== null
                || $line['total_price'] !== null
                || $line['remesa_transporte'] !== null)
            ->values()
            ->all();
    }

    private function hasOperationLinesToPersist(string $rowKey): bool
    {
        return $this->operationLinesForPersistence($rowKey) !== [];
    }

    /** @param array<int, array{remesa_transporte:string|null}> $operationLines */
    private function firstTransportRemittance(array $operationLines): ?string
    {
        return $operationLines[0]['remesa_transporte'] ?? null;
    }

    /** @param array<int, array{line_id:int|null,origin_id:int|null,destination_id:int|null,operation_concept_id:int|null,remesa_transporte:string|null}> $operationLines */
    private function persistOperationLines(ServiceResourceReport $report, array $operationLines): void
    {
        $existingLines = $report->lines()->get()->keyBy('id');

        foreach ($operationLines as $lineData) {
            $lineId = $lineData['line_id'];
            $line = $lineId !== null ? $existingLines->pull($lineId) : null;
            $line ??= new ServiceResourceReportLine(['service_resource_report_id' => $report->id]);
            $line->fill($lineData);
            $line->save();
        }

        $existingLines->each->delete();
    }

    /**
     * @param array{
     *     row_key:string,
     *     pivot_id:int,
     *     resource_id:int,
     *     required_report_mask:int,
     *     resource_operation:string,
     *     resource_operation_id:int|null,
     *     personnel_requirements:array<int, array<string, mixed>>
     * } $row
     */
    private function persistAdditionalInformationRow(
        Service $service,
        array $row,
        ?ServiceResourceReport $existingReport = null,
    ): ServiceResourceReport
    {
        $requirements = $this->requirementsForRow($row['row_key']);
        $draft = $this->additional_information[$row['row_key']] ?? [];
        $operationLines = $this->operationLinesForPersistence($row['row_key']);
        $vehicleId = $requirements['vehicle']
            ? $this->persistVehicle((string) data_get($draft, 'vehicle_plate'))
            : null;
        $containerId = $requirements['container']
            ? $this->persistContainer((string) data_get($draft, 'container_number'))
            : null;

        $report = $existingReport ?? ServiceResourceReport::withTrashed()->firstOrNew([
            'service_resource_id' => $row['pivot_id'],
        ]);

        if ($report->trashed()) {
            $report->restore();
        }

        if (!$report->exists) {
            $report->created_by = Auth::id();
        }

        $report->fill([
            'service_id' => $service->id,
            'resource_id' => $row['resource_id'],
            'vehicle_id' => $vehicleId,
            'container_id' => $containerId,
            'remesa_transporte' => $this->isTransportOperation($row['row_key'])
                ? $this->firstTransportRemittance($operationLines)
                : ($requirements['remittance'] ? trim((string) data_get($draft, 'remesa_transporte')) : null),
            'updated_by' => Auth::id(),
        ]);
        $report->save();
        $this->persistOperationLines($report, $operationLines);
        $this->persistReportPersonnel($report, $draft, $requirements['personnel']
            ? $this->personnelRequirementsForRow($row['row_key'])
            : []);

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
     * @param array<string, mixed> $entry
     */
    private function persistOperator(array $entry): int
    {
        $identification = Operator::normalizeIdentification(data_get($entry, 'identification'));
        $operator = Operator::withTrashed()->firstOrNew([
            'identification' => $identification,
        ]);

        if ($operator->trashed()) {
            $operator->restore();
        }

        $operator->fill([
            'first_name' => trim((string) data_get($entry, 'first_name')),
            'last_name' => trim((string) data_get($entry, 'last_name')),
        ]);
        $operator->save();

        return (int) $operator->id;
    }

    /**
     * @param array<string, mixed> $draft
     * @param array<int, array{role_id:int, quantity_required:int}> $personnelRequirements
     */
    private function persistReportPersonnel(
        ServiceResourceReport $report,
        array $draft,
        array $personnelRequirements,
    ): void {
        $report->personnel()->delete();

        foreach ($personnelRequirements as $personnelRequirement) {
            $roleId = (int) $personnelRequirement['role_id'];
            $quantityRequired = (int) $personnelRequirement['quantity_required'];

            for ($index = 0; $index < $quantityRequired; $index++) {
                $entry = (array) data_get($draft, "personnel.{$roleId}.{$index}", []);

                ServiceResourceReportPersonnel::query()->create([
                    'service_resource_report_id' => $report->id,
                    'operator_id' => $this->persistOperator($entry),
                    'personnel_role_id' => $roleId,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            }
        }
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
    private function additionalInformationFromReport(
        ?ServiceResourceReport $report,
        array $personnelRequirements = [],
    ): array
    {
        if (!$report) {
            return $this->emptyAdditionalInformation($personnelRequirements);
        }

        $personnel = $this->emptyPersonnelInformation($personnelRequirements);
        $reportedPersonnel = $report->personnel
            ?->sortBy('id')
            ->groupBy('personnel_role_id') ?? collect();

        foreach ($personnelRequirements as $personnelRequirement) {
            $roleId = (int) $personnelRequirement['role_id'];
            $reportedEntries = $reportedPersonnel->get($roleId, collect())->values();

            foreach ($personnel[$roleId] ?? [] as $index => $entry) {
                $reportedEntry = $reportedEntries->get($index);

                if (!$reportedEntry) {
                    continue;
                }

                $personnel[$roleId][$index] = [
                    'report_personnel_id' => (int) $reportedEntry->id,
                    'identification' => $reportedEntry->operator?->identification,
                    'first_name' => $reportedEntry->operator?->first_name,
                    'last_name' => $reportedEntry->operator?->last_name,
                ];
            }
        }

        return [
            'report_id' => (int) $report->id,
            'vehicle_plate' => $report->vehicle?->plate,
            'personnel' => $personnel,
            'remesa_transporte' => $report->remesa_transporte,
            'container_number' => $report->container?->container_number,
            'operation_lines' => $report->lines
                ?->map(fn (ServiceResourceReportLine $line) => [
                    'line_id' => (int) $line->id,
                    'origin_id' => $line->origin_id,
                    'destination_id' => $line->destination_id,
                    'regional' => $this->regionalNameForOrigin($line->origin),
                    'operation_concept_id' => $line->operation_concept_id,
                    'quantity' => $this->trimStoredDecimal($line->quantity),
                    'unit_price' => $this->trimStoredDecimal($line->unit_price),
                    'total_price' => $this->trimStoredDecimal($line->total_price),
                    'remesa_transporte' => $line->remesa_transporte,
                ])
                ->values()
                ->all() ?? [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function emptyAdditionalInformation(array $personnelRequirements = []): array
    {
        return [
            'report_id' => null,
            'vehicle_plate' => null,
            'personnel' => $this->emptyPersonnelInformation($personnelRequirements),
            'remesa_transporte' => null,
            'container_number' => null,
            'operation_lines' => [],
        ];
    }

    /**
     * @param array<int, array{role_id:int, quantity_required:int}> $personnelRequirements
     * @return array<int, array<int, array{
     *     report_personnel_id:int|null,
     *     identification:string|null,
     *     first_name:string|null,
     *     last_name:string|null
     * }>>
     */
    private function emptyPersonnelInformation(array $personnelRequirements): array
    {
        $personnel = [];

        foreach ($personnelRequirements as $personnelRequirement) {
            $roleId = (int) $personnelRequirement['role_id'];
            $quantityRequired = (int) $personnelRequirement['quantity_required'];

            for ($index = 0; $index < $quantityRequired; $index++) {
                $personnel[$roleId][$index] = [
                    'report_personnel_id' => null,
                    'identification' => null,
                    'first_name' => null,
                    'last_name' => null,
                ];
            }
        }

        return $personnel;
    }

    /**
     * @return array<int, array{
     *     role_id:int,
     *     role_code:string,
     *     role_name:string,
     *     quantity_required:int,
     *     is_required:bool,
     *     sort_order:int
     * }>
     */
    private function resourcePersonnelRequirementsPayload(Resource $resource): array
    {
        return $resource->personnelRequirements
            ?->filter(fn($requirement) => (bool) $requirement->is_required)
            ->map(fn($requirement) => [
                'role_id' => (int) $requirement->personnel_role_id,
                'role_code' => (string) ($requirement->personnelRole?->code ?? ''),
                'role_name' => (string) ($requirement->personnelRole?->name ?? 'Personal'),
                'quantity_required' => max(1, (int) $requirement->quantity_required),
                'is_required' => (bool) $requirement->is_required,
                'sort_order' => (int) $requirement->sort_order,
            ])
            ->sortBy([
                ['sort_order', 'asc'],
                ['role_id', 'asc'],
            ])
            ->values()
            ->all() ?? [];
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
