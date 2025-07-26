<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Potong Rambut Regular',
                'description' => 'Potong rambut standar dengan gaya klasik',
                'price' => 25000,
                'duration' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Potong Rambut Premium',
                'description' => 'Potong rambut dengan konsultasi styling dan finishing premium',
                'price' => 50000,
                'duration' => 45,
                'is_active' => true,
            ],
            [
                'name' => 'Shaving/Cukur',
                'description' => 'Cukur bersih dengan pisau cukur profesional',
                'price' => 15000,
                'duration' => 20,
                'is_active' => true,
            ],
            [
                'name' => 'Hair Wash & Styling',
                'description' => 'Cuci rambut dengan shampo khusus dan styling sesuai keinginan',
                'price' => 35000,
                'duration' => 40,
                'is_active' => true,
            ],
            [
                'name' => 'Beard Trimming',
                'description' => 'Rapikan jenggot dan kumis dengan gaya modern',
                'price' => 20000,
                'duration' => 25,
                'is_active' => true,
            ],
            [
                'name' => 'Hair Treatment',
                'description' => 'Perawatan rambut dengan vitamin dan nutrisi khusus',
                'price' => 75000,
                'duration' => 60,
                'is_active' => true,
            ],
            [
                'name' => 'Complete Package',
                'description' => 'Paket lengkap: potong rambut, cukur, cuci, dan styling',
                'price' => 85000,
                'duration' => 90,
                'is_active' => true,
            ],
            [
                'name' => 'Kids Haircut',
                'description' => 'Potong rambut khusus anak-anak dengan kesabaran extra',
                'price' => 30000,
                'duration' => 35,
                'is_active' => true,
            ]
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}