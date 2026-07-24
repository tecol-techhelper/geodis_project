<?php

namespace App\Http\Resources\Api\Geodis;

use App\Models\ServiceResourceStatusReport;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ExpedienteResource extends JsonResource
{
    /**
     * @var array<string, array{initial:array{be:string,edifact:int},final:array{be:string,edifact:int}}>
     */
    private const FLOW_STATUSES = [
        'POST-CARRIAGE' => [
            'initial' => ['be' => 'ACT013', 'edifact' => 13],
            'final' => ['be' => 'WHRECP', 'edifact' => 29],
        ],
        'DOM-CONSOL' => [
            'initial' => ['be' => 'ACT035', 'edifact' => 35],
            'final' => ['be' => 'ACT021', 'edifact' => 74],
        ],
        'EMPTY-CONTAINER' => [
            'initial' => ['be' => 'CNT013', 'edifact' => 79],
            'final' => ['be' => 'CNT021', 'edifact' => 82],
        ],
        'DELIVERY-SO' => [
            'initial' => ['be' => 'ACT013', 'edifact' => 13],
            'final' => ['be' => 'ACT021', 'edifact' => 74],
        ],
    ];

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $flow = $this->serviceFlow();
        $catalogResource = $this->resource->resource;

        return [
            'so' => $this->service?->consecutive !== null
                ? (string) $this->service->consecutive
                : null,
            'item' => $catalogResource?->id !== null
                ? (string) $catalogResource->id
                : null,
            'item_geodis' => null,
            'recurso' => $catalogResource?->resource_id,
            'administrativo' => $this->administrativeData(),
            'regional' => null,
            'origen' => $this->service?->service_parties?->first()?->party_street,
            'destino' => $this->destination(),
            'remesa' => $this->report?->remesa_transporte,
            'fecha_de_posicionamiento' => $this->service?->positioning_date?->format('Y-m-d'),
            'fecha_de_cargue' => $this->reportedDateFor($flow, 'initial'),
            'fecha_de_arribo' => $this->service?->arrival_date?->format('Y-m-d'),
            'fecha_de_descargue' => $this->reportedDateFor($flow, 'final'),
            'unidad' => null,
            'cantidad' => null,
            'valor_unitario' => null,
            'valor_total' => null,
            'numero_contenedor' => $this->report?->container?->container_number,
        ];
    }

    /**
     * @return array<int, array{placa:?string,nombre_operador:?string,identificacion_operador:?string}>
     */
    private function administrativeData(): array
    {
        if (! $this->report) {
            return [];
        }

        $plate = $this->report->vehicle?->plate;
        $personnel = $this->report->personnel
            ->filter(fn ($reportedPersonnel) => $reportedPersonnel->operator !== null)
            ->map(function ($reportedPersonnel) use ($plate): array {
                $operator = $reportedPersonnel->operator;
                $operatorName = trim($operator->first_name.' '.$operator->last_name);

                return [
                    'placa' => $plate,
                    'nombre_operador' => $operatorName !== '' ? $operatorName : null,
                    'identificacion_operador' => $operator->identification,
                ];
            })
            ->values()
            ->all();

        if ($personnel === [] && $plate !== null) {
            return [[
                'placa' => $plate,
                'nombre_operador' => null,
                'identificacion_operador' => null,
            ]];
        }

        return $personnel;
    }

    private function destination(): ?string
    {
        $purchaseOrders = $this->service?->purchase_orders ?? collect();

        if ($purchaseOrders->count() === 1) {
            $candidatePurchaseOrders = $purchaseOrders;
        } else {
            $resourceId = $this->resource->resource?->id;
            $candidatePurchaseOrders = $purchaseOrders->filter(
                fn ($purchaseOrder) => $resourceId !== null
                    && $purchaseOrder->resources->contains('id', $resourceId),
            );
        }

        $destinations = $candidatePurchaseOrders
            ->flatMap(fn ($purchaseOrder) => $purchaseOrder->purchase_order_parties)
            ->pluck('party_street')
            ->filter(fn ($destination) => filled($destination))
            ->map(fn ($destination) => trim((string) $destination))
            ->unique()
            ->values();

        return $destinations->count() === 1 ? $destinations->first() : null;
    }

    private function serviceFlow(): ?string
    {
        $purchaseOrders = $this->service?->purchase_orders ?? collect();

        if ($purchaseOrders->isEmpty()) {
            return null;
        }

        $flows = $purchaseOrders->map(function ($purchaseOrder): ?string {
            $references = $purchaseOrder->order_references;

            if ($references->count() !== 1) {
                return null;
            }

            $flow = Str::upper(trim((string) $references->first()->order_reference_value));
            $flow = str_replace([' ', '_'], '-', $flow);

            return $flow === 'ROAD' ? 'DELIVERY-SO' : $flow;
        });

        if ($flows->contains(null)) {
            return null;
        }

        $uniqueFlows = $flows->unique()->values();
        $flow = $uniqueFlows->count() === 1 ? $uniqueFlows->first() : null;

        return is_string($flow) && isset(self::FLOW_STATUSES[$flow]) ? $flow : null;
    }

    private function reportedDateFor(?string $flow, string $event): ?string
    {
        if ($flow === null || ! isset(self::FLOW_STATUSES[$flow][$event])) {
            return null;
        }

        $expectedStatus = self::FLOW_STATUSES[$flow][$event];

        /** @var Collection<int, ServiceResourceStatusReport> $matchingReports */
        $matchingReports = $this->statusReports->filter(function (ServiceResourceStatusReport $resourceStatusReport) use ($flow, $expectedStatus): bool {
            $status = $resourceStatusReport->serviceStatusReport?->status;

            return $status !== null
                && Str::upper(trim((string) $status->status_purpose?->purpose_subcode)) === $flow
                && Str::upper(trim((string) $status->status_be)) === $expectedStatus['be']
                && (int) $status->edifact_code === $expectedStatus['edifact'];
        });

        $reportedAt = $matchingReports
            ->sortByDesc(fn (ServiceResourceStatusReport $report) => $report->reported_at?->getTimestamp() ?? 0)
            ->first()?->reported_at;

        return $reportedAt?->format('Y-m-d');
    }
}
