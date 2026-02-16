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
        Schema::create('product_identifier_types', function (Blueprint $table) {
            $table->id();

            $table->string('identifier_type_code', 3)->unique();
            $table->string('identifier_type_name', 64);
            $table->string('identifier_type_description', 256);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_identifier_types');
    }
};
