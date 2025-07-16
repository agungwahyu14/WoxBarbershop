<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['name' => 'Cukur Dewasa', 'description' => 'Potong rambut khusus pria dewasa', 'price' => 30000],
            ['name' => 'Cukur Anak-anak', 'description' => 'Potong rambut untuk anak-anak', 'price' => 25000],
            ['name' => 'Cukur Jenggot dan Kumis', 'description' => 'Rapikan jenggot dan kumis', 'price' => 15000],
            ['name' => 'Hair Spa', 'description' => 'Perawatan rambut dan kulit kepala', 'price' => 50000],
            ['name' => 'Cuci Rambut', 'description' => 'Cuci rambut sebelum/selesai cukur', 'price' => 10000],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
