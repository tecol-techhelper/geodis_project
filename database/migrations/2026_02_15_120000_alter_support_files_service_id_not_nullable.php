<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $nullServiceCount = DB::table('support_files')->whereNull('service_id')->count();

        if ($nullServiceCount > 0) {
            $firstServiceId = DB::table('services')->orderBy('id')->value('id');

            if ($firstServiceId === null) {
                throw new RuntimeException('No se puede hacer support_files.service_id obligatorio: existen soportes sin servicio y no hay servicios creados para reasignar.');
            }

            DB::table('support_files')
                ->whereNull('service_id')
                ->update(['service_id' => $firstServiceId]);
        }

        DB::statement('ALTER TABLE support_files MODIFY service_id BIGINT UNSIGNED NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE support_files MODIFY service_id BIGINT UNSIGNED NULL');
    }
};
