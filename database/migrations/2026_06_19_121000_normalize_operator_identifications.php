<?php

use App\Models\Operator;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $operators = DB::table('operators')
            ->select(['id', 'identification', 'deleted_at'])
            ->orderBy('id')
            ->get()
            ->groupBy(fn($operator) => Operator::normalizeIdentification($operator->identification));

        foreach ($operators as $normalizedIdentification => $group) {
            if ($normalizedIdentification === '') {
                continue;
            }

            $keeper = $group
                ->sortBy([
                    fn($operator) => $operator->deleted_at === null ? 0 : 1,
                    fn($operator) => (int) $operator->id,
                ])
                ->first();

            $duplicateIds = $group
                ->pluck('id')
                ->map(fn($id) => (int) $id)
                ->reject(fn($id) => $id === (int) $keeper->id)
                ->values();

            if ($duplicateIds->isNotEmpty()) {
                DB::table('service_resource_report_personnel')
                    ->whereIn('operator_id', $duplicateIds->all())
                    ->update(['operator_id' => (int) $keeper->id]);

                DB::table('operators')
                    ->whereIn('id', $duplicateIds->all())
                    ->delete();
            }

            DB::table('operators')
                ->where('id', (int) $keeper->id)
                ->update([
                    'identification' => $normalizedIdentification,
                    'deleted_at' => null,
                    'updated_at' => now(),
                ]);
        }
    }

    public function down(): void
    {
        // Normalization is intentionally not reversible.
    }
};
