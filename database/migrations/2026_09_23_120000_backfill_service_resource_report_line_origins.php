<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $pwPartyTypeId = DB::table('party_types')
            ->whereRaw('UPPER(TRIM(party_qualifier)) = ?', ['PW'])
            ->value('id');

        if ($pwPartyTypeId === null) {
            return;
        }

        $timestamp = now();

        DB::table('service_resource_report_lines as lines')
            ->join('service_resource_reports as reports', 'reports.id', '=', 'lines.service_resource_report_id')
            ->whereNull('lines.origin_id')
            ->select(['lines.id as line_id', 'reports.service_id'])
            ->orderBy('lines.id')
            ->chunkById(200, function ($lines) use ($pwPartyTypeId, $timestamp): void {
                foreach ($lines as $line) {
                    $origins = DB::table('service_parties')
                        ->where('service_id', $line->service_id)
                        ->where('party_type_id', $pwPartyTypeId)
                        ->whereNull('deleted_at')
                        ->pluck('party_city')
                        ->map(function ($origin): ?string {
                            $normalizedOrigin = preg_replace('/\s+/u', ' ', trim((string) $origin));

                            return $normalizedOrigin === null || $normalizedOrigin === ''
                                ? null
                                : Str::upper($normalizedOrigin);
                        })
                        ->filter()
                        ->unique()
                        ->values();

                    if ($origins->count() !== 1) {
                        continue;
                    }

                    $normalizedOrigin = $origins->first();
                    $originId = DB::table('origins')
                        ->where('normalized_origin', $normalizedOrigin)
                        ->value('id');

                    if ($originId === null) {
                        $originId = DB::table('origins')->insertGetId([
                            'origin' => $normalizedOrigin,
                            'normalized_origin' => $normalizedOrigin,
                            'created_at' => $timestamp,
                            'updated_at' => $timestamp,
                        ]);
                    }

                    DB::table('service_resource_report_lines')
                        ->where('id', $line->line_id)
                        ->whereNull('origin_id')
                        ->update([
                            'origin_id' => $originId,
                            'updated_at' => $timestamp,
                        ]);
                }
            }, 'lines.id', 'line_id');
    }

    public function down(): void
    {
        // Existing report lines retain their resolved origin when rolling back.
    }
};
