<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Support\Facades\DB;
use LogicException;

class ServicePurgeService
{
    public function purge(Service $service): void
    {
        DB::transaction(function () use ($service): void {
            $service = Service::onlyTrashed()
                ->lockForUpdate()
                ->findOrFail($service->getKey());

            if (! $service->trashed()) {
                throw new LogicException('Only trashed services can be permanently deleted.');
            }

            $serviceId = (int) $service->getKey();
            $purchaseOrderIds = DB::table('purchase_orders')
                ->where('service_id', $serviceId)
                ->pluck('id');

            $this->deleteSupportFiles($serviceId, $purchaseOrderIds->all());
            $this->deletePurchaseOrders($purchaseOrderIds->all());
            $this->deleteServiceContacts($serviceId);

            foreach ([
                'notifications',
                'service_dates',
                'service_measurements',
                'service_parties',
                'transport_details',
                'location_details',
                'service_equipment',
                'service_resource',
                'edifact_files',
                'edi_failed_files',
            ] as $table) {
                DB::table($table)->where('service_id', $serviceId)->delete();
            }

            $service->forceDelete();
        });
    }

    /**
     * @param  array<int, int>  $purchaseOrderIds
     */
    private function deleteSupportFiles(int $serviceId, array $purchaseOrderIds): void
    {
        DB::table('support_files')
            ->where('service_id', $serviceId)
            ->when(
                $purchaseOrderIds !== [],
                fn ($query) => $query->orWhereIn('purchase_order_id', $purchaseOrderIds)
            )
            ->delete();
    }

    /**
     * @param  array<int, int>  $purchaseOrderIds
     */
    private function deletePurchaseOrders(array $purchaseOrderIds): void
    {
        if ($purchaseOrderIds === []) {
            return;
        }

        $itemIds = DB::table('purchase_order_items')
            ->whereIn('purchase_order_id', $purchaseOrderIds)
            ->pluck('id');

        if ($itemIds->isNotEmpty()) {
            foreach ([
                'item_product_identifiers',
                'item_container_identifiers',
                'item_unit_identifiers',
                'item_measures',
                'item_dimensions',
                'item_notes',
            ] as $table) {
                DB::table($table)
                    ->whereIn('purchase_order_item_id', $itemIds)
                    ->delete();
            }

            DB::table('purchase_order_items')
                ->whereIn('id', $itemIds)
                ->delete();
        }

        $contactIds = DB::table('purchase_order_contacts')
            ->whereIn('purchase_order_id', $purchaseOrderIds)
            ->pluck('id');

        if ($contactIds->isNotEmpty()) {
            DB::table('purchase_order_contact_details')
                ->whereIn('purchase_order_contact_id', $contactIds)
                ->delete();
        }

        foreach ([
            'purchase_order_contacts',
            'order_references',
            'purchase_order_parties',
            'purchase_order_notes',
            'purchase_order_measurements',
            'purchase_order_requirements',
            'transport_charges',
            'delivery_terms',
            'purchase_order_resource',
        ] as $table) {
            DB::table($table)
                ->whereIn('purchase_order_id', $purchaseOrderIds)
                ->delete();
        }

        DB::table('purchase_orders')
            ->whereIn('id', $purchaseOrderIds)
            ->delete();
    }

    private function deleteServiceContacts(int $serviceId): void
    {
        $contactIds = DB::table('service_contacts')
            ->where('service_id', $serviceId)
            ->pluck('id');

        if ($contactIds->isNotEmpty()) {
            DB::table('service_contact_details')
                ->whereIn('service_contact_id', $contactIds)
                ->delete();
        }

        DB::table('service_contacts')
            ->where('service_id', $serviceId)
            ->delete();
    }
}
