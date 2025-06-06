<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {};
?>
@section('title', 'Usuarios')
<div class="space-y-6">
    <x-breadcrums :items="[
        ['label' => 'Inicio', 'url' => route('dashboard'), 'icon' => 'home'],
        ['label' => 'Usuarios', 'icon' => 'users'],
    ]"></x-breadcrums>
    <div class="py-6 px-6 border-2 rounded-lg shadow-lg bg-white dark:bg-white">
        <a href="{{ route('user.create') }}"
            class="space-x-2 inline-flex items-center justify-between px-4 py-2 bg-white dark:bg-white border-[1px] border-blue-700 rounded-md font-semibold text-xs text-blue-700 dark:text-blue-700 uppercase tracking-widest hover:bg-blue-700 hover:shadow-lg hover:text-white dark:hover:text-white dark:hover:bg-white focus:bg-blue-700 dark:focus:bg-white active:bg-blue-900 dark:active:bg-blue-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-blue-800 transition ease-in-out duration-150">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-user-plus-icon lucide-user-plus">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                <circle cx="9" cy="7" r="4" />
                <line x1="19" x2="19" y1="8" y2="14" />
                <line x1="22" x2="16" y1="11" y2="11" />
            </svg>
            <span>Nuevo</span>
        </a>
        <livewire:users.user-table />
    </div>
</div>
