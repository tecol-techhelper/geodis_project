<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('support_files', function (Blueprint $table) {
            $table->string('original_name')->nullable();
            $table->string('local_path')->nullable();
            $table->string('observations')->nullable();
            $table->timestamp('deletion_started_at')->nullable();
        });

        Schema::create('support_file_copies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_file_id')->constrained('support_files')->cascadeOnDelete();
            $table->string('identifier');
            $table->string('file_name', 50)->unique();
            $table->json('associations');
            $table->string('sharepoint_path', 2048);
            $table->string('file_url', 2048)->nullable();
            $table->timestamp('sharepoint_uploaded_at')->nullable();
            $table->timestamp('sftp_uploaded_at')->nullable();
            $table->timestamp('sharepoint_deleted_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
            $table->unique(['support_file_id', 'identifier']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_file_copies');
        Schema::table('support_files', function (Blueprint $table) {
            $table->dropColumn(['original_name', 'local_path', 'observations', 'deletion_started_at']);
        });
    }
};
