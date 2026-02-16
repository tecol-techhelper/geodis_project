<?php

use App\Models\User;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use App\Core\InternalControllers\AuditController;
use App\Livewire\Forms\EditUserForm;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    use WithFileUploads;
    public EditUserForm $form;

    public function mount(User $user)
    {
        $this->form->mount($user);
    }

    public function update(): void
    {
        $this->form->update();

        if (Auth::user()->hasRole('admin')) {
            redirect()->route('user.index');
        } else {
            redirect()->route('dashboard');
        }
    }
}; ?>
@section('title', 'Edición')
<div class="space-y-4">
    <x-breadcrums :items="[
        ['label' => 'Inicio', 'url' => route('dashboard'), 'icon' => 'home'],
        ['label' => 'Usuarios', 'url' => route('user.index'), 'icon' => 'users'],
        ['label' => 'Edición Usuario', 'icon' => 'user-pen'],
    ]"></x-breadcrums>

    <form wire:submit="update" class="border-2 border-gray-200 shadow-2xl rounded-2xl bg-white">
        {{-- Contenedor tipo split: izquierda formulario, derecha imagen fija --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Columna izquierda (formulario completo) --}}
            <div class="flex flex-col justify-center md:col-span-2 px-12 py-10 space-y-6 md:px-24 md:py-12 md:space-y-6">
                <div>
                    <h1 class="flex items-center text-5xl font-extrabold dark:text-white space-x-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-user-pen">
                            <path d="M11.5 15H7a4 4 0 0 0-4 4v2" />
                            <path
                                d="M21.378 16.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z" />
                            <circle cx="10" cy="7" r="4" />
                        </svg>
                        <span>Edición de Usuario</span>
                    </h1>
                </div>

                {{-- Avatar + input --}}
                <div class="flex items-center space-x-4">
                    <div
                        class="mt-2 w-24 h-22 border border-gray-400 rounded-full flex items-center justify-center overflow-hidden bg-gray-100">

                        @if (!empty($form->user_icon_url) && Storage::exists($form->user_icon_url))
                            <img src="{{ Storage::url($form->user_icon_url) }}" class="w-full h-full object-cover">
                        @else
                            {{-- Si no hay imagen, muestra un recuadro vacío con un ícono opcional --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5.121 17.804A13.937 13.937 0 0112 15c2.486 0 4.797.64 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        @endif
                    </div>

                    <input type="file" id="user_icon" wire:model="form.user_icon"
                        accept="image/jpg, image/jpeg, image/png, image/webp"
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-sm cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" />

                    <x-input-error :messages="$errors->get('form.user_icon')" class="mt-2" />
                </div>

                {{-- Inputs --}}
                <div>
                    <x-input-label for="username">Usuario</x-input-label>
                    <x-text-input type="text" id="username" wire:model.defer="form.username" class="w-full" />
                    <x-input-error :messages="$errors->get('form.username')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="first_name">Nombre(s)</x-input-label>
                    <x-text-input type="text" id="first_name" wire:model.defer="form.first_name" class="w-full" />
                    <x-input-error :messages="$errors->get('form.first_name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="last_name">Apellido(s)</x-input-label>
                    <x-text-input type="text" id="last_name" wire:model.defer="form.last_name" class="w-full" />
                    <x-input-error :messages="$errors->get('form.last_name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="email">Correo</x-input-label>
                    <x-text-input type="email" id="email" wire:model.defer="form.email" class="w-full" />
                    <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password">Contraseña</x-input-label>
                    <x-text-input type="password" id="password" wire:model.defer="form.password" class="w-full" />
                    <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password_confirmation">Confirmar Contraseña</x-input-label>
                    <x-text-input type="password" id="password_confirmation"
                        wire:model.defer="form.password_confirmation" class="w-full" />
                </div>

                <div>
                    <x-input-label for="role_id">Rol</x-input-label>
                    <select {{ !Auth::user()->hasRole('admin') ? 'disabled' : '' }} id="role_id"
                        wire:model.defer="form.role_id"
                        class="w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        @foreach (\App\Models\Role::all() as $rol)
                            <option value="{{ $rol->id }}">{{ $rol->rol_name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('form.role_id')" class="mt-2"  />
                </div>

                @if (Auth::user()->hasRole('admin'))
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
                @endif

                {{-- Botón --}}
                <div class="flex justify-end pt-6">
                    <x-success-button type="submit" class="px-6 py-3 text-lg">Guardar</x-success-button>
                </div>
            </div>

            {{-- Columna derecha (solo tu imagen fija con overlay rojo) --}}
            <div class="relative w-full h-full border-l-2 border-black hidden md:block">
                <img src="{{ asset('images/logos/userFormsImage.jpg') }}" alt="Imagen lateral"
                    class="w-full h-full object-cover shadow-md rounded-r-2xl">
                <div class="absolute inset-0 bg-red-600 opacity-40 rounded-r-2xl"></div>
            </div>
        </div>
    </form>
</div>
