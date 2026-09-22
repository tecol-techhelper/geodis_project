<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = array_values(array_filter([
            Schema::hasColumn('resource_tariffs', 'source') ? 'source' : null,
            Schema::hasColumn('resource_tariffs', 'source_reference') ? 'source_reference' : null,
            Schema::hasColumn('resource_tariffs', 'imported_at') ? 'imported_at' : null,
        ]));

        if ($columns !== []) {
            Schema::table('resource_tariffs', function (Blueprint $table) use ($columns): void {
                $table->dropColumn($columns);
            });
        }
    }

    public function down(): void
    {
        Schema::table('resource_tariffs', function (Blueprint $table): void {
            $table->string('source', 32)->default('MANUAL')->after('is_active');
            $table->string('source_reference', 191)->nullable()->after('source');
            $table->timestamp('imported_at')->nullable()->after('source_reference');
        });
    }
};
