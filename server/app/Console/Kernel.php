<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\SyncCarriersJob;
class Kernel extends ConsoleKernel
{
    use App\Jobs\SyncCarriersJob;

Schedule::job(new SyncCarriersJob)->everyFifteenMinutes();
}
