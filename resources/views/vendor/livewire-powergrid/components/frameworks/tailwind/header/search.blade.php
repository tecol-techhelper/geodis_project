@if (data_get($setUp, 'header.searchInput'))
    <div class="flex flex-row mt-3 md:mt-0 w-full rounded-full justify-start sm:justify-center md:justify-end gap-2">
        <button type="button"
            wire:click="$dispatch('pg:eventRefresh-{{ $this->tableName }}')"
            title="Recargar"
            class="inline-flex items-center justify-center w-10 h-10 rounded-full border border-gray-300 bg-white text-gray-700 hover:border-indigo-500 hover:text-indigo-600 transition">
            <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 12a9 9 0 1 1-3.2-6.9" />
                <polyline points="21 3 21 9 15 9" />
            </svg>
        </button>
        <div class="group relative rounded-full w-full md:w-4/12 float-end float-right md:w-full lg:w-1/2">
            <span class="absolute inset-y-0 left-0 flex items-center pl-1">
                <span class="p-1 focus:outline-none focus:shadow-outline">
                    <x-livewire-powergrid::icons.search
                        class="{{ theme_style($theme, 'searchBox.iconSearch') }}"
                    />
                </span>
            </span>
            <input
                wire:model.live.debounce.700ms="search"
                type="text"
                class="{{ theme_style($theme, 'searchBox.input') }}"
                placeholder="{{ trans('livewire-powergrid::datatable.placeholders.search') }}"
            >
            @if ($search)
                <span
                    class="absolute opacity-0 group-hover:opacity-100 transition-all inset-y-0 right-0 flex items-center"
                >
                    <span class="p-2 rounded-full focus:outline-none focus:shadow-outline cursor-pointer">
                        <a wire:click.prevent="$set('search','')">
                            <x-livewire-powergrid::icons.x
                                class="w-4 h-4 {{ theme_style($theme, 'searchBox.iconClose') }}"
                            />
                        </a>
                    </span>
                </span>
            @endif
        </div>
    </div>
@endif
