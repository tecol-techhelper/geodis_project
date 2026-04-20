<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_resource', function (Blueprint $table) {
            $table->timestamp('last_reported_at')->nullable()->after('updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('service_resource', function (Blueprint $table) {
            $table->dropColumn('last_reported_at');
        });
    }
};
