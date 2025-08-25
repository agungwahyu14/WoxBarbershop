<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Loyalty;
use App\Models\User;
use Illuminate\Database\Seeder;

class LoyaltySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = User::role('customer')->get();

        if ($customers->isEmpty()) {
            $this->command->warn('Please run UserSeeder first');

            return;
        }

        foreach ($customers as $customer) {
            // Get customer's completed bookings to calculate loyalty points
            $completedBookings = Booking::where('user_id', $customer->id)
                ->where('status', 'completed')
                ->with('service')
                ->get();

            $totalEarned = 0;
            $totalRedeemed = 0;

            // Calculate points from completed bookings (1 point per 1000 rupiah spent)
            foreach ($completedBookings as $booking) {
                $points = intval($booking->total_price / 1000);
                $totalEarned += $points;
            }

            // Some customers may have redeemed points
            if ($totalEarned > 0) {
                $totalRedeemed = rand(0, intval($totalEarned * 0.3)); // Max 30% redeemed
            }

            $currentPoints = $totalEarned - $totalRedeemed;

            // Determine tier based on total earned
            $tier = 'bronze';
            $tierBenefits = [
                'discount_percentage' => 0,
                'free_services' => 0,
                'priority_booking' => false,
            ];

            if ($totalEarned >= 1000) {
                $tier = 'platinum';
                $tierBenefits = [
                    'discount_percentage' => 15,
                    'free_services' => 2,
                    'priority_booking' => true,
                ];
            } elseif ($totalEarned >= 500) {
                $tier = 'gold';
                $tierBenefits = [
                    'discount_percentage' => 10,
                    'free_services' => 1,
                    'priority_booking' => true,
                ];
            } elseif ($totalEarned >= 250) {
                $tier = 'silver';
                $tierBenefits = [
                    'discount_percentage' => 5,
                    'free_services' => 0,
                    'priority_booking' => false,
                ];
            }

            $lastTransactionDate = $completedBookings->isNotEmpty()
                ? $completedBookings->sortByDesc('created_at')->first()->created_at->toDateString()
                : null;

            Loyalty::create([
                'user_id' => $customer->id,
                'points' => $currentPoints,
                'total_earned' => $totalEarned,
                'total_redeemed' => $totalRedeemed,
                'last_transaction_date' => $lastTransactionDate,
                'tier' => $tier,
                'tier_benefits' => $tierBenefits,
            ]);
        }

        $this->command->info('Created loyalty records for '.$customers->count().' customers');
    }
}
