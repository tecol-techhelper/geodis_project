<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('status_purposes', function (Blueprint $table) {
            $table->string('purpose_subcode', 16)->nullable()->after('purpose_code');
        });
    }

    public function down(): void
    {
        Schema::table('status_purposes', function (Blueprint $table) {
            $table->dropColumn('purpose_subcode');
        });
    }
};
