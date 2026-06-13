@if (auth()->user()?->hasRole('admin'))
    <div x-data="{ trash: @entangle('showTrash') }"
        wire:key="service-table-view-toggle"
        class="relative grid h-10 w-[4.5rem] shrink-0 grid-cols-2 items-center rounded-full border border-gray-300
            bg-gray-100 p-1 shadow-inner"
        role="group" aria-label="Cambiar vista de servicios">
        <span aria-hidden="true"
            class="absolute left-1 top-1 h-8 w-8 rounded-full shadow-sm
                transition-all duration-300 ease-in-out motion-reduce:transition-none"
            x-bind:class="trash
                ? 'translate-x-8 bg-red-600'
                : 'translate-x-0 bg-green-600'"></span>

        <button type="button"
            x-on:click="$wire.setTrashMode(false)"
            x-bind:disabled="!trash"
            x-bind:aria-pressed="(!trash).toString()"
            wire:loading.attr="disabled"
            wire:target="setTrashMode"
            title="Servicios activos"
            aria-label="Mostrar servicios activos"
            class="relative z-10 inline-flex h-8 w-8 items-center justify-center rounded-full
                transition-colors duration-300 ease-in-out motion-reduce:transition-none
                focus:outline-none focus-visible:ring-2 focus-visible:ring-green-500 focus-visible:ring-offset-2"
            x-bind:class="trash ? 'text-green-700' : 'text-white'">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                stroke-linejoin="round" aria-hidden="true">
                <path d="M20 6 9 17l-5-5" />
            </svg>
        </button>

        <button type="button"
            x-on:click="$wire.setTrashMode(true)"
            x-bind:disabled="trash"
            x-bind:aria-pressed="trash.toString()"
            wire:loading.attr="disabled"
            wire:target="setTrashMode"
            title="Papelera"
            aria-label="Mostrar papelera de servicios"
            class="relative z-10 inline-flex h-8 w-8 items-center justify-center rounded-full
                transition-colors duration-300 ease-in-out motion-reduce:transition-none
                focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2"
            x-bind:class="trash ? 'text-white' : 'text-red-700'">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" aria-hidden="true">
                <path d="M3 6h18" />
                <path d="M8 6V4h8v2" />
                <path d="m7 6 1 14h8l1-14" />
                <path d="M10 11v5" />
                <path d="M14 11v5" />
            </svg>
        </button>
    </div>
@endif
