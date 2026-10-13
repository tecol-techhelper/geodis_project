<?php

namespace Tests\Unit;

use App\Livewire\Services\ServiceTable;
use App\Models\Service;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\DataProvider;
use PowerComponents\LivewirePowerGrid\DataSource\Builder;
use PowerComponents\LivewirePowerGrid\DataSource\Processors\ModelProcessor;
use ReflectionMethod;
use Tests\TestCase;

class ServiceTableSearchTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('consecutive');
            $table->softDeletes();
        });
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_id');
            $table->softDeletes();
        });
        Schema::create('reference_types', function (Blueprint $table) {
            $table->id();
            $table->string('reference_type_code');
        });
        Schema::create('order_references', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_order_id');
            $table->unsignedBigInteger('reference_type_id');
            $table->string('order_reference_value');
            $table->softDeletes();
        });

        DB::table('services')->insert([
            ['id' => 1, 'consecutive' => 'N3594432', 'deleted_at' => null],
            ['id' => 2, 'consecutive' => 'N3524514', 'deleted_at' => null],
            ['id' => 3, 'consecutive' => 'N-DELETED', 'deleted_at' => '2026-10-01'],
        ]);
        DB::table('purchase_orders')->insert([
            ['id' => 1, 'service_id' => 1, 'deleted_at' => null],
            ['id' => 2, 'service_id' => 1, 'deleted_at' => null],
            ['id' => 3, 'service_id' => 2, 'deleted_at' => null],
            ['id' => 4, 'service_id' => 1, 'deleted_at' => '2026-10-01'],
            ['id' => 5, 'service_id' => 3, 'deleted_at' => null],
        ]);
        DB::table('reference_types')->insert([
            ['id' => 1, 'reference_type_code' => 'COI'],
            ['id' => 2, 'reference_type_code' => 'AGW'],
            ['id' => 3, 'reference_type_code' => 'ACD'],
        ]);
        foreach ([
            [1, 1, 'FN226786P125/ORDER-SUFFIX', null],
            [2, 1, 'FN226786P132', null],
            [1, 2, 'CRITICAL/CON-001', null],
            [2, 2, 'STANDARD/CON-002', null],
            [3, 1, 'OTHER-ORDER', null],
            [1, 3, 'UNRELATED', null],
            [4, 1, 'DELETED-ORDER', null],
            [1, 1, 'DELETED-REFERENCE', '2026-10-01'],
            [5, 1, 'DELETED-SERVICE-ORDER', null],
        ] as [$orderId, $typeId, $value, $deletedAt]) {
            DB::table('order_references')->insert([
                'purchase_order_id' => $orderId,
                'reference_type_id' => $typeId,
                'order_reference_value' => $value,
                'deleted_at' => $deletedAt,
            ]);
        }
    }

    #[DataProvider('searches')]
    public function test_search_matches_any_order_without_duplicate_services(string $search, array $expected): void
    {
        $component = new ServiceTable;
        $component->columns = $component->columns();
        $component->search = $search;
        $query = Service::query();
        Builder::make($query, $component)->filterContains();
        (new ReflectionMethod(ModelProcessor::class, 'applyColumnRawQueries'))
            ->invoke(new ModelProcessor($component), $query);

        $this->assertSame($expected, $query->orderBy('id')->pluck('id')->all());
    }

    public static function searches(): array
    {
        return [
            'consecutive' => ['N3594432', [1]],
            'first order' => ['FN226786P125', [1]],
            'second order' => ['FN226786P132', [1]],
            'partial orders once' => ['fn226786', [1]],
            'first consolidated' => ['CON-001', [1]],
            'second consolidated' => ['con-002', [1]],
            'other service' => ['OTHER-ORDER', [2]],
            'priority excluded' => ['CRITICAL', []],
            'order suffix excluded' => ['ORDER-SUFFIX', []],
            'other reference excluded' => ['UNRELATED', []],
            'deleted order excluded' => ['DELETED-ORDER', []],
            'deleted reference excluded' => ['DELETED-REFERENCE', []],
            'deleted service excluded' => ['DELETED-SERVICE-ORDER', []],
        ];
    }

    public function test_origin_and_destination_are_no_longer_searchable(): void
    {
        $columns = collect((new ServiceTable)->columns());

        foreach (['origin_full', 'destination_full'] as $field) {
            $column = $columns->firstWhere('field', $field);
            $this->assertFalse($column->searchable);
            $this->assertSame([], $column->rawQueries);
        }
    }
}
