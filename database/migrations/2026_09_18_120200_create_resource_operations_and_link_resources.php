<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resource_operations', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 32)->unique();
            $table->timestamps();
        });

        Schema::table('resources', function (Blueprint $table): void {
            $table->foreignId('resource_operation_id')
                ->nullable()
                ->after('resource_operation')
                ->constrained('resource_operations')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });

        $timestamp = now();
        $operations = DB::table('resources')
            ->select('resource_operation')
            ->whereNotNull('resource_operation')
            ->distinct()
            ->pluck('resource_operation');

        foreach ($operations as $legacyOperation) {
            $operationName = trim((string) $legacyOperation);

            if ($operationName === '') {
                continue;
            }

            $operationId = DB::table('resource_operations')
                ->where('name', $operationName)
                ->value('id');

            if ($operationId === null) {
                $operationId = DB::table('resource_operations')->insertGetId([
                    'name' => $operationName,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);
            }

            DB::table('resources')
                ->whereRaw('TRIM(resource_operation) = ?', [$operationName])
                ->update(['resource_operation_id' => $operationId]);
        }

        if (DB::table('resources')->whereNull('resource_operation_id')->exists()) {
            throw new \RuntimeException('No fue posible asociar todos los recursos existentes al catalogo de operaciones.');
        }

        Schema::table('resources', function (Blueprint $table): void {
            $table->foreignId('resource_operation_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table): void {
            $table->dropForeign(['resource_operation_id']);
            $table->dropColumn('resource_operation_id');
        });

        Schema::dropIfExists('resource_operations');
    }
};
