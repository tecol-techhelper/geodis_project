<div class="mt-4">
    @if (!$service)
        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 text-sm text-gray-600">
            Seleccione una fila en `Ver Resumen` para ver información adicional.
        </div>
    @else
        <div class="rounded-lg border border-gray-200 bg-white shadow-sm p-4 space-y-4">
            <h3 class="text-base font-semibold text-gray-900">
                Resumen Servicio #{{ $service->consecutive ?? $service->id }}
            </h3>

            <div>
                <h4 class="text-sm font-semibold text-gray-800 mb-2">Órdenes de compra</h4>
                @if ($service->purchase_orders->isEmpty())
                    <p class="text-sm text-gray-600">No hay órdenes de compra asociadas.</p>
                @else
                    <div class="overflow-x-auto border rounded-lg">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left">Orden</th>
                                    <th class="px-3 py-2 text-left">Estado</th>
                                    <th class="px-3 py-2 text-left">Items</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($service->purchase_orders as $po)
                                    <tr>
                                        <td class="px-3 py-2">{{ $po->purchase_order_number ?? '-' }}</td>
                                        <td class="px-3 py-2">
                                            {{ $po->status?->status_name ?? $po->status?->status_be ?? 'Sin estado' }}
                                        </td>
                                        <td class="px-3 py-2">{{ $po->purchase_order_items->count() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div>
                <h4 class="text-sm font-semibold text-gray-800 mb-2">Contactos del servicio</h4>
                @if ($service->service_contacts->isEmpty())
                    <p class="text-sm text-gray-600">No hay contactos registrados.</p>
                @else
                    @php
                        $channelDictionary = [
                            'FX' => 'Fax',
                            'TE' => 'Telefono',
                            'EM' => 'Correo electronico',
                            'TL' => 'Telex',
                        ];
                    @endphp
                    <ul class="space-y-2">
                        @foreach ($service->service_contacts as $contact)
                            <li class="text-sm border border-gray-200 rounded-md p-2">
                                <div>
                                    <span class="font-medium">{{ $contact->contact_name ?? 'Sin nombre' }}</span>
                                    <span class="text-gray-600">
                                        - {{ $contact->contact_type?->type_description ?? 'Tipo no definido' }}
                                    </span>
                                </div>

                                @if ($contact->service_contact_details && $contact->service_contact_details->isNotEmpty())
                                    <ul class="mt-1 space-y-1 text-gray-700">
                                        @foreach ($contact->service_contact_details as $detail)
                                            @php
                                                $channelCode = strtoupper((string) ($detail->channel_contact ?? ''));
                                                $channelLabel = $channelDictionary[$channelCode] ?? ($channelCode !== '' ? $channelCode : 'Canal');
                                                $info = $detail->contact_information ?? '-';
                                            @endphp
                                            <li>{{ $channelLabel }}: {{ $info }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="mt-1 text-gray-500">Sin detalles de contacto.</div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    @endif
</div>
