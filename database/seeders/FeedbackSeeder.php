<?php

namespace Database\Seeders;

use App\Models\Feedback;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user pelanggan dan booking yang ada
        $customers = User::role('pelanggan')->get();
        $bookings = Booking::where('status', 'completed')->get();

        if ($customers->isEmpty() || $bookings->isEmpty()) {
            $this->command->info('Tidak ada pelanggan atau booking completed untuk membuat feedback');
            return;
        }

        $feedbackData = [
            [
                'rating' => 5,
                'comment' => 'Pengalaman cukur terbaik yang pernah saya dapatkan. Barber sangat profesional dan hasilnya sempurna!',
                'is_public' => true,
                'is_active' => true
            ],
            [
                'rating' => 5,
                'comment' => 'Produk perawatan jenggot mereka sangat bagus. Jenggot saya jadi lebih sehat dan mudah diatur.',
                'is_public' => true,
                'is_active' => true
            ],
            [
                'rating' => 5,
                'comment' => 'Atmosfernya sangat nyaman dan stafnya ramah. Potongan rambutnya selalu sesuai permintaan.',
                'is_public' => true,
                'is_active' => true
            ],
            [
                'rating' => 4,
                'comment' => 'Layanan yang memuaskan dengan harga yang terjangkau. Tempat yang bersih dan nyaman.',
                'is_public' => true,
                'is_active' => true
            ],
            [
                'rating' => 5,
                'comment' => 'Rekomendasi gaya rambut yang diberikan sangat cocok dengan bentuk wajah saya. Terima kasih!',
                'is_public' => true,
                'is_active' => true
            ],
            [
                'rating' => 4,
                'comment' => 'Pelayanannya cepat dan hasil potongan rambutnya rapi. Akan kembali lagi pasti.',
                'is_public' => true,
                'is_active' => true
            ],
            [
                'rating' => 5,
                'comment' => 'Wox Barbershop adalah barbershop terbaik di Gianyar. Highly recommended!',
                'is_public' => true,
                'is_active' => true
            ],
            [
                'rating' => 4,
                'comment' => 'Staff yang ramah dan profesional. Potongan sesuai dengan yang diminta.',
                'is_public' => true,
                'is_active' => true
            ]
        ];

        foreach ($feedbackData as $index => $data) {
            $customer = $customers->random();
            $booking = $bookings->where('user_id', $customer->id)->first() ?? $bookings->random();

            Feedback::create([
                'user_id' => $customer->id,
                'booking_id' => $booking->id,
                'rating' => $data['rating'],
                'comment' => $data['comment'],
                'is_public' => $data['is_public'],
                'is_active' => $data['is_active'],
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(1, 30))
            ]);
        }

        $this->command->info('Feedback seeder berhasil dijalankan: ' . count($feedbackData) . ' feedback dibuat');
    }
}
