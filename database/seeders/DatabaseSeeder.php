<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // First, seed roles and permissions
            RoleSeeder::class,
            PermissionSeeder::class,

            // Then seed basic data
            ServiceSeeder::class,
            HairstyleSeeder::class,

            // Then users (they need roles to exist)
            UserSeeder::class,

            // Then bookings (they need users, services, and hairstyles)
            BookingSeeder::class,

            // Then transactions (they need bookings)
            TransactionSeeder::class,

            // Then loyalty (needs users and bookings)
            LoyaltySeeder::class,

            // Finally dashboard stats (needs all other data)
            DashboardSeeder::class,

            // Recommendations seeder
            RecommendationSeeder::class,
        ]);
    }
}
