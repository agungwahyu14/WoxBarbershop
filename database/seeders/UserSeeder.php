<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Seeder ini membuat 3 user utama untuk sistem:
     * 1. adminwoxbarbershop (Role: admin)
     * 2. pegawaibarbershop (Role: pegawai)
     * 3. pelangganbarbershop (Role: customer)
     */
    public function run(): void
    {
        // Hapus semua user yang ada (fresh start)
        $this->command->info('Cleaning existing users...');
        User::query()->delete();

        // 1. Create Admin User
        $this->command->info('Creating admin user: adminwoxbarbershop');
        $admin = User::create([
            'name' => 'Admin WOX Barbershop',
            'email' => 'adminwoxbarbershop@gmail.com',
            'no_telepon' => '081234567890',
            'password' => Hash::make('adminwox123'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');
        $this->command->info('✓ Admin created: adminwoxbarbershop@gmail.com (Password: adminwox123)');

        // 2. Create Pegawai/Staff User
        $this->command->info('Creating pegawai user: pegawaibarbershop');
        $pegawai = User::create([
            'name' => 'Pegawai Barbershop',
            'email' => 'pegawaibarbershop@gmail.com',
            'no_telepon' => '081234567891',
            'password' => Hash::make('pegawai123'),
            'email_verified_at' => now(),
        ]);
        $pegawai->assignRole('pegawai');
        $this->command->info('✓ Pegawai created: pegawaibarbershop@gmail.com (Password: pegawai123)');

        // 3. Create Customer/Pelanggan User
        $this->command->info('Creating customer user: pelangganbarbershop');
        $customer = User::create([
            'name' => 'Pelanggan Barbershop',
            'email' => 'pelangganbarbershop@gmail.com',
            'no_telepon' => '081234567892',
            'password' => Hash::make('pelanggan123'),
            'email_verified_at' => now(),
        ]);
        $customer->assignRole('customer');
        $this->command->info('✓ Customer created: pelangganbarbershop@gmail.com (Password: pelanggan123)');

        $this->command->info('');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('✓ Successfully created 3 users:');
        $this->command->info('  1. adminwoxbarbershop@gmail.com (admin)');
        $this->command->info('  2. pegawaibarbershop@gmail.com (pegawai)');
        $this->command->info('  3. pelangganbarbershop@gmail.com (customer)');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }
}
