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
        Schema::create('purchase_order_contact_details', function (Blueprint $table) {
            $table->id();
            $table->string('segment_tag')->default('COM');
            $table->string('channel_contact');
            $table->string('contact_information');
            $table->string('raw_segment');
            $table->foreignId('purchase_order_contact_id')->constrained('purchase_order_contacts')->cascadeOnUpdate();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_contact_details');
    }
};
