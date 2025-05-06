<?php

use App\Models\User;
use App\Livewire\Forms\EditUserForm;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    public EditUserForm $form;

    public function mount(User $user){
        $this->form->mount($user);
    }

    public function update(): void
    {
        $this->form->update();

        redirect()->route('user.index');
        
    }
}; ?>

<div>
    <x-breadcrums :items="[
        ['label' => 'Inicio', 'url' => route('dashboard'), 'icon' => 'home'],
        ['label' => 'Usuarios', 'url' => route('user.index'), 'icon' => 'users'],
        ['label' => 'Edición Usuario', 'icon' => 'user-pen'],
    ]"></x-breadcrums>
    <form wire:submit="update">
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Usuario')" />
            <x-text-input wire:model="form.username" id="username" class="block mt-1 w-full" type="text" name="username"
                required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.username')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full" type="email" name="email"
                required autocomplete="email" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full" type="password"
                name="password"  autocomplete="new-password" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input wire:model="form.password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                type="password" name="password_confirmation"  autocomplete="new-password" />

            <x-input-error :messages="$errors->get('form.password_confirmation')" class="mt-2" />
        </div>

        {{-- Estado --}}
        <div class="mt-4">
            <x-input-label for="is_active" :value="__('Estado')" />
            <select wire:model.defer = "form.is_active" id="is_active" class="w-full">
                @foreach (App\Enums\UserStatus::cases() as $status)
                    <option value="{{ $status->value }}"> {{ $status->label() }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('form.is_active')" class="mt-2" />
        </div>

        {{-- Rol --}}
        <div class="mt-4">
            <x-input-label for="rol_id" :value="__('Rol del Usuario')" />
            <select wire:model.defer = "form.rol_id" id="rol_id" class="w-full">
                @foreach (App\Enums\Rol::cases() as $rol)
                    <option value="{{ $rol->value }}"> {{ $rol->label() }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('form.rol_id')" class="mt-2" />
        </div>
        <div class="flex items-center justify-end mt-4">

            <x-success-button class="ms-4">
                {{ __('Guardar') }}
            </x-success-button>
        </div>
    </form>
</div>
