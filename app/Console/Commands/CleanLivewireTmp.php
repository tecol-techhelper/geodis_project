<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CleanLivewireTmp extends Command
{
    protected $signature = 'custom:clean-livewire-tmp';
    protected $description = 'Elimina archivos temporales viejos de Livewire';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = storage_path('app/private/livewire-tmp');
        $now = Carbon::now();

        if (!File::exists($path)) {
            $this->info('La carpeta livewire-tmp no existe en storage/app/private.');
            return;
        }

        $files = File::allFiles($path);
        $deleted = 0;

        foreach ($files as $file) {
            $modTime = Carbon::createFromTimestamp($file->getMTime());
            $diff = $modTime->diffInMinutes($now, false); // asegúrate de que sea negativo si está en el futuro

            if ($diff > 120) {
                File::delete($file->getPathname());
                $deleted++;
            }
        }
    }
}
