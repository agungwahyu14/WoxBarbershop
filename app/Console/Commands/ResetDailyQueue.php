<?php

namespace App\Console\Commands;

use App\Services\QueueService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ResetDailyQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:reset-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset daily queue counters at midnight';

    /**
     * Execute the console command.
     */
    public function handle(QueueService $queueService): int
    {
        try {
            $this->info('Starting daily queue reset...');
            
            // Log the reset operation
            Log::info('Daily queue reset command executed', [
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'command' => $this->signature
            ]);
            
            // Call the queue service reset method
            $queueService->resetDailyQueue();
            
            $this->info('Daily queue reset completed successfully.');
            
            Log::info('Daily queue reset completed successfully', [
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'next_reset' => now()->addDay()->startOfDay()->format('Y-m-d H:i:s')
            ]);
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('Error during daily queue reset: ' . $e->getMessage());
            
            Log::error('Daily queue reset failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);
            
            return Command::FAILURE;
        }
    }
}
