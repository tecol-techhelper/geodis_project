<?php

use App\Models\User;
use App\Core\InternalControllers\AuditController;
use Illuminate\Support\Facades\Log;
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
        // dd('Entró al save');
        $user = $this->form->save();

        Log::info('Usuario guardado: ' . $user->id);

        (new AuditController())->log($user, Auth::id(), Auth::user()->username, Auth::user()->roles->first()?->rol_key, 'CREATED');
        redirect()->route('user.index');
    }
}; ?>
@section('title', 'Nuevo Usuario')
<div class="space-y-4">
    <x-breadcrums :items="[
        ['label' => 'Inicio', 'url' => route('dashboard'), 'icon' => 'home'],
        ['label' => 'Usuarios', 'url' => route('user.index'), 'icon' => 'users'],
        ['label' => 'Registro Usuario', 'icon' => 'user-plus'],
    ]"></x-breadcrums>

    <form wire:submit="register" class="border-2 border-gray-200 shadow-2xl rounded-2xl bg-white">
        {{-- Contenedor tipo split: izquierda formulario, derecha imagen fija --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Columna izquierda (formulario completo) --}}
            <div class="flex flex-col justify-center md:col-span-2 px-12 py-10 space-y-6 md:px-24 md:py-12 md:space-y-6">
                <div>
                    <h1 class="flex items-center text-5xl font-extrabold dark:text-white space-x-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-user-round-plus-icon lucide-user-round-plus">
                            <path d="M2 21a8 8 0 0 1 13.292-6" />
                            <circle cx="10" cy="8" r="5" />
                            <path d="M19 16v6" />
                            <path d="M22 19h-6" />
                        </svg>
                        <span>Registro de Usuario</span>
                    </h1>
                </div>
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
                            accept="image/jpg, image/jpeg, image/png, image/webp"
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-sm cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" />
                    </div>
                    <x-input-error :messages="$errors->get('form.user_icon')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="username">Usuario:</x-input-label>
                    <x-text-input type="text" id="username" wire:model.defer="form.username" class="w-full" />
                    <x-input-error :messages="$errors->get('form.username')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="first_name">Nombre(s):</x-input-label>
                    <x-text-input type="text" id="first_name" wire:model.defer="form.first_name" class="w-full" />
                    <x-input-error :messages="$errors->get('form.first_name')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="last_name">Apellido(s):</x-input-label>
                    <x-text-input type="text" id="last_name" wire:model.defer="form.last_name" class="w-full" />
                    <x-input-error :messages="$errors->get('form.last_name')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="email">Email</x-input-label>
                    <x-text-input type="email" id="email" wire:model.defer="form.email" class="w-full" />
                    <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="password">Password</x-input-label>
                    <x-text-input type="password" id="password" wire:model.defer="form.password" class="w-full" />
                    <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="password_confirmation">Confirm Password</x-input-label>
                    <x-text-input type="password" id="password_confirmation"
                        wire:model.defer="form.password_confirmation" class="w-full" />
                </div>
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
                <div class="flex justify-end mt-6">
                    <x-success-button type="submit" class="px-4 py-2">Guardar</x-success-button>
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
