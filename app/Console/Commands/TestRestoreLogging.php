<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestRestoreLogging extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:restore-logging';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the restore logging functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing restore logging functionality...');

        // Simulate restore process logs
        Log::info('Database restore initiated', [
            'user_id' => 999,
            'user_email' => 'test@example.com',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Test Command',
            'timestamp' => now()->toDateTimeString()
        ]);

        Log::info('Restore file uploaded', [
            'original_filename' => 'test_backup.sql',
            'file_size' => 1024000,
            'mime_type' => 'application/sql',
            'extension' => 'sql',
            'user_id' => 999
        ]);

        Log::info('SQL file validation passed', [
            'filename' => 'test_backup.sql',
            'has_create_table' => true,
            'has_insert_into' => true,
            'user_id' => 999
        ]);

        Log::info('Database restore completed successfully', [
            'filename' => '1699266645_test_backup.sql',
            'original_filename' => 'test_backup.sql',
            'file_size' => 1024000,
            'restore_duration_seconds' => 12.34,
            'total_duration_seconds' => 15.67,
            'user_id' => 999,
            'user_email' => 'test@example.com',
            'ip_address' => '127.0.0.1',
            'completed_at' => now()->toDateTimeString()
        ]);

        // Simulate an error log
        Log::error('Database restore failed', [
            'filename' => '1699266922_corrupted_file.sql',
            'original_filename' => 'corrupted_file.sql',
            'return_code' => 1,
            'restore_duration_seconds' => 2.15,
            'output' => 'ERROR 1064 (42000): You have an error in your SQL syntax',
            'user_id' => 999,
            'user_email' => 'test@example.com',
            'ip_address' => '127.0.0.1',
            'failed_at' => now()->toDateTimeString()
        ]);

        $this->info('Test logs have been written to storage/logs/laravel.log');
        $this->info('You can view them using: tail -f storage/logs/laravel.log | grep restore');
        
        return 0;
    }
}
