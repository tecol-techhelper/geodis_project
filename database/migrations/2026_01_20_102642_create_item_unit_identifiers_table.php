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
        Schema::create('item_unit_identifiers', function (Blueprint $table) {
            $table->id(); // PK estándar Laravel

            $table->string('segment_tag', 16)->default('GIN');

            $table->string('unit_identifier_type', 16);
            $table->string('identifier_value_from', 64);
            $table->string('identifier_value_to', 64)->nullable();

            $table->text('raw_segment');

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
        Schema::dropIfExists('item_unit_identifiers');
    }
};
