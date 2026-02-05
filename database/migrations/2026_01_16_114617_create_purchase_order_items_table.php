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
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();

            $table->string('segment_tag', 4)->default('GID');
            $table->integer('item_number');
            $table->integer('item_count');
            $table->string('item_type', 16);
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
        Schema::dropIfExists('purchase_order_items');
    }
};
