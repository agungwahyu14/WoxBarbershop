<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateUserActiveStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update all existing users to be active by default
        User::whereNull('is_active')->update(['is_active' => true]);
        
        $this->command->info('Updated all users to active status.');
    }
}
