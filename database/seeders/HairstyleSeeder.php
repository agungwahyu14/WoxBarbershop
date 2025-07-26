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
        $hairstyles = [
            [
                'name' => 'Crew Cut',
                'description' => 'Potongan rambut klasik dengan bagian samping pendek dan atas sedikit lebih panjang',
                'bentuk_kepala' => 'Oval',
                'tipe_rambut' => 'Lurus',
                'image' => 'crew-cut.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Fade Cut',
                'description' => 'Potongan rambut dengan gradasi dari pendek ke panjang secara halus',
                'bentuk_kepala' => 'Round',
                'tipe_rambut' => 'Keriting',
                'image' => 'fade-cut.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Pompadour',
                'description' => 'Gaya rambut vintage dengan bagian atas yang di-sweep ke belakang',
                'bentuk_kepala' => 'Square',
                'tipe_rambut' => 'Wavy',
                'image' => 'pompadour.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Buzz Cut',
                'description' => 'Potongan rambut sangat pendek dengan panjang yang sama di seluruh kepala',
                'bentuk_kepala' => 'Diamond',
                'tipe_rambut' => 'Lurus',
                'image' => 'buzz-cut.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Undercut',
                'description' => 'Potongan dengan bagian samping dan belakang dicukur pendek, atas panjang',
                'bentuk_kepala' => 'Heart',
                'tipe_rambut' => 'Ikal',
                'image' => 'undercut.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Quiff',
                'description' => 'Gaya rambut dengan bagian depan yang di-style ke atas dan belakang',
                'bentuk_kepala' => 'Oval',
                'tipe_rambut' => 'Wavy',
                'image' => 'quiff.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Side Part',
                'description' => 'Potongan klasik dengan belahan samping yang rapi',
                'bentuk_kepala' => 'Square',
                'tipe_rambut' => 'Lurus',
                'image' => 'side-part.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Textured Crop',
                'description' => 'Potongan modern dengan texture yang natural dan tidak terlalu rapi',
                'bentuk_kepala' => 'Round',
                'tipe_rambut' => 'Keriting',
                'image' => 'textured-crop.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Slicked Back',
                'description' => 'Rambut yang disisir ke belakang dengan gel atau pomade',
                'bentuk_kepala' => 'Diamond',
                'tipe_rambut' => 'Lurus',
                'image' => 'slicked-back.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Taper Cut',
                'description' => 'Potongan dengan gradasi panjang dari atas ke bawah secara bertahap',
                'bentuk_kepala' => 'Heart',
                'tipe_rambut' => 'Ikal',
                'image' => 'taper-cut.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Mullet Modern',
                'description' => 'Versi modern dari mullet dengan bagian depan pendek dan belakang panjang',
                'bentuk_kepala' => 'Oval',
                'tipe_rambut' => 'Wavy',
                'image' => 'mullet-modern.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Bowl Cut',
                'description' => 'Potongan dengan bentuk mangkuk, cocok untuk anak-anak',
                'bentuk_kepala' => 'Round',
                'tipe_rambut' => 'Lurus',
                'image' => 'bowl-cut.jpg',
                'is_active' => true,
            ]
        ];

        foreach ($hairstyles as $hairstyle) {
            Hairstyle::create($hairstyle);
        }
    }
}