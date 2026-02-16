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
        Schema::create('transport_details', function (Blueprint $table) {
            $table->id();
            $table->string('segment_tag',3)->default('TDT');
            $table->text('raw_segment');
            $table->string('vehicle_details', 256);
            $table->foreignId('transport_stage_id')->constrained('transport_stages')->cascadeOnUpdate();
            $table->foreignId('transport_mode_id')->constrained('transport_modes')->cascadeOnUpdate();
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
        Schema::dropIfExists('transport_details');
    }
};
