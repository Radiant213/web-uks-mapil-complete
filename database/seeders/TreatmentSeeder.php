<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Treatment;
use App\Models\TreatmentDetail;
use App\Models\Medicine;

class TreatmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $treatments = [
            // Januari 2026
            [
                'student_id' => 1, 'keluhan' => 'Pusing dan mual sejak pagi', 'diagnosa' => 'Vertigo ringan',
                'tanggal_kunjungan' => '2026-01-15 08:30:00',
                'obat' => [['medicine_id' => 1, 'jumlah_obat' => 2]], // Paracetamol
            ],
            [
                'student_id' => 5, 'keluhan' => 'Sakit perut setelah makan di kantin', 'diagnosa' => 'Maag / Dispepsia',
                'tanggal_kunjungan' => '2026-01-20 10:15:00',
                'obat' => [['medicine_id' => 5, 'jumlah_obat' => 2]], // Promag
            ],
            // Februari 2026
            [
                'student_id' => 3, 'keluhan' => 'Demam tinggi, badan lemas', 'diagnosa' => 'Demam / Flu',
                'tanggal_kunjungan' => '2026-02-05 09:00:00',
                'obat' => [['medicine_id' => 1, 'jumlah_obat' => 3]], // Paracetamol
            ],
            [
                'student_id' => 8, 'keluhan' => 'Luka lecet di lutut akibat jatuh saat olahraga', 'diagnosa' => 'Luka lecet / Abrasi',
                'tanggal_kunjungan' => '2026-02-12 11:00:00',
                'obat' => [
                    ['medicine_id' => 2, 'jumlah_obat' => 1], // Betadine
                    ['medicine_id' => 8, 'jumlah_obat' => 2], // Hansaplast
                ],
            ],
            [
                'student_id' => 12, 'keluhan' => 'Masuk angin, perut kembung', 'diagnosa' => 'Masuk angin',
                'tanggal_kunjungan' => '2026-02-18 08:45:00',
                'obat' => [['medicine_id' => 4, 'jumlah_obat' => 1]], // Antangin
            ],
            // Maret 2026
            [
                'student_id' => 7, 'keluhan' => 'Pusing setelah upacara bendera', 'diagnosa' => 'Kelelahan / Dehidrasi',
                'tanggal_kunjungan' => '2026-03-03 09:30:00',
                'obat' => [['medicine_id' => 6, 'jumlah_obat' => 2]], // Oralit
            ],
            [
                'student_id' => 14, 'keluhan' => 'Sakit kepala berat sejak semalam', 'diagnosa' => 'Cephalgia / Sakit Kepala',
                'tanggal_kunjungan' => '2026-03-10 10:00:00',
                'obat' => [['medicine_id' => 7, 'jumlah_obat' => 2]], // Bodrex
            ],
            [
                'student_id' => 2, 'keluhan' => 'Mual dan muntah di kelas', 'diagnosa' => 'Gastritis akut',
                'tanggal_kunjungan' => '2026-03-17 08:15:00',
                'obat' => [
                    ['medicine_id' => 5, 'jumlah_obat' => 2], // Promag
                    ['medicine_id' => 6, 'jumlah_obat' => 1], // Oralit
                ],
            ],
            // April 2026
            [
                'student_id' => 10, 'keluhan' => 'Tergores pisau saat praktikum', 'diagnosa' => 'Luka sayat ringan',
                'tanggal_kunjungan' => '2026-04-02 13:00:00',
                'obat' => [
                    ['medicine_id' => 2, 'jumlah_obat' => 1], // Betadine
                    ['medicine_id' => 9, 'jumlah_obat' => 2], // Kasa Steril
                    ['medicine_id' => 8, 'jumlah_obat' => 1], // Hansaplast
                ],
            ],
            [
                'student_id' => 16, 'keluhan' => 'Demam dan batuk pilek', 'diagnosa' => 'ISPA ringan',
                'tanggal_kunjungan' => '2026-04-08 09:45:00',
                'obat' => [['medicine_id' => 1, 'jumlah_obat' => 3]], // Paracetamol
            ],
            [
                'student_id' => 4, 'keluhan' => 'Pusing dan lemas, belum sarapan', 'diagnosa' => 'Hipoglikemia ringan',
                'tanggal_kunjungan' => '2026-04-15 08:00:00',
                'obat' => [['medicine_id' => 6, 'jumlah_obat' => 1]], // Oralit
            ],
            [
                'student_id' => 19, 'keluhan' => 'Sakit perut melilit', 'diagnosa' => 'Diare ringan',
                'tanggal_kunjungan' => '2026-04-22 10:30:00',
                'obat' => [['medicine_id' => 6, 'jumlah_obat' => 3]], // Oralit
            ],
            // Mei 2026
            [
                'student_id' => 6, 'keluhan' => 'Meriang dan pegal-pegal', 'diagnosa' => 'Myalgia / Pegal otot',
                'tanggal_kunjungan' => '2026-05-02 09:15:00',
                'obat' => [
                    ['medicine_id' => 1, 'jumlah_obat' => 2], // Paracetamol
                    ['medicine_id' => 3, 'jumlah_obat' => 1], // Minyak Kayu Putih
                ],
            ],
            [
                'student_id' => 11, 'keluhan' => 'Hanya ingin istirahat, badan kurang enak', 'diagnosa' => 'Konsultasi / Istirahat',
                'tanggal_kunjungan' => '2026-05-06 11:30:00',
                'obat' => [], // Tidak dikasih obat, cuma istirahat
            ],
            [
                'student_id' => 15, 'keluhan' => 'Jari tangan terjepit pintu', 'diagnosa' => 'Kontusi / Memar jari',
                'tanggal_kunjungan' => '2026-05-09 14:00:00',
                'obat' => [
                    ['medicine_id' => 3, 'jumlah_obat' => 1], // Minyak Kayu Putih
                    ['medicine_id' => 9, 'jumlah_obat' => 1], // Kasa Steril
                ],
            ],
        ];

        foreach ($treatments as $data) {
            // Buat record kunjungan
            $treatment = Treatment::create([
                'student_id'        => $data['student_id'],
                'keluhan'           => $data['keluhan'],
                'diagnosa'          => $data['diagnosa'],
                'tanggal_kunjungan' => $data['tanggal_kunjungan'],
            ]);

            // Buat detail obat dan kurangin stok
            foreach ($data['obat'] as $obatData) {
                TreatmentDetail::create([
                    'treatment_id' => $treatment->id,
                    'medicine_id'  => $obatData['medicine_id'],
                    'jumlah_obat'  => $obatData['jumlah_obat'],
                ]);

                // Kurangin stok obat
                $medicine = Medicine::find($obatData['medicine_id']);
                if ($medicine) {
                    $medicine->stok -= $obatData['jumlah_obat'];
                    $medicine->save();
                }
            }
        }
    }
}
