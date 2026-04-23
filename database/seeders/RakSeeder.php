<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rak;

class RakSeeder extends Seeder
{
    public function run(): void
    {
        $raks = [
            ['kode_rak' => 'A1', 'lokasi' => 'Lantai 1 - Sayap Kiri'],
            ['kode_rak' => 'A2', 'lokasi' => 'Lantai 1 - Sayap Kanan'],
            ['kode_rak' => 'B1', 'lokasi' => 'Lantai 2 - Sayap Kiri'],
            ['kode_rak' => 'B2', 'lokasi' => 'Lantai 2 - Sayap Kanan'],
            ['kode_rak' => 'C1', 'lokasi' => 'Lantai 3 - Ruang Referensi'],
        ];

        foreach ($raks as $rak) {
            Rak::firstOrCreate(['kode_rak' => $rak['kode_rak']], $rak);
        }
    }
}
