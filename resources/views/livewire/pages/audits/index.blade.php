<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {};
?>
@section('title', 'Auditoría')
<div class="space-y-6">
    <x-breadcrums :items="[
        ['label' => 'Inicio', 'url' => route('dashboard'), 'icon' => 'home'],
        ['label' => 'Auditoría', 'icon' => 'shield-check'],
    ]"></x-breadcrums>

    <div class="py-6 px-6 border-2 rounded-lg shadow-lg bg-white dark:bg-white">
        <livewire:audits.audit-table />
    </div>
</div>
