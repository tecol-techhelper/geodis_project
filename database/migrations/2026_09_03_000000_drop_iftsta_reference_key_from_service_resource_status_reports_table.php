<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('service_resource_status_reports')
            || !Schema::hasColumn('service_resource_status_reports', 'iftsta_reference_key')) {
            return;
        }

        Schema::table('service_resource_status_reports', function (Blueprint $table) {
            $table->dropColumn('iftsta_reference_key');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('service_resource_status_reports')
            || Schema::hasColumn('service_resource_status_reports', 'iftsta_reference_key')) {
            return;
        }

        Schema::table('service_resource_status_reports', function (Blueprint $table) {
            $table->char('iftsta_reference_key', 6)
                ->nullable()
                ->after('service_status_report_id');
        });
    }
};
