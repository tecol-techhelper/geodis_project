<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_status_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')
                ->constrained('services')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('status_id')
                ->constrained('statuses')
                ->cascadeOnUpdate();
            $table->timestamp('reported_at')->nullable();
            $table->string('status_name_snapshot', 64)->nullable();
            $table->integer('edifact_code_snapshot')->nullable();
            $table->timestamps();

            $table->unique(['service_id', 'status_id'], 'service_status_reports_service_status_unique');
        });

        Schema::create('service_resource_status_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_resource_id')
                ->constrained('service_resource')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('service_status_report_id')
                ->constrained('service_status_reports')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->timestamps();

            $table->unique(
                ['service_resource_id', 'service_status_report_id'],
                'service_resource_status_reports_unique',
            );
        });

        $this->backfillExistingReports();
    }

    public function down(): void
    {
        Schema::dropIfExists('service_resource_status_reports');
        Schema::dropIfExists('service_status_reports');
    }

    private function backfillExistingReports(): void
    {
        $reportedResources = DB::table('service_resource')
            ->leftJoin('services', 'services.id', '=', 'service_resource.service_id')
            ->whereNotNull('service_resource.last_reported_at')
            ->whereNotNull('service_resource.status_name')
            ->select([
                'service_resource.id as service_resource_id',
                'service_resource.service_id',
                'service_resource.last_reported_at',
                'service_resource.status_name',
                'services.status_id as current_service_status_id',
            ])
            ->orderBy('service_resource.id')
            ->get();

        foreach ($reportedResources as $reportedResource) {
            $status = $this->resolveStatus(
                (string) $reportedResource->status_name,
                $reportedResource->current_service_status_id !== null
                    ? (int) $reportedResource->current_service_status_id
                    : null,
                (int) $reportedResource->service_resource_id,
            );

            $serviceStatusReportId = $this->upsertServiceStatusReport(
                (int) $reportedResource->service_id,
                (int) $status->id,
                (string) $reportedResource->last_reported_at,
                $status,
            );

            DB::table('service_resource_status_reports')->insertOrIgnore([
                'service_resource_id' => (int) $reportedResource->service_resource_id,
                'service_status_report_id' => $serviceStatusReportId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function resolveStatus(string $statusName, ?int $currentServiceStatusId, int $serviceResourceId): object
    {
        $candidates = DB::table('statuses')
            ->where('status_name', trim($statusName))
            ->whereNull('deleted_at')
            ->orderBy('id')
            ->get();

        if ($candidates->count() === 1) {
            return $candidates->first();
        }

        if ($currentServiceStatusId !== null) {
            $currentStatus = $candidates->firstWhere('id', $currentServiceStatusId);

            if ($currentStatus) {
                return $currentStatus;
            }
        }

        throw new RuntimeException(
            "No se pudo resolver el estado historico '{$statusName}' para service_resource {$serviceResourceId}.",
        );
    }

    private function upsertServiceStatusReport(int $serviceId, int $statusId, string $reportedAt, object $status): int
    {
        $existing = DB::table('service_status_reports')
            ->where('service_id', $serviceId)
            ->where('status_id', $statusId)
            ->first();

        $payload = [
            'reported_at' => $reportedAt,
            'status_name_snapshot' => $status->status_name,
            'edifact_code_snapshot' => $status->edifact_code,
            'updated_at' => now(),
        ];

        if ($existing) {
            if ($existing->reported_at === null || $reportedAt > (string) $existing->reported_at) {
                DB::table('service_status_reports')
                    ->where('id', (int) $existing->id)
                    ->update($payload);
            }

            return (int) $existing->id;
        }

        return (int) DB::table('service_status_reports')->insertGetId($payload + [
            'service_id' => $serviceId,
            'status_id' => $statusId,
            'created_at' => now(),
        ]);
    }
};
