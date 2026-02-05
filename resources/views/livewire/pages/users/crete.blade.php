<div>
    <x-breadcrums :items="[
        ['label' => 'Inicio', 'url' => route('dashboard'), 'icon' => 'home'],
        ['label' => 'Usuarios', 'url' => route('user.index'), 'icon' => 'users'],
        ['label' => 'Registro Usuario', 'icon' => 'user-plus'],
    ]"></x-breadcrums>

    <form wire:submit.prevent="register" enctype="multipart/form-data" class="pt-4"
        class="px-6 py-6 space-y-6 my-4 border-2 border-gray-200 shadow-2xl rounded-2xl">

        <h1 class="text-5xl font-extrabold dark:text-white py-8">Registro de Usuario</h1>
        {{-- Contenedor responsivo: 1 columna en móviles, 2 en pantallas medianas en adelante --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Columna izquierda --}}
            <div class="space-y-4 py-4">

                {{-- Imagen con cuadro vacío y previsualización --}}
                

                

                

                <div>
                    <x-input-label for="role_id">Rol</x-input-label>
                    <select id="role_id" wire:model.defer="form.role_id"
                        class="w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        @foreach (App\Enums\Rol::cases() as $rol)
                            <option value="{{ $rol->value }}">{{ $rol->label() }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('form.role_id')" class="mt-2" />
                </div>
            </div>

            {{-- Columna derecha --}}
            <div class="space-y-4">

                

                

                

                

                <div>
                    <x-input-label for="is_active">Estado</x-input-label>
                    <select id="is_active" wire:model.defer="form.is_active"
                        class="w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        @foreach (App\Enums\UserStatus::cases() as $status)
                            <option value="{{ $status->value }}">{{ $status->label() }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('form.is_active')" class="mt-2" />
                </div>
            </div>
        </div>

        {{-- Botón de guardar --}}
        <div class="flex justify-end mt-6">
            <x-success-button type="submit" class="px-4 py-2">Guardar</x-success-button>
        </div>

    </form>
</div>
