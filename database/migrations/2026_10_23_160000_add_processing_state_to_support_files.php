<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['processing_status', 'processing_token', 'processing_heartbeat_at'] as $column) {
            if (! Schema::hasColumn('support_files', $column)) {
                Schema::table('support_files', function (Blueprint $table) use ($column) {
                    match ($column) {
                        'processing_status' => $table->string($column, 20)->nullable(),
                        'processing_token' => $table->uuid($column)->nullable(),
                        'processing_heartbeat_at' => $table->timestamp($column)->nullable(),
                    };
                });
            }
        }
    }

    public function down(): void
    {
        foreach (['processing_status', 'processing_token', 'processing_heartbeat_at'] as $column) {
            if (Schema::hasColumn('support_files', $column)) {
                Schema::table('support_files', fn (Blueprint $table) => $table->dropColumn($column));
            }
        }
    }
};
