<?php

namespace App\Services\Edi;

use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class GenerateIftstaPayloadService
{
    /**
     * Genera el payload IFTSTA para un Service.
     *
     * Regla: STS se reporta por CNI (purchase_orders) y se inserta justo después del CNI.
     *
     * @param  \App\Models\Service  $service  Service ya existente (idealmente con relaciones cargadas)
     * @param  array<int>|null      $purchaseOrderIds  Si se pasa, solo incluye esos CNI. Si null, usa "pendientes".
     * @return array{payload:string, meta:array<string,mixed>}
     */
    public function generate(Service $service, ?array $purchaseOrderIds = null): array
    {
        // Cargar TODO lo necesario (si ya viene cargado, Eloquent no repite query)
        $service->loadMissing([
            'service_dates',
            'service_parties',
            'service_contacts',
            'service_contacts.service_contact_details',
            'location_details',
            'transport_details',
            'service_equipments',
            'service_measurements',

            'purchase_orders',
            'purchase_orders.order_references',
            'purchase_orders.delivery_terms',
            'purchase_orders.purchase_order_parties',
            'purchase_orders.purchase_order_contacts',
            'purchase_orders.purchase_order_notes',
            'purchase_orders.purchase_order_measurements',
            'purchase_orders.purchase_order_requirements',
            'purchase_orders.transport_charges',

            'purchase_orders.purchase_order_items',
            'purchase_orders.purchase_order_items.item_notes',
            'purchase_orders.purchase_order_items.item_measures',
            'purchase_orders.purchase_order_items.item_dimensions',
            'purchase_orders.purchase_order_items.item_container_identifiers',
            'purchase_orders.purchase_order_items.item_product_identifiers',
            'purchase_orders.purchase_order_items.item_unit_identifiers',
        ]);

        $pos = $this->selectPurchaseOrders($service, $purchaseOrderIds);

        // Si no hay CNIs a reportar, no generes basura.
        if ($pos->isEmpty()) {
            return [
                'payload' => '',
                'meta' => [
                    'service_id' => $service->id,
                    'purchase_orders' => [],
                    'reason' => 'No hay CNI con cambios/pedidos para reportar.',
                ],
            ];
        }

        // Referencias de control (ajusta a tu regla interna si ya tienes un generador estándar)
        $interchangeRef = $this->buildInterchangeRef();
        $messageRef     = $this->buildMessageRef($interchangeRef);

        // Configurables (ponlos en config/ si quieres)
        $senderId   = config('geodis.edifact.sender_id', 'CARRIER_NAME');
        $receiverId = config('geodis.edifact.receiver_id', 'GEOSCO');
        $stsType    = config('geodis.edifact.iftsta_sts_type', '1'); // el primer componente del STS+X+Y

        $segments = [];

        // UNA (siempre igual en tus ejemplos)
        $segments[] = "UNA:+.? '";

        // UNB (intercambio)
        $segments[] = $this->seg("UNB+UNOC:3+{$senderId}:22+{$receiverId}:22+{$this->formatDate('ymd')}:{$this->formatDate('Hi')}+{$interchangeRef}");

        // UNH (mensaje)
        $segments[] = $this->seg("UNH+{$messageRef}+IFTSTA:D:97A:UN");

        /**
         * HEADER del mensaje:
         * Te dejo lo más seguro con lo que ya tienes:
         * - BGM del service (services.raw_segment)
         * - DTM del service (service_dates.raw_segment)
         * - NAD del service (service_parties.raw_segment)
         *
         * Si mañana GEODIS exige RFF a nivel header y no lo tienes como tabla,
         * lo agregas aquí o lo generas desde donde corresponda.
         */
        $segments = array_merge($segments, $this->collectServiceHeaderSegments($service));

        /**
         * BLOQUE POR CNI (Purchase Order):
         * CNI + STS + resto (RFF/DTM/LOC/TDT/… según raw_segment que tengas guardado)
         */
        foreach ($pos as $po) {
            // 1) CNI (purchase_orders.raw_segment)
            $segments[] = $this->seg((string) $po->raw_segment);

            // 2) STS por CNI (tu nueva regla)
            //    status_id es lo que GEODIS quiere ver (según tu definición).
            $statusId = $po->status_id;
            if ($statusId !== null) {
                $segments[] = $this->seg("STS+{$stsType}+{$statusId}");
            } else {
                // Si no hay status, no invento uno. Solo omito STS.
                // (Si GEODIS exige STS siempre, mañana lo defines).
            }

            // 3) Resto de segmentos del PO (por raw_segment / *_segment_raw)
            $segments = array_merge($segments, $this->collectPurchaseOrderSegments($po));
        }

        // UNT / UNZ (conteos)
        $segments = $this->finalizeWithTrailers($segments, $messageRef, $interchangeRef);

        $payload = implode("\n", $segments) . "\n";

        return [
            'payload' => $payload,
            'meta' => [
                'service_id' => $service->id,
                'message_type' => 'IFTSTA',
                'interchange_ref' => $interchangeRef,
                'message_ref' => $messageRef,
                'purchase_orders' => $pos->pluck('id')->values()->all(),
            ],
        ];
    }

    /**
     * Selecciona qué Purchase Orders (CNI) van en el IFTSTA.
     * - Si vienen IDs: solo esos.
     * - Si no vienen: usa "pendientes" (hoy básico: status_id != null).
     */
    protected function selectPurchaseOrders(Service $service, ?array $purchaseOrderIds): Collection
    {
        $pos = $service->purchase_orders ?? collect();

        if (is_array($purchaseOrderIds) && count($purchaseOrderIds) > 0) {
            $ids = array_map('intval', $purchaseOrderIds);
            return $pos->whereIn('id', $ids)->values();
        }

        // Pendientes (versión simple por ahora):
        // Si mañana agregas last_iftsta_status_id / last_iftsta_sent_at, cambias esta condición.
        return $pos->filter(fn($po) => $po->status_id !== null)->values();
    }

    /**
     * Segmentos header del Service usando raw_segment.
     */
    protected function collectServiceHeaderSegments(Service $service): array
    {
        $out = [];

        // BGM (services.raw_segment)
        if (!empty($service->raw_segment)) {
            $out[] = $this->seg((string) $service->raw_segment);
        }

        // DTM de fecha/hora del mensaje (obligatorio justo despuÃ©s de BGM)
        // Formato requerido: DTM+137:{yyyymmddhhmm}:203'
        $out[] = $this->seg('DTM+137:' . now()->format('YmdHi') . ':203');

        // DTM + NAD
        $this->appendRawSegments($out, $service->service_dates ?? collect());
        $this->appendRawSegments($out, $service->service_parties ?? collect());

        // CTA + COM (service_contacts + service_contact_details)
        foreach (($service->service_contacts ?? collect()) as $contact) {
            $this->appendRawSegments($out, collect([$contact]));
            $this->appendRawSegments($out, $contact->service_contact_details ?? collect());
        }

        // Hijos service-level adicionales (solo si tienen datos/segmento)
        $this->appendRawSegments($out, $service->location_details ?? collect());
        $this->appendRawSegments($out, $service->transport_details ?? collect());
        $this->appendRawSegments($out, $service->service_equipments ?? collect());
        $this->appendRawSegments($out, $service->service_measurements ?? collect());

        return $out;
    }

    protected function appendRawSegments(array &$out, Collection $models): void
    {
        foreach ($models as $model) {
            if (!empty($model?->raw_segment)) {
                $out[] = $this->seg((string) $model->raw_segment);
            }
        }
    }

    /**
     * Segmentos por Purchase Order, usando raw_segment / *_segment_raw.
     * Respeta lo que tengas guardado, sin adivinar contenido.
     */
    protected function collectPurchaseOrderSegments($po): array
    {
        $out = [];

        // Order references (RFF)
        foreach (($po->order_references ?? collect()) as $rff) {
            if (!empty($rff->raw_segment)) {
                $out[] = $this->seg((string) $rff->raw_segment);
            }
        }

        // Delivery terms
        foreach (($po->delivery_terms ?? collect()) as $term) {
            if (!empty($term->raw_segment)) {
                $out[] = $this->seg((string) $term->raw_segment);
            }
        }

        // Parties / Contacts / Notes / Measurements / Requirements
        foreach (($po->purchase_order_parties ?? collect()) as $p) {
            if (!empty($p->raw_segment)) $out[] = $this->seg((string) $p->raw_segment);
        }

        foreach (($po->purchase_order_contacts ?? collect()) as $c) {
            if (!empty($c->raw_segment)) $out[] = $this->seg((string) $c->raw_segment);
        }

        foreach (($po->purchase_order_notes ?? collect()) as $n) {
            if (!empty($n->raw_segment)) $out[] = $this->seg((string) $n->raw_segment);
        }

        foreach (($po->purchase_order_measurements ?? collect()) as $m) {
            if (!empty($m->raw_segment)) $out[] = $this->seg((string) $m->raw_segment);
        }

        foreach (($po->purchase_order_requirements ?? collect()) as $r) {
            if (!empty($r->raw_segment)) $out[] = $this->seg((string) $r->raw_segment);
        }

        // Transport charges (PRI / TCC raws)
        foreach (($po->transport_charges ?? collect()) as $ch) {
            if (!empty($ch->pri_segment_raw)) $out[] = $this->seg((string) $ch->pri_segment_raw);
            if (!empty($ch->tcc_segment_raw)) $out[] = $this->seg((string) $ch->tcc_segment_raw);
        }

        // Items + sus hijos
        foreach (($po->purchase_order_items ?? collect()) as $item) {
            if (!empty($item->raw_segment)) {
                $out[] = $this->seg((string) $item->raw_segment);
            }

            foreach (($item->item_notes ?? collect()) as $n) {
                if (!empty($n->raw_segment)) $out[] = $this->seg((string) $n->raw_segment);
            }

            foreach (($item->item_measures ?? collect()) as $m) {
                if (!empty($m->raw_segment)) $out[] = $this->seg((string) $m->raw_segment);
            }

            foreach (($item->item_dimensions ?? collect()) as $d) {
                if (!empty($d->raw_segment)) $out[] = $this->seg((string) $d->raw_segment);
            }

            foreach (($item->item_container_identifiers ?? collect()) as $ci) {
                if (!empty($ci->raw_segment)) $out[] = $this->seg((string) $ci->raw_segment);
            }

            foreach (($item->item_product_identifiers ?? collect()) as $pi) {
                if (!empty($pi->raw_segment)) $out[] = $this->seg((string) $pi->raw_segment);
            }

            foreach (($item->item_unit_identifiers ?? collect()) as $ui) {
                if (!empty($ui->raw_segment)) $out[] = $this->seg((string) $ui->raw_segment);
            }
        }

        return $out;
    }

    /**
     * Agrega UNT/UNZ calculando conteos correctamente.
     */
    protected function finalizeWithTrailers(array $segments, string $messageRef, string $interchangeRef): array
    {
        // Contar desde UNH hasta (incluyendo) UNT.
        // Buscamos el índice del UNH
        $unhIndex = null;
        foreach ($segments as $i => $seg) {
            if (Str::startsWith($seg, 'UNH+')) {
                $unhIndex = $i;
                break;
            }
        }

        // Si no hay UNH, algo está roto
        if ($unhIndex === null) {
            return $segments;
        }

        // UNT se añade al final, pero el conteo incluye UNT,
        // así que calculamos (cantidad desde UNH hasta final + 1 por el UNT)
        $countFromUnhToEnd = count($segments) - $unhIndex;
        $untCount = $countFromUnhToEnd + 1;

        $segments[] = $this->seg("UNT+{$untCount}+{$messageRef}");
        $segments[] = $this->seg("UNZ+1+{$interchangeRef}");

        return $segments;
    }

    /**
     * Normaliza un segmento: limpia saltos, quita/aplica comilla final.
     */
    protected function seg(string $raw): string
    {
        $s = trim(str_replace(["\r\n", "\r", "\n"], '', $raw));
        $s = rtrim($s, "'");
        return $s . "'";
    }

    protected function buildInterchangeRef(): string
    {
        // Ej: 100008071 (como tu screenshot). Si ya tienes numeración oficial, reemplaza esto.
        return (string) random_int(100000000, 999999999);
    }

    protected function buildMessageRef(string $interchangeRef): string
    {
        // Puedes igualarlo al interchange o derivarlo
        return $interchangeRef;
    }

    protected function formatDate(string $format): string
    {
        return Carbon::now()->format($format);
    }
}

