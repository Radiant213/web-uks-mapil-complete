<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Kelas;
class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // NGAMBIL SEMUA DATA SISWA
        // Kita juga bisa pake Student::with('kelas')->get() kalo mau narik nama kelasnya sekalian (Eager Loading)
        // Biar database-nya ngga ngelakuin proses query berulang-ulang pas nampilin di HTML
        $students = Student::all();
        
        // BUKA HALAMAN DAFTAR SISWA (View)
        // compact('students') itu cara cepet buat ngirim array/variabel ke dalam file blade
        return view('siswa.index', compact('students'));
    }

    public function create()
    {
        // AMBIL DATA RELASI
        // Karena waktu nambah siswa kita butuh milih Kelas-nya, kita harus narik data dari tabel Classes dulu
        $kelas = Kelas::all();

        // BUKA FORM TAMBAH SISWA
        // Bawa data $kelas tadi biar bisa dilooping pake tag <select> di HTML-nya
        return view('siswa.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        // 1. VALIDASI DATA SISWA BARU
        $request->validate([
            'nis' => 'required|unique:students,nis', // Harus diisi, dan ngga boleh ada NIS yg kembar di tabel students
            'nama' => 'required|string|max:255',
            'kelas_id' => 'required|integer', // Ini adalah Foreign Key (Kunci Relasi) yang nyambung ke tabel Kelas
            'jenis_kelamin' => 'required|in:L,P', // Cuma nerima value huruf L atau P (kayak fitur ENUM di database)
        ]);

        // 2. INSERT KE DATABASE
        Student::create($request->all());

        // 3. BALIK KE DAFTAR SISWA
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        // Kosong
    }

    public function edit(string $id)
    {
        // CARI DATA SISWA YANG MAU DIEDIT
        // Kalo pake findOrFail, pas siswanya ngga ketemu, Laravel bakal nampilin error cantik bawaan mereka (404)
        $student = Student::findOrFail($id);
        
        // Ambil data kelas lagi buat ditampilin di dalem menu dropdown (select option)
        $kelas = Kelas::all();
        
        // Tunjukin form edit, sekalian bawa data siswa buat ngisi value otomatis di kotak formnya
        return view('siswa.edit', compact('student', 'kelas'));
    }

    public function update(Request $request, string $id)
    {
        // 1. VALIDASI UPDATE
        $request->validate([
            // Khusus NIS, aturannya beda dikit. "NIS ngga boleh kembar, KECUALI dia pake NIS punya dia sendiri"
            // Makanya di akhir ditambahin koma terus .$id
            'nis' => 'required|unique:students,nis,'.$id, 
            'nama' => 'required|string|max:255',
            'kelas_id' => 'required|integer',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        // 2. TIMPA DATA LAMA
        $student = Student::findOrFail($id);
        $student->update($request->all());

        // 3. BALIK KE HALAMAN DEPAN
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diupdate!');
    }

    public function destroy(string $id)
    {
        // CARI DAN HAPUS PERMANEN
        // Kalo ada data Kunjungan UKS pake ID siswa ini, riwayat kunjungannya JUGA ikut kehapus otomatis
        // Asalkan di Migration-nya lu ngasih fitur onDelete('cascade')
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus!');
    }
}
