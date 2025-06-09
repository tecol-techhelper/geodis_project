@section('title', 'Consulta de Disponilidad')
<div class="space-y-6">
    <x-breadcrums :items="[
        ['label' => 'Inicio', 'url' => route('dashboard'), 'icon' => 'home'],
        ['label' => 'Servicios', 'url' => route('user.index'), 'icon' => 'file-question'],
        ['label' => 'Consulta de Disponibilidad', 'icon' => 'package-search'],
    ]"></x-breadcrums>

    <form wire:submit.prevent="" enctype="multipart/form-data"
        class="px-6 py-6 space-y-6 border-2 border-gray-200 bg-white shadow-2xl rounded-2xl">
        <h1 class="flex items-center text-5xl font-extrabold dark:text-white space-x-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-package-search-icon lucide-package-search">
                <path
                    d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14" />
                <path d="m7.5 4.27 9 5.15" />
                <polyline points="3.29 7 12 12 20.71 7" />
                <line x1="12" x2="12" y1="22" y2="12" />
                <circle cx="18.5" cy="15.5" r="2.5" />
                <path d="M20.27 17.27 22 19" />
            </svg>
            <span>Consulta de Disponibilidad</span>
        </h1>

        {{-- Contenedor responsivo: 1 columna en móviles, 2 en pantallas medianas en adelante --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <x-input-label for="item" class="block text-sm font-medium">Item</x-input-label>
                <div class=" flex mt-1 block w-full">
                    <span
                        class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 border-e-0 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                        {{ date('m') }}-
                    </span>
                    <x-text-input type="text" id="item" wire:model.defer="item"
                        class="rounded-none rounded-e-md min-w-0 w-full" />
                </div>
                {{-- <x-text-input type="text" id="item" 
                    class="mt-1 block w-full border rounded px-3 py-2" /> --}}
            </div>

            <div>
                <x-input-label for="consecutive" class="block text-sm font-medium">Consecutivo</x-input-label>
                <x-text-input type="text" id="purchase_order" wire:model.defer="purchase_order"
                    class="mt-1 block w-full border rounded px-3 py-2" />
            </div>

            <div>
                <x-input-label for="purchase_order" class="block text-sm font-medium">Orden de Servicio</x-input-label>
                <x-text-input type="text" id="purchase_order" wire:model.defer="purchase_order"
                    class="mt-1 block w-full border rounded px-3 py-2" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div>
                <x-input-label for="type" class="block text-sm font-medium">Tipo</x-input-label>
                <x-text-input type="text" id="type" wire:model.defer="type"
                    class="mt-1 block w-full border rounded px-3 py-2" />
            </div>

            <div>
                <x-input-label for="company" class="block text-sm font-medium">Empresa</x-input-label>
                <x-text-input type="text" id="company" wire:model.defer="company"
                    class="mt-1 block w-full border rounded px-3 py-2" />
            </div>
        </div>

        <div class="mt-4">

            <x-input-label for="observations" class="block text-sm font-medium">Observaciones</x-input-label>
            <x-textare-input id="observations" wire:model.defer="observations" rows="4"
                class="mt-1 block w-full border rounded px-3 py-2"></x-textare-input>
        </div>

        <div class="flex justify-end space-x-4 mt-6">
            <x-danger-button type="button" class="px-4 py-2 border rounded space-x-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-package-x-icon lucide-package-x">
                    <path
                        d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14" />
                    <path d="m7.5 4.27 9 5.15" />
                    <polyline points="3.29 7 12 12 20.71 7" />
                    <line x1="12" x2="12" y1="22" y2="12" />
                    <path d="m17 13 5 5m-5 0 5-5" />
                </svg>
                <span>Rechazar</span></x-danger-button>
            <x-success-button type="submit" class="px-4 py-2 border rounded space-x-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-package-check-icon lucide-package-check">
                    <path d="m16 16 2 2 4-4" />
                    <path
                        d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14" />
                    <path d="m7.5 4.27 9 5.15" />
                    <polyline points="3.29 7 12 12 20.71 7" />
                    <line x1="12" x2="12" y1="22" y2="12" />
                </svg>
                <span>Aceptar</span>
            </x-success-button>
        </div>
    </form>
</div>
