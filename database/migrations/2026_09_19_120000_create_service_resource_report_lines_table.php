<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_resource_report_lines', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('service_resource_report_id')
                ->constrained('service_resource_reports')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('origin_id')
                ->nullable()
                ->constrained('origins')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('destination_id')
                ->nullable()
                ->constrained('origins')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('operation_concept_id')
                ->nullable()
                ->constrained('operation_concepts')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('remesa_transporte', 128)->nullable();
            $table->timestamps();

            $table->index('service_resource_report_id');
        });

        $timestamp = now();

        DB::table('service_resource_reports')
            ->join('resources', 'resources.id', '=', 'service_resource_reports.resource_id')
            ->where('resources.resource_operation', 'TRANSPORTE')
            ->select([
                'service_resource_reports.id as report_id',
                'service_resource_reports.remesa_transporte',
            ])
            ->orderBy('service_resource_reports.id')
            ->chunkById(200, function ($reports) use ($timestamp): void {
                foreach ($reports as $report) {
                    DB::table('service_resource_report_lines')->insert([
                        'service_resource_report_id' => $report->report_id,
                        'remesa_transporte' => $report->remesa_transporte,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp,
                    ]);
                }
            }, 'service_resource_reports.id', 'report_id');
    }

    public function down(): void
    {
        Schema::dropIfExists('service_resource_report_lines');
    }
};
