<?php

namespace Tests\Feature\Services;

use App\Livewire\Services\ServiceInlineDetails;
use App\Livewire\Services\ServiceTable;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Tests\TestCase;

class ServiceDeletionWorkflowTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createAuthorizationTables();
        $this->createServiceTables();
    }

    public function test_coordinator_can_soft_delete_but_cannot_open_trash_or_purge(): void
    {
        $coordinator = $this->createUserWithRole('coord', ['delete_services']);
        $service = Service::query()->create(['consecutive' => 'SVC-COORD']);

        $component = Livewire::actingAs($coordinator)
            ->test(ServiceTable::class);

        $component
            ->assertDontSeeHtml('aria-label="Mostrar papelera de servicios"')
            ->call('requestServiceDeletion', $service->id)
            ->assertSet('pendingDeletionType', 'delete')
            ->assertSee('¿Está seguro de eliminar el servicio #SVC-COORD?')
            ->assertSee('Eliminar')
            ->assertDontSee('Esta eliminación es permanente')
            ->call('confirmServiceDeletion');

        $this->assertSoftDeleted('services', ['id' => $service->id]);

        Livewire::actingAs($coordinator)
            ->test(ServiceTable::class)
            ->call('setTrashMode', true)
            ->assertForbidden();

        Livewire::actingAs($coordinator)
            ->test(ServiceTable::class)
            ->set('showTrash', true)
            ->assertForbidden();

        Livewire::actingAs($coordinator)
            ->test(ServiceTable::class)
            ->set('pendingServiceId', $service->id)
            ->set('pendingDeletionType', 'purge')
            ->call('confirmServiceDeletion')
            ->assertForbidden();

        $this->assertDatabaseHas('services', ['id' => $service->id]);
    }

    public function test_admin_can_view_trash_and_purge_the_complete_database_graph(): void
    {
        $admin = $this->createUserWithRole('admin', ['delete_services', 'purge_services']);
        $service = Service::query()->create(['consecutive' => 'SVC-ADMIN']);
        $relatedTables = $this->createRelatedGraph($service->id);
        $service->delete();

        $component = Livewire::actingAs($admin)
            ->test(ServiceTable::class);

        $component
            ->assertSeeHtml('aria-label="Cambiar vista de servicios"')
            ->assertSeeHtml('aria-label="Mostrar servicios activos"')
            ->assertSeeHtml('aria-label="Mostrar papelera de servicios"')
            ->assertSeeHtml('duration-300')
            ->call('setTrashMode', true)
            ->assertSet('showTrash', true)
            ->call('setTrashMode', false)
            ->assertSet('showTrash', false)
            ->call('setTrashMode', true)
            ->call('requestServiceDeletion', $service->id)
            ->assertSet('pendingDeletionType', 'purge')
            ->assertSee('¿Está seguro de eliminar el servicio #SVC-ADMIN?')
            ->assertSee('Esta eliminación es permanente')
            ->assertSee('Eliminar')
            ->call('confirmServiceDeletion');

        $this->assertDatabaseMissing('services', ['id' => $service->id]);

        foreach ($relatedTables as $table) {
            $this->assertDatabaseCount($table, 0);
        }
    }

    public function test_active_and_trash_modes_never_mix_services(): void
    {
        $admin = $this->createUserWithRole('admin', ['delete_services', 'purge_services']);
        Service::query()->create(['consecutive' => 'ACTIVE-ONLY']);
        $trashedService = Service::query()->create(['consecutive' => 'TRASH-ONLY']);
        $trashedService->delete();

        Livewire::actingAs($admin)
            ->test(ServiceTable::class)
            ->assertSee('ACTIVE-ONLY')
            ->assertDontSee('TRASH-ONLY')
            ->call('setTrashMode', true)
            ->assertDontSee('ACTIVE-ONLY')
            ->assertSee('TRASH-ONLY');
    }

    public function test_inline_details_keeps_a_root_element_when_hidden_for_trash(): void
    {
        Livewire::test(ServiceInlineDetails::class)
            ->dispatch('service-list-mode-changed', showTrash: true)
            ->assertSet('hiddenForTrash', true)
            ->assertOk();
    }

    private function createAuthorizationTables(): void
    {
        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('username')->unique();
            $table->string('user_area')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedTinyInteger('is_active')->default(1);
            $table->string('guard_name')->default('web');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table): void {
            $table->id();
            $table->string('rol_key')->unique();
            $table->string('rol_name');
            $table->string('rol_description');
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table): void {
            $table->id();
            $table->string('permission_key')->unique();
            $table->string('permission_name');
            $table->string('permission_description');
            $table->timestamps();
        });

        Schema::create('role_user', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('user_id');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('permission_roles', function (Blueprint $table): void {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');
            $table->timestamps();
        });

        Schema::create('permission_users', function (Blueprint $table): void {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });
    }

    private function createServiceTables(): void
    {
        Schema::create('services', function (Blueprint $table): void {
            $table->id();
            $table->string('consecutive')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('reference_types', function (Blueprint $table): void {
            $table->id();
            $table->string('reference_type_code')->nullable();
        });

        Schema::create('global_measure_types', function (Blueprint $table): void {
            $table->id();
            $table->string('type_qualifier')->nullable();
        });

        Schema::create('party_types', function (Blueprint $table): void {
            $table->id();
            $table->string('party_qualifier')->nullable();
        });

        Schema::create('purchase_orders', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('service_id');
            $table->softDeletes();
        });

        Schema::create('order_references', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('purchase_order_id');
            $table->unsignedBigInteger('reference_type_id')->nullable();
            $table->string('order_reference_value')->nullable();
        });

        Schema::create('service_measurements', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('global_measure_type_id')->nullable();
            $table->decimal('measure_value')->nullable();
            $table->string('measure_unit')->nullable();
        });

        Schema::create('service_parties', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('party_type_id')->nullable();
            $table->string('party_name')->nullable();
            $table->string('party_street')->nullable();
            $table->string('party_city')->nullable();
            $table->string('party_region')->nullable();
        });

        Schema::create('purchase_order_parties', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('purchase_order_id');
            $table->unsignedBigInteger('party_type_id')->nullable();
            $table->string('party_name')->nullable();
            $table->string('party_street')->nullable();
            $table->string('party_city')->nullable();
            $table->string('party_region')->nullable();
        });

        foreach ([
            'notifications',
            'service_dates',
            'transport_details',
            'location_details',
            'service_equipment',
            'service_resource',
            'edifact_files',
            'edi_failed_files',
        ] as $tableName) {
            Schema::create($tableName, function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('service_id')->nullable();
            });
        }

        Schema::create('support_files', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->unsignedBigInteger('purchase_order_id')->nullable();
        });

        Schema::create('service_contacts', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('service_id');
        });

        Schema::create('service_contact_details', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('service_contact_id');
        });

        Schema::create('purchase_order_items', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('purchase_order_id');
        });

        Schema::create('purchase_order_contacts', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('purchase_order_id');
        });

        Schema::create('purchase_order_contact_details', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('purchase_order_contact_id');
        });

        foreach ([
            'purchase_order_notes',
            'purchase_order_measurements',
            'purchase_order_requirements',
            'transport_charges',
            'delivery_terms',
            'purchase_order_resource',
        ] as $tableName) {
            Schema::create($tableName, function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('purchase_order_id');
            });
        }

        foreach ([
            'item_product_identifiers',
            'item_container_identifiers',
            'item_unit_identifiers',
            'item_measures',
            'item_dimensions',
            'item_notes',
        ] as $tableName) {
            Schema::create($tableName, function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('purchase_order_item_id');
            });
        }
    }

    /**
     * @param  array<int, string>  $permissionKeys
     */
    private function createUserWithRole(string $roleKey, array $permissionKeys): User
    {
        $user = User::query()->create([
            'username' => $roleKey.'_user',
            'email' => $roleKey.'@example.test',
            'password' => 'Password123*',
            'is_active' => 1,
        ]);

        $roleId = DB::table('roles')->insertGetId([
            'rol_key' => $roleKey,
            'rol_name' => ucfirst($roleKey),
            'rol_description' => $roleKey,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('role_user')->insert([
            'role_id' => $roleId,
            'user_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($permissionKeys as $permissionKey) {
            $permissionId = DB::table('permissions')->insertGetId([
                'permission_key' => $permissionKey,
                'permission_name' => $permissionKey,
                'permission_description' => $permissionKey,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('permission_roles')->insert([
                'permission_id' => $permissionId,
                'role_id' => $roleId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $user;
    }

    /**
     * @return array<int, string>
     */
    private function createRelatedGraph(int $serviceId): array
    {
        $purchaseOrderId = DB::table('purchase_orders')->insertGetId(['service_id' => $serviceId]);
        $itemId = DB::table('purchase_order_items')->insertGetId(['purchase_order_id' => $purchaseOrderId]);
        $purchaseOrderContactId = DB::table('purchase_order_contacts')
            ->insertGetId(['purchase_order_id' => $purchaseOrderId]);
        $serviceContactId = DB::table('service_contacts')->insertGetId(['service_id' => $serviceId]);

        DB::table('support_files')->insert([
            'service_id' => $serviceId,
            'purchase_order_id' => $purchaseOrderId,
        ]);
        DB::table('service_contact_details')->insert(['service_contact_id' => $serviceContactId]);
        DB::table('purchase_order_contact_details')->insert([
            'purchase_order_contact_id' => $purchaseOrderContactId,
        ]);

        foreach ([
            'notifications',
            'service_dates',
            'service_measurements',
            'service_parties',
            'transport_details',
            'location_details',
            'service_equipment',
            'service_resource',
            'edifact_files',
            'edi_failed_files',
        ] as $tableName) {
            DB::table($tableName)->insert(['service_id' => $serviceId]);
        }

        foreach ([
            'order_references',
            'purchase_order_parties',
            'purchase_order_notes',
            'purchase_order_measurements',
            'purchase_order_requirements',
            'transport_charges',
            'delivery_terms',
            'purchase_order_resource',
        ] as $tableName) {
            DB::table($tableName)->insert(['purchase_order_id' => $purchaseOrderId]);
        }

        foreach ([
            'item_product_identifiers',
            'item_container_identifiers',
            'item_unit_identifiers',
            'item_measures',
            'item_dimensions',
            'item_notes',
        ] as $tableName) {
            DB::table($tableName)->insert(['purchase_order_item_id' => $itemId]);
        }

        return [
            'support_files',
            'service_contact_details',
            'service_contacts',
            'purchase_order_contact_details',
            'purchase_order_contacts',
            'purchase_order_items',
            'order_references',
            'purchase_order_parties',
            'purchase_order_notes',
            'purchase_order_measurements',
            'purchase_order_requirements',
            'transport_charges',
            'delivery_terms',
            'purchase_order_resource',
            'item_product_identifiers',
            'item_container_identifiers',
            'item_unit_identifiers',
            'item_measures',
            'item_dimensions',
            'item_notes',
            'purchase_orders',
            'notifications',
            'service_dates',
            'service_measurements',
            'service_parties',
            'transport_details',
            'location_details',
            'service_equipment',
            'service_resource',
            'edifact_files',
            'edi_failed_files',
        ];
    }
}
