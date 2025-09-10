<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CleanExpiredRememberTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:clean-remember-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean expired remember tokens from users table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Cleaning expired remember tokens...');
        
        // Get remember me lifetime from config (in minutes)
        $lifetimeMinutes = config('session.remember_me_lifetime', 43200); // 30 days default
        $cutoffDate = Carbon::now()->subMinutes($lifetimeMinutes);
        
        // Count tokens to be cleaned
        $tokensToClean = User::whereNotNull('remember_token')
            ->where('updated_at', '<', $cutoffDate)
            ->count();
        
        if ($tokensToClean === 0) {
            $this->info('No expired remember tokens found.');
            return 0;
        }
        
        // Clean expired tokens
        $cleaned = User::whereNotNull('remember_token')
            ->where('updated_at', '<', $cutoffDate)
            ->update(['remember_token' => null]);
        
        $this->info("Successfully cleaned {$cleaned} expired remember tokens.");
        
        // Show statistics
        $totalActiveTokens = User::whereNotNull('remember_token')->count();
        $this->line("Active remember tokens remaining: {$totalActiveTokens}");
        
        return 0;
    }
}
