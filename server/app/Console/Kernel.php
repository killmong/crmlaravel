<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\SyncCarriersJob;

class Kernel extends ConsoleKernel
{
    // Define your scheduled tasks here
    protected function schedule(Schedule $schedule): void
    {
        // Run SyncCarriersJob every 15 minutes
        // $schedule->job(new SyncCarriersJob)->everyFifteenMinutes();
    }
}