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
        Schema::create('service_equipment', function (Blueprint $table) {
            $table->id();
            $table->string('segment_tag', 3)->default('EQD');
            $table->text('raw_segment');
            $table->string('equipment_identifier', 64)->nullable();
            $table->foreignId('equipment_type_id')->constrained('equipment_types')->cascadeOnUpdate();
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
        Schema::dropIfExists('service_equipment');
    }
};
