<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Hairstyle; 

class HairstyleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bentukKepalaList = ['Oval', 'Round', 'Square', 'Diamond', 'Heart'];
        $tipeRambutList = ['Lurus', 'Ikal', 'Keriting', 'Wavy'];

        for ($i = 1; $i <= 10; $i++) {
            Hairstyle::create([
                'name' => 'Hairstyle ' . $i,
                'description' => 'Deskripsi gaya rambut ' . $i . ' yang cocok untuk berbagai bentuk kepala.',
                'bentuk_kepala' => $bentukKepalaList[array_rand($bentukKepalaList)],
                'tipe_rambut' => $tipeRambutList[array_rand($tipeRambutList)],
                'image' => 'mullet.jpg', // Pastikan default.jpg ada di folder storage/app/public/hairstyles/
            ]);
        }
    }
}
