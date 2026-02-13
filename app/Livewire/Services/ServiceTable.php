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
            PowerGrid::detail()
                ->view('livewire.services.partials.service-details')
                ->showCollapseIcon()
        ];
    }

    public function datasource(): Builder
    {
        return Service::query()
            ->select([
                'services.id',
                'services.consecutive',
                'services.status_id',

                // Estado
                DB::raw("st.status_name as status_name"),

                // Fechas (se dejan por si quieres auditar, pero se usa date_any)
                DB::raw("dtm11.service_date as dtm_11"),
                DB::raw("dtm81.service_date as dtm_81"),

                // Fecha unificada: prioriza DTM+81, si no existe usa DTM+11
                DB::raw("COALESCE(dtm81.service_date, dtm11.service_date) as date_any"),

                // PESO bruto (7) / neto (29)
                DB::raw("cnt_wg.measure_value as weight_gross_value"),
                DB::raw("cnt_wg.measure_unit  as weight_gross_unit"),
                DB::raw("cnt_wn.measure_value as weight_net_value"),
                DB::raw("cnt_wn.measure_unit  as weight_net_unit"),

                // VOLUMEN bruto (26) / neto (15)
                DB::raw("cnt_vg.measure_value as volume_gross_value"),
                DB::raw("cnt_vg.measure_unit  as volume_gross_unit"),
                DB::raw("cnt_vn.measure_value as volume_net_value"),
                DB::raw("cnt_vn.measure_unit  as volume_net_unit"),

                // PIEZAS (CNT+11)
                DB::raw("cnt_p.measure_value as pieces_value"),
                DB::raw("cnt_p.measure_unit  as pieces_unit"),

                // Origen (CZ) - campos completos
                DB::raw("nad_cz.party_name   as origin_party_name"),
                DB::raw("nad_cz.party_street as origin_party_street"),
                DB::raw("nad_cz.party_city   as origin_party_city"),
                DB::raw("nad_cz.party_region as origin_party_region"),

                // Destino (PW) - campos completos
                DB::raw("nad_pw.party_name   as dest_party_name"),
                DB::raw("nad_pw.party_street as dest_party_street"),
                DB::raw("nad_pw.party_city   as dest_party_city"),
                DB::raw("nad_pw.party_region as dest_party_region"),
            ])

            // ====== STATUS ======
            ->leftJoin('statuses as st', 'st.id', '=', 'services.status_id')

            // ====== DTM 11 ======
            ->leftJoin('service_dates as dtm11', function ($join) {
                $join->on('dtm11.service_id', '=', 'services.id')
                    ->whereIn('dtm11.date_type_id', function ($q) {
                        $q->select('id')
                            ->from('date_types')
                            ->where('type_qualifier', 11);
                    });
            })

            // ====== DTM 81 ======
            ->leftJoin('service_dates as dtm81', function ($join) {
                $join->on('dtm81.service_id', '=', 'services.id')
                    ->whereIn('dtm81.date_type_id', function ($q) {
                        $q->select('id')
                            ->from('date_types')
                            ->where('type_qualifier', 81);
                    });
            })

            // ====== CNT peso bruto (7) ======
            ->leftJoin('service_measurements as cnt_wg', function ($join) {
                $join->on('cnt_wg.service_id', '=', 'services.id')
                    ->whereIn('cnt_wg.global_measure_type_id', function ($q) {
                        $q->select('id')
                            ->from('global_measure_types')
                            ->where('type_qualifier', '7');
                    });
            })

            // ====== CNT peso neto (29) ======
            ->leftJoin('service_measurements as cnt_wn', function ($join) {
                $join->on('cnt_wn.service_id', '=', 'services.id')
                    ->whereIn('cnt_wn.global_measure_type_id', function ($q) {
                        $q->select('id')
                            ->from('global_measure_types')
                            ->where('type_qualifier', '29');
                    });
            })

            // ====== CNT volumen bruto (26) ======
            ->leftJoin('service_measurements as cnt_vg', function ($join) {
                $join->on('cnt_vg.service_id', '=', 'services.id')
                    ->whereIn('cnt_vg.global_measure_type_id', function ($q) {
                        $q->select('id')
                            ->from('global_measure_types')
                            ->where('type_qualifier', '26');
                    });
            })

            // ====== CNT volumen neto (15) ======
            ->leftJoin('service_measurements as cnt_vn', function ($join) {
                $join->on('cnt_vn.service_id', '=', 'services.id')
                    ->whereIn('cnt_vn.global_measure_type_id', function ($q) {
                        $q->select('id')
                            ->from('global_measure_types')
                            ->where('type_qualifier', '15');
                    });
            })

            // ====== CNT piezas (11) ======
            ->leftJoin('service_measurements as cnt_p', function ($join) {
                $join->on('cnt_p.service_id', '=', 'services.id')
                    ->whereIn('cnt_p.global_measure_type_id', function ($q) {
                        $q->select('id')
                            ->from('global_measure_types')
                            ->where('type_qualifier', '11');
                    });
            })

            // ====== NAD Origen CZ ======
            ->leftJoin('service_parties as nad_cz', function ($join) {
                $join->on('nad_cz.service_id', '=', 'services.id')
                    ->whereIn('nad_cz.party_type_id', function ($q) {
                        $q->select('id')
                            ->from('party_types')
                            ->where('party_qualifier', 'CZ');
                    });
            })

            // ====== NAD Destino PW ======
            ->leftJoin('service_parties as nad_pw', function ($join) {
                $join->on('nad_pw.service_id', '=', 'services.id')
                    ->whereIn('nad_pw.party_type_id', function ($q) {
                        $q->select('id')
                            ->from('party_types')
                            ->where('party_qualifier', 'PW');
                    });
            })

            ->groupBy([
                'services.id',
                'services.consecutive',
                'services.status_id',
                'st.status_name',

                'dtm11.service_date',
                'dtm81.service_date',

                'cnt_wg.measure_value',
                'cnt_wg.measure_unit',
                'cnt_wn.measure_value',
                'cnt_wn.measure_unit',
                'cnt_vg.measure_value',
                'cnt_vg.measure_unit',
                'cnt_vn.measure_value',
                'cnt_vn.measure_unit',

                'cnt_p.measure_value',
                'cnt_p.measure_unit',

                'nad_cz.party_name',
                'nad_cz.party_street',
                'nad_cz.party_city',
                'nad_cz.party_region',
                'nad_pw.party_name',
                'nad_pw.party_street',
                'nad_pw.party_city',
                'nad_pw.party_region',
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

            ->add('status_name', fn($row) => $row->status_name ?: '-')

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

            Column::make('Estado', 'status_name')
                ->sortable()
                ->searchable(),
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
