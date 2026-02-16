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
        Schema::create('transport_charges', function (Blueprint $table) {
            $table->id();

            $table->string('charge_code', 16);
            $table->string('rate_class_code', 16);

            $table->decimal('price_amount', 18, 2);
            $table->decimal('unit_price_basis', 18, 2)->nullable();
            $table->char('measure_unit_code', 3)->nullable();

            $table->text('pri_segment_raw');
            $table->text('tcc_segment_raw');

            $table->foreignId('price_qualifier_id')->constrained('price_qualifiers')->cascadeOnUpdate();
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
        Schema::dropIfExists('transport_charges');
    }
};
