<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medicine;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medicines = [
            ['nama_obat' => 'Paracetamol 500mg',     'satuan' => 'Tablet', 'stok' => 50],
            ['nama_obat' => 'Betadine Antiseptik',    'satuan' => 'Sirup',  'stok' => 10],
            ['nama_obat' => 'Minyak Kayu Putih',      'satuan' => 'Sirup',  'stok' => 15],
            ['nama_obat' => 'Antangin',                'satuan' => 'Kapsul', 'stok' => 30],
            ['nama_obat' => 'Promag Tablet',           'satuan' => 'Tablet', 'stok' => 25],
            ['nama_obat' => 'Oralit Sachet',           'satuan' => 'Pcs',   'stok' => 40],
            ['nama_obat' => 'Bodrex Tablet',           'satuan' => 'Tablet', 'stok' => 35],
            ['nama_obat' => 'Hansaplast',              'satuan' => 'Pcs',   'stok' => 20],
            ['nama_obat' => 'Kasa Steril',             'satuan' => 'Pcs',   'stok' => 30],
            ['nama_obat' => 'Amoxicillin 500mg',      'satuan' => 'Kapsul', 'stok' => 20],
        ];

        foreach ($medicines as $medicine) {
            Medicine::create($medicine);
        }
    }
}
