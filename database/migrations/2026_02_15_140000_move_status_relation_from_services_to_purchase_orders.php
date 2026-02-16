<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->foreignId('status_id')->nullable()->after('service_id')->constrained('statuses')->cascadeOnUpdate();
        });

        $hasPurchaseOrders = DB::table('purchase_orders')->exists();
        $firstStatusId = DB::table('statuses')->orderBy('id')->value('id');

        if ($hasPurchaseOrders) {
            DB::table('purchase_orders as po')
                ->leftJoin('services as s', 's.id', '=', 'po.service_id')
                ->whereNull('po.status_id')
                ->update([
                    'po.status_id' => DB::raw('COALESCE(s.status_id, ' . ($firstStatusId !== null ? (int) $firstStatusId : 'NULL') . ')'),
                ]);

            $remainingNull = DB::table('purchase_orders')->whereNull('status_id')->count();
            if ($remainingNull > 0) {
                throw new RuntimeException('No se puede hacer purchase_orders.status_id obligatorio: hay órdenes sin estado y no existe estado inicial.');
            }
        }

        DB::statement('ALTER TABLE purchase_orders MODIFY status_id BIGINT UNSIGNED NOT NULL');

        if (Schema::hasColumn('services', 'status_id')) {
            Schema::table('services', function (Blueprint $table) {
                $table->dropConstrainedForeignId('status_id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->foreignId('status_id')->nullable()->after('raw_segment')->constrained('statuses')->cascadeOnUpdate();
        });

        DB::statement(
            'UPDATE services s
             LEFT JOIN (
                SELECT po1.service_id, po1.status_id
                FROM purchase_orders po1
                INNER JOIN (
                    SELECT service_id, MAX(id) AS max_id
                    FROM purchase_orders
                    GROUP BY service_id
                ) po2 ON po2.max_id = po1.id
             ) x ON x.service_id = s.id
             SET s.status_id = x.status_id
             WHERE s.status_id IS NULL'
        );

        $firstStatusId = DB::table('statuses')->orderBy('id')->value('id');
        if ($firstStatusId !== null) {
            DB::table('services')->whereNull('status_id')->update(['status_id' => (int) $firstStatusId]);
        }

        $remainingNull = DB::table('services')->whereNull('status_id')->count();
        if ($remainingNull > 0) {
            throw new RuntimeException('No se puede restaurar services.status_id obligatorio: hay servicios sin estado y no existe estado inicial.');
        }

        DB::statement('ALTER TABLE services MODIFY status_id BIGINT UNSIGNED NOT NULL');

        if (Schema::hasColumn('purchase_orders', 'status_id')) {
            Schema::table('purchase_orders', function (Blueprint $table) {
                $table->dropConstrainedForeignId('status_id');
            });
        }
    }
};
