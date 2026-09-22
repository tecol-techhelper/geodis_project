<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('region_origins', function (Blueprint $table): void {
            $table->foreignId('origin_id')->nullable()->after('region_id');
        });

        $timestamp = now();

        DB::table('region_origins')
            ->orderBy('id')
            ->chunkById(200, function ($regionOrigins) use ($timestamp): void {
                foreach ($regionOrigins as $regionOrigin) {
                    $originId = DB::table('origins')
                        ->where('normalized_origin', $regionOrigin->normalized_origin)
                        ->value('id');

                    if ($originId === null) {
                        $originId = DB::table('origins')->insertGetId([
                            'origin' => $regionOrigin->origin,
                            'normalized_origin' => $regionOrigin->normalized_origin,
                            'created_at' => $timestamp,
                            'updated_at' => $timestamp,
                        ]);
                    }

                    DB::table('region_origins')
                        ->where('id', $regionOrigin->id)
                        ->update(['origin_id' => $originId]);
                }
            });

        if (DB::table('region_origins')->whereNull('origin_id')->exists()) {
            throw new \RuntimeException('No fue posible asociar todos los origenes existentes al nuevo catalogo.');
        }

        Schema::table('region_origins', function (Blueprint $table): void {
            $table->foreignId('origin_id')->nullable(false)->change();
            $table->foreign('origin_id')
                ->references('id')
                ->on('origins')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->unique('origin_id');
        });
    }

    public function down(): void
    {
        Schema::table('region_origins', function (Blueprint $table): void {
            $table->dropForeign(['origin_id']);
            $table->dropUnique(['origin_id']);
            $table->dropColumn('origin_id');
        });
    }
};
