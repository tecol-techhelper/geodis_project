{{-- resources/views/partials/service-details.blade.php --}}

<div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg shadow space-y-6">

    {{-- =========================
        CONTACTOS (CTA + COM)
    ========================== --}}
    <div>
        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">
            Información de contacto
        </h3>

        @php
            // Acepta $service o $row (PowerGrid)
            $service = $service ?? ($row ?? null);

            // Relación esperada: service_contacts
            $contacts = $service?->service_contacts ?? collect();

            // Solución A: mapa fijo de códigos COM (channel_contact) -> nombre
            $channelNames = [
                'TE' => 'Teléfono',
                'EM' => 'Correo electrónico',
                'FX' => 'Fax',
                'MO' => 'Móvil',
                'WH' => 'WhatsApp',
                'TL' => 'Teléfono',
                'UR' => 'URL',
                'VA' => 'VAT / Identificación',
            ];
        @endphp

        @if ($contacts->isEmpty())
            <div class="text-sm text-gray-600 dark:text-gray-300">
                No hay contactos registrados.
            </div>
        @else
            <div class="space-y-3">
                @foreach ($contacts as $c)
                    @php
                        // type_name (para badge derecho)
                        $typeName = trim((string) optional($c->contact_type)->type_name);
                        $typeName = $typeName !== '' ? $typeName : 'Tipo no definido';

                        // contact_description (para etiqueta al lado del nombre)
                        $typeDescription = trim((string) optional($c->contact_type)->contact_description);

                        // Relación real en tu modelo: service_contact_details()
                        $details = $c->service_contact_details ?? collect();
                    @endphp

                    <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-3">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100 break-words">
                                        {{ $c->contact_name ?: 'Sin nombre' }}
                                    </div>

                                    {{-- Etiqueta al lado del nombre: contact_description --}}
                                    @if ($typeDescription !== '')
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded
                                                   bg-blue-50 dark:bg-blue-900/30
                                                   text-xs font-semibold
                                                   text-blue-700 dark:text-blue-200">
                                            {{ $typeDescription }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Badge del tipo (type_name) --}}
                            <span
                                class="shrink-0 inline-flex items-center px-2 py-0.5 rounded
                                       bg-gray-100 dark:bg-gray-800 text-xs font-semibold
                                       text-gray-700 dark:text-gray-200">
                                {{ $typeName }}
                            </span>
                        </div>

                        {{-- Detalles COM --}}
                        @if ($details->isEmpty())
                            <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                Sin información de contacto adicional
                            </div>
                        @else
                            <ul class="mt-3 space-y-2 text-sm text-gray-700 dark:text-gray-200">
                                @foreach ($details as $d)
                                    @php
                                        $channelCode = strtoupper(trim((string) ($d->channel_contact ?? 'UNK')));
                                        $channelLabel = $channelNames[$channelCode] ?? $channelCode;

                                        $info = trim((string) ($d->contact_information ?? '-'));
                                        $info = $info !== '' ? $info : '-';
                                    @endphp

                                    <li class="flex flex-wrap gap-2 items-start">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded
                                                   bg-gray-100 dark:bg-gray-800 text-xs font-medium
                                                   text-gray-700 dark:text-gray-200">
                                            {{ $channelLabel }}
                                        </span>

                                        <span class="break-all">
                                            {{ $info }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- =========================
        ORDENES DE COMPRA
    ========================== --}}
    <div>
        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">
            Órdenes de compra asociadas
        </h3>

        @php
            $purchaseOrders = $service?->purchase_orders ?? collect();

            // Evitar reventar si PurchaseOrder no tiene items()
            $purchaseOrderHasItemsRelation = false;
            if ($purchaseOrders instanceof \Illuminate\Support\Collection && $purchaseOrders->isNotEmpty()) {
                $firstPo = $purchaseOrders->first();
                $purchaseOrderHasItemsRelation = $firstPo && method_exists($firstPo, 'items');
            }
        @endphp

        @if ($purchaseOrders->isEmpty())
            <div class="text-sm text-gray-600 dark:text-gray-300">
                No hay órdenes de compra asociadas.
            </div>
        @else
            <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900">
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($purchaseOrders as $index => $po)
                        @php
                            $poNumber = $po->purchase_order_number ?? null;
                            $seq = $po->purchase_order_secuence ?? null;

                            $labelSeq = $seq ?? $index + 1;

                            // Contador de items solo si existe relación
                            $itemsCount = null;
                            if ($purchaseOrderHasItemsRelation) {
                                try {
                                    $itemsCount = $po->items()->count();
                                } catch (\Throwable $e) {
                                    $itemsCount = null;
                                }
                            }
                        @endphp

                        <li class="p-3 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100 break-all">
                                    Orden de Compra N°{{ $labelSeq }} - {{ $poNumber ?: 'Sin número' }}
                                </div>
                            </div>

                            @if ($itemsCount !== null)
                                <span
                                    class="shrink-0 inline-flex items-center px-2 py-0.5 rounded
                                           bg-gray-100 dark:bg-gray-800 text-xs
                                           text-gray-700 dark:text-gray-200">
                                    Items: {{ $itemsCount }}
                                </span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

</div>
