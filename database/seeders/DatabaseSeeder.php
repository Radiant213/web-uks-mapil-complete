<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Manggil semua seeder dalam urutan yang benar (dependensi dulu)
        $this->call([
            UserSeeder::class,      // Akun Admin & Petugas
            KelasSeeder::class,     // Master Kelas (6 kelas)
            StudentSeeder::class,   // Data Siswa (20 siswa, butuh Kelas)
            MedicineSeeder::class,  // Master Obat (10 obat)
            TreatmentSeeder::class, // Kunjungan UKS (15 data, butuh Siswa + Obat)
        ]);
    }
}
