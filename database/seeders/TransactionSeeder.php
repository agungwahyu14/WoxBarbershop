<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Booking;
use App\Models\User;
use App\Models\Service;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bookings = Booking::with(['user', 'service'])->get();

        if ($bookings->isEmpty()) {
            $this->command->warn('Please run BookingSeeder first');
            return;
        }

        $paymentMethods = ['cash', 'bank_transfer', 'e_wallet', 'credit_card'];
        $transactions = [];

        foreach ($bookings as $booking) {
            // Create a transaction for each booking
            $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
            
            // Determine payment status based on booking status
            $paymentStatus = match ($booking->status) {
                'completed' => 'settlement',
                'cancelled' => 'expire',
                'pending' => 'pending',
                'confirmed' => rand(1, 10) <= 7 ? 'settlement' : 'pending',
                'in_progress' => 'settlement',
                default => 'pending'
            };

            $transactions[] = [
                'user_id' => $booking->user_id,
                'booking_id' => $booking->id,
                'service_id' => $booking->service_id,
                'price' => $booking->service->price,
                'total_amount' => $booking->total_price ?? $booking->service->price,
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'created_at' => $booking->created_at,
                'updated_at' => $booking->updated_at,
            ];
        }

        // Insert transactions in batches for better performance
        foreach (array_chunk($transactions, 50) as $batch) {
            Transaction::insert($batch);
        }

        $this->command->info('Created ' . count($transactions) . ' transactions');
    }
}