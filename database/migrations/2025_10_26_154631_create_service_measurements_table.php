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
        Schema::create('service_measurements', function (Blueprint $table) {
            $table->id();
            $table->string('segment_tag')->default('CNT');
            $table->decimal('measure_value', 16, 2)->nullable();
            $table->string('measure_unit')->nullable();
            $table->text('raw_segment');
            $table->foreignId('service_id')->constrained('services')->cascadeOnUpdate();
            $table->foreignId('global_measure_type_id')->constrained('global_measure_types')->cascadeOnUpdate();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_measurements');
    }
};
