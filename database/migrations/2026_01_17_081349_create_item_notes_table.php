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
        Schema::create('item_notes', function (Blueprint $table) {
            $table->id();
            $table->string('segment_tag',3)->default('FTX');
            $table->text('raw_segment');
            $table->text('note_text')->nullable();

            $table->foreignId('purchase_order_items_id')->constrained('purchase_order_items')->cascadeOnUpdate();
            $table->foreignId('note_types_id')->constrained('note_types')->cascadeOnUpdate();
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_notes');
    }
};
