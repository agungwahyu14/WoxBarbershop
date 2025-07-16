<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Optional: hapus semua data sebelumnya
        // User::truncate();

        // Buat data user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'no_telepon' => '081234567890',
            'password' => Hash::make('password'),

        ]);

        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'no_telepon' => '089876543210',
            'password' => Hash::make('secret123'),

        ]);

        User::create([
            'name' => 'Agung',
            'email' => 'agung@example.com',
            'no_telepon' => '089876543218',
            'password' => Hash::make('agung123'),

        ]);

        
    }

    //  public function run(): void
    // {
    //     for ($i = 1; $i <= 10; $i++) {
    //         User::create([
    //             'name' => 'User ' . $i,
    //             'email' => 'user' . $i . '@example.com',
    //             'password' => Hash::make('password'),
    //             'no_telepon' => '0812345678' . $i, // Nomor dummy unik
    //         ]);
    //     }
    // }
}
