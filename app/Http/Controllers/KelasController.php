<?php

namespace App\Http\Controllers;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // MANGGIL MODEL (M dari MVC)
        // Kelas::all() itu sama aja kayak ngetik SQL "SELECT * FROM classes"
        // Semua data dari database ditarik dan disimpen ke dalem variabel $kelas (bentuknya array/collection)
        $kelas = Kelas::all();
        
        // MANGGIL VIEW (V dari MVC)
        // Fungsi view() nyuruh Laravel ngebuka file di resources/views/kelas/index.blade.php
        // compact('kelas') artinya: "Eh, bawa juga variabel $kelas ini ke dalem file HTML biar bisa ditampilin!"
        return view('kelas.index', compact('kelas'));
    }

    public function create()
    {
        // Cuma nampilin halaman web form HTML buat nambah kelas baru
        // Nggak bawa data apa-apa karena form-nya masih kosong
        return view('kelas.create');
    }

    public function store(Request $request)
    {
        // TAHAP 1: VALIDASI (Pengecekan keamanan)
        // Pastiin user ngetik nama kelas, nggak boleh kosong (required), tipe teks (string), maksimal 255 huruf
        $request->validate([
            'kelas' => 'required|string|max:255',
        ]);

        // TAHAP 2: SIMPAN KE DATABASE
        // Kelas::create() nyuruh Model Kelas buat masukin data ke tabel.
        // $request->all() ngambil SEMUA data yang dikirim dari form HTML (nama-nama inputannya harus sama persis kek di tabel)
        Kelas::create($request->all());

        // TAHAP 3: REDIRECT (Pindah Halaman)
        // Kalo udah sukses nyimpen, otomatis alihin browser user balik ke halaman rute 'kelas.index' (halaman tabel)
        // with() itu fungsi buat nitip pesen sementara (Flash Session) yang bakal muncul berupa alert hijau
        return redirect()->route('kelas.index')->with('success', 'Data Kelas berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        // Biasanya buat liat detail 1 kelas (Read 1 item), tapi jarang dipake kalo buat Master Data sederhana kek gini
    }

    public function edit(string $id)
    {
        // CARI DATA SPESIFIK
        // findOrFail() nyuruh Laravel nyari data kelas berdasarkan ID-nya (misal ID 5). 
        // Kalo ID 5 ngga ada di database, otomatis bakal ngasih error 404 (Not Found).
        $kelas = Kelas::findOrFail($id);
        
        // Tampilin form edit (file resources/views/kelas/edit.blade.php), 
        // dan BAWA DATA KELAS LAMA ($kelas) biar form HTML-nya keisi otomatis pake value=".."
        return view('kelas.edit', compact('kelas'));
    }

    public function update(Request $request, string $id)
    {
        // 1. Validasi ulang form pas user ngedit (harus aman dari input aneh-aneh)
        $request->validate([
            'kelas' => 'required|string|max:255',
        ]);

        // 2. Cari lagi kelas mana yang mau di-update pake ID
        $kelas = Kelas::findOrFail($id);
        
        // 3. Timpa (Update) data lamanya pake data baru hasil ketikan form ($request->all())
        $kelas->update($request->all());

        // 4. Balikin user ke halaman daftar tabel kelas, bawa pesen sukses warna ijo
        return redirect()->route('kelas.index')->with('success', 'Data Kelas berhasil diupdate!');
    }

    public function destroy(string $id)
    {
        // 1. Cari dulu datanya ada ngga di database pake ID-nya
        $kelas = Kelas::findOrFail($id);
        
        // 2. Eksekusi mati (HAPUS DATA DARI TABEL)
        // Hati-hati, kalo ada siswa yang pake ID Kelas ini, siswa itu juga bakal kena hapus 
        // kalo kita pake settingan onDelete('cascade') di file Migration tabel Siswa!
        $kelas->delete();

        // 3. Balikin lagi ke halaman tabel
        return redirect()->route('kelas.index')->with('success', 'Data Kelas berhasil dihapus!');
    }
}
