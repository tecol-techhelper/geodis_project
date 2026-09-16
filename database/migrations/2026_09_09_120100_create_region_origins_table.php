<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('region_origins', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('region_id')
                ->constrained('regions')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('origin', 191);
            $table->string('normalized_origin', 191)->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['region_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('region_origins');
    }
};
