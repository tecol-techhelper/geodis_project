<?php

namespace App\Livewire\Services;

use App\Enums\Permission;
use App\Models\Service;
use App\Services\ServicePurgeService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use Throwable;

final class ServiceTable extends PowerGridComponent
{
    public string $tableName = 'service-table-aqh7dc-table';

    public bool $showTrash = false;

    public bool $canDeleteServices = false;

    public bool $canPurgeServices = false;

    public ?int $pendingServiceId = null;

    public ?string $pendingServiceLabel = null;

    public ?string $pendingDeletionType = null;

    public function mount(): void
    {
        $user = Auth::user();

        $this->canDeleteServices = (bool) $user?->hasPermission(Permission::DeleteServices->value);
        $this->canPurgeServices = (bool) $user?->hasRole('admin');

        parent::mount();
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->includeViewOnTop('livewire.services.partials.service-table-toggle')
                ->includeViewOnBottom('livewire.services.partials.service-table-controls')
                ->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        if ($this->showTrash) {
            abort_unless($this->currentUserIsAdmin(), 403, 'No tienes permisos para consultar la papelera.');
        }

        $query = $this->showTrash
            ? Service::onlyTrashed()
            : Service::query();

        return $query
            ->select([
                'services.id',
                'services.consecutive',
                DB::raw("(SELECT UPPER(TRIM(orx.order_reference_value))
                    FROM purchase_orders po
                    INNER JOIN order_references orx ON orx.purchase_order_id = po.id
                    INNER JOIN reference_types rt ON rt.id = orx.reference_type_id
                    WHERE po.service_id = services.id
                      AND UPPER(TRIM(rt.reference_type_code)) = 'ACD'
                      AND TRIM(COALESCE(orx.order_reference_value, '')) <> ''
                    ORDER BY orx.id DESC
                    LIMIT 1) as acd_type_value"),

                DB::raw("(SELECT sm.measure_value
                    FROM service_measurements sm
                    INNER JOIN global_measure_types gmt ON gmt.id = sm.global_measure_type_id
                    WHERE sm.service_id = services.id AND gmt.type_qualifier = '7'
                    ORDER BY sm.id DESC LIMIT 1) as weight_gross_value"),
                DB::raw("(SELECT sm.measure_unit
                    FROM service_measurements sm
                    INNER JOIN global_measure_types gmt ON gmt.id = sm.global_measure_type_id
                    WHERE sm.service_id = services.id AND gmt.type_qualifier = '7'
                    ORDER BY sm.id DESC LIMIT 1) as weight_gross_unit"),
                DB::raw("(SELECT sm.measure_value
                    FROM service_measurements sm
                    INNER JOIN global_measure_types gmt ON gmt.id = sm.global_measure_type_id
                    WHERE sm.service_id = services.id AND gmt.type_qualifier = '29'
                    ORDER BY sm.id DESC LIMIT 1) as weight_net_value"),
                DB::raw("(SELECT sm.measure_unit
                    FROM service_measurements sm
                    INNER JOIN global_measure_types gmt ON gmt.id = sm.global_measure_type_id
                    WHERE sm.service_id = services.id AND gmt.type_qualifier = '29'
                    ORDER BY sm.id DESC LIMIT 1) as weight_net_unit"),

                DB::raw("(SELECT sm.measure_value
                    FROM service_measurements sm
                    INNER JOIN global_measure_types gmt ON gmt.id = sm.global_measure_type_id
                    WHERE sm.service_id = services.id AND gmt.type_qualifier = '26'
                    ORDER BY sm.id DESC LIMIT 1) as volume_gross_value"),
                DB::raw("(SELECT sm.measure_unit
                    FROM service_measurements sm
                    INNER JOIN global_measure_types gmt ON gmt.id = sm.global_measure_type_id
                    WHERE sm.service_id = services.id AND gmt.type_qualifier = '26'
                    ORDER BY sm.id DESC LIMIT 1) as volume_gross_unit"),
                DB::raw("(SELECT sm.measure_value
                    FROM service_measurements sm
                    INNER JOIN global_measure_types gmt ON gmt.id = sm.global_measure_type_id
                    WHERE sm.service_id = services.id AND gmt.type_qualifier = '15'
                    ORDER BY sm.id DESC LIMIT 1) as volume_net_value"),
                DB::raw("(SELECT sm.measure_unit
                    FROM service_measurements sm
                    INNER JOIN global_measure_types gmt ON gmt.id = sm.global_measure_type_id
                    WHERE sm.service_id = services.id AND gmt.type_qualifier = '15'
                    ORDER BY sm.id DESC LIMIT 1) as volume_net_unit"),

                DB::raw("(SELECT sm.measure_value
                    FROM service_measurements sm
                    INNER JOIN global_measure_types gmt ON gmt.id = sm.global_measure_type_id
                    WHERE sm.service_id = services.id AND gmt.type_qualifier = '11'
                    ORDER BY sm.id DESC LIMIT 1) as pieces_value"),

                DB::raw("(SELECT sp.party_name
                    FROM service_parties sp
                    INNER JOIN party_types pt ON pt.id = sp.party_type_id
                    WHERE sp.service_id = services.id AND UPPER(TRIM(pt.party_qualifier)) = 'PW'
                    ORDER BY sp.id DESC LIMIT 1) as origin_party_name"),
                DB::raw("(SELECT sp.party_street
                    FROM service_parties sp
                    INNER JOIN party_types pt ON pt.id = sp.party_type_id
                    WHERE sp.service_id = services.id AND UPPER(TRIM(pt.party_qualifier)) = 'PW'
                    ORDER BY sp.id DESC LIMIT 1) as origin_party_street"),
                DB::raw("(SELECT sp.party_city
                    FROM service_parties sp
                    INNER JOIN party_types pt ON pt.id = sp.party_type_id
                    WHERE sp.service_id = services.id AND UPPER(TRIM(pt.party_qualifier)) = 'PW'
                    ORDER BY sp.id DESC LIMIT 1) as origin_party_city"),
                DB::raw("(SELECT sp.party_region
                    FROM service_parties sp
                    INNER JOIN party_types pt ON pt.id = sp.party_type_id
                    WHERE sp.service_id = services.id AND UPPER(TRIM(pt.party_qualifier)) = 'PW'
                    ORDER BY sp.id DESC LIMIT 1) as origin_party_region"),

                DB::raw("(SELECT pop.party_name
                    FROM purchase_order_parties pop
                    INNER JOIN party_types pt ON pt.id = pop.party_type_id
                    INNER JOIN purchase_orders po ON po.id = pop.purchase_order_id
                    WHERE po.service_id = services.id AND UPPER(TRIM(pt.party_qualifier)) = 'DP'
                    ORDER BY pop.id DESC LIMIT 1) as dest_party_name"),
                DB::raw("(SELECT pop.party_street
                    FROM purchase_order_parties pop
                    INNER JOIN party_types pt ON pt.id = pop.party_type_id
                    INNER JOIN purchase_orders po ON po.id = pop.purchase_order_id
                    WHERE po.service_id = services.id AND UPPER(TRIM(pt.party_qualifier)) = 'DP'
                    ORDER BY pop.id DESC LIMIT 1) as dest_party_street"),
                DB::raw("(SELECT pop.party_city
                    FROM purchase_order_parties pop
                    INNER JOIN party_types pt ON pt.id = pop.party_type_id
                    INNER JOIN purchase_orders po ON po.id = pop.purchase_order_id
                    WHERE po.service_id = services.id AND UPPER(TRIM(pt.party_qualifier)) = 'DP'
                    ORDER BY pop.id DESC LIMIT 1) as dest_party_city"),
                DB::raw("(SELECT pop.party_region
                    FROM purchase_order_parties pop
                    INNER JOIN party_types pt ON pt.id = pop.party_type_id
                    INNER JOIN purchase_orders po ON po.id = pop.purchase_order_id
                    WHERE po.service_id = services.id AND UPPER(TRIM(pt.party_qualifier)) = 'DP'
                    ORDER BY pop.id DESC LIMIT 1) as dest_party_region"),
            ]);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('consecutive')
            ->add('service_type', fn ($row) => $this->fmtServiceType($row->acd_type_value ?? null))

            ->add('weight', function ($row) {
                $gross = $this->fmtMeasure($row->weight_gross_value, $row->weight_gross_unit, 'Bruto', true);
                $net = $this->fmtMeasure($row->weight_net_value, $row->weight_net_unit, 'Neto', true);

                $parts = array_values(array_filter([$gross, $net], fn ($v) => $v !== null));

                return ! empty($parts) ? implode(' | ', $parts) : '';
            })

            ->add('volume', function ($row) {
                $gross = $this->fmtMeasure($row->volume_gross_value, $row->volume_gross_unit, 'Bruto', true);
                $net = $this->fmtMeasure($row->volume_net_value, $row->volume_net_unit, 'Neto', true);

                $parts = array_values(array_filter([$gross, $net], fn ($v) => $v !== null));

                return ! empty($parts) ? implode(' | ', $parts) : '';
            })

            ->add('pieces', fn ($row) => $row->pieces_value !== null ? (string) $row->pieces_value : '')

            ->add('origin_full', fn ($row) => $this->fmtParty(
                $row->origin_party_name,
                $row->origin_party_street,
                $row->origin_party_city,
                $row->origin_party_region
            ))

            ->add('destination_full', fn ($row) => $this->fmtParty(
                $row->dest_party_name,
                $row->dest_party_street,
                $row->dest_party_city,
                $row->dest_party_region
            ));
    }

    public function columns(): array
    {
        return [
            Column::action('Acciones'),

            Column::make('Consecutivo', 'consecutive')
                ->sortable()
                ->searchable(),

            Column::make('Tipo de Servicio', 'service_type', 'acd_type_value')
                ->sortable(),

            Column::make('Peso', 'weight'),
            Column::make('Volumen', 'volume'),

            Column::make('Piezas', 'pieces')->sortable(),

            Column::make('Origen', 'origin_full')
                ->searchableRaw("EXISTS (
                    SELECT 1
                    FROM service_parties sp
                    INNER JOIN party_types pt ON pt.id = sp.party_type_id
                    WHERE sp.service_id = services.id
                      AND UPPER(TRIM(pt.party_qualifier)) = 'PW'
                      AND CONCAT_WS(' ',
                          COALESCE(sp.party_name, ''),
                          COALESCE(sp.party_street, ''),
                          COALESCE(sp.party_city, ''),
                          COALESCE(sp.party_region, '')
                      ) LIKE ?
                )"),
            Column::make('Destino', 'destination_full')
                ->searchableRaw("EXISTS (
                    SELECT 1
                    FROM purchase_order_parties pop
                    INNER JOIN party_types pt ON pt.id = pop.party_type_id
                    INNER JOIN purchase_orders po ON po.id = pop.purchase_order_id
                    WHERE po.service_id = services.id
                      AND UPPER(TRIM(pt.party_qualifier)) = 'DP'
                      AND CONCAT_WS(' ',
                          COALESCE(pop.party_name, ''),
                          COALESCE(pop.party_street, ''),
                          COALESCE(pop.party_city, ''),
                          COALESCE(pop.party_region, '')
                      ) LIKE ?
                )"),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.(int) $rowId.')');
    }

    public function actions(Service $row): array
    {
        if ($this->showTrash) {
            if (! $this->canPurgeServices) {
                return [];
            }

            return [
                Button::add('purge')
                    ->slot('<span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 6h18M8 6V4h8v2m-9 0 1 14h8l1-14M10 11v5m4-5v5" />
                            </svg>
                        </span>')
                    ->class('inline-flex h-9 w-9 items-center justify-center rounded-full
                         border border-red-700 bg-red-700 text-white
                         hover:bg-red-800 transition
                         focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2')
                    ->attributes([
                        'title' => 'Eliminar definitivamente',
                        'aria-label' => 'Eliminar servicio definitivamente',
                    ])
                    ->dispatch('request-service-deletion', ['rowId' => $row->id]),
            ];
        }

        return [
            Button::add('preview')
                ->slot('<span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 7h18M3 12h18M3 17h18" />
                        </svg>
                    </span>')
                ->class('inline-flex h-9 w-9 items-center justify-center rounded-full
                     border border-indigo-600 bg-white text-indigo-700
                     hover:bg-indigo-600 hover:text-white transition
                     focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2')
                ->attributes(['title' => 'Ver resumen', 'aria-label' => 'Ver resumen'])
                ->dispatch('preview', ['rowId' => $row->id]),
            Button::add('view')
                ->slot('<span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5
                                     c4.477 0 8.268 2.943 9.542 7
                                     -1.274 4.057-5.065 7-9.542 7
                                     -4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </span>')
                ->class('inline-flex h-9 w-9 items-center justify-center rounded-full
                     border border-gray-700 bg-white text-gray-700
                     hover:bg-gray-800 hover:text-white transition
                     focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2')
                ->attributes(['title' => 'Ver detalles', 'aria-label' => 'Ver detalles'])
                ->route('service.manage', ['service' => $row->id]),
            Button::add('delete')
                ->slot('<span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 6h18M8 6V4h8v2m-9 0 1 14h8l1-14M10 11v5m4-5v5" />
                        </svg>
                    </span>')
                ->can($this->canDeleteServices)
                ->class('inline-flex h-9 w-9 items-center justify-center rounded-full
                     border border-red-600 bg-white text-red-600
                     hover:bg-red-600 hover:text-white transition
                     focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2')
                ->attributes([
                    'title' => 'Enviar a la papelera',
                    'aria-label' => 'Enviar servicio a la papelera',
                ])
                ->dispatch('request-service-deletion', ['rowId' => $row->id]),
        ];
    }

    #[On('preview')]
    public function preview($rowId): void
    {
        $this->dispatch('service-preview-selected', serviceId: (int) $rowId);
    }

    public function setTrashMode(bool $showTrash): void
    {
        if ($showTrash) {
            abort_unless($this->currentUserIsAdmin(), 403, 'No tienes permisos para consultar la papelera.');
        }

        $this->showTrash = $showTrash;
        $this->clearPendingDeletion();
        $this->gotoPage(1);
        $this->dispatch('service-list-mode-changed', showTrash: $showTrash);
    }

    #[On('request-service-deletion')]
    public function requestServiceDeletion(int $rowId): void
    {
        if ($this->showTrash) {
            abort_unless($this->currentUserIsAdmin(), 403, 'No tienes permisos para eliminar definitivamente.');

            $service = Service::onlyTrashed()->findOrFail($rowId);
            $this->pendingDeletionType = 'purge';
        } else {
            abort_unless(
                Auth::user()?->hasPermission(Permission::DeleteServices->value),
                403,
                'No tienes permisos para enviar servicios a la papelera.'
            );

            $service = Service::query()->findOrFail($rowId);
            $this->pendingDeletionType = 'delete';
        }

        $this->pendingServiceId = (int) $service->getKey();
        $this->pendingServiceLabel = (string) ($service->consecutive ?: $service->getKey());
    }

    public function cancelServiceDeletion(): void
    {
        $this->clearPendingDeletion();
    }

    public function confirmServiceDeletion(ServicePurgeService $purgeService): void
    {
        abort_unless($this->pendingServiceId !== null, 404);

        if ($this->pendingDeletionType === 'purge') {
            abort_unless($this->currentUserIsAdmin(), 403, 'No tienes permisos para eliminar definitivamente.');
        } else {
            abort_unless(
                $this->pendingDeletionType === 'delete'
                    && Auth::user()?->hasPermission(Permission::DeleteServices->value),
                403,
                'No tienes permisos para enviar servicios a la papelera.'
            );
        }

        try {
            if ($this->pendingDeletionType === 'purge') {
                $service = Service::onlyTrashed()->findOrFail($this->pendingServiceId);
                $purgeService->purge($service);

                flash()
                    ->title('Servicio eliminado definitivamente')
                    ->success('El servicio y sus datos asociados fueron eliminados de la base de datos.');
            } else {
                Service::query()->findOrFail($this->pendingServiceId)->delete();

                flash()
                    ->title('Servicio enviado a la papelera')
                    ->success('El servicio fue enviado a la papelera correctamente.');
            }

            $this->dispatch('service-list-mode-changed', showTrash: $this->showTrash);
        } catch (Throwable $exception) {
            report($exception);

            flash()
                ->title('No fue posible eliminar el servicio')
                ->error('La operacion no pudo completarse. Verifica los datos relacionados e intenta nuevamente.');
        } finally {
            $this->clearPendingDeletion();
        }
    }

    public function noDataLabel(): string
    {
        return $this->showTrash
            ? 'La papelera no contiene servicios.'
            : 'No hay servicios activos para mostrar.';
    }

    // ==========================
    // Helpers
    // ==========================

    private function currentUserIsAdmin(): bool
    {
        return (bool) Auth::user()?->hasRole('admin');
    }

    private function clearPendingDeletion(): void
    {
        $this->pendingServiceId = null;
        $this->pendingServiceLabel = null;
        $this->pendingDeletionType = null;
    }

    protected function fmtServiceType($acdValue): string
    {
        $value = strtoupper(trim((string) ($acdValue ?? '')));
        if ($value === '') {
            return '';
        }

        $normalized = str_replace(['_', ' '], '-', $value);

        if (in_array($normalized, ['POST-CARRIAGE', 'DOM-CONSOL', 'EMPTY-CONTAINER'], true)) {
            return $this->renderServiceType('LOG ENT', $normalized);
        }

        if (in_array($normalized, ['DELIVERY-SO', 'ROAD'], true)) {
            return $this->renderServiceType('OTX', $normalized);
        }

        return '';
    }

    private function renderServiceType(string $type, string $subtype): string
    {
        return sprintf(
            '<span class="whitespace-nowrap"><span class="font-bold text-indigo-700">%s</span> <span class="font-medium text-gray-500">(%s)</span></span>',
            e($type),
            e($subtype)
        );
    }

    private function fmtMeasure($value, $unit, string $label, bool $nullable = false): ?string
    {
        $hasUnit = $unit !== null && trim((string) $unit) !== '';
        $hasValue = $value !== null;

        if (! $hasUnit && ! $hasValue) {
            return $nullable ? null : '';
        }

        $u = $hasUnit ? trim((string) $unit) : 'UNK';
        $v = $hasValue ? (string) $value : '0';

        return "{$v} {$u} ({$label})";
    }

    private function fmtParty($name, $street, $city, $region): string
    {
        $parts = [];

        $name = $this->cleanValue($name);
        $street = $this->cleanValue($street);
        $city = $this->cleanValue($city);
        $region = $this->cleanValue($region);

        if ($name !== '') {
            $parts[] = $name;
        }
        if ($street !== '') {
            $parts[] = $street;
        }

        $loc = trim(implode(', ', array_values(array_filter([$city, $region], fn ($v) => $v !== ''))));
        if ($loc !== '') {
            $parts[] = $loc;
        }

        return ! empty($parts) ? implode(' - ', $parts) : '';
    }

    private function cleanValue($value): string
    {
        $v = trim((string) ($value ?? ''));
        if ($v === '') {
            return '';
        }
        $upper = strtoupper($v);
        if (in_array($upper, ['UNKNOWN', 'UNKNOW', 'N/A', 'NA', 'NULL'], true)) {
            return '';
        }

        return $v;
    }
}
