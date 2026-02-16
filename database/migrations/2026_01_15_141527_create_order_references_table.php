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
        Schema::create('order_references', function (Blueprint $table) {
            $table->id();
            $table->string('segment_tag',3)->default('RFF');
            $table->text('raw_segment');
            $table->string('order_reference_value')->nullable();
            $table->foreignId('reference_type_id')->constrained('reference_types')->cascadeOnUpdate();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->cascadeOnUpdate();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_references');
    }
};
