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
        Schema::create('dimension_types', function (Blueprint $table) {
            $table->id();

            $table->integer('dimension_type_code')->unique();
            $table->string('dimension_type_name', 64);
            $table->string('dimension_type_description', 256);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dimension_types');
    }
};
