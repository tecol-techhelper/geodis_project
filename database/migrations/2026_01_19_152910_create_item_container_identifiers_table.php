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
        Schema::create('item_container_identifiers', function (Blueprint $table) {
            $table->id();
            $table->string('segment_tag', 3)->default('PCI');
            $table->text('raw_segment');
            $table->string('package_identifier_value')->nullable();
            $table->foreignId('identifier_type_id')->constrained('identifier_types')->cascadeOnUpdate();
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
        Schema::dropIfExists('item_container_identifiers');
    }
};
