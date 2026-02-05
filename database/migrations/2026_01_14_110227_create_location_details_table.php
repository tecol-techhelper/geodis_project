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
        Schema::create('location_details', function (Blueprint $table) {
            $table->id();
            $table->string('segment_tag', 3)->default('LOC');
            $table->text('raw_segment');
            $table->string('location_details', 256);
            $table->foreignId('location_code_id')->constrained('location_codes')->cascadeOnUpdate();
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
        Schema::dropIfExists('location_details');
    }
};
