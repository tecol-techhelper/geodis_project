<?php

use App\Services\Edi\IftstaResourceReferenceKey;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_resource_status_reports', function (Blueprint $table) {
            $table->char('iftsta_reference_key', 6)
                ->nullable()
                ->after('service_status_report_id');
        });

        $keyGenerator = app(IftstaResourceReferenceKey::class);

        DB::table('service_resource_status_reports as resource_status_report')
            ->join('service_resource as service_resource', 'service_resource.id', '=', 'resource_status_report.service_resource_id')
            ->join('resources as resource', 'resource.id', '=', 'service_resource.resource_id')
            ->join('service_status_reports as status_report', 'status_report.id', '=', 'resource_status_report.service_status_report_id')
            ->whereNull('resource_status_report.iftsta_reference_key')
            ->select([
                'resource_status_report.id',
                'resource.resource_id',
                'status_report.status_id',
            ])
            ->orderBy('resource_status_report.id')
            ->chunkById(500, function ($reports) use ($keyGenerator): void {
                foreach ($reports as $report) {
                    DB::table('service_resource_status_reports')
                        ->where('id', (int) $report->id)
                        ->update([
                            'iftsta_reference_key' => $keyGenerator->make(
                                (string) $report->resource_id,
                                (int) $report->status_id,
                            ),
                        ]);
                }
            }, 'resource_status_report.id', 'id');
    }

    public function down(): void
    {
        Schema::table('service_resource_status_reports', function (Blueprint $table) {
            $table->dropColumn('iftsta_reference_key');
        });
    }
};
