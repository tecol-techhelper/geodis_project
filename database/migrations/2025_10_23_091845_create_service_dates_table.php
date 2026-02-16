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
        Schema::create('service_dates', function (Blueprint $table) {
            $table->id();
            $table->string('segment_tag')->default('DTM');
            $table->text('raw_segment');
            $table->date('service_date');
            $table->integer('format_date')->nullable();
            $table->foreignId('service_id')->constrained('services')->cascadeOnUpdate();
            $table->foreignId('date_type_id')->constrained('date_types')->cascadeOnUpdate();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_dates');
    }
};
