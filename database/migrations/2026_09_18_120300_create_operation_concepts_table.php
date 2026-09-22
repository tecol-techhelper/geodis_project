<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operation_concepts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('resource_operation_id')
                ->constrained('resource_operations')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('name', 191);
            $table->timestamps();

            $table->unique(['resource_operation_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operation_concepts');
    }
};
