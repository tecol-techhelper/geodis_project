<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate', 32)->unique();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('operators', function (Blueprint $table) {
            $table->id();
            $table->string('identification', 64)->unique();
            $table->string('first_name', 128);
            $table->string('last_name', 128);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('containers', function (Blueprint $table) {
            $table->id();
            $table->string('container_number', 64)->unique();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('service_resource_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_resource_id')
                ->unique()
                ->constrained('service_resource')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('service_id')
                ->constrained('services')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('resource_id')
                ->constrained('resources')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('vehicle_id')
                ->nullable()
                ->constrained('vehicles')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('operator_id')
                ->nullable()
                ->constrained('operators')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('container_id')
                ->nullable()
                ->constrained('containers')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->string('remesa_transporte', 128)->nullable();
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->timestamps();

            $table->index(['service_id', 'resource_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_resource_reports');
        Schema::dropIfExists('containers');
        Schema::dropIfExists('operators');
        Schema::dropIfExists('vehicles');
    }
};
