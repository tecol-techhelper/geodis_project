<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const INDEXES = [
        'services' => ['services_consecutive_index', ['consecutive']],
        'service_status_reports' => ['service_status_reports_status_reported_service_index', ['status_id', 'reported_at', 'service_id']],
        'order_references' => ['order_references_purchase_type_value_index', ['purchase_order_id', 'reference_type_id', 'order_reference_value']],
        'service_resource_status_reports' => ['service_resource_status_reports_resource_reported_index', ['service_resource_id', 'reported_at']],
    ];

    public function up(): void
    {
        foreach (self::INDEXES as $name => [$index, $columns]) {
            if (! Schema::hasIndex($name, $index)) {
                Schema::table($name, fn (Blueprint $table) => $table->index($columns, $index));
            }
        }
    }

    public function down(): void
    {
        foreach (array_reverse(self::INDEXES, true) as $name => [$index, $columns]) {
            if (! Schema::hasIndex($name, $index)) {
                continue;
            }
            foreach (Schema::getForeignKeys($name) as $foreignKey) {
                $foreignColumns = $foreignKey['columns'];
                if (array_slice($columns, 0, count($foreignColumns)) !== $foreignColumns) {
                    continue;
                }
                $hasReplacement = collect(Schema::getIndexes($name))->contains(
                    fn ($existing) => $existing['name'] !== $index
                        && array_slice($existing['columns'], 0, count($foreignColumns)) === $foreignColumns
                );
                if (! $hasReplacement) {
                    // InnoDB may have removed the original FK index when this composite index was added.
                    Schema::table($name, fn (Blueprint $table) => $table->index($foreignColumns));
                }
            }
            Schema::table($name, fn (Blueprint $table) => $table->dropIndex($index));
        }
    }
};
