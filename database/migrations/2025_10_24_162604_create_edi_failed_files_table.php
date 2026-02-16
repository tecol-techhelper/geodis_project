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
        Schema::create('edi_failed_files', function (Blueprint $table) {
            $table->id();
            $table->string('file_name', 256);
            $table->string('service_reference', 50)->nullable();
            $table->string('sender_id', 50)->nullable();
            $table->string('receiver_id', 50)->nullable();
            $table->string('segment_error', 10)->nullable();
            $table->string('error_type', 100);
            $table->text('error_message');
            $table->integer('error_line')->nullable();
            $table->longText('raw_content')->nullable();
            $table->date('processed_at');
            $table->text('notes')->nullable()->comment('Para registro de solución o descarte del archivo');
            $table->foreignId('service_id')->constrained('services')->cascadeOnUpdate();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('edi_failed_files');
    }
};
