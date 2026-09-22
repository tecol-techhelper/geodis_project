<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_resource_report_lines', function (Blueprint $table): void {
            $table->decimal('unit_price', 20, 6)->nullable()->after('operation_concept_id');
        });
    }

    public function down(): void
    {
        Schema::table('service_resource_report_lines', function (Blueprint $table): void {
            $table->dropColumn('unit_price');
        });
    }
};
