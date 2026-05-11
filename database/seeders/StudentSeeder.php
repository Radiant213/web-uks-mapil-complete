<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = [
            ['nis' => '100001', 'nama' => 'Ahmad Fauzi',       'kelas_id' => 1, 'jenis_kelamin' => 'L', 'alamat' => 'Jl. Merdeka No. 10, Jakarta Selatan'],
            ['nis' => '100002', 'nama' => 'Siti Nurhaliza',     'kelas_id' => 1, 'jenis_kelamin' => 'P', 'alamat' => 'Jl. Sudirman No. 45, Jakarta Pusat'],
            ['nis' => '100003', 'nama' => 'Rizky Pratama',      'kelas_id' => 1, 'jenis_kelamin' => 'L', 'alamat' => 'Jl. Gatot Subroto No. 22, Bekasi'],
            ['nis' => '100004', 'nama' => 'Dewi Anggraini',     'kelas_id' => 2, 'jenis_kelamin' => 'P', 'alamat' => 'Jl. Ahmad Yani No. 8, Depok'],
            ['nis' => '100005', 'nama' => 'Muhammad Rafli',     'kelas_id' => 2, 'jenis_kelamin' => 'L', 'alamat' => 'Jl. Pemuda No. 33, Tangerang'],
            ['nis' => '100006', 'nama' => 'Putri Wulandari',    'kelas_id' => 2, 'jenis_kelamin' => 'P', 'alamat' => 'Jl. Diponegoro No. 17, Bogor'],
            ['nis' => '100007', 'nama' => 'Dimas Ardiansyah',   'kelas_id' => 3, 'jenis_kelamin' => 'L', 'alamat' => 'Jl. Cendana No. 5, Jakarta Timur'],
            ['nis' => '100008', 'nama' => 'Anisa Rahma',        'kelas_id' => 3, 'jenis_kelamin' => 'P', 'alamat' => 'Jl. Kenanga No. 12, Bandung'],
            ['nis' => '100009', 'nama' => 'Budi Santoso',       'kelas_id' => 3, 'jenis_kelamin' => 'L', 'alamat' => 'Jl. Mawar No. 7, Cirebon'],
            ['nis' => '100010', 'nama' => 'Rina Marlina',       'kelas_id' => 4, 'jenis_kelamin' => 'P', 'alamat' => 'Jl. Melati No. 20, Surabaya'],
            ['nis' => '100011', 'nama' => 'Fajar Nugroho',      'kelas_id' => 4, 'jenis_kelamin' => 'L', 'alamat' => 'Jl. Anggrek No. 3, Semarang'],
            ['nis' => '100012', 'nama' => 'Lestari Widya',      'kelas_id' => 4, 'jenis_kelamin' => 'P', 'alamat' => 'Jl. Dahlia No. 15, Yogyakarta'],
            ['nis' => '100013', 'nama' => 'Andi Wijaya',        'kelas_id' => 5, 'jenis_kelamin' => 'L', 'alamat' => 'Jl. Flamboyan No. 9, Malang'],
            ['nis' => '100014', 'nama' => 'Nadia Safitri',      'kelas_id' => 5, 'jenis_kelamin' => 'P', 'alamat' => 'Jl. Teratai No. 28, Solo'],
            ['nis' => '100015', 'nama' => 'Galih Permana',      'kelas_id' => 5, 'jenis_kelamin' => 'L', 'alamat' => 'Jl. Bougenville No. 11, Medan'],
            ['nis' => '100016', 'nama' => 'Indah Permatasari',  'kelas_id' => 6, 'jenis_kelamin' => 'P', 'alamat' => 'Jl. Seruni No. 6, Palembang'],
            ['nis' => '100017', 'nama' => 'Hendra Saputra',     'kelas_id' => 6, 'jenis_kelamin' => 'L', 'alamat' => 'Jl. Kamboja No. 14, Makassar'],
            ['nis' => '100018', 'nama' => 'Yuliana Sari',       'kelas_id' => 6, 'jenis_kelamin' => 'P', 'alamat' => 'Jl. Tulip No. 19, Denpasar'],
            ['nis' => '100019', 'nama' => 'Rangga Aditya',      'kelas_id' => 1, 'jenis_kelamin' => 'L', 'alamat' => 'Jl. Sakura No. 25, Tangerang Selatan'],
            ['nis' => '100020', 'nama' => 'Mega Puspita',       'kelas_id' => 2, 'jenis_kelamin' => 'P', 'alamat' => 'Jl. Lavender No. 31, Bekasi Timur'],
        ];

        foreach ($students as $student) {
            Student::create($student);
        }
    }
}
