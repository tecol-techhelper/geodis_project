<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table): void {
            $table->index('consecutive', 'services_consecutive_index');
        });

        Schema::table('service_status_reports', function (Blueprint $table): void {
            $table->index(
                ['status_id', 'reported_at', 'service_id'],
                'service_status_reports_status_reported_service_index',
            );
        });

        Schema::table('order_references', function (Blueprint $table): void {
            $table->index(
                ['purchase_order_id', 'reference_type_id', 'order_reference_value'],
                'order_references_purchase_type_value_index',
            );
        });

        Schema::table('service_resource_status_reports', function (Blueprint $table): void {
            $table->index(
                ['service_resource_id', 'reported_at'],
                'service_resource_status_reports_resource_reported_index',
            );
        });
    }

    public function down(): void
    {
        Schema::table('service_resource_status_reports', function (Blueprint $table): void {
            $table->dropIndex('service_resource_status_reports_resource_reported_index');
        });

        Schema::table('order_references', function (Blueprint $table): void {
            $table->dropIndex('order_references_purchase_type_value_index');
        });

        Schema::table('service_status_reports', function (Blueprint $table): void {
            $table->dropIndex('service_status_reports_status_reported_service_index');
        });

        Schema::table('services', function (Blueprint $table): void {
            $table->dropIndex('services_consecutive_index');
        });
    }
};
