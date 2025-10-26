<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CancelExpiredBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cancel-expired-bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel bookings and transactions that have passed their booking date and still have pending status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to check for expired bookings...');
        
        $now = Carbon::now();
        $cancelledCount = 0;
        
        // Find bookings that are:
        // 1. Still pending status
        // 2. Booking date_time has passed
        // 3. Not already cancelled
        $expiredBookings = Booking::where('status', 'pending')
            ->where('date_time', '<', $now)
            ->with(['transactions', 'user'])
            ->get();
            
        if ($expiredBookings->isEmpty()) {
            $this->info('No expired bookings found.');
            return 0;
        }
        
        $this->info("Found {$expiredBookings->count()} expired bookings to process.");
        
        DB::beginTransaction();
        
        try {
            foreach ($expiredBookings as $booking) {
                // Update booking status to cancelled only
                $booking->status = 'cancelled';
                $booking->save();
                
                // Update all related transactions to cancelled
                foreach ($booking->transactions as $transaction) {
                    $transaction->transaction_status = 'cancel';
                    $transaction->save();
                    
                    Log::info('Transaction cancelled due to expired booking', [
                        'transaction_id' => $transaction->id,
                        'booking_id' => $booking->id,
                        'user_id' => $booking->user_id,
                        'booking_date_time' => $booking->date_time,
                        'current_time' => $now->format('Y-m-d H:i:s')
                    ]);
                }
                
                $cancelledCount++;
                
                Log::info('Booking cancelled due to expiration', [
                    'booking_id' => $booking->id,
                    'user_id' => $booking->user_id,
                    'customer_name' => $booking->name,
                    'booking_date_time' => $booking->date_time,
                    'current_time' => $now->format('Y-m-d H:i:s'),
                    'transactions_cancelled' => $booking->transactions->count()
                ]);
                
                $this->line("Cancelled booking #{$booking->id} for {$booking->name} ({$booking->date_time})");
            }
            
            DB::commit();
            
            $this->info("Successfully cancelled {$cancelledCount} expired bookings and their related transactions.");
            
            return $cancelledCount;
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to cancel expired bookings', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->error('Failed to cancel expired bookings: ' . $e->getMessage());
            
            return 1;
        }
    }
}
