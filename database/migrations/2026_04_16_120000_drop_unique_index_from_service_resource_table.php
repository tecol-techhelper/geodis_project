<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::table('service_resource', function (Blueprint $table) {
            if (Schema::hasColumn('service_resource', 'service_id')) {
                $table->dropIndex('service_resource_service_id_index');
            }

            if (Schema::hasColumn('service_resource', 'resource_id')) {
                $table->dropIndex('service_resource_resource_id_index');
            }
        });
    }
};
