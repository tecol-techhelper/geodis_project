<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('region_origins')->whereNull('origin_id')->exists()) {
            throw new RuntimeException('No se pueden retirar las columnas heredadas: existen regionales sin origen catalogado.');
        }

        if (DB::table('resources')->whereNull('resource_operation_id')->exists()) {
            throw new RuntimeException('No se puede retirar la columna heredada: existen recursos sin operación catalogada.');
        }

        Schema::table('region_origins', function (Blueprint $table): void {
            $table->dropUnique(['normalized_origin']);
            $table->dropColumn(['origin', 'normalized_origin']);
        });

        Schema::table('resources', function (Blueprint $table): void {
            $table->dropColumn('resource_operation');
        });
    }

    public function down(): void
    {
        Schema::table('region_origins', function (Blueprint $table): void {
            $table->string('origin', 191)->nullable()->after('origin_id');
            $table->string('normalized_origin', 191)->nullable()->after('origin');
        });

        DB::table('region_origins')
            ->orderBy('id')
            ->chunkById(200, function ($regionOrigins): void {
                foreach ($regionOrigins as $regionOrigin) {
                    $origin = DB::table('origins')->where('id', $regionOrigin->origin_id)->first();

                    DB::table('region_origins')->where('id', $regionOrigin->id)->update([
                        'origin' => $origin->origin,
                        'normalized_origin' => $origin->normalized_origin,
                    ]);
                }
            });

        Schema::table('region_origins', function (Blueprint $table): void {
            $table->string('origin', 191)->nullable(false)->change();
            $table->string('normalized_origin', 191)->nullable(false)->change();
            $table->unique('normalized_origin');
        });

        Schema::table('resources', function (Blueprint $table): void {
            $table->string('resource_operation', 32)->nullable()->after('resource_id');
        });

        DB::table('resources')
            ->orderBy('id')
            ->chunkById(200, function ($resources): void {
                foreach ($resources as $resource) {
                    $operation = DB::table('resource_operations')->where('id', $resource->resource_operation_id)->value('name');

                    DB::table('resources')->where('id', $resource->id)->update([
                        'resource_operation' => $operation,
                    ]);
                }
            });

        Schema::table('resources', function (Blueprint $table): void {
            $table->string('resource_operation', 32)->nullable(false)->change();
        });
    }
};
