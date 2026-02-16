<?php

namespace App\Livewire\Services;

use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class ServiceTable extends PowerGridComponent
{
    public string $tableName = 'service-table-aqh7dc-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Service::query()
            ->select([
                'services.id',
                'services.consecutive',

                DB::raw("(SELECT sd.service_date
                    FROM service_dates sd
                    INNER JOIN date_types dt ON dt.id = sd.date_type_id
                    WHERE sd.service_id = services.id
                      AND dt.type_qualifier IN ('81', '11')
                    ORDER BY CASE WHEN dt.type_qualifier = '81' THEN 0 ELSE 1 END, sd.id DESC
                    LIMIT 1) as date_any"),

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
                    WHERE sp.service_id = services.id AND pt.party_qualifier = 'CZ'
                    ORDER BY sp.id DESC LIMIT 1) as origin_party_name"),
                DB::raw("(SELECT sp.party_street
                    FROM service_parties sp
                    INNER JOIN party_types pt ON pt.id = sp.party_type_id
                    WHERE sp.service_id = services.id AND pt.party_qualifier = 'CZ'
                    ORDER BY sp.id DESC LIMIT 1) as origin_party_street"),
                DB::raw("(SELECT sp.party_city
                    FROM service_parties sp
                    INNER JOIN party_types pt ON pt.id = sp.party_type_id
                    WHERE sp.service_id = services.id AND pt.party_qualifier = 'CZ'
                    ORDER BY sp.id DESC LIMIT 1) as origin_party_city"),
                DB::raw("(SELECT sp.party_region
                    FROM service_parties sp
                    INNER JOIN party_types pt ON pt.id = sp.party_type_id
                    WHERE sp.service_id = services.id AND pt.party_qualifier = 'CZ'
                    ORDER BY sp.id DESC LIMIT 1) as origin_party_region"),

                DB::raw("(SELECT sp.party_name
                    FROM service_parties sp
                    INNER JOIN party_types pt ON pt.id = sp.party_type_id
                    WHERE sp.service_id = services.id AND pt.party_qualifier = 'PW'
                    ORDER BY sp.id DESC LIMIT 1) as dest_party_name"),
                DB::raw("(SELECT sp.party_street
                    FROM service_parties sp
                    INNER JOIN party_types pt ON pt.id = sp.party_type_id
                    WHERE sp.service_id = services.id AND pt.party_qualifier = 'PW'
                    ORDER BY sp.id DESC LIMIT 1) as dest_party_street"),
                DB::raw("(SELECT sp.party_city
                    FROM service_parties sp
                    INNER JOIN party_types pt ON pt.id = sp.party_type_id
                    WHERE sp.service_id = services.id AND pt.party_qualifier = 'PW'
                    ORDER BY sp.id DESC LIMIT 1) as dest_party_city"),
                DB::raw("(SELECT sp.party_region
                    FROM service_parties sp
                    INNER JOIN party_types pt ON pt.id = sp.party_type_id
                    WHERE sp.service_id = services.id AND pt.party_qualifier = 'PW'
                    ORDER BY sp.id DESC LIMIT 1) as dest_party_region"),
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

            // Fecha unificada
            ->add('date_any', fn($row) => $this->fmtDate($row->date_any))

            ->add('weight', function ($row) {
                $gross = $this->fmtMeasure($row->weight_gross_value, $row->weight_gross_unit, 'Bruto', true);
                $net   = $this->fmtMeasure($row->weight_net_value,   $row->weight_net_unit,   'Neto', true);

                $parts = array_values(array_filter([$gross, $net], fn($v) => $v !== null));
                return !empty($parts) ? implode(' | ', $parts) : '-';
            })

            ->add('volume', function ($row) {
                $gross = $this->fmtMeasure($row->volume_gross_value, $row->volume_gross_unit, 'Bruto', true);
                $net   = $this->fmtMeasure($row->volume_net_value,   $row->volume_net_unit,   'Neto', true);

                $parts = array_values(array_filter([$gross, $net], fn($v) => $v !== null));
                return !empty($parts) ? implode(' | ', $parts) : '-';
            })

            ->add('pieces', fn($row) => $row->pieces_value !== null ? (string)$row->pieces_value : '-')

            ->add('origin_full', fn($row) => $this->fmtParty(
                $row->origin_party_name,
                $row->origin_party_street,
                $row->origin_party_city,
                $row->origin_party_region
            ))

            ->add('destination_full', fn($row) => $this->fmtParty(
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

            // ÚNICA columna de fecha
            Column::make('Fecha Inicio Estimada', 'date_any')->sortable(),

            Column::make('Peso', 'weight'),
            Column::make('Volumen', 'volume'),

            Column::make('Piezas', 'pieces')->sortable(),

            Column::make('Origen', 'origin_full')->searchable(),
            Column::make('Destino', 'destination_full')->searchable(),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . (int)$rowId . ')');
    }

    public function actions(Service $row): array
    {
        return [
            Button::add('preview')
                ->slot('<span class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 7h18M3 12h18M3 17h18" />
                        </svg>
                        Ver Resumen
                    </span>')
                ->class('inline-flex items-center px-3 py-1.5 rounded-full
                     border border-indigo-600 text-indigo-700 bg-transparent text-xs font-semibold
                     hover:bg-indigo-600 hover:text-white transition')
                ->dispatch('preview', ['rowId' => $row->id]),
            Button::add('view')
                ->slot('<span class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5
                                     c4.477 0 8.268 2.943 9.542 7
                                     -1.274 4.057-5.065 7-9.542 7
                                     -4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Ver Detalles
                    </span>')
                ->class('inline-flex items-center px-3 py-1.5 rounded-full
                     border border-black text-black bg-transparent text-xs font-semibold
                     hover:bg-black hover:text-white transition')
                ->route('service.manage', ['service' => $row->id]),
        ];
    }

    #[\Livewire\Attributes\On('preview')]
    public function preview($rowId): void
    {
        $this->dispatch('service-preview-selected', serviceId: (int) $rowId);
    }

    // ==========================
    // Helpers
    // ==========================

    private function fmtDate($date): string
    {
        if ($date === null || trim((string)$date) === '') return '-';
        return substr((string)$date, 0, 10);
    }

    private function fmtMeasure($value, $unit, string $label, bool $nullable = false): ?string
    {
        $hasUnit  = $unit !== null && trim((string)$unit) !== '';
        $hasValue = $value !== null;

        if (!$hasUnit && !$hasValue) {
            return $nullable ? null : '-';
        }

        $u = $hasUnit ? trim((string)$unit) : 'UNK';
        $v = $hasValue ? (string)$value : '0';

        return "{$v} {$u} ({$label})";
    }

    private function fmtParty($name, $street, $city, $region): string
    {
        $parts = [];

        $name   = trim((string)($name ?? ''));
        $street = trim((string)($street ?? ''));
        $city   = trim((string)($city ?? ''));
        $region = trim((string)($region ?? ''));

        if ($name !== '') $parts[] = $name;
        if ($street !== '') $parts[] = $street;

        $loc = trim(implode(', ', array_values(array_filter([$city, $region], fn($v) => $v !== ''))));
        if ($loc !== '') $parts[] = $loc;

        return !empty($parts) ? implode(' - ', $parts) : '-';
    }
}
