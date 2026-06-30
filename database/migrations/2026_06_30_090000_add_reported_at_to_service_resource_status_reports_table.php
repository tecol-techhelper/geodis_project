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

        DB::table('service_resource_status_reports as resource_status')
            ->join('service_status_reports as status_report', 'status_report.id', '=', 'resource_status.service_status_report_id')
            ->whereNull('resource_status.reported_at')
            ->update([
                'resource_status.reported_at' => DB::raw('status_report.reported_at'),
            ]);
    }

    public function down(): void
    {
        Schema::table('service_resource_status_reports', function (Blueprint $table) {
            $table->dropColumn('reported_at');
        });
    }
};
