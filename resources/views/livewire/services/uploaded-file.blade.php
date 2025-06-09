<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {};
?>
@section('title', 'Archivos Cargados')
<div class="space-y-6">
    <x-breadcrums :items="[
        ['label' => 'Inicio', 'url' => route('dashboard'), 'icon' => 'home'],
        ['label' => 'Parsed Files', 'url' => '#', 'icon' => 'file-json'],
        ['label' => 'Uploaded Files', 'icon' => 'file-up']
    ]"></x-breadcrums>
    <div class="py-6 px-6 border-2 rounded-lg shadow-lg bg-white dark:bg-white">
        <livewire:services.files-management.support-file-table />
    </div>
</div>