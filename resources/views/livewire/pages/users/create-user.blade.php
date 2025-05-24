<?php

use App\Models\User;
use Livewire\WithFileUploads;
use App\Livewire\Forms\CreateUserForm;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    use WithFileUploads;
    public CreateUserForm $form;

    public function register(): void
    {
        $this->form->save();
        redirect()->route('user.index');
    }
}; ?>
@section('title', 'Registro')
<div>
    <x-breadcrums :items="[
        ['label' => 'Inicio', 'url' => route('dashboard'), 'icon' => 'home'],
        ['label' => 'Usuarios', 'url' => route('user.index'), 'icon' => 'users'],
        ['label' => 'Registro Usuario', 'icon' => 'user-plus'],
    ]"></x-breadcrums>
    <h1 class="text-5xl font-extrabold dark:text-white py-8">Registro de Usuario</h1>
    <form wire:submit.prevent="register" enctype="multipart/form-data" class="pt-4">

        {{-- Contenedor responsivo: 1 columna en móviles, 2 en pantallas medianas en adelante --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Columna izquierda --}}
            <div class="space-y-4 py-4">

                {{-- Imagen con cuadro vacío y previsualización --}}
                <div>
                    <div class="flex items-center space-x-4">
                        <div
                            class="mt-2 w-24 h-24 border border-gray-400 flex items-center justify-center overflow-hidden">
                            @if ($form->user_icon)
                                <img src="{{ $form->user_icon->temporaryUrl() }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-sm text-gray-500">Icono</span>
                            @endif
                        </div>
                        <input type="file" id="user_icon" wire:model="form.user_icon"
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-sm cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" />
                    </div>
                    <x-input-error :messages="$errors->get('form.user_icon')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="first_name">First Name</x-input-label>
                    <x-text-input type="text" id="first_name" wire:model.defer="form.first_name" class="w-full" />
                    <x-input-error :messages="$errors->get('form.first_name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password">Password</x-input-label>
                    <x-text-input type="password" id="password" wire:model.defer="form.password" class="w-full" />
                    <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="role_id">Rol</x-input-label>
                    <select id="role_id" wire:model.defer="form.role_id" class="w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
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
                    <x-input-label for="username">Username</x-input-label>
                    <x-text-input type="text" id="username" wire:model.defer="form.username" class="w-full" />
                    <x-input-error :messages="$errors->get('form.username')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="email">Email</x-input-label>
                    <x-text-input type="email" id="email" wire:model.defer="form.email" class="w-full" />
                    <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="last_name">Last Name</x-input-label>
                    <x-text-input type="text" id="last_name" wire:model.defer="form.last_name" class="w-full" />
                    <x-input-error :messages="$errors->get('form.last_name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password_confirmation">Confirm Password</x-input-label>
                    <x-text-input type="password" id="password_confirmation"
                        wire:model.defer="form.password_confirmation" class="w-full" />
                </div>

                <div>
                    <x-input-label for="is_active">Estado</x-input-label>
                    <select id="is_active" wire:model.defer="form.is_active" class="w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
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
