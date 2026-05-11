<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelasData = [
            ['kelas' => 'X RPL 1'],
            ['kelas' => 'X RPL 2'],
            ['kelas' => 'XI RPL 1'],
            ['kelas' => 'XI RPL 2'],
            ['kelas' => 'XII RPL 1'],
            ['kelas' => 'XII RPL 2'],
        ];

        foreach ($kelasData as $kelas) {
            Kelas::create($kelas);
        }
    }
}
