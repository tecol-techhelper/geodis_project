<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('statuses', function (Blueprint $table) {
            $table->unsignedBigInteger('status_purpose_id')->nullable()->after('id');
            $table->foreign('status_purpose_id')
                ->references('id')
                ->on('status_purposes')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('statuses', function (Blueprint $table) {
            $table->dropForeign(['status_purpose_id']);
            $table->dropColumn('status_purpose_id');
        });
    }
};
