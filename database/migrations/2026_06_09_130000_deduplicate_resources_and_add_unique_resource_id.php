<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function () {
            $duplicateCodes = DB::table('resources')
                ->select('resource_id')
                ->groupBy('resource_id')
                ->havingRaw('COUNT(*) > 1')
                ->pluck('resource_id');

            foreach ($duplicateCodes as $resourceCode) {
                $resourceIds = DB::table('resources')
                    ->where('resource_id', $resourceCode)
                    ->orderBy('id')
                    ->pluck('id')
                    ->map(fn($id) => (int) $id)
                    ->all();

                $keeperId = array_shift($resourceIds);

                if ($keeperId === null || $resourceIds === []) {
                    continue;
                }

                $this->mergeServiceResourceReferences($keeperId, $resourceIds);
                $this->mergePurchaseOrderResourceReferences($keeperId, $resourceIds);

                DB::table('resources')->whereIn('id', $resourceIds)->delete();
            }
        });

        Schema::table('resources', function (Blueprint $table) {
            $table->unique('resource_id', 'resources_resource_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->dropUnique('resources_resource_id_unique');
        });
    }

    /**
     * service_resource permits repeated resource rows, so references can be
     * reassigned directly without collapsing valid service-level duplicates.
     *
     * @param array<int, int> $duplicateIds
     */
    private function mergeServiceResourceReferences(int $keeperId, array $duplicateIds): void
    {
        if (!Schema::hasTable('service_resource')) {
            return;
        }

        DB::table('service_resource')
            ->whereIn('resource_id', $duplicateIds)
            ->update(['resource_id' => $keeperId]);
    }

    /**
     * purchase_order_resource has a unique purchase-order/resource pair.
     * Remove conflicting duplicate references before reassigning the rest.
     *
     * @param array<int, int> $duplicateIds
     */
    private function mergePurchaseOrderResourceReferences(int $keeperId, array $duplicateIds): void
    {
        if (!Schema::hasTable('purchase_order_resource')) {
            return;
        }

        $purchaseOrderIdsWithKeeper = DB::table('purchase_order_resource')
            ->where('resource_id', $keeperId)
            ->pluck('purchase_order_id');

        if ($purchaseOrderIdsWithKeeper->isNotEmpty()) {
            DB::table('purchase_order_resource')
                ->whereIn('resource_id', $duplicateIds)
                ->whereIn('purchase_order_id', $purchaseOrderIdsWithKeeper)
                ->delete();
        }

        DB::table('purchase_order_resource')
            ->whereIn('resource_id', $duplicateIds)
            ->update(['resource_id' => $keeperId]);
    }
};
