<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resource_tariff_distance_ranges', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('resource_tariff_id')
                ->constrained('resource_tariffs')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->decimal('minimum_km', 10, 2)->nullable();
            $table->decimal('maximum_km', 10, 2)->nullable();
            $table->boolean('is_minimum_inclusive')->default(true);
            $table->boolean('is_maximum_inclusive')->default(true);
            $table->decimal('unit_price', 20, 6)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(
                ['resource_tariff_id', 'minimum_km', 'maximum_km', 'is_minimum_inclusive', 'is_maximum_inclusive'],
                'resource_tariff_distance_ranges_boundaries_unique',
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resource_tariff_distance_ranges');
    }
};
