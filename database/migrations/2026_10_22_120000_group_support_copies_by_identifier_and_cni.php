<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('support_file_copies', function (Blueprint $table) {
            // Existing uploads retain their names and association snapshots.
            $table->string('cni')->nullable();
            $table->dropUnique(['support_file_id', 'identifier']);
            $table->unique(['support_file_id', 'identifier', 'cni'], 'support_copies_identifier_cni_unique');
        });
    }

    public function down(): void
    {
        if (DB::table('support_file_copies')->select('support_file_id', 'identifier')
            ->groupBy('support_file_id', 'identifier')->havingRaw('COUNT(*) > 1')->exists()) {
            throw new RuntimeException('No se puede revertir: existen copias de una OC/FN para distintas SHO. No se eliminarán sus registros.');
        }
        Schema::table('support_file_copies', function (Blueprint $table) {
            $table->dropUnique('support_copies_identifier_cni_unique');
            $table->dropColumn('cni');
            $table->unique(['support_file_id', 'identifier']);
        });
    }
};
