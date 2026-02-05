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
        Schema::create('product_identifier_roles', function (Blueprint $table) {
            $table->id();

            $table->unsignedTinyInteger('role_code')->unique();
            $table->string('role_name', 64);
            $table->string('role_description', 256);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_identifier_roles');
    }
};
