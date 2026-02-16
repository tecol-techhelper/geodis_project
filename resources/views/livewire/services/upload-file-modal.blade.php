<?php

use App\Models\Service;
use Livewire\WithFileUploads;
use App\Livewire\Forms\Services\UploadFileForm;
use Livewire\Volt\Component;
use App\Services\SharePointUploader;

new class extends Component {
    use WithFileUploads;
    public UploadFileForm $form;

    public function mount(Service $service): void
    {
        $this->form->service_id = $service->id;
    }

    public function uploadFiles(): void
    {
        $this->form->uploadFiles();
    }

    public function removeTempFiles(int $index): void
    {
        $this->form->removeTempFiles($index);
    }

    public function clearTempFiles(): void
    {
        $this->form->clearTempFiles();
    }

    public function saveFiles(SharePointUploader $sharePointUploader): void
    {
        $saved = $this->form->saveFiles($sharePointUploader);

        if ($saved) {
            $this->dispatch('support-files-saved');
        }
    }
}; ?>

{{-- @section('title', 'Cargar Soportes') --}}
{{-- @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('coord')) --}}
<div x-data="{ modalIsOpen: false, confirmClear: false }" x-effect="if (!modalIsOpen) { $refs.openSupportBtn?.blur() }">
    <x-primary-button x-ref="openSupportBtn" x-on:click="modalIsOpen = true; $nextTick(() => $refs.openSupportBtn.blur())"
        type="button" class="space-x-1">
        <span>Carga Soportes</span>
    </x-primary-button>

    <div x-cloak x-show="modalIsOpen" x-transition.opacity.duration.200ms x-trap.inert.noscroll="modalIsOpen"
        x-on:keydown.esc.window="modalIsOpen = false"
        class="absolute inset-0 z-50 flex items-start justify-center overflow-y-auto bg-black/40 p-4 pt-6 backdrop-blur-xs lg:p-8"
        role="dialog" aria-modal="true" aria-labelledby="defaultModalTitle">
        <!-- Modal Dialog -->
        <div x-show="modalIsOpen"
            x-transition:enter="transition ease-out duration-200 delay-100 motion-reduce:transition-opacity"
            x-transition:enter-start="opacity-0 scale-50" x-transition:enter-end="opacity-100 scale-100"
            class="flex max-w-lg overflow-auto max-h-[90vh] flex-col my-4 gap-4 rounded-md border border-neutral-300 bg-white text-neutral-600 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300">
            <!-- Dialog Header -->
            <div
                class="flex items-center justify-between border-b border-neutral-300 bg-neutral-50/60 p-4 dark:border-neutral-700 dark:bg-neutral-950/20">
                <h3 id="defaultModalTitle" class="font-semibold tracking-wide text-neutral-900 dark:text-white">
                    Cargue de Soportes
                </h3>

                <button type="button" x-on:click="modalIsOpen = false; $nextTick(() => $el.blur())"
                    aria-label="close modal">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor"
                        fill="none" stroke-width="1.4" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Dialog Body -->
            <div class="px-4 py-2">
                <div class="space-y-1">
                    <x-input-label for="file_type">Tipo de Soporte</x-input-label>
                    <select id="file_type" wire:model="form.file_type"
                        class="w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <option selected value="">Seleccione un tipo</option>
                        <option value="CLI">Cumplido</option>
                        <option value="CLP">Check List Preoperacional</option>
                        <option value="IC">Informe de Cargue</option>
                        <option value="IF">Informe final (Registro Fotográfico)</option>
                        <option value="RO">Reporte OnSite</option>
                        <option value="RT">Remesa de Transporte</option>
                        <option value="ID">Informe de Descargue</option>
                        <option value="TRC">Tirilla de Retiro de Contenedores</option>
                        <option value="TDC">Tirilla de Devolución de Contenedores</option>
                        <option value="GABF301">Formato de inspección de contenedores y unidades de carga para
                            importación y exportación</option>
                        <option value="PDR">Plan de Ruta Contenedores – Impo // Expo</option>
                        <option value="GPS">Reporte de GPS – Impo // Expo</option>
                        <option value="RP">Reempaques</option>
                    </select>
                    <x-input-error :messages="$errors->get('form.file_type')" class="mt-2" />
                </div>

                <div class="pt-3">
                    <x-input-label for="free_text">Texto Libre:</x-input-label>
                    <x-text-input type="text" id="free_text" wire:model.defer="form.free_text" class="w-full" />
                    <x-input-error :messages="$errors->get('form.free_text')" class="mt-2" />
                </div>

                <div class="py-4">
                    <!-- CLAVE: el input debe apuntar a form.files -->
                    <input multiple wire:model="form.files" type="file"
                        class="w-full text-slate-500 font-medium text-sm bg-gray-100 file:cursor-pointer cursor-pointer file:border-0 file:py-2 file:px-4 file:mr-4 file:bg-gray-800 file:hover:bg-gray-700 file:text-white rounded" />

                    @error('form.files')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror

                    {{-- Opcional: errores por archivo --}}
                    @error('form.files.*')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror

                    <!-- CLAVE: target correcto -->
                    <div wire:loading wire:target="form.files" class="text-xs text-blue-600 mt-2">
                        Subiendo archivo(s) temporalmente...
                    </div>
                </div>

                <div>
                    <x-input-label>Archivos Cargados</x-input-label>
                    <ul
                        class="mt-2 space-y-1 text-sm text-gray-700 dark:text-gray-300 border-2 border-dashed px-4 py-4 rounded-md">
                        @forelse ($form->tempFiles as $index => $file)
                            <li class="flex items-center justify-between">
                                <span>{{ $file['fileName'] }}</span>
                                <button type="button" wire:click="removeTempFiles({{ $index }})"
                                    class="text-red-500 hover:underline">
                                    Eliminar
                                </button>
                            </li>
                        @empty
                            <li class="text-xs text-gray-500">
                                No hay archivos cargados.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Dialog Footer -->
            <div
                class="flex flex-col-reverse justify-end gap-2 border-t border-neutral-300 bg-neutral-50/60 p-4 dark:border-neutral-700 dark:bg-neutral-950/20 sm:flex-row sm:items-center md:justify-end">
                <x-danger-button
                    x-on:click="
                        $wire.form.tempFiles.length > 0
                            ? confirmClear = true
                            : (modalIsOpen = false, $nextTick(() => $el.blur()))
                    "
                    type="button" class="space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-trash2-icon lucide-trash-2">
                        <path d="M3 6h18" />
                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                        <line x1="10" x2="10" y1="11" y2="17" />
                        <line x1="14" x2="14" y1="11" y2="17" />
                    </svg>
                    <span>Cancelar</span>
                </x-danger-button>

                <div x-cloak x-show="confirmClear" x-transition.opacity
                    x-on:keydown.escape.window="confirmClear = false" x-on:click.self="confirmClear = false"
                    class="fixed inset-0 z-40 flex items-center justify-center bg-black/40 p-4 backdrop-blur-sm">
                    <div class="bg-white dark:bg-neutral-800 rounded-md shadow-xl p-6 w-full max-w-md space-y-4">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                            ¿Está seguro que desea salir?
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Existen archivos pendiente por guardar
                        </p>

                        <div class="flex justify-end gap-2">
                            <x-secondary-button x-on:click="confirmClear = false">No</x-secondary-button>
                            <x-danger-button type="button"
                                x-on:click="
                                    $wire.clearTempFiles();
                                    modalIsOpen = false;
                                    confirmClear = false;
                                ">
                                Sí, eliminar
                            </x-danger-button>
                        </div>
                    </div>
                </div>

                <x-primary-button wire:click.prevent="uploadFiles" wire:loading.attr="disabled"
                    wire:target="form.files,uploadFiles" x-on:click="$nextTick(() => $el.blur())" type="button"
                    class="space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-file-up-icon lucide-file-up">
                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                        <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                        <path d="M12 12v6" />
                        <path d="m15 15-3-3-3 3" />
                    </svg>
                    <span>Cargar</span>
                </x-primary-button>

                <x-success-button wire:click="saveFiles" wire:loading.attr="disabled" wire:target="saveFiles"
                    x-on:click="$nextTick(() => $el.blur())" type="button" class="space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-save-icon lucide-save">
                        <path
                            d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                        <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
                        <path d="M7 3v4a1 1 0 0 0 1 1h7" />
                    </svg>
                    <span>Guardar</span>
                </x-success-button>
            </div>
        </div>
    </div>
</div>
{{-- @endif --}}
