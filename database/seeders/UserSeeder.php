<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        if (!User::where('email', 'admin@woxbarbershop.com')->exists()) {
            $admin = User::create([
                'name' => 'Super Admin',
                'email' => 'admin@woxbarbershop.com',
                'no_telepon' => '081234567890',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]);
            $admin->assignRole('admin');
        }

        // Create staff/pegawai users
        if (!User::where('email', 'ahmad@woxbarbershop.com')->exists()) {
            $staff1 = User::create([
                'name' => 'Barber Ahmad',
                'email' => 'ahmad@woxbarbershop.com',
                'no_telepon' => '081234567891',
                'password' => Hash::make('barber123'),
                'email_verified_at' => now(),
            ]);
            $staff1->assignRole('pegawai');
        }

        if (!User::where('email', 'budi@woxbarbershop.com')->exists()) {
            $staff2 = User::create([
                'name' => 'Barber Budi',
                'email' => 'budi@woxbarbershop.com',
                'no_telepon' => '081234567892',
                'password' => Hash::make('barber123'),
                'email_verified_at' => now(),
            ]);
            $staff2->assignRole('pegawai');
        }

        // Create customer users
        $customers = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'no_telepon' => '089876543210',
                'password' => Hash::make('customer123'),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'no_telepon' => '089876543211',
                'password' => Hash::make('customer123'),
            ],
            [
                'name' => 'Michael Johnson',
                'email' => 'michael@example.com',
                'no_telepon' => '089876543212',
                'password' => Hash::make('customer123'),
            ],
            [
                'name' => 'Sarah Wilson',
                'email' => 'sarah@example.com',
                'no_telepon' => '089876543213',
                'password' => Hash::make('customer123'),
            ],
            [
                'name' => 'David Brown',
                'email' => 'david@example.com',
                'no_telepon' => '089876543214',
                'password' => Hash::make('customer123'),
            ],
            [
                'name' => 'Emily Davis',
                'email' => 'emily@example.com',
                'no_telepon' => '089876543215',
                'password' => Hash::make('customer123'),
            ],
            [
                'name' => 'Chris Miller',
                'email' => 'chris@example.com',
                'no_telepon' => '089876543216',
                'password' => Hash::make('customer123'),
            ],
            [
                'name' => 'Lisa Garcia',
                'email' => 'lisa@example.com',
                'no_telepon' => '089876543217',
                'password' => Hash::make('customer123'),
            ],
            [
                'name' => 'Robert Martinez',
                'email' => 'robert@example.com',
                'no_telepon' => '089876543218',
                'password' => Hash::make('customer123'),
            ],
            [
                'name' => 'Jennifer Rodriguez',
                'email' => 'jennifer@example.com',
                'no_telepon' => '089876543219',
                'password' => Hash::make('customer123'),
            ]
        ];

        foreach ($customers as $customerData) {
            if (!User::where('email', $customerData['email'])->exists()) {
                $customerData['email_verified_at'] = now();
                $customer = User::create($customerData);
                $customer->assignRole('customer');
            }
        }
    }
}