<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personnel_roles', function (Blueprint $table) {
            $table->id();
            $table->string('code', 64)->unique();
            $table->string('name', 128);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('resource_personnel_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_id');
            $table->foreignId('personnel_role_id');
            $table->unsignedSmallInteger('quantity_required')->default(1);
            $table->boolean('is_required')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('resource_id', 'resource_personnel_resource_fk')
                ->references('id')
                ->on('resources')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('personnel_role_id', 'resource_personnel_role_fk')
                ->references('id')
                ->on('personnel_roles')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->unique(['resource_id', 'personnel_role_id'], 'resource_personnel_role_unique');
            $table->index(['resource_id', 'sort_order'], 'resource_personnel_resource_sort_index');
        });

        Schema::create('service_resource_report_personnel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_resource_report_id');
            $table->foreignId('operator_id');
            $table->foreignId('personnel_role_id');
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('service_resource_report_id', 'srr_personnel_report_fk')
                ->references('id')
                ->on('service_resource_reports')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('operator_id', 'srr_personnel_operator_fk')
                ->references('id')
                ->on('operators')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreign('personnel_role_id', 'srr_personnel_role_fk')
                ->references('id')
                ->on('personnel_roles')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreign('created_by', 'srr_personnel_created_by_fk')
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreign('updated_by', 'srr_personnel_updated_by_fk')
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->index(
                ['service_resource_report_id', 'personnel_role_id'],
                'report_personnel_report_role_index',
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_resource_report_personnel');
        Schema::dropIfExists('resource_personnel_requirements');
        Schema::dropIfExists('personnel_roles');
    }
};
