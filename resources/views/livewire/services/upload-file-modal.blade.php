<?php

use App\Models\Service;
use Livewire\WithFileUploads;
use App\Livewire\Forms\Services\UploadFileForm;
use Livewire\Volt\Component;
use App\Services\SharePointUploader;

new class extends Component {
    use WithFileUploads;
    public UploadFileForm $form;
    public Service $service;
    public bool $canUploadSupports = false;
    public array $cniOptions = [];
    public array $coiOptions = [];

    public function mount(Service $service, bool $canUploadSupports = false): void
    {
        $this->service = $service;
        $this->canUploadSupports = $canUploadSupports;
        $this->form->service_id = $service->id;
        $service->loadMissing('purchase_orders.order_references.reference_type');

        $this->cniOptions = $service->purchase_orders
            ->map(function ($purchaseOrder) {
                $number = trim((string) ($purchaseOrder->purchase_order_number ?? ''));

                return $number !== '' ? [
                    'id' => $purchaseOrder->id,
                    'value' => $number,
                ] : null;
            })
            ->filter()
            ->unique('value')
            ->values()
            ->all();

        $this->coiOptions = $service->purchase_orders
            ->flatMap(fn($purchaseOrder) => $purchaseOrder->order_references ?? collect())
            ->filter(function ($reference) {
                return strtoupper(trim((string) ($reference->reference_type?->reference_type_code ?? ''))) === 'COI';
            })
            ->map(function ($reference) {
                $value = trim((string) ($reference->order_reference_value ?? ''));
                $value = trim(explode('/', $value, 2)[0] ?? '');

                return $value !== '' ? [
                    'id' => $reference->id,
                    'value' => $value,
                ] : null;
            })
            ->filter()
            ->unique('value')
            ->values()
            ->all();

        if (count($this->cniOptions) === 1) {
            $this->form->purchase_order_id = (int) $this->cniOptions[0]['id'];
            $this->form->purchase_order_number = $this->cniOptions[0]['value'];
        }

        if (count($this->coiOptions) === 1) {
            $this->form->order_reference_id = (int) $this->coiOptions[0]['id'];
            $this->form->order_reference_value = $this->coiOptions[0]['value'];
        }
    }

    public function with(): array
    {
        return [
            'supportFiles' => $this->service->support_files()->with('file_type')->get(),
        ];
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

    public function deleteSupportFile(int $supportFileId, SharePointUploader $sharePointUploader): void
    {
        $this->form->deleteSupportFile($supportFileId, $this->service, $sharePointUploader);
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
<div x-data="{ modalIsOpen: false, confirmClear: false, uploadClientError: '', activeSupportTab: @js($canUploadSupports ? 'upload' : 'uploaded') }" x-effect="if (!modalIsOpen) { $refs.openSupportBtn?.blur() }">
    <x-primary-button x-ref="openSupportBtn" x-on:click="modalIsOpen = true; $nextTick(() => $refs.openSupportBtn.blur())"
        type="button" class="w-full sm:w-auto space-x-1">
        <span>{{ $canUploadSupports ? 'Cargar soportes' : 'Ver soportes' }}</span>
    </x-primary-button>

    <div x-cloak x-show="modalIsOpen" x-transition.opacity.duration.200ms x-trap.inert.noscroll="modalIsOpen"
        x-on:keydown.esc.window="modalIsOpen = false"
        class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto bg-black/40 p-4 pt-6 backdrop-blur-xs lg:p-8"
        role="dialog" aria-modal="true" aria-labelledby="defaultModalTitle">
        <!-- Modal Dialog -->
        <div x-show="modalIsOpen"
            x-transition:enter="transition ease-out duration-200 delay-100 motion-reduce:transition-opacity"
            x-transition:enter-start="opacity-0 scale-50" x-transition:enter-end="opacity-100 scale-100"
            :class="activeSupportTab === 'upload' ? 'max-w-2xl' : 'max-w-5xl'"
            class="my-4 flex max-h-[90vh] w-full flex-col overflow-hidden rounded-xl border border-gray-200 bg-white text-gray-700 shadow-xl">
            <!-- Dialog Header -->
            <div
                class="flex items-center justify-between border-b border-gray-200 bg-gray-50 px-5 py-4">
                <h3 id="defaultModalTitle" class="text-base font-semibold text-gray-900">
                    Cargue de soportes
                </h3>

                <button type="button" x-on:click="modalIsOpen = false; $nextTick(() => $el.blur())"
                    aria-label="Cerrar modal"
                    class="rounded-md p-1 text-gray-500 transition hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor"
                        fill="none" stroke-width="1.4" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Dialog Body -->
            <div class="space-y-5 overflow-y-auto px-5 py-5">
                <div class="border-b border-gray-200" role="tablist" aria-label="Secciones de soportes">
                    <button id="supportUploadTab" type="button" role="tab" aria-controls="supportUploadPanel"
                        @click="activeSupportTab = 'upload'"
                        :aria-selected="(activeSupportTab === 'upload').toString()"
                        :class="activeSupportTab === 'upload' ? 'border-blue-700 text-blue-700' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'"
                        class="border-b-2 px-4 py-3 text-sm font-semibold transition focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">
                        Cargue
                    </button>
                    <button id="supportUploadedTab" type="button" role="tab" aria-controls="supportUploadedPanel"
                        @click="activeSupportTab = 'uploaded'"
                        :aria-selected="(activeSupportTab === 'uploaded').toString()"
                        :class="activeSupportTab === 'uploaded' ? 'border-blue-700 text-blue-700' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'"
                        class="border-b-2 px-4 py-3 text-sm font-semibold transition focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">
                        Cargados
                    </button>
                </div>

                <section id="supportUploadPanel" role="tabpanel" aria-labelledby="supportUploadTab"
                    x-cloak x-show="activeSupportTab === 'upload'" class="space-y-4">
                    @if ($canUploadSupports)
                    <div class="space-y-1">
                        <x-input-label for="file_type">Tipo de soporte</x-input-label>
                        <select id="file_type" wire:model="form.file_type"
                            class="h-11 w-full cursor-pointer rounded-md border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option selected value="">Seleccione un tipo</option>
                            <option value="CLI">CUMPLIDO</option>
                            <option value="CLP">CHECK LIST PREOPERACIONAL</option>
                            <option value="GABF301">CONTAINER AND CARGO UNIT INSPECTION FORMAT FOR IMPORT AND EXPORT</option>
                            <option value="GPS">REPORTE DE GPS - IMPO // EXPO</option>
                            <option value="IC">INFORME DE CARGUE (REGISTRO FOTOGRÁFICO)</option>
                            <option value="ID">INFORME DE DESCARGUE</option>
                            <option value="IF">INFORME FINAL</option>
                            <option value="PDR">PLAN DE RUTA CONTENEDORES - IMPO // EXPO</option>
                            <option value="RP">REEMPAQUES</option>
                            <option value="RT">REMESA DE TRANSPORTE</option>
                            <option value="TDC">TIRILLA DE DEVOLUCION DE CONTENEDORES</option>
                            <option value="TRC">TIRILLA DE RETIRO DE CONTENEDORES</option>
                        </select>
                        <x-input-error :messages="$errors->get('form.file_type')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 gap-3 pt-3 sm:grid-cols-2">
                        <div class="space-y-1">
                            <x-input-label for="cni_value">CNI</x-input-label>
                            <select id="cni_value" wire:model="form.purchase_order_id"
                                class="h-11 w-full cursor-pointer rounded-md border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-500"
                                @disabled(count($cniOptions) <= 1)>
                                @if (count($cniOptions) !== 1)
                                    <option value="">Seleccione CNI</option>
                                @endif
                                @forelse ($cniOptions as $cniOption)
                                    <option value="{{ $cniOption['id'] }}">{{ $cniOption['value'] }}</option>
                                @empty
                                    <option value="">Sin CNI</option>
                                @endforelse
                            </select>
                        </div>

                        <div class="space-y-1">
                            <x-input-label for="coi_value">COI</x-input-label>
                            <select id="coi_value" wire:model="form.order_reference_id"
                                class="h-11 w-full cursor-pointer rounded-md border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-500"
                                @disabled(count($coiOptions) <= 1)>
                                @if (count($coiOptions) !== 1)
                                    <option value="">Seleccione COI</option>
                                @endif
                                @forelse ($coiOptions as $coiOption)
                                    <option value="{{ $coiOption['id'] }}">{{ $coiOption['value'] }}</option>
                                @empty
                                    <option value="">Sin COI</option>
                                @endforelse
                            </select>
                        </div>
                    </div>

                    <div class="pt-3">
                        <x-input-label for="free_text">Texto libre</x-input-label>
                        <x-text-input type="text" id="free_text" wire:model.defer="form.free_text" class="w-full" />
                        <x-input-error :messages="$errors->get('form.free_text')" class="mt-2" />
                    </div>

                    <div class="space-y-2">
                        <!-- CLAVE: el input debe apuntar a form.files -->
                        <input multiple wire:model="form.files" type="file"
                            x-on:change="uploadClientError = ''"
                            x-on:livewire-upload-error="uploadClientError = 'No se pudo cargar el archivo temporal. Verifique que el formato sea permitido y que no supere 10 MB.'"
                            class="block w-full cursor-pointer rounded-md border border-gray-300 bg-white text-sm text-gray-700 file:mr-4 file:cursor-pointer file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-medium file:text-gray-700 hover:file:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1" />

                        <p x-cloak x-show="uploadClientError" x-text="uploadClientError" class="text-xs text-red-600"></p>
                        <x-input-error :messages="$errors->get('form.files')" class="text-xs" />
                        <x-input-error :messages="collect($errors->get('form.files.*'))->flatten()->all()" class="text-xs" />

                        <!-- CLAVE: target correcto -->
                        <div wire:loading wire:target="form.files" class="text-xs font-medium text-indigo-600">
                            Subiendo archivo(s) temporalmente...
                        </div>
                    </div>

                    <div>
                        <x-input-label>Archivos pendientes de guardar</x-input-label>
                        <ul
                            class="mt-2 divide-y divide-gray-200 rounded-lg border border-gray-200 bg-white text-sm text-gray-700">
                            @forelse ($form->tempFiles as $index => $file)
                                <li class="flex items-center justify-between gap-3 px-4 py-3">
                                    <span class="min-w-0 truncate">{{ $file['fileName'] }}</span>
                                    <button type="button" wire:click="removeTempFiles({{ $index }})"
                                        class="shrink-0 text-sm font-medium text-red-600 hover:text-red-700 hover:underline">
                                        Eliminar
                                    </button>
                                </li>
                            @empty
                                <li class="px-4 py-4 text-xs text-gray-500">
                                    No hay archivos pendientes de guardar.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                    @else
                        <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
                            Tienes acceso de solo lectura a los soportes. Puedes consultarlos en Cargados.
                        </div>
                    @endif
                </section>

                <section id="supportUploadedPanel" role="tabpanel" aria-labelledby="supportUploadedTab"
                    x-cloak x-show="activeSupportTab === 'uploaded'" class="space-y-3">
                    <x-input-error :messages="$errors->get('form.supportDeletion')" />
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        Nombre del Archivo
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        Tipo
                                    </th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($supportFiles as $file)
                                    <tr wire:key="support-file-{{ $file->id }}" class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 text-sm">
                                            @if ($file->file_url)
                                                <a href="{{ $file->file_url }}" target="_blank"
                                                    rel="noopener noreferrer" class="font-medium text-indigo-600 hover:text-indigo-800 hover:underline">
                                                    {{ $file->file_name ?? '-' }}
                                                </a>
                                            @else
                                                {{ $file->file_name ?? '-' }}
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm">{{ $file->file_type?->file_type_full_name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm">
                                            @if ($canUploadSupports)
                                                <button type="button" wire:click="deleteSupportFile({{ $file->id }})"
                                                    wire:confirm="¿Eliminar este soporte de SharePoint y del listado?"
                                                    wire:loading.attr="disabled" wire:target="deleteSupportFile"
                                                    aria-label="Eliminar soporte {{ $file->file_name }}"
                                                    title="Eliminar soporte"
                                                    class="inline-flex items-center justify-center rounded-md p-2 text-red-600 transition hover:bg-red-50 hover:text-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 disabled:cursor-wait disabled:opacity-50">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="h-5 w-5" aria-hidden="true">
                                                        <path d="M3 6h18" />
                                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                        <path d="M10 11v6M14 11v6" />
                                                    </svg>
                                                </button>
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-4 text-sm text-gray-500">
                                            No hay soportes cargados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <!-- Dialog Footer -->
            <div
                class="flex flex-col-reverse justify-end gap-2 border-t border-gray-200 bg-gray-50 px-5 py-4 sm:flex-row sm:items-center md:justify-end">
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
                    <span>{{ $canUploadSupports ? 'Cancelar' : 'Cerrar' }}</span>
                </x-danger-button>

                <div x-cloak x-show="confirmClear" x-transition.opacity
                    x-on:keydown.escape.window="confirmClear = false" x-on:click.self="confirmClear = false"
                    class="fixed inset-0 z-[60] flex items-center justify-center bg-black/40 p-4 backdrop-blur-sm">
                    <div class="w-full max-w-md space-y-4 rounded-xl border border-gray-200 bg-white p-6 shadow-xl">
                        <h2 class="text-lg font-semibold text-gray-900">
                            ¿Está seguro que desea salir?
                        </h2>
                        <p class="text-sm text-gray-600">
                            Existen archivos pendientes por guardar.
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

                @if ($canUploadSupports)
                    <x-primary-button x-show="activeSupportTab === 'upload'" wire:click.prevent="uploadFiles" wire:loading.attr="disabled"
                        wire:target="form.files,uploadFiles" type="button"
                        x-on:click="uploadClientError = ''; $nextTick(() => $el.blur())"
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

                    <x-success-button x-show="activeSupportTab === 'upload'" wire:click="saveFiles" wire:loading.attr="disabled" wire:target="saveFiles"
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
                @endif
            </div>
        </div>
    </div>
</div>
{{-- @endif --}}
