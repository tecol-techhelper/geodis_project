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
        Schema::create('item_product_identifiers', function (Blueprint $table) {
            $table->id();

            $table->string('segment_tag', 16)->default('PIA');
            $table->text('raw_segment');

            $table->string('identifier_value', 64);

            $table->foreignId('product_identifier_role_id')->constrained('product_identifier_roles')->cascadeOnUpdate();
            $table->foreignId('product_identifier_type_id')->nullable()->constrained('product_identifier_types')->cascadeOnUpdate();
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
        Schema::dropIfExists('item_product_identifiers');
    }
};
