<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {};
?>
@section('title', 'Usuarios')
<div>
    <x-breadcrums :items="[
        ['label' => 'Inicio', 'url' => route('dashboard'), 'icon' => 'home'],
        ['label' => 'Usuarios', 'icon' => 'users']
    ]"></x-breadcrums>
    <livewire:users.user-table/>
</div>
