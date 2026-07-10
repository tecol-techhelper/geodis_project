<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_resource_status_reports', function (Blueprint $table) {
            $table->timestamp('reported_at')
                ->nullable()
                ->after('service_status_report_id');
        });

        DB::table('service_resource_status_reports')
            ->whereNull('reported_at')
            ->whereNotNull('service_status_report_id')
            ->update([
                'reported_at' => DB::raw(
                    '(SELECT status_report.reported_at
                        FROM service_status_reports AS status_report
                        WHERE status_report.id = service_resource_status_reports.service_status_report_id
                        LIMIT 1)'
                ),
            ]);
    }

    public function down(): void
    {
        Schema::table('service_resource_status_reports', function (Blueprint $table) {
            $table->dropColumn('reported_at');
        });
    }
};
