<?php

namespace App\Http\Resources\Api\Geodis;

use App\Models\ServiceResourceReportLine;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpedienteResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $catalogResource = $this->resource->resource;

        return [
            'so' => $this->service?->consecutive !== null
                ? (string) $this->service->consecutive
                : null,
            'n_consolidado' => $this->consolidatedNumbers(),
            'orden_compra' => $this->purchaseOrderNumbers(),
            'item' => $catalogResource?->id !== null
                ? (string) $catalogResource->id
                : null,
            'item_geodis' => null,
            'recurso' => $catalogResource?->resource_id,
            'administrativo' => $this->administrativeData(),
            'unidad' => 1,
            'informe_final' => $this->finalReport(),
            'fecha_de_posicionamiento' => $this->service?->positioning_date?->format('Y-m-d'),
            'fecha_de_arribo' => $this->service?->arrival_date?->format('Y-m-d'),
            'numero_contenedor' => $this->report?->container?->container_number,
        ];
    }

    /** @return array<int, array<string, mixed>> */
    private function finalReport(): array
    {
        $lines = $this->report?->lines ?? collect();
        if ($lines->isEmpty()) {
            return [];
        }

        $isTransport = trim((string) $this->resource->resource?->operation?->name) === 'TRANSPORTE';

        return $lines->map(fn (ServiceResourceReportLine $line): array => [
            'remesa' => $isTransport ? $line->remesa_transporte : $this->report->remesa_transporte,
            'regional' => $line->resolved_regional,
            'origen' => $line->origin?->origin,
            'fecha_de_cargue' => $line->origin_date?->format('Y-m-d'),
            'destino' => $line->destination?->origin,
            'fecha_de_descargue' => $line->destination_date?->format('Y-m-d'),
            'concepto' => $line->concept?->name,
            'cantidad' => $line->quantity,
            'valor_unitario' => $line->unit_price,
            'valor_total' => $line->total_price,
        ])->values()->all();
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
            ->filter(fn($reportedPersonnel) => $reportedPersonnel->operator !== null)
            ->map(function ($reportedPersonnel) use ($plate): array {
                $operator = $reportedPersonnel->operator;
                $operatorName = trim($operator->first_name . ' ' . $operator->last_name);

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

    private function consolidatedNumbers(): ?string
    {
        return $this->referenceValues('AGW', function (string $value): ?string {
            $parts = array_map('trim', explode('/', $value, 2));

            return ($parts[1] ?? '') !== '' ? $parts[1] : null;
        });
    }

    private function purchaseOrderNumbers(): ?string
    {
        return $this->referenceValues('COI', fn(string $value): ?string => trim(explode('/', $value, 2)[0] ?? '') ?: null);
    }

    /**
     * @param  callable(string): ?string  $valueExtractor
     */
    private function referenceValues(string $referenceTypeCode, callable $valueExtractor): ?string
    {
        $values = ($this->service?->purchase_orders ?? collect())
            ->flatMap(fn($purchaseOrder) => $purchaseOrder->order_references)
            ->filter(
                fn($reference) => strtoupper(trim((string) $reference->reference_type?->reference_type_code)) === $referenceTypeCode,
            )
            ->map(fn($reference) => $valueExtractor(trim((string) $reference->order_reference_value)))
            ->filter(fn($value) => filled($value))
            ->unique()
            ->values();

        return $values->isEmpty() ? null : $values->implode('/');
    }

}
