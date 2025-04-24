<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink($this->only('email'));

        // dd($status);

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', 'Este correo no se encuentra registrado');

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>
@section('title','Cambio de Contraseña')
<div class="w-full max-w-full bg-white p-6 sm:p-8 rounded-lg border border-gray-300 shadow-lg space-y-6">
    <div class="mb-4 text-[12px] md:text-base lg:text-md  text-gray-600 dark:text-gray-400">
        {{ __('Ingre tu correo para recuperar el acceso a tu cuenta.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required
                autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <a href="{{ route('login') }}"
                class="text-xs sm:text-sm lg:text-sm text-blue-600 hover:underline">Regresar</a>
            <x-primary-button class="lg:w-[50%] lg:text-[10px]">
                {{ __('Enviar correo de reestablecimiento') }}
            </x-primary-button>
        </div>
    </form>
</div>
