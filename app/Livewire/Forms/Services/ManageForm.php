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
     * status por Purchase Order (CNI)
     * key = purchase_orders.id
     * value = statuses.id | null
     *
     * @var array<int, int|null>
     */
    public array $purchase_order_statuses = [];

    /**
     * Snapshot original para detectar cambios reales
     *
     * @var array<int, int|null>
     */
    public array $original_purchase_order_statuses = [];

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

                // Purchase Orders (CNIs)
                'purchase_orders',
                'purchase_orders.status',

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

        $this->item = $s->item;
        $this->observation = $s->observation;

        $this->ttcol_value = $s->ttcol_value;
        $this->cargo_value = $s->cargo_value;
        $this->driver = $s->driver;
        $this->advance_payment = $s->advance_payment;

        // Estado por CNI (purchase_order)
        $map = ($s->purchase_orders ?? collect())
            ->mapWithKeys(function ($po) {
                $poId = (int) $po->id;
                $statusId = $po->status_id !== null ? (int) $po->status_id : null;
                return [$poId => $statusId];
            })
            ->toArray();

        $this->purchase_order_statuses = $map;
        $this->original_purchase_order_statuses = $map;

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
            'purchase_order_statuses' => ['array'],
            'purchase_order_statuses.*' => ['nullable', 'integer', 'exists:statuses,id'],
        ];
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

            $s->fill([
                'item' => $this->item,
                'observation' => $this->observation,
                'ttcol_value' => $this->ttcol_value,
                'cargo_value' => $this->cargo_value,
                'driver' => $this->driver,
                'advance_payment' => $this->advance_payment,
            ]);

            $s->save();

            // Detectar cambios reales por CNI

            foreach ($this->purchase_order_statuses as $purchaseOrderId => $newStatusId) {
                $purchaseOrderId = (int) $purchaseOrderId;

                // normaliza null/int
                $newStatusId = $newStatusId !== null ? (int) $newStatusId : null;

                $oldStatusId = $this->original_purchase_order_statuses[$purchaseOrderId] ?? null;
                $oldStatusId = $oldStatusId !== null ? (int) $oldStatusId : null;

                if ($newStatusId === $oldStatusId) {
                    continue; // no cambió, no lo toques
                }

                $dirtyIds[] = $purchaseOrderId;

                DB::table('purchase_orders')
                    ->where('id', $purchaseOrderId)
                    ->where('service_id', $s->id)
                    ->update([
                        'status_id' => $newStatusId,
                        'updated_at' => now(),
                    ]);
            }

            // Guarda “dirty” para que el botón Enviar sepa qué CNIs incluir en IFTSTA
            $dirtyIds = array_values(array_unique($dirtyIds));
            $this->dirty_purchase_order_ids = $dirtyIds;

            // Recarga todo (y refresca snapshot)
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
