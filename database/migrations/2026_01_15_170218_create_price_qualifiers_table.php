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
        Schema::create('price_qualifiers', function (Blueprint $table) {
            $table->id();
            $table->string('price_qualifier_code', 4)->unique();
            $table->string('price_qualifier_name', 32);
            $table->string('price_qualifier_description', 256);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_qualifiers');
    }
};
