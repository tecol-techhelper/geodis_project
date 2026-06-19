<?php

namespace App\Livewire\Services;

use App\Models\Service;
use Livewire\Attributes\On;
use Livewire\Component;

class ServiceInlineDetails extends Component
{
    public ?Service $service = null;

    public bool $hiddenForTrash = false;

    #[On('service-preview-selected')]
    public function loadService(int $serviceId): void
    {
        $this->service = Service::query()
            ->with([
                'status',
                'purchase_orders.purchase_order_items',
                'service_contacts.contact_type',
                'service_contacts.service_contact_details',
            ])
            ->find($serviceId);
    }

    #[On('service-list-mode-changed')]
    public function clearService(bool $showTrash = false): void
    {
        $this->service = null;
        $this->hiddenForTrash = $showTrash;
    }

    public function render()
    {
        return view('livewire.services.service-inline-details');
    }
}
