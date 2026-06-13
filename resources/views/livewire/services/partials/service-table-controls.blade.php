<div>
    @if (auth()->user()?->hasRole('admin') && $showTrash)
        <div class="mb-3 flex max-w-xl items-start gap-2 rounded-xl border border-amber-200 bg-amber-50 px-3 py-2
            text-sm text-amber-900 shadow-sm transition-all duration-300 ease-out motion-reduce:transition-none"
            role="status">
            <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-4 w-4 shrink-0" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" aria-hidden="true">
                <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z" />
                <path d="M12 9v4" />
                <path d="M12 17h.01" />
            </svg>
            <span>Estás viendo la papelera. Las eliminaciones realizadas aquí son definitivas.</span>
        </div>
    @endif

    @if ($pendingServiceId !== null)
        @php
            $isPurge = $pendingDeletionType === 'purge';
        @endphp

        <div x-data x-init="$nextTick(() => $refs.cancelButton.focus())"
            x-on:keydown.escape.window="$wire.cancelServiceDeletion()"
            x-trap.inert.noscroll="true"
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            role="dialog" aria-modal="true" aria-labelledby="serviceDeletionTitle">
            <button type="button" class="absolute inset-0 bg-gray-950/55"
                wire:click="cancelServiceDeletion" aria-label="Cerrar confirmación"></button>

            <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
                <h2 id="serviceDeletionTitle" class="text-lg font-semibold text-gray-900">
                    ¿Está seguro de eliminar el servicio #{{ $pendingServiceLabel }}?
                </h2>

                @if ($isPurge)
                    <div class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm leading-6 text-red-800"
                        role="alert">
                        Esta eliminación es permanente y no podrá deshacerse.
                    </div>
                @endif

                <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <x-secondary-button x-ref="cancelButton" type="button" wire:click="cancelServiceDeletion">
                        Cancelar
                    </x-secondary-button>

                    <x-danger-button type="button" wire:click="confirmServiceDeletion"
                        wire:loading.attr="disabled" wire:target="confirmServiceDeletion">
                        Eliminar
                    </x-danger-button>
                </div>
            </div>
        </div>
    @endif
</div>
