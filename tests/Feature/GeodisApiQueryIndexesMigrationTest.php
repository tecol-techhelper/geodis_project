<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class GeodisApiQueryIndexesMigrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        foreach (['purchase_orders', 'reference_types', 'statuses', 'service_resource'] as $name) {
            Schema::create($name, fn (Blueprint $table) => $table->id());
        }
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('consecutive')->nullable();
        });
        Schema::create('order_references', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained();
            $table->foreignId('reference_type_id')->constrained();
            $table->string('order_reference_value')->nullable();
        });
        Schema::create('service_status_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained();
            $table->foreignId('status_id')->constrained();
            $table->timestamp('reported_at')->nullable();
            $table->unique(['service_id', 'status_id']);
        });
        Schema::create('service_resource_status_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_resource_id')->constrained('service_resource');
            $table->foreignId('service_status_report_id')->constrained();
            $table->timestamp('reported_at')->nullable();
            $table->unique(['service_resource_id', 'service_status_report_id']);
        });
    }

    public function test_rollback_preserves_references_and_foreign_keys_and_can_be_repeated(): void
    {
        DB::table('purchase_orders')->insert(['id' => 1]);
        DB::table('reference_types')->insert(['id' => 1]);
        DB::table('order_references')->insert(['purchase_order_id' => 1, 'reference_type_id' => 1, 'order_reference_value' => 'OC1']);
        $migration = require database_path('migrations/2026_07_15_120000_add_geodis_api_query_indexes.php');
        $migration->up();
        $migration->up();
        $migration->down();
        $migration->down();
        $this->assertSame('OC1', DB::table('order_references')->value('order_reference_value'));
        $this->assertTrue(Schema::hasIndex('order_references', ['purchase_order_id']));
        $this->assertTrue(Schema::hasIndex('service_status_reports', ['status_id']));
        $this->assertFalse(Schema::hasIndex('service_resource_status_reports', ['service_resource_id']));
        $this->assertCount(2, Schema::getForeignKeys('order_references'));
        $this->assertFalse(Schema::hasIndex('order_references', 'order_references_purchase_type_value_index'));
        $migration->up();
        $this->assertTrue(Schema::hasIndex('order_references', 'order_references_purchase_type_value_index'));
    }

    public function test_partial_rollback_can_resume_and_recreates_fk_index_before_dropping_composite(): void
    {
        $migration = require database_path('migrations/2026_07_15_120000_add_geodis_api_query_indexes.php');
        $migration->up();
        Schema::table('service_resource_status_reports', fn (Blueprint $table) => $table->dropIndex('service_resource_status_reports_resource_reported_index'));
        DB::connection()->enableQueryLog();
        $migration->down();
        $queries = array_column(DB::getQueryLog(), 'query');
        DB::connection()->disableQueryLog();
        $create = array_keys(array_filter($queries, fn ($query) => str_contains($query, 'create index') && str_contains($query, 'order_references_purchase_order_id_index')));
        $drop = array_keys(array_filter($queries, fn ($query) => str_contains($query, 'drop index') && str_contains($query, 'order_references_purchase_type_value_index')));
        $this->assertCount(1, $create);
        $this->assertCount(1, $drop);
        $this->assertLessThan($drop[0], $create[0]);
        $this->assertTrue(Schema::hasIndex('order_references', ['purchase_order_id']));
    }

    public function test_existing_single_column_index_is_reused(): void
    {
        Schema::table('order_references', fn (Blueprint $table) => $table->index('purchase_order_id', 'existing_purchase_index'));
        $migration = require database_path('migrations/2026_07_15_120000_add_geodis_api_query_indexes.php');
        $migration->up();
        $migration->down();
        $this->assertTrue(Schema::hasIndex('order_references', 'existing_purchase_index'));
        $this->assertFalse(Schema::hasIndex('order_references', 'order_references_purchase_order_id_index'));
    }
}
