<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resource_tariffs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('resource_id')
                ->constrained('resources')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('region_id')
                ->nullable()
                ->constrained('regions')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('operation_concept_id')
                ->constrained('operation_concepts')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->decimal('unit_price', 20, 6)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // MySQL permits repeated NULL values in a regular unique index.
            // This generated scope key makes a general tariff (region_id = NULL)
            // unique in the same way as a regional tariff.
            $table->unsignedBigInteger('region_scope_key')
                ->storedAs('COALESCE(region_id, 0)');

            $table->unique(
                ['resource_id', 'region_scope_key', 'operation_concept_id'],
                'resource_tariffs_scope_unique',
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resource_tariffs');
    }
};
