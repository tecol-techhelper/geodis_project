<?php

namespace App\Livewire\Services;

use App\Models\Service;
use Livewire\Attributes\On;
use Livewire\Component;

class ServiceInlineDetails extends Component
{
    public ?Service $service = null;

    #[On('service-preview-selected')]
    public function loadService(int $serviceId): void
    {
        $this->service = Service::query()
            ->with([
                'purchase_orders.status',
                'purchase_orders.purchase_order_items',
                'service_contacts.contact_type',
                'service_contacts.service_contact_details',
            ])
            ->find($serviceId);
    }

    public function render()
    {
        return view('livewire.services.service-inline-details');
    }
}

