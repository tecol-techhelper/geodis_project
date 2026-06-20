<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE services MODIFY positioning_date DATETIME NULL');
            DB::statement('ALTER TABLE services MODIFY arrival_date DATETIME NULL');
        }
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE services MODIFY positioning_date DATE NULL');
            DB::statement('ALTER TABLE services MODIFY arrival_date DATE NULL');
        }
    }
};
