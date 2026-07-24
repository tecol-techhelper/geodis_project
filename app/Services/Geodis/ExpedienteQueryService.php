<?php

namespace App\Services\Geodis;

use App\Models\ServiceResource;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ExpedienteQueryService
{
    /**
     * @param  array{
     *     so:?string,
     *     fecha_inicio:?string,
     *     fecha_fin:?string,
     *     consolidado:?string,
     *     page:int,
     *     per_page:int
     * }  $filters
     */
    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = ServiceResource::query()
            ->select([
                'service_resource.id',
                'service_resource.service_id',
                'service_resource.resource_id',
            ])
            ->whereHas('service')
            ->whereHas('resource')
            ->with($this->relations())
            ->orderBy('service_resource.id');

        $this->applyFilters($query, $filters);

        return $query->paginate(
            perPage: $filters['per_page'],
            page: $filters['page'],
        );
    }

    /**
     * @return array<string, callable|array|string>
     */
    private function relations(): array
    {
        return [
            'service' => fn ($query) => $query->select([
                'services.id',
                'services.consecutive',
                'services.positioning_date',
                'services.arrival_date',
            ]),
            'service.service_parties' => fn ($query) => $query
                ->select([
                    'service_parties.id',
                    'service_parties.service_id',
                    'service_parties.party_type_id',
                    'service_parties.party_street',
                ])
                ->whereHas('party_type', fn ($partyTypeQuery) => $partyTypeQuery
                    ->where('party_qualifier', 'PW'))
                ->orderByDesc('service_parties.id'),
            'service.service_parties.party_type' => fn ($query) => $query->select([
                'party_types.id',
                'party_types.party_qualifier',
            ]),
            'service.purchase_orders' => fn ($query) => $query->select([
                'purchase_orders.id',
                'purchase_orders.service_id',
            ]),
            'service.purchase_orders.order_references' => fn ($query) => $query
                ->select([
                    'order_references.id',
                    'order_references.purchase_order_id',
                    'order_references.reference_type_id',
                    'order_references.order_reference_value',
                ])
                ->whereHas('reference_type', fn ($referenceTypeQuery) => $referenceTypeQuery
                    ->where('reference_type_code', 'ACD'))
                ->orderBy('order_references.id'),
            'service.purchase_orders.order_references.reference_type' => fn ($query) => $query->select([
                'reference_types.id',
                'reference_types.reference_type_code',
            ]),
            'service.purchase_orders.purchase_order_parties' => fn ($query) => $query
                ->select([
                    'purchase_order_parties.id',
                    'purchase_order_parties.purchase_order_id',
                    'purchase_order_parties.party_type_id',
                    'purchase_order_parties.party_street',
                ])
                ->whereHas('party_type', fn ($partyTypeQuery) => $partyTypeQuery
                    ->where('party_qualifier', 'DP'))
                ->orderByDesc('purchase_order_parties.id'),
            'service.purchase_orders.purchase_order_parties.party_type' => fn ($query) => $query->select([
                'party_types.id',
                'party_types.party_qualifier',
            ]),
            'service.purchase_orders.resources' => fn ($query) => $query->select([
                'resources.id',
            ]),
            'resource' => fn ($query) => $query->select([
                'resources.id',
                'resources.resource_id',
            ]),
            'report' => fn ($query) => $query->select([
                'service_resource_reports.id',
                'service_resource_reports.service_resource_id',
                'service_resource_reports.vehicle_id',
                'service_resource_reports.container_id',
                'service_resource_reports.remesa_transporte',
            ]),
            'report.vehicle' => fn ($query) => $query->select([
                'vehicles.id',
                'vehicles.plate',
            ]),
            'report.container' => fn ($query) => $query->select([
                'containers.id',
                'containers.container_number',
            ]),
            'report.personnel' => fn ($query) => $query
                ->select([
                    'service_resource_report_personnel.id',
                    'service_resource_report_personnel.service_resource_report_id',
                    'service_resource_report_personnel.operator_id',
                ])
                ->orderBy('service_resource_report_personnel.id'),
            'report.personnel.operator' => fn ($query) => $query->select([
                'operators.id',
                'operators.identification',
                'operators.first_name',
                'operators.last_name',
            ]),
            'statusReports' => fn ($query) => $query->select([
                'service_resource_status_reports.id',
                'service_resource_status_reports.service_resource_id',
                'service_resource_status_reports.service_status_report_id',
                'service_resource_status_reports.reported_at',
            ]),
            'statusReports.serviceStatusReport' => fn ($query) => $query->select([
                'service_status_reports.id',
                'service_status_reports.status_id',
            ]),
            'statusReports.serviceStatusReport.status' => fn ($query) => $query->select([
                'statuses.id',
                'statuses.status_purpose_id',
                'statuses.status_be',
                'statuses.edifact_code',
            ]),
            'statusReports.serviceStatusReport.status.status_purpose' => fn ($query) => $query->select([
                'status_purposes.id',
                'status_purposes.purpose_subcode',
            ]),
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function applyFilters(Builder $query, array $filters): void
    {
        if (filled($filters['so'] ?? null)) {
            $query->whereHas('service', fn (Builder $serviceQuery) => $serviceQuery
                ->where('consecutive', $filters['so']));
        }

        if (filled($filters['fecha_inicio'] ?? null) && filled($filters['fecha_fin'] ?? null)) {
            $rangeStart = CarbonImmutable::createFromFormat('Y-m-d', $filters['fecha_inicio'])->startOfDay();
            $rangeEndExclusive = CarbonImmutable::createFromFormat('Y-m-d', $filters['fecha_fin'])
                ->addDay()
                ->startOfDay();

            $query->whereHas('service', fn (Builder $serviceQuery) => $serviceQuery
                ->where('created_at', '>=', $rangeStart)
                ->where('created_at', '<', $rangeEndExclusive));
        }

        if (filled($filters['consolidado'] ?? null)) {
            $consolidatedNumber = trim((string) $filters['consolidado']);
            $consolidatedSuffix = '/'.$consolidatedNumber;

            $query->whereHas('service.purchase_orders.order_references', function (Builder $referenceQuery) use ($consolidatedNumber, $consolidatedSuffix): void {
                $referenceQuery
                    ->whereHas('reference_type', fn (Builder $referenceTypeQuery) => $referenceTypeQuery
                        ->where('reference_type_code', 'AGW'))
                    ->where(function (Builder $valueQuery) use ($consolidatedNumber, $consolidatedSuffix): void {
                        $valueQuery
                            ->where('order_reference_value', $consolidatedNumber)
                            ->orWhereRaw(
                                'SUBSTR(TRIM(order_reference_value), ?) = ?',
                                [-strlen($consolidatedSuffix), $consolidatedSuffix],
                            );
                    });
            });
        }
    }
}
