<?php

namespace App\Livewire\Forms\Services;

use App\Models\Service;
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
     * @var array<int, array{pivot_id:int|null,resource_id:int}>
     */
    public array $service_resource_rows = [];

    /**
     * Snapshot original de recursos.
     *
     * @var array<int, array{pivot_id:int|null,resource_id:int}>
     */
    public array $original_service_resource_rows = [];

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
        $rows = $s->resources
            ?->map(fn($resource) => [
                'pivot_id' => $resource->pivot?->id !== null ? (int) $resource->pivot->id : null,
                'resource_id' => (int) $resource->id,
            ])
            ->values()
            ->all() ?? [];
        $this->service_resource_rows = $rows;
        $this->original_service_resource_rows = $rows;
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
            'service_resource_rows.*.pivot_id' => ['nullable', 'integer'],
            'service_resource_rows.*.resource_id' => ['integer', 'exists:resources,id'],
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

        $this->service_resource_rows[] = [
            'pivot_id' => null,
            'resource_id' => $id,
        ];
    }

    public function removeServiceResource(int $resourceIndex): void
    {
        if (!array_key_exists($resourceIndex, $this->service_resource_rows)) {
            return;
        }

        unset($this->service_resource_rows[$resourceIndex]);
        $this->service_resource_rows = array_values($this->service_resource_rows);
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
                        'pivot_id' => $pivotId !== null ? (int) $pivotId : null,
                        'resource_id' => $resourceId,
                    ];
                }, $rows)));
            };

            $newResourceRows = $normalizeResourceRows($this->service_resource_rows);
            $oldResourceRows = $normalizeResourceRows($this->original_service_resource_rows);

            if ($newResourceRows !== $oldResourceRows) {
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

                $rowsToInsert = array_map(
                    fn($row) => [
                        'service_id' => $s->id,
                        'resource_id' => $row['resource_id'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    array_values(array_filter($newResourceRows, fn($row) => $row['pivot_id'] === null))
                );

                if ($rowsToInsert !== []) {
                    DB::table('service_resource')->insert($rowsToInsert);
                }

                // Si cambia recurso, marcar todos los CNI para IFTSTA
                $dirtyIds = array_merge(
                    $dirtyIds,
                    $s->purchase_orders?->pluck('id')->map(fn($id) => (int) $id)->all() ?? []
                );
            }

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
