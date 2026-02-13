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

    public ?int $status_id = null;

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

        $this->canEdit =
            ($user?->rol_key === 'admin')
            || ($user && method_exists($user, 'hasRole') && $user->hasRole('admin'));

        $this->service = Service::query()
            ->with([
                // Service
                'status',

                'service_parties.party_type',
                'service_contacts.contact_type',
                'service_contacts.service_contact_details',
                'service_dates.date_type',
                'service_equipments.equipment_type',                 // (tu relación se llama así)
                'service_measurements.global_measure_type',

                'location_details.location_code',

                'transport_details.transport_stage',
                'transport_details.transport_mode',

                // Purchase Orders (hijos del service)
                'purchase_orders',

                // Delivery terms cuelgan de purchase_orders (tabla delivery_terms tiene purchase_order_id)
                'purchase_orders.delivery_terms.delivery_term_catalog',

                // Purchase order parties / contacts
                'purchase_orders.purchase_order_parties.party_type',
                'purchase_orders.purchase_order_contacts.contact_type',
                'purchase_orders.purchase_order_contacts.purchase_order_contact_details',

                // Notes (OJO: para poder cargar note_type, el modelo PurchaseOrderNote debe tener note_type())
                'purchase_orders.purchase_order_notes.note_type',

                // Measurements
                'purchase_orders.purchase_order_measurements.global_measure_type',

                // Requirements (en tu BD NO hay reference_type_id ni FK a reference_types)
                'purchase_orders.purchase_order_requirements',

                // Charges (en tu BD transport_charges cuelga de purchase_orders)
                'purchase_orders.transport_charges.price_qualifier',

                // Items
                'purchase_orders.purchase_order_items',

                // Item notes (OJO: en BD el FK es note_types_id)
                'purchase_orders.purchase_order_items.item_notes.note_type',

                // Item measures
                'purchase_orders.purchase_order_items.item_measures.measurement_attribute_code',
                'purchase_orders.purchase_order_items.item_measures.measurement_purpose_code',

                // Item dimensions / identifiers
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

        $this->status_id = $s->status_id;
    }

    public function rules(): array
    {
        return [
            'item' => ['nullable', 'string', 'max:16'],
            'observation' => ['nullable', 'string', 'max:2000'],
            'ttcol_value' => ['nullable'],
            'cargo_value' => ['nullable'],
            'driver' => ['nullable', 'string', 'max:64'],
            'advance_payment' => ['nullable'],
            'status_id' => ['nullable', 'integer'],
        ];
    }

    public function update(): void
    {
        abort_unless($this->canEdit, 403);

        $this->validate();

        DB::transaction(function () {
            $s = Service::query()->findOrFail($this->id);

            $s->fill([
                'item' => $this->item,
                'observation' => $this->observation,
                'ttcol_value' => $this->ttcol_value,
                'cargo_value' => $this->cargo_value,
                'driver' => $this->driver,
                'advance_payment' => $this->advance_payment,
                'status_id' => $this->status_id,
            ]);

            $s->save();

            $this->mount($s);
        });
    }

    public function visibleAttributes(Model $m): array
    {
        $attrs = $m->getAttributes();
        foreach ($this->omit as $bad) unset($attrs[$bad]);
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
