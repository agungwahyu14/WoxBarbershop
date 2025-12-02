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
        
        // Auto-cancel and expire bookings every hour
        // Rule 1: Bookings older than 24 hours → EXPIRED
        // Rule 2: Bookings passed but < 24 hours with pending status → CANCELLED
        $schedule->command('app:cancel-expired-bookings')
                 ->hourly()
                 ->description('Auto-cancel and expire bookings based on time constraints')
                 ->withoutOverlapping()
                 ->runInBackground();
        
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
