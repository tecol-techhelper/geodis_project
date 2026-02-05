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
        Schema::create('purchase_order_requirements', function (Blueprint $table) {
            $table->id();
            $table->string('segment_tag', 16);
            $table->string('contract_carriage_condition_code', 32);
            $table->string('po_requirements_code', 16);
            $table->string('additional_po_requirement_code', 32)->nullable();
            $table->string('transport_priority', 32);
            $table->text('raw_segment');
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
        Schema::dropIfExists('purchase_order_requirements');
    }
};
