<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_resource', function (Blueprint $table) {
            $table->index('service_id', 'service_resource_service_id_index');
            $table->index('resource_id', 'service_resource_resource_id_index');
            $table->dropUnique('service_resource_service_id_resource_id_unique');
        });
    }

    public function down(): void
    {
        // If duplicates exist, keep the oldest row and remove the rest before restoring unique.
        DB::statement("
            DELETE sr1
            FROM service_resource sr1
            INNER JOIN service_resource sr2
                ON sr1.service_id = sr2.service_id
                AND sr1.resource_id = sr2.resource_id
                AND sr1.id > sr2.id
        ");

        Schema::table('service_resource', function (Blueprint $table) {
            $table->unique(['service_id', 'resource_id'], 'service_resource_service_id_resource_id_unique');
        });
    }
};
