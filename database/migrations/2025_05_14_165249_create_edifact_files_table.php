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
        Schema::create('edifact_files', function (Blueprint $table) {
            $table->id();
            $table->string('transmission_id')->unique();
            $table->enum('message_type', ['IFTSTA', 'IFCSUM']);
            $table->string('file_name');
            $table->string('purchase_order')->nullable();
            $table->date('recived_at')->nullable();
            $table->date('sended_at')->nullable();
            $table->string('file_url',2048)->nullable();
            $table->string('file_path',2048)->nullable();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('edifact_files');
    }
};
