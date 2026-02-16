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
        Schema::create('delivery_terms', function (Blueprint $table) {
            $table->id();
            $table->string('segment_tag',3)->default('TOD');
            $table->text('raw_segment');
            $table->string('freight_payment_code')->nullable();
            $table->integer('delivery_term_function')->nullable();
            $table->foreignId('delivery_term_catalog_id')->constrained('delivery_term_catalogs')->cascadeOnUpdate();
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
        Schema::dropIfExists('delivery_terms');
    }
};
