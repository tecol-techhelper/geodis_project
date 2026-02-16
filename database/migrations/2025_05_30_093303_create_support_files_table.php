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
        Schema::create('support_files', function (Blueprint $table) {
            $table->id();
            $table->string('file_name', 64);
            $table->string('file_url', 2048)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('file_extension')->nullable();
            $table->date('uploaded_at');
            $table->boolean('uploaded_sftp')->default(0);
            $table->text('sftp_error')->nullable();
            $table->foreignId('file_type_id')->constrained('file_types')->cascadeOnUpdate();
            $table->foreignId('service_id')->nullable()->constrained('services')->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_files');
    }
};
