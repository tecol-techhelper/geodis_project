<?php

namespace Tests\Unit;

use App\Models\PurchaseOrder;
use App\Models\Resource;
use App\Models\Service;
use App\Models\Status;
use App\Services\Edi\GenerateIftstaPayloadService;
use Illuminate\Support\Collection;
use Tests\TestCase;

class GenerateIftstaPayloadServiceTest extends TestCase
{
    public function test_status_only_payload_does_not_fallback_to_service_resource(): void
    {
        $service = new Service();
        $service->forceFill([
            'id' => 1,
            'consecutive' => 'N123',
            'raw_segment' => 'BGM+335+N123+9',
            'status_id' => 1,
        ]);

        $status = new Status();
        $status->forceFill([
            'id' => 1,
            'status_name' => 'En transito',
            'edifact_code' => 35,
        ]);

        $resource = new Resource();
        $resource->forceFill([
            'id' => 1,
            'resource_id' => 'T_SEN8T0',
            'resource_name' => 'Sencillo',
        ]);

        $purchaseOrder = new PurchaseOrder();
        $purchaseOrder->forceFill([
            'id' => 10,
            'service_id' => 1,
            'raw_segment' => "'",
            'purchase_order_number' => 'N123',
        ]);

        foreach ([
            'order_references',
            'delivery_terms',
            'purchase_order_parties',
            'purchase_order_contacts',
            'purchase_order_notes',
            'purchase_order_measurements',
            'purchase_order_requirements',
            'transport_charges',
            'purchase_order_items',
        ] as $relation) {
            $purchaseOrder->setRelation($relation, collect());
        }

        foreach ([
            'service_dates',
            'service_parties',
            'service_contacts',
            'location_details',
            'transport_details',
            'service_equipments',
            'service_measurements',
        ] as $relation) {
            $service->setRelation($relation, collect());
        }

        $service->setRelation('status', $status);
        $service->setRelation('resources', new Collection([$resource]));
        $service->setRelation('purchase_orders', new Collection([$purchaseOrder]));

        $payload = app(GenerateIftstaPayloadService::class)
            ->generate($service, [10], null, '2026-06-20 10:48:00')['payload'];

        $this->assertStringNotContainsString('RFF+FS:T_SEN8T0', $payload);
        $this->assertStringNotContainsString("\n'\n", $payload);
    }
}
