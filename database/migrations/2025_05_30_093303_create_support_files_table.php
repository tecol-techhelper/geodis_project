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
            $table->foreignId('user_id')->constrained()->onUpdate('cascade');
            $table->foreignId('file_type_id')->constrained()->onUpdate('cascade');
            $table->softDeletes();
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
