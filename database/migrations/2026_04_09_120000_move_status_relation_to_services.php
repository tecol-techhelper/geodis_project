<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('services', 'status_id')) {
            Schema::table('services', function (Blueprint $table) {
                $table->foreignId('status_id')->nullable()->after('raw_segment')
                    ->constrained('statuses')->cascadeOnUpdate();
            });
        }

        // Backfill status_id en servicios usando el primer estado de sus órdenes.
        $firstStatusId = DB::table('statuses')->orderBy('id')->value('id');
        if ($firstStatusId !== null) {
            DB::statement("
                UPDATE services s
                LEFT JOIN (
                    SELECT po1.service_id, po1.status_id
                    FROM purchase_orders po1
                    INNER JOIN (
                        SELECT service_id, MIN(id) AS min_po_id
                        FROM purchase_orders
                        GROUP BY service_id
                    ) x ON x.min_po_id = po1.id
                ) t ON t.service_id = s.id
                SET s.status_id = COALESCE(s.status_id, t.status_id, {$firstStatusId})
                WHERE s.status_id IS NULL
            ");
        }

        if (Schema::hasColumn('purchase_orders', 'status_id')) {
            Schema::table('purchase_orders', function (Blueprint $table) {
                $table->dropConstrainedForeignId('status_id');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('purchase_orders', 'status_id')) {
            Schema::table('purchase_orders', function (Blueprint $table) {
                $table->foreignId('status_id')->nullable()->after('service_id')
                    ->constrained('statuses')->cascadeOnUpdate();
            });
        }

        // Backfill status_id en órdenes usando el estado del servicio.
        DB::statement("
            UPDATE purchase_orders po
            INNER JOIN services s ON s.id = po.service_id
            SET po.status_id = COALESCE(po.status_id, s.status_id)
            WHERE po.status_id IS NULL
        ");

        if (Schema::hasColumn('services', 'status_id')) {
            Schema::table('services', function (Blueprint $table) {
                $table->dropConstrainedForeignId('status_id');
            });
        }
    }
};
