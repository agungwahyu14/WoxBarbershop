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
        // $schedule->command('inspire')->hourly();
        
        // Cancel expired bookings every hour
        $schedule->command('app:cancel-expired-bookings')
                 ->hourly()
                 ->description('Cancel bookings and transactions that have expired');
        
        // Reset daily queue counters at midnight (00:00)
        $schedule->command('queue:reset-daily')
                 ->daily()
                 ->at('00:00')
                 ->description('Reset daily queue counters at midnight');
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
