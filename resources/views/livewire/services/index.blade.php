<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {};
?>
@section('title', 'Servicios')
<div class="space-y-6">
    <x-breadcrums :items="[
        ['label' => 'Inicio', 'url' => route('dashboard'), 'icon' => 'home'],
        ['label' => 'Servicios', 'icon' => 'package'],
    ]"></x-breadcrums>
    <div class="py-6 px-6 border-2 rounded-lg shadow-lg bg-white dark:bg-white">
        <h1 class="flex items-center text-5xl font-extrabold dark:text-white space-x-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-package-icon lucide-package">
                <path
                    d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z" />
                <path d="M12 22V12" />
                <polyline points="3.29 7 12 12 20.71 7" />
                <path d="m7.5 4.27 9 5.15" />
            </svg>
            <span>Servicios</span>
        </h1>
        <livewire:services.service-table />
        <livewire:services.service-inline-details />
    </div>
</div>
