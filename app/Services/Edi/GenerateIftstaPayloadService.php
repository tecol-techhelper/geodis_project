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
     * Regla: STS se reporta por CNI (purchase_orders) y se inserta justo despuÃ©s del CNI.
     *
     * @param  \App\Models\Service  $service  Service ya existente (idealmente con relaciones cargadas)
     * @param  array<int>|null      $purchaseOrderIds  Si se pasa, solo incluye esos CNI. Si null, usa "pendientes".
     * @param  string|null          $resourceId  Si se pasa, solo incluye ese RFF+FS (1 por mensaje).
     * @param  Carbon|string|null   $statusReportedAt  Fecha/hora manual para DTM+7.
     * @return array{payload:string, meta:array<string,mixed>}
     */
    public function generate(
        Service $service,
        ?array $purchaseOrderIds = null,
        ?string $resourceId = null,
        Carbon|string|null $statusReportedAt = null
    ): array
    {
        // Cargar TODO lo necesario (si ya viene cargado, Eloquent no repite query)
        $service->loadMissing([
            'status',
            'service_dates',
            'service_parties',
            'service_contacts',
            'service_contacts.service_contact_details',
            'location_details',
            'transport_details',
            'service_equipments',
            'service_measurements',
            'resources',

            'purchase_orders',
            'purchase_orders.order_references',
            'purchase_orders.order_references.reference_type',
            'purchase_orders.delivery_terms',
            'purchase_orders.purchase_order_parties',
            'purchase_orders.purchase_order_contacts',
            'purchase_orders.purchase_order_contacts.purchase_order_contact_details',
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
        $messagePos = $pos->take(1)->values();

        // Si no hay CNIs a reportar, no generes basura.
        if ($messagePos->isEmpty()) {
            return [
                'payload' => '',
                'meta' => [
                    'service_id' => $service->id,
                    'purchase_orders' => [],
                    'reason' => 'No hay CNI con cambios/pedidos para reportar.',
                ],
            ];
        }

        // Referencias de control (ajusta a tu regla interna si ya tienes un generador estÃ¡ndar)
        $interchangeRef = $this->buildInterchangeRef();
        $messageRef     = 1;

        // Configurables (ponlos en config/ si quieres)
        $senderId   = config('edi.unb.sender_id', config('geodis.edifact.sender_id', 'TRANSTECOL'));
        $receiverId = config('edi.unb.receiver_id', config('geodis.edifact.receiver_id', 'GEOSCOT'));
        $stsType    = config('edi.iftsta.sts_type', config('geodis.edifact.iftsta_sts_type', '1')); // el primer componente del STS+X+Y

        $segments = [];

        // UNA (siempre igual en tus ejemplos)
        $segments[] = "UNA:+.? '";

        // UNB (intercambio)
        $segments[] = $this->seg("UNB+UNOC:3+{$senderId}:22+{$receiverId}:22+{$this->formatDate('ymd')}:{$this->formatDate('Hi')}+{$interchangeRef}");

        // UNH (mensaje)
        $segments[] = $this->seg("UNH+{$messageRef}+IFTSTA:D:97A:UN");

        /**
         * HEADER del mensaje:
         * Te dejo lo mÃ¡s seguro con lo que ya tienes:
         * - BGM del service (services.raw_segment)
         * - DTM del service (service_dates.raw_segment)
         * - NAD del service (service_parties.raw_segment)
         *
         * Si maÃ±ana GEODIS exige RFF a nivel header y no lo tienes como tabla,
         * lo agregas aquÃ­ o lo generas desde donde corresponda.
         */
        $segments = array_merge($segments, $this->collectServiceHeaderSegments($service));

        /**
         * BLOQUE POR CNI (Purchase Order):
         * CNI + STS + resto (RFF/DTM/LOC/TDT/â€¦ segÃºn raw_segment que tengas guardado)
         */
        foreach ($messagePos as $poIndex => $po) {
            $isFirstCni = $poIndex === 0;

            // 1) CNI (purchase_orders.raw_segment)
            $segments[] = $this->seg((string) $po->raw_segment);

            // 2) CNT dentro de CNI antes de STS
            if (!$this->shouldOmitSegment('CNT')) {
                foreach (($po->purchase_order_measurements ?? collect()) as $m) {
                    if (!empty($m->raw_segment)) {
                        $segments[] = $this->seg((string) $m->raw_segment);
                    }
                }
            }

            $edifactCode = $service->status?->edifact_code;

            // 3) STS solo una vez, en el primer bloque CNI.
            if ($isFirstCni && $edifactCode !== null && $edifactCode !== '') {
                $segments[] = $this->seg("STS+{$stsType}+{$edifactCode}");
            }

            // 3.1) RFF (solo SRN del servicio) justo despues de STS
            if (!$this->shouldOmitSegment('RFF')) {
                foreach (($po->order_references ?? collect()) as $rff) {
                    $refType = $rff->reference_type?->reference_type_code ?? null;
                    if ($refType !== 'SRN') {
                        continue;
                    }
                    if (!empty($rff->raw_segment)) {
                        $segments[] = $this->seg((string) $rff->raw_segment);
                        continue;
                    }
                    if (!empty($rff->order_reference_value)) {
                        $segments[] = $this->seg('RFF+SRN:' . $rff->order_reference_value);
                    }
                }

                // RFF+FS: solo una vez, en el primer bloque CNI.
                if ($isFirstCni) {
                    $resourceId = $resourceId !== null ? trim((string) $resourceId) : null;
                    if ($resourceId !== null && $resourceId !== '') {
                        $segments[] = $this->seg('RFF+FS:' . $resourceId);
                    } else {
                        $resourceIds = $service->resources?->pluck('resource_id')->filter()->map(fn ($v) => trim((string) $v))->filter()->values() ?? collect();
                        foreach ($resourceIds as $rid) {
                            if ($rid !== '') {
                                $segments[] = $this->seg('RFF+FS:' . $rid);
                                break;
                            }
                        }
                    }
                }
            }

            // DTM+7 asociado al bloque de estado, por lo tanto se emite una sola vez.
            if ($isFirstCni && $edifactCode !== null && $edifactCode !== '' && !$this->shouldOmitSegment('DTM')) {
                $dtmReportedAt = $statusReportedAt;
                if (is_string($dtmReportedAt)) {
                    $dtmReportedAt = Carbon::parse($dtmReportedAt);
                }
                if (!$dtmReportedAt instanceof Carbon) {
                    $dtmReportedAt = $service->updated_at ?? now();
                }
                $statusDateTime = $dtmReportedAt->format('YmdHi');
                $segments[] = $this->seg("DTM+7:{$statusDateTime}:203");
            }

            // 4) Resto de segmentos del PO (por raw_segment / *_segment_raw)
            $segments = array_merge($segments, $this->collectPurchaseOrderSegments($service, $po));
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
                'resource_id' => $resourceId,
                'status_reported_at' => $statusReportedAt ? (string) $statusReportedAt : null,
                'purchase_orders' => $messagePos->pluck('id')->values()->all(),
            ],
        ];
    }

    /**
     * Selecciona quÃ© Purchase Orders (CNI) van en el IFTSTA.
     * - Si vienen IDs: solo esos.
     * - Si no vienen: usa "pendientes" (hoy bÃ¡sico: status_id != null).
     */
    protected function selectPurchaseOrders(Service $service, ?array $purchaseOrderIds): Collection
    {
        $pos = $service->purchase_orders ?? collect();

        if (is_array($purchaseOrderIds) && count($purchaseOrderIds) > 0) {
            $ids = array_map('intval', $purchaseOrderIds);
            return $pos->whereIn('id', $ids)->values();
        }

        // Pendientes (versiÃ³n simple por ahora):
        // Si maÃ±ana agregas last_iftsta_status_id / last_iftsta_sent_at, cambias esta condiciÃ³n.
        return $service->status_id !== null ? $pos->values() : collect();
    }

    /**
     * Segmentos header del Service usando raw_segment.
     */
    protected function collectServiceHeaderSegments(Service $service): array
    {
        $out = [];

        // BGM (prefer legacy raw_segment if it is actually BGM)
        $bgmSegment = null;
        if (!empty($service->raw_segment) && str_starts_with((string) $service->raw_segment, 'BGM+')) {
            $bgmSegment = (string) $service->raw_segment;
        } elseif (!empty($service->consecutive)) {
            $bgmSegment = 'BGM+335+' . $service->consecutive . '+9';
        }

        if (!empty($bgmSegment)) {
            $out[] = $this->seg($bgmSegment);
        }

        // DTM de fecha/hora del mensaje (obligatorio justo despuÃƒÂ©s de BGM)
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
        if (!$this->shouldOmitSegment('EQD')) {
            $this->appendRawSegments($out, $service->service_equipments ?? collect());
        }
        // CNT fuera de CNI: excluidos por requerimiento

        return $out;
    }

    protected function appendRawSegments(array &$out, Collection $models): void
    {
        foreach ($models as $model) {
            $tag = $model?->segment_tag ? strtoupper((string) $model->segment_tag) : null;
            if ($tag && $this->shouldOmitSegment($tag)) {
                continue;
            }
            if (!empty($model?->raw_segment)) {
                $out[] = $this->seg((string) $model->raw_segment);
            }
        }
    }

    /**
     * Segmentos por Purchase Order, usando raw_segment / *_segment_raw.
     * Respeta lo que tengas guardado, sin adivinar contenido.
     */
    protected function collectPurchaseOrderSegments(Service $service, $po): array
    {
        $out = [];

        // Delivery terms (TOD)
        if (!$this->shouldOmitSegment('TOD')) {
            foreach (($po->delivery_terms ?? collect()) as $term) {
                if (!empty($term->raw_segment)) {
                    $out[] = $this->seg((string) $term->raw_segment);
                }
            }
        }

        // Requirements (TSR) antes de Parties (NAD)
        if (!$this->shouldOmitSegment('TSR')) {
            foreach (($po->purchase_order_requirements ?? collect()) as $r) {
                if (!empty($r->raw_segment)) $out[] = $this->seg((string) $r->raw_segment);
            }
        }

        // Notes (FTX) antes de Parties (NAD)
        if (!$this->shouldOmitSegment('FTX')) {
            foreach (($po->purchase_order_notes ?? collect()) as $n) {
                if (!empty($n->raw_segment)) $out[] = $this->seg((string) $n->raw_segment);
            }
        }

        // Parties (NAD) despues de FTX
        if (!$this->shouldOmitSegment('NAD')) {
            foreach (($po->purchase_order_parties ?? collect()) as $p) {
                if (!empty($p->raw_segment)) $out[] = $this->seg((string) $p->raw_segment);
            }
        }

        // CTA/COM dentro de CNI: excluidos por requerimiento

        // Transport charges (PRI / TCC)
        if (!$this->shouldOmitSegment('TCC') && !$this->shouldOmitSegment('PRI')) {
            foreach (($po->transport_charges ?? collect()) as $ch) {
                if (!empty($ch->pri_segment_raw) && !$this->shouldOmitSegment('PRI')) {
                    $out[] = $this->seg((string) $ch->pri_segment_raw);
                }
                if (!empty($ch->tcc_segment_raw) && !$this->shouldOmitSegment('TCC')) {
                    $out[] = $this->seg((string) $ch->tcc_segment_raw);
                }
            }
        }

        // TDT dentro de CNI antes de RFF
        if (!$this->shouldOmitSegment('TDT')) {
            $this->appendRawSegments($out, $service->transport_details ?? collect());
        }

        // Items + sus hijos
        foreach (($po->purchase_order_items ?? collect()) as $item) {
            if (!empty($item->raw_segment) && !$this->shouldOmitSegment('GID')) {
                $out[] = $this->seg((string) $item->raw_segment);
            }

            if (!$this->shouldOmitSegment('FTX')) {
                foreach (($item->item_notes ?? collect()) as $n) {
                    if (!empty($n->raw_segment)) $out[] = $this->seg((string) $n->raw_segment);
                }
            }

            if (!$this->shouldOmitSegment('MEA')) {
                foreach (($item->item_measures ?? collect()) as $m) {
                    if (!empty($m->raw_segment)) $out[] = $this->seg((string) $m->raw_segment);
                }
            }

            if (!$this->shouldOmitSegment('DIM')) {
                foreach (($item->item_dimensions ?? collect()) as $d) {
                    if (!empty($d->raw_segment)) $out[] = $this->seg((string) $d->raw_segment);
                }
            }

            if (!$this->shouldOmitSegment('PCI')) {
                foreach (($item->item_container_identifiers ?? collect()) as $ci) {
                    if (!empty($ci->raw_segment)) $out[] = $this->seg((string) $ci->raw_segment);
                }
            }

            if (!$this->shouldOmitSegment('PIA')) {
                foreach (($item->item_product_identifiers ?? collect()) as $pi) {
                    if (!empty($pi->raw_segment)) $out[] = $this->seg((string) $pi->raw_segment);
                }
            }

            if (!$this->shouldOmitSegment('GIN')) {
                foreach (($item->item_unit_identifiers ?? collect()) as $ui) {
                    if (!empty($ui->raw_segment)) $out[] = $this->seg((string) $ui->raw_segment);
                }
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
        // Buscamos el Ã­ndice del UNH
        $unhIndex = null;
        foreach ($segments as $i => $seg) {
            if (Str::startsWith($seg, 'UNH+')) {
                $unhIndex = $i;
                break;
            }
        }

        // Si no hay UNH, algo estÃ¡ roto
        if ($unhIndex === null) {
            return $segments;
        }

        // UNT se aÃ±ade al final, pero el conteo incluye UNT,
        // asÃ­ que calculamos (cantidad desde UNH hasta final + 1 por el UNT)
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

    protected function shouldOmitSegment(string $tag): bool
    {
        $omit = config('edi.omit_segments', []);
        if (!is_array($omit)) {
            return false;
        }
        return in_array(strtoupper($tag), array_map('strtoupper', $omit), true);
    }

    protected function buildInterchangeRef(): string
    {
        // Ej: 100008071 (como tu screenshot). Si ya tienes numeraciÃ³n oficial, reemplaza esto.
        return (string) random_int(100000000, 999999999);
    }

    // protected function buildMessageRef(string $interchangeRef): string
    // {
    //     // Puedes igualarlo al interchange o derivarlo
    //     return $interchangeRef;
    // }

    protected function formatDate(string $format): string
    {
        return Carbon::now()->format($format);
    }
}
