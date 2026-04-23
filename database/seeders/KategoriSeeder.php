<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            'Fiksi',
            'Non-Fiksi',
            'Sains & Teknologi',
            'Sejarah',
            'Ekonomi & Bisnis',
            'Pendidikan',
            'Kesehatan',
            'Filsafat',
            'Agama & Spiritualitas',
            'Seni & Budaya',
        ];

        foreach ($kategoris as $nama) {
            Kategori::firstOrCreate(['nama' => $nama]);
        }
    }
}
