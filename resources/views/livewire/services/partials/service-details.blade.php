<div class="p-3 bg-gray-50 border border-gray-200 rounded">
    @php
        $id = data_get($row ?? ($service ?? null), 'id');
        $consecutive = data_get($row ?? ($service ?? null), 'consecutive');
    @endphp
    <div class="text-sm text-gray-700">
        Detalle rápido del servicio
    </div>
    <div class="text-xs text-gray-600 mt-1">
        ID: {{ $id ?? '-' }} | Consecutivo: {{ $consecutive ?? '-' }}
    </div>
</div>
