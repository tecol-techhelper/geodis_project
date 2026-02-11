<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('edifact:process --max=50 --move-remote=1')->everyFiveMinutes()->withoutOverlapping(10)->appendOutputTo(storage_path('logs/edifact-process.log'));
Schedule::command('custom:clean-livewire-tmp')->hourly();
