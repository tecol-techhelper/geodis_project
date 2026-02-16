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
        Schema::create('measurement_attribute_codes', function (Blueprint $table) {
            $table->id();

            $table->string('measurement_attribute_code', 4)->unique();
            $table->string('measurement_attribute_name', 64);
            $table->string('measurement_attribute_description', 256);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('measurement_attribute_codes');
    }
};
