<?php

namespace Database\Seeders;

use App\Models\Hairstyle;
use Illuminate\Database\Seeder;

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
                'style_preference' => 'professional',
                'image' => 'crew-cut.jpg',
            ],
            [
                'name' => 'Fade Cut',
                'description' => 'Potongan rambut dengan gradasi dari pendek ke panjang secara halus',
                'bentuk_kepala' => 'Round',
                'tipe_rambut' => 'Keriting',
                'style_preference' => 'modern',
                'image' => 'fade-cut.jpg',
            ],
            // Previous entries...

            // New additions:
            [
                'name' => 'French Crop',
                'description' => 'Potongan pendek dengan bagian depan yang dipotong lurus dan texture alami',
                'bentuk_kepala' => 'Square',
                'tipe_rambut' => 'Lurus',
                'style_preference' => 'modern',
                'image' => 'french-crop.jpg',
            ],
            [
                'name' => 'Curtain Fringe',
                'description' => 'Gaya rambut dengan bagian depan yang terbelah di tengah seperti tirai',
                'bentuk_kepala' => 'Oval',
                'tipe_rambut' => 'Wavy',
                'style_preference' => 'trendy',
                'image' => 'curtain-fringe.jpg',
            ],
            [
                'name' => 'High Top Fade',
                'description' => 'Gaya rambut dengan bagian atas yang tinggi dan gradasi fade di samping',
                'bentuk_kepala' => 'Round',
                'tipe_rambut' => 'Keriting',
                'style_preference' => 'edgy',
                'image' => 'high-top-fade.jpg',
            ],
            [
                'name' => 'Comb Over',
                'description' => 'Potongan rambut dengan bagian atas disisir ke satu sisi',
                'bentuk_kepala' => 'Square',
                'tipe_rambut' => 'Lurus',
                'style_preference' => 'professional',
                'image' => 'comb-over.jpg',
            ],
            [
                'name' => 'Dreadlocks',
                'description' => 'Rambut yang dipilin menjadi bagian-bagian panjang dan kencang',
                'bentuk_kepala' => 'Oval',
                'tipe_rambut' => 'Keriting',
                'style_preference' => 'bohemian',
                'image' => 'dreadlocks.jpg',
            ],
            [
                'name' => 'Man Bun',
                'description' => 'Gaya rambut panjang yang diikat ke belakang menjadi sanggul kecil',
                'bentuk_kepala' => 'Oval',
                'tipe_rambut' => 'Lurus',
                'style_preference' => 'casual',
                'image' => 'man-bun.jpg',
            ],
            [
                'name' => 'Two Block Cut',
                'description' => 'Gaya Korea populer dengan bagian atas panjang dan samping pendek',
                'bentuk_kepala' => 'Heart',
                'tipe_rambut' => 'Lurus',
                'style_preference' => 'korean',
                'image' => 'two-block.jpg',
            ],
            [
                'name' => 'Afro',
                'description' => 'Gaya rambut alami yang membentuk bulat voluminous',
                'bentuk_kepala' => 'Round',
                'tipe_rambut' => 'Keriting',
                'style_preference' => 'natural',
                'image' => 'afro.jpg',
            ],
            [
                'name' => 'Bald Fade',
                'description' => 'Potongan fade yang berakhir hampir botak di bagian bawah',
                'bentuk_kepala' => 'Diamond',
                'tipe_rambut' => 'Lurus',
                'style_preference' => 'bold',
                'image' => 'bald-fade.jpg',
            ],
            [
                'name' => 'Long Layers',
                'description' => 'Rambut panjang dengan lapisan untuk menambah volume dan gerakan',
                'bentuk_kepala' => 'Oval',
                'tipe_rambut' => 'Wavy',
                'style_preference' => 'romantic',
                'image' => 'long-layers.jpg',
            ],
            [
                'name' => 'Mohawk',
                'description' => 'Gaya punk dengan bagian tengah berdiri dan samping dicukur',
                'bentuk_kepala' => 'Square',
                'tipe_rambut' => 'Lurus',
                'style_preference' => 'edgy',
                'image' => 'mohawk.jpg',
            ],
            [
                'name' => 'Shag Cut',
                'description' => 'Potongan berlapis dengan texture yang messy dan modern',
                'bentuk_kepala' => 'Heart',
                'tipe_rambut' => 'Wavy',
                'style_preference' => 'retro',
                'image' => 'shag-cut.jpg',
            ],
            [
                'name' => 'Tousled Waves',
                'description' => 'Gaya rambut bergelombang yang sengaja dibuat acak-acakan',
                'bentuk_kepala' => 'Oval',
                'tipe_rambut' => 'Wavy',
                'style_preference' => 'beach',
                'image' => 'tousled-waves.jpg',
            ],
            [
                'name' => 'Braided Cornrows',
                'description' => 'Gaya rambut dengan anyaman ketat yang menempel di kulit kepala',
                'bentuk_kepala' => 'Round',
                'tipe_rambut' => 'Keriting',
                'style_preference' => 'urban',
                'image' => 'cornrows.jpg',
            ],
        ];

        foreach ($hairstyles as $hairstyle) {
            Hairstyle::create($hairstyle);
        }
    }
}
