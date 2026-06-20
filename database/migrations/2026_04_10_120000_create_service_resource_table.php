<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('service_resource')) {
            Schema::create('service_resource', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('service_id');
                $table->unsignedBigInteger('resource_id');
                $table->timestamps();

                $table->foreign('service_id')
                    ->references('id')
                    ->on('services')
                    ->cascadeOnDelete();

                $table->foreign('resource_id')
                    ->references('id')
                    ->on('resources')
                    ->cascadeOnDelete();

                $table->unique(['service_id', 'resource_id']);
            });
        }

        // Backfill: si hay recursos ligados a CNIs, tomar el primero por servicio.
        if (Schema::hasTable('purchase_order_resource')) {
            $firstResourcePerService = DB::table('purchase_order_resource as por2')
                ->join('purchase_orders as po2', 'po2.id', '=', 'por2.purchase_order_id')
                ->select('po2.service_id', DB::raw('MIN(por2.id) AS min_por_id'))
                ->groupBy('po2.service_id');

            DB::table('purchase_order_resource as por')
                ->join('purchase_orders as po', 'po.id', '=', 'por.purchase_order_id')
                ->joinSub($firstResourcePerService, 'x', fn($join) => $join->on('x.min_por_id', '=', 'por.id'))
                ->whereNotNull('po.service_id')
                ->select('po.service_id', 'por.resource_id')
                ->orderBy('po.service_id')
                ->chunk(500, function ($rows): void {
                    $now = now();

                    DB::table('service_resource')->insertOrIgnore(
                        $rows->map(fn($row) => [
                            'service_id' => (int) $row->service_id,
                            'resource_id' => (int) $row->resource_id,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ])->all()
                    );
                });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('service_resource');
    }
};
