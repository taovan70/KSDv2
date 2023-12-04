<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('app:publish-articles-command')->daily();
        $schedule->command('app:clear-temp-images-folder-command')->dailyAt('04:00');
	$schedule->command('process:invoices')->daily()->at('02:00')->timezone('America/Chicago');
	$schedule->command('process:latefees')->daily()->at('04:00')->timezone('America/Chicago');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
