<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\SyncCarriersCommand::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Run full sync every 10 minutes in production
        $schedule->job(new \App\Jobs\SyncCarriersJob)
                 ->everyTenMinutes()
                 ->withoutOverlapping(); // prevent double runs
    }
}
