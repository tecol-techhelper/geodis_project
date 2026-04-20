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
            DB::statement("
                INSERT INTO service_resource (service_id, resource_id, created_at, updated_at)
                SELECT po.service_id, por.resource_id, NOW(), NOW()
                FROM purchase_order_resource por
                INNER JOIN purchase_orders po ON po.id = por.purchase_order_id
                INNER JOIN (
                    SELECT po2.service_id, MIN(por2.id) AS min_por_id
                    FROM purchase_order_resource por2
                    INNER JOIN purchase_orders po2 ON po2.id = por2.purchase_order_id
                    GROUP BY po2.service_id
                ) x ON x.min_por_id = por.id
                WHERE po.service_id IS NOT NULL
                ON DUPLICATE KEY UPDATE updated_at = VALUES(updated_at)
            ");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('service_resource');
    }
};
