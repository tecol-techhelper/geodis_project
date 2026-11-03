<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SupportDocumentNaming
{
    public const TYPES = ['CLI', 'CLP', 'IC', 'IF', 'RT', 'ID', 'TRC', 'TDC', 'GABF301', 'PDR', 'GPS', 'RP'];

    public function context(Service $service): array
    {
        $service->loadMissing('purchase_orders.order_references.reference_type');
        $references = $service->purchase_orders->flatMap(fn ($order) => $order->order_references)
            ->filter(fn ($ref) => $ref->deleted_at === null);
        $operation = $references->filter(fn ($ref) => strtoupper(trim((string) $ref->reference_type?->reference_type_code)) === 'ACD')
            ->sortByDesc('id')->first();
        $type = str_replace(['_', ' '], '-', strtoupper(trim((string) $operation?->order_reference_value)));
        $isLog = in_array($type, ['POST-CARRIAGE', 'DOM-CONSOL', 'EMPTY-CONTAINER'], true);
        $isOtx = in_array($type, ['DELIVERY-SO', 'ROAD'], true);
        $targets = [];

        if ($isLog || $isOtx) {
            foreach ($service->purchase_orders as $order) {
                $values = $isLog
                    ? $order->order_references->filter(fn ($ref) => $ref->deleted_at === null && strtoupper(trim((string) $ref->reference_type?->reference_type_code)) === 'COI')
                        ->map(fn ($ref) => ['value' => trim(explode('/', (string) $ref->order_reference_value, 2)[0]), 'reference_id' => $ref->id])
                    : collect([['value' => trim((string) $order->purchase_order_number), 'reference_id' => null]]);

                foreach ($values as $value) {
                    $identifier = $value['value'];
                    if ($identifier === '' || in_array(strtoupper($identifier), ['-', 'N/A', 'NA', 'NULL', 'UNKNOWN'], true)) {
                        continue;
                    }
                    // Prefixing prevents PHP from coercing numeric OC keys to integers.
                    $cni = trim((string) $order->purchase_order_number);
                    $key = 'id:'.$identifier;
                    $targets[$key] ??= ['identifier' => $identifier, 'cni' => $isLog ? null : $cni, 'associations' => []];
                    $targets[$key]['associations'][] = [
                        'purchase_order_id' => $order->id,
                        'cni' => $order->purchase_order_number,
                        'order_reference_id' => $value['reference_id'],
                    ];
                }
            }
        }

        return ['type' => $type, 'is_log' => $isLog, 'label' => $isLog ? 'OC / FN' : 'GR / Identificador CNI', 'targets' => array_values($targets)];
    }

    public function targets(Service $service, ?array $selectedIdentifiers = null): array
    {
        $context = $this->context($service);
        $targets = $context['targets'];
        if ($targets === []) {
            throw ValidationException::withMessages(['files' => 'El servicio no tiene identificadores válidos para generar las copias. Revise sus referencias de origen.']);
        }

        if ($context['is_log'] && $selectedIdentifiers !== null) {
            $available = array_column($targets, 'identifier');
            if ($selectedIdentifiers === [] || collect($selectedIdentifiers)->contains(fn ($identifier) => ! is_string($identifier) || ! in_array($identifier, $available, true))) {
                throw ValidationException::withMessages(['selectedIdentifiers' => 'Seleccione al menos una OC/FN válida de este servicio.']);
            }
            $targets = array_values(array_filter($targets, fn ($target) => in_array($target['identifier'], $selectedIdentifiers, true)));
        }

        return $targets;
    }

    public function normalizeObservations(?string $observations): string
    {
        return preg_replace('/[^A-Za-z0-9]/', '', Str::ascii(trim((string) $observations)));
    }

    public function observations(Service $service, ?string $extra): string
    {
        $so = $this->normalizeObservations($service->consecutive);
        $parts = [$so];
        $extra = $this->normalizeObservations($extra);
        if ($extra !== '' && ! in_array($extra, $parts, true)) {
            $parts[] = $extra;
        }

        return implode('-', array_filter($parts, fn ($part) => $part !== ''));
    }

    public function filename(string $identifier, string $type, string $observations, string $extension, int $sequence = 1): string
    {
        if (! preg_match('/^[A-Za-z0-9]+$/D', $identifier)) {
            throw ValidationException::withMessages(['files' => "El identificador {$identifier} contiene espacios o símbolos no permitidos. Corrija su referencia de origen."]);
        }
        if (! in_array($type, self::TYPES, true)) {
            throw ValidationException::withMessages(['file_type' => 'Seleccione un tipo de soporte válido.']);
        }
        $name = $identifier.'-N-'.$type.($observations !== '' ? '-'.$observations : '')
            .($sequence > 1 ? '-'.$sequence : '').'.'.$extension;
        if (strlen($name) > 50) {
            throw ValidationException::withMessages(['free_text' => 'El nombre completo supera 50 caracteres. Reduzca las observaciones; el identificador no se recortará.']);
        }

        return $name;
    }
}
