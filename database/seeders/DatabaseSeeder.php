<?php

namespace Database\Seeders;

use App\Models\FileType;
use App\Models\MeasurementAttributeCode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            PermissionRoleSeeder::class,
            UserSeeder::class,
            FileTypeSeeder::class,
            ContactTypeSeeder::class,
            StatusSeeder::class,
            GlobalMeasureTypeSeeder::class,
            DateTypeSeeder::class,
            TransportModeSeeder::class,
            TransportStageSeeder::class,
            LocationCodeSeeder::class,
            EquipmentTypeSeeder::class,
            ReferenceTypeSeeder::class,
            IdentifierTypeSeeder::class,
            PriceQualifierSeeder::class,
            NoteTypesSeeder::class,
            MeasurementPurposeCodeSeeder::class,
            MeasurementAttributeCodeSeeder::class,
            DimensionTypeSeeder::class,
            ProductIdentifierRoleSeeder::class,
            ProductIdentifierTypeSeeder::class,
            DeliveryTermCatalogSeeder::class,
            PartyTypeSeeder::class
        ]);
    }
}
