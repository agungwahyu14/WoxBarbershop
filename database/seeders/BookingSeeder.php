<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Service;
use App\Models\Hairstyle;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::role('customer')->get();
        $services = Service::all();
        $hairstyles = Hairstyle::all();

        if ($users->isEmpty() || $services->isEmpty() || $hairstyles->isEmpty()) {
            $this->command->warn('Please run UserSeeder, ServiceSeeder, and HairstyleSeeder first');
            return;
        }

        // Create bookings for the past week, current week, and next week
        $startDate = Carbon::now()->subWeek();
        $endDate = Carbon::now()->addWeek();

        $statuses = ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'];
        $bookings = [];

        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            // Skip Sundays (barbershop closed)
            if ($date->dayOfWeek === 0) {
                continue;
            }

            // Create 3-8 bookings per day
            $bookingsPerDay = rand(3, 8);
            $queueNumber = 1;

            for ($i = 0; $i < $bookingsPerDay; $i++) {
                $user = $users->random();
                $service = $services->random();
                $hairstyle = $hairstyles->random();

                // Generate time between 9 AM and 6 PM
                $hour = rand(9, 17);
                $minute = rand(0, 1) * 30; // 0 or 30 minutes
                $dateTime = $date->copy()->setTime($hour, $minute);

                // Determine status based on date
                if ($dateTime->isPast()) {
                    // Past bookings should be mostly completed or cancelled
                    $status = rand(1, 10) <= 8 ? 'completed' : 'cancelled';
                } elseif ($dateTime->isToday()) {
                    // Today's bookings can be any status
                    $status = $statuses[array_rand($statuses)];
                } else {
                    // Future bookings should be pending or confirmed
                    $status = rand(1, 10) <= 7 ? 'pending' : 'confirmed';
                }

                $bookings[] = [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'service_id' => $service->id,
                    'hairstyle_id' => $hairstyle->id,
                    'date_time' => $dateTime,
                    'queue_number' => $queueNumber++,
                    'description' => $this->getRandomDescription(),
                    'status' => $status,
                    'total_price' => $service->price,
                    'payment_status' => $status === 'completed' ? 'paid' : ($status === 'cancelled' ? 'refunded' : 'unpaid'),
                    'created_at' => $dateTime->copy()->subHours(rand(1, 72)),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert bookings in batches for better performance
        foreach (array_chunk($bookings, 50) as $batch) {
            Booking::insert($batch);
        }

        $this->command->info('Created ' . count($bookings) . ' bookings');
    }

    private function getRandomDescription(): string
    {
        $descriptions = [
            'Potong rambut model terbaru yang kekinian',
            'Minta dipotong pendek di bagian samping',
            'Gaya rambut untuk acara formal',
            'Potong rambut seperti biasa',
            'Minta konsultasi untuk gaya rambut yang cocok',
            'Potong rambut untuk anak, jangan terlalu pendek',
            'Ingin gaya rambut yang mudah diatur',
            'Potong rambut model fade yang rapi',
            'Gaya rambut kasual untuk sehari-hari',
            'Minta dipotong sesuai bentuk wajah',
        ];

        return rand(1, 10) <= 7 ? $descriptions[array_rand($descriptions)] : '';
    }
}