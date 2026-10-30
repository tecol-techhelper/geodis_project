<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audits', function (Blueprint $table) {
            $table->longText('old_value')->nullable()->change();
            $table->longText('new_value')->nullable()->change();
        });
    }

    public function down(): void
    {
        $length = DB::connection()->getDriverName() === 'sqlite' ? 'length' : 'char_length';
        if (DB::table('audits')->whereRaw("{$length}(old_value) > 2048 OR {$length}(new_value) > 2048")->exists()) {
            throw new RuntimeException('No se puede revertir: existen auditorías de más de 2048 caracteres. No se recortarán sus datos.');
        }

        Schema::table('audits', function (Blueprint $table) {
            $table->string('old_value', 2048)->nullable()->change();
            $table->string('new_value', 2048)->nullable()->change();
        });
    }
};
