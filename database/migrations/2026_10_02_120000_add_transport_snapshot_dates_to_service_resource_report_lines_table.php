<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_resource_report_lines', function (Blueprint $table): void {
            $table->date('positioning_date')->nullable()->after('origin_id');
            $table->date('arrival_date')->nullable()->after('destination_id');
        });

        if (!Schema::hasTable('services')
            || !Schema::hasColumn('services', 'positioning_date')
            || !Schema::hasColumn('services', 'arrival_date')
            || !Schema::hasColumn('service_resource_reports', 'service_id')) {
            return;
        }

        $timestamp = now();

        DB::table('service_resource_report_lines as lines')
            ->join('service_resource_reports as reports', 'reports.id', '=', 'lines.service_resource_report_id')
            ->join('services', 'services.id', '=', 'reports.service_id')
            ->where(function ($query): void {
                $query->whereNotNull('services.positioning_date')
                    ->orWhereNotNull('services.arrival_date');
            })
            ->select([
                'lines.id as line_id',
                'lines.positioning_date as line_positioning_date',
                'lines.arrival_date as line_arrival_date',
                'services.positioning_date as service_positioning_date',
                'services.arrival_date as service_arrival_date',
            ])
            ->orderBy('lines.id')
            ->chunkById(200, function ($lines) use ($timestamp): void {
                foreach ($lines as $line) {
                    $values = [];

                    if ($line->line_positioning_date === null && $line->service_positioning_date !== null) {
                        $values['positioning_date'] = substr((string) $line->service_positioning_date, 0, 10);
                    }

                    if ($line->line_arrival_date === null && $line->service_arrival_date !== null) {
                        $values['arrival_date'] = substr((string) $line->service_arrival_date, 0, 10);
                    }

                    if ($values !== []) {
                        $values['updated_at'] = $timestamp;
                        DB::table('service_resource_report_lines')
                            ->where('id', $line->line_id)
                            ->update($values);
                    }
                }
            }, 'lines.id', 'line_id');
    }

    public function down(): void
    {
        Schema::table('service_resource_report_lines', function (Blueprint $table): void {
            $table->dropColumn(['positioning_date', 'arrival_date']);
        });
    }
};
