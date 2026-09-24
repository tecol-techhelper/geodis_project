<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_resource_report_lines', function (Blueprint $table): void {
            $table->dateTime('origin_date')->nullable()->after('origin_id');
            $table->dateTime('destination_date')->nullable()->after('destination_id');
        });
    }

    public function down(): void
    {
        Schema::table('service_resource_report_lines', function (Blueprint $table): void {
            $table->dropColumn(['origin_date', 'destination_date']);
        });
    }
};
