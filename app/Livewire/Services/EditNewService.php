<?php

namespace App\Livewire\Services;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class EditNewService extends Component
{
    public function render()
    {
        return view('livewire.services.edit-new-service');
    }
}
