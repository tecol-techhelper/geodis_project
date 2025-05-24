<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {};
?>
@section('title', 'Usuarios')
<div>
    <x-breadcrums :items="[
        ['label' => 'Inicio', 'url' => route('dashboard'), 'icon' => 'home'],
        ['label' => 'Usuarios', 'icon' => 'users'],
    ]"></x-breadcrums>
    <div class="py-6 px-6 border-2 rounded-lg shadow-lg bg-white dark:bg-white">
        <livewire:services.edifact-file-manager.edifact-file-table />
    </div>
</div>
