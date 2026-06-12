<?php

namespace Tests\Feature\Authorization;

use App\Enums\Permission as PermissionEnum;
use App\Models\Role;
use Database\Seeders\PermissionRoleSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ServicePermissionCatalogTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('roles', function (Blueprint $table): void {
            $table->id();
            $table->string('rol_key', 16)->unique();
            $table->string('rol_name', 64)->unique();
            $table->string('rol_description');
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table): void {
            $table->id();
            $table->string('permission_key', 32)->unique();
            $table->string('permission_name', 64)->unique();
            $table->string('permission_description');
            $table->timestamps();
        });

        Schema::create('permission_roles', function (Blueprint $table): void {
            $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->primary(['permission_id', 'role_id']);
            $table->timestamps();
        });
    }

    public function test_service_permissions_and_role_assignments_match_the_approved_matrix(): void
    {
        $this->seed([
            RoleSeeder::class,
            PermissionSeeder::class,
            PermissionRoleSeeder::class,
        ]);

        $servicePermissions = DB::table('permissions')
            ->whereIn('permission_key', [
                'view_services',
                'edit_services',
                'delete_services',
                'purge_services',
            ])
            ->pluck('permission_key')
            ->sort()
            ->values()
            ->all();

        $this->assertSame([
            'delete_services',
            'edit_services',
            'purge_services',
            'view_services',
        ], $servicePermissions);

        $this->assertSame([
            'delete_services',
            'edit_services',
            'upload_files',
            'view_services',
        ], $this->permissionKeysForRole('coord'));

        foreach (['account', 'od', 'sec', 'spec'] as $roleKey) {
            $this->assertSame(['view_services'], $this->permissionKeysForRole($roleKey));
        }

        $adminPermissions = $this->permissionKeysForRole('admin');
        $this->assertContains('view_services', $adminPermissions);
        $this->assertContains('edit_services', $adminPermissions);
        $this->assertContains('delete_services', $adminPermissions);
        $this->assertContains('purge_services', $adminPermissions);
        $this->assertNotContains('purge_services', $this->permissionKeysForRole('coord'));

        $this->assertSame(0, DB::table('permissions')
            ->whereIn('permission_key', [
                'edit_transport_block',
                'edit_accounting_block',
                'edit_tracking_block',
                'edit_payment_block',
                'edit_record_block',
            ])
            ->count());
    }

    public function test_permission_enum_uses_the_catalog_keys(): void
    {
        $this->assertSame('view_services', PermissionEnum::ViewServices->value);
        $this->assertSame('edit_services', PermissionEnum::EditServices->value);
        $this->assertSame('delete_services', PermissionEnum::DeleteServices->value);
        $this->assertSame('purge_services', PermissionEnum::PurgeServices->value);
        $this->assertSame('upload_files', PermissionEnum::UploadFile->value);
    }

    /**
     * @return array<int, string>
     */
    private function permissionKeysForRole(string $roleKey): array
    {
        return Role::query()
            ->where('rol_key', $roleKey)
            ->firstOrFail()
            ->permissions()
            ->orderBy('permission_key')
            ->pluck('permission_key')
            ->all();
    }
}
