<?php

use App\Livewire\Forms\LoginForm;
use App\Core\InternalControllers\SessionLogController;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use App\Models\User;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        (new SessionLogController())->logSession(
            Auth::id(),
            Auth::user()->username,
            Auth::user()->role->rol_key,
            request()->ip(),
            request()->userAgent(),
            session()->id()
        );

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

@section('title','Inicio de Sesión')
<div class="w-full max-w-full bg-white p-6 sm:p-8 rounded-lg border shadow-lg space-y-6">

    <!-- Imagen centrada (máx 50% del ancho del contenedor) -->
    <div class="flex justify-center">
        <img src="{{ asset('images/logos/logo_top.png') }}" alt="Logo Transtecol" class="img-fluid max-w-[60%]" />
    </div>

    <!-- Formulario con espaciado interno -->
    <form wire:submit.prevent="login" class="space-y-3">
        <!-- Usuario -->
        <div>
            <x-input-label for="username" :value="'Usuario'" />
            <x-text-input id="username" type="text" name="username" wire:model.defer="form.username"
                class="block mt-1 w-full" required autofocus />
            <x-input-error :messages="$errors->get('form.username')" class="mt-2" />
        </div>

        <!-- Contraseña -->
        <div>
            <x-input-label for="password" :value="'Contraseña'" />
            <x-text-input id="password" type="password" name="password" wire:model.defer="form.password"
                class="block mt-1 w-full" required />
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Recordarme -->
        <div class="flex items-center">
            <label for="remember" class="flex items-center text-sm">
                <input id="remember" type="checkbox" wire:model="form.remember"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                <span class="ml-2 text-gray-700">Recordarme</span>
            </label>
        </div>

        <!-- Botón de envío -->
        <div class="flex items-center justify-between items-center">
            <a href="{{route('password.request')}}" class="text-xs sm:text-sm lg:text-sm text-blue-600 hover:underline">¿Olvidaste tu
                contraseña?</a>
            <x-success-button class="sm:w-24 lg:w-[40%]">
                Iniciar sesión
            </x-success-button>
        </div>
    </form>
</div>
