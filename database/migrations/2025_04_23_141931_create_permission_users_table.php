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
        Schema::create('permission_users', function (Blueprint $table) {
            $table->id();
            // Polymorfic relation with users table
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            //Indexing for easier lookup: model_id + model_type
            $table->index(['model_id','model_type'], 'permission_model_type_index');
            // Foreing key to permission table
            $table->foreignId('permission_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_users');
    }
};
