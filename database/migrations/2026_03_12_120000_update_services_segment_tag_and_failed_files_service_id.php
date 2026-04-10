<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('segment_tag', 7)->default('RFF+SRN')->change();
        });

        Schema::table('edi_failed_files', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->foreignId('service_id')->nullable()->change();
            $table->foreign('service_id')->references('id')->on('services')->nullOnDelete();
        });
    }

    public function down(): void
    {
        // El FK puede no existir en algunos entornos, validar antes de dropear
        $fk = 'edi_failed_files_service_id_foreign';
        $exists = DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', DB::raw('DATABASE()'))
            ->where('TABLE_NAME', 'edi_failed_files')
            ->where('CONSTRAINT_NAME', $fk)
            ->where('CONSTRAINT_TYPE', 'FOREIGN KEY')
            ->exists();

        if ($exists) {
            DB::statement("ALTER TABLE `edi_failed_files` DROP FOREIGN KEY `{$fk}`");
        }

        Schema::table('edi_failed_files', function (Blueprint $table) {
            // Mantener nullable para no romper historiales sin service_id
            $table->foreignId('service_id')->nullable()->change();
            $table->foreign('service_id')->references('id')->on('services')->nullOnDelete();
        });

        Schema::table('services', function (Blueprint $table) {
            // Mantener longitud 7 para evitar truncamiento si hay valores como "RFF+SRN"
            $table->string('segment_tag', 7)->default('BGM')->change();
        });
    }
};
