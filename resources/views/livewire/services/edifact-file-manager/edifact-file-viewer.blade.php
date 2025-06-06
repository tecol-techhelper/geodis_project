@section('title', 'Messages Edi Viewer')
<div class="space-y-6">
    {{-- <h2 class="text-xl font-bold mb-4">Contenido procesado del archivo: {{ $fileName }}</h2> --}}
    <x-breadcrums :items="[
        ['label' => 'Inicio', 'url' => route('dashboard'), 'icon' => 'home'],
        ['label' => 'Parsed Files', 'url' => '#', 'icon' => 'file-json'],
        ['label' => 'Messages Viewer', 'icon' => 'eye']
    ]"></x-breadcrums>
    @forelse ($parsedMessages as $i => $message)
        <div class="mb-6 p-4 border border-gray-300 rounded shadow-sm bg-gray-50">
            <h3 class="font-semibold text-lg mb-2">Mensaje #{{ $i + 1 }}</h3>
            <ul class="text-sm space-y-1">
                @foreach ($message as $key => $value)
                    {{-- <li><strong>{{ ucfirst($key) }}:</strong> {{ is_array($value) ? json_encode($value) : $value }}</li> --}}
                    <li>
                        <strong>{{ ucfirst($key) }}:</strong>
                        @if (is_array($value))
                            <ul class="ml-4 list-disc text-gray-700">
                                @foreach ($value as $subKey => $subValue)
                                    <li>{{ is_string($subKey) ? ucfirst($subKey) . ': ' : '' }}{{ $subValue }}</li>
                                @endforeach
                            </ul>
                        @else
                            {{ $value }}
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @empty
        <p class="text-gray-500">No se encontraron mensajes parseados para este archivo.</p>
    @endforelse
</div>
