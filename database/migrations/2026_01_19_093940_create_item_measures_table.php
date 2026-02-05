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
        Schema::create('item_measures', function (Blueprint $table) {
            $table->id();

            $table->string('segment_tag', 16)->default('MEA');
            $table->text('raw_segment');

            $table->string('measure_unit_code', 3);
            $table->decimal('measurement_value', 18, 6);

            $table->foreignId('measurement_purpose_code_id')->constrained('measurement_purpose_codes')->cascadeOnUpdate();
            $table->foreignId('measurement_attribute_code_id')->constrained('measurement_attribute_codes')->cascadeOnUpdate();
            $table->foreignId('purchase_order_item_id')->constrained('purchase_order_items')->cascadeOnUpdate();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_measures');
    }
};
