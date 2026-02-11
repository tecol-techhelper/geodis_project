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
        Schema::create('purchase_order_parties', function (Blueprint $table) {
            $table->id();
            $table->string('segment_tag', 3)->default('NAD');
            $table->text('raw_segment');
            $table->string('party_name')->nullable();
            $table->string('party_street')->nullable();
            $table->string('party_city');
            $table->string('party_region');
            $table->string('party_country_code');
            $table->foreignId('party_type_id')->constrained('party_types')->cascadeOnUpdate();
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
        Schema::dropIfExists('purchase_order_parties');
    }
};
