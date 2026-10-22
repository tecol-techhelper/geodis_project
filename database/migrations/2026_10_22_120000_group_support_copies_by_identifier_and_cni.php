<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL commits each ALTER separately; allow resuming after a partial failure.
        if (! Schema::hasColumn('support_file_copies', 'cni')) {
            Schema::table('support_file_copies', function (Blueprint $table) {
                $table->string('cni')->nullable();
            });
        }
        if (! Schema::hasIndex('support_file_copies', 'support_copies_identifier_cni_unique', 'unique')) {
            Schema::table('support_file_copies', function (Blueprint $table) {
                $table->unique(['support_file_id', 'identifier', 'cni'], 'support_copies_identifier_cni_unique');
            });
        }
        // Keep an index starting with support_file_id for MySQL's foreign key.
        if (Schema::hasIndex('support_file_copies', 'support_file_copies_support_file_id_identifier_unique', 'unique')) {
            Schema::table('support_file_copies', function (Blueprint $table) {
                $table->dropUnique(['support_file_id', 'identifier']);
            });
        }
    }

    public function down(): void
    {
        if (DB::table('support_file_copies')->select('support_file_id', 'identifier')
            ->groupBy('support_file_id', 'identifier')->havingRaw('COUNT(*) > 1')->exists()) {
            throw new RuntimeException('No se puede revertir: existen copias de una OC/FN para distintas SHO. No se eliminarán sus registros.');
        }
        if (! Schema::hasIndex('support_file_copies', 'support_file_copies_support_file_id_identifier_unique', 'unique')) {
            Schema::table('support_file_copies', function (Blueprint $table) {
                $table->unique(['support_file_id', 'identifier']);
            });
        }
        if (Schema::hasIndex('support_file_copies', 'support_copies_identifier_cni_unique', 'unique')) {
            Schema::table('support_file_copies', function (Blueprint $table) {
                $table->dropUnique('support_copies_identifier_cni_unique');
            });
        }
        if (Schema::hasColumn('support_file_copies', 'cni')) {
            Schema::table('support_file_copies', function (Blueprint $table) {
                $table->dropColumn('cni');
            });
        }
    }
};
