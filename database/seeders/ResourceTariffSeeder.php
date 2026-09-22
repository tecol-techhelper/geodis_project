<?php

namespace Database\Seeders;

use App\Services\Tariffs\TariffSpreadsheetImporter;
use App\Services\Tariffs\TransportTariffSpreadsheetImporter;
use Illuminate\Database\Seeder;

class ResourceTariffSeeder extends Seeder
{
    public function run(): void
    {
        app(TariffSpreadsheetImporter::class)->import();
        app(TransportTariffSpreadsheetImporter::class)->import();
    }
}
