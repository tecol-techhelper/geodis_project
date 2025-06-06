<?php

use App\Models\User;
use Livewire\WithFileUploads;
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
<div>
    <x-breadcrums :items="[
        ['label' => 'Inicio', 'url' => route('dashboard'), 'icon' => 'home'],
        ['label' => 'Usuarios', 'url' => route('user.index'), 'icon' => 'users'],
        ['label' => 'Edición Usuario', 'icon' => 'user-pen'],
    ]"></x-breadcrums>

    <form wire:submit="update" class="px-6 py-6 space-y-6 my-4 border-2 border-gray-200 shadow-2xl rounded-2xl">
        <h1 class="flex items-center text-5xl font-extrabold dark:text-white space-x-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-user-pen-icon lucide-user-pen">
                <path d="M11.5 15H7a4 4 0 0 0-4 4v2" />
                <path
                    d="M21.378 16.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z" />
                <circle cx="10" cy="7" r="4" />
            </svg>
            <span>Edición de Usuario</span>
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Columna izquierda --}}
            <div class="space-y-4 py-8">

                {{-- Imagen con cuadro vacío y previsualización --}}
                <div>
                    <div class="flex items-center space-x-4 ">
                        <div
                            class="mt-2 w-24 h-22 border border-gray-400 rounded-full flex items-center justify-center overflow-hidden">
                            <img src="{{ Storage::url($form->user_icon_url) }}" class="w-full h-full object-cover">

                        </div>
                        <input type="file" id="user_icon" wire:model="form.user_icon" accept="image/jpg, image/jpeg, image/png, image/webp"
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-sm cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" />
                    </div>
                    <x-input-error :messages="$errors->get('form.user_icon')" class="mt-2" />
                </div>
                <div class="pt-5">
                    <x-input-label for="first_name">Nombre(s)</x-input-label>
                    <x-text-input type="text" id="first_name" wire:model.defer="form.first_name" class="w-full" />
                    <x-input-error :messages="$errors->get('form.first_name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password">Contraseña</x-input-label>
                    <x-text-input type="password" id="password" wire:model.defer="form.password" class="w-full" />
                    <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="role_id">Rol</x-input-label>
                    <select {{ !Auth::user()->hasRole('admin') ? 'disabled' : '' }} id="role_id"
                        wire:model.defer="form.role_id"
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
                    <x-input-label for="username">Usuario</x-input-label>
                    <x-text-input type="text" id="username" wire:model.defer="form.username" class="w-full" />
                    <x-input-error :messages="$errors->get('form.username')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="email">Correo</x-input-label>
                    <x-text-input type="email" id="email" wire:model.defer="form.email" class="w-full" />
                    <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="last_name">Apellido(s)</x-input-label>
                    <x-text-input type="text" id="last_name" wire:model.defer="form.last_name" class="w-full" />
                    <x-input-error :messages="$errors->get('form.last_name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password_confirmation">Confirmar Contraseña</x-input-label>
                    <x-text-input type="password" id="password_confirmation"
                        wire:model.defer="form.password_confirmation" class="w-full" />
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
            </div>
        </div>

        {{-- Botón de guardar --}}
        <div class="flex justify-end mt-6">
            <x-success-button type="submit" class="px-4 py-2">Guardar</x-success-button>
        </div>
    </form>
</div>
