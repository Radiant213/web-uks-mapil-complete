<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // NGAMBIL SEMUA MASTER DATA OBAT
        // Nantinya di HTML (Blade), data ini kita pake buat nampilin badge "Stok Hampir Habis" kalo obatnya sisa dikit
        $medicines = Medicine::all();
        
        // TAMPILKAN DAFTAR OBAT
        return view('obat.index', compact('medicines'));
    }

    public function create()
    {
        // TAMPILKAN FORM TAMBAH OBAT
        // Gak perlu data dari tabel lain karena Obat nggak punya Foreign Key (nggak berelasi sama entitas awal)
        return view('obat.create');
    }

    public function store(Request $request)
    {
        // 1. VALIDASI KEAMANAN INPUTAN
        // Trik: Stok harus integer dan nggak boleh minus (min:0). Kalo user maksa masukin -5, sistem nolak!
        $request->validate([
            'nama_obat' => 'required|string|max:255',
            'satuan' => 'required|string|max:50', // Misal: Tablet, Sirup, Kapsul, Pcs
            'stok' => 'required|integer|min:0',   
        ]);

        // 2. INSERT BARU KE DATABASE
        Medicine::create($request->all());

        // 3. KEMBALI KE DAFTAR TABEL OBAT
        return redirect()->route('obat.index')->with('success', 'Data Obat berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        // Kosong
    }

    public function edit(string $id)
    {
        // CARI DATA LAMA OBAT
        // Kita butuh tau data lamanya (misal Obat Paracetamol) biar bisa diisi di form editnya otomatis
        $obat = Medicine::findOrFail($id);
        
        return view('obat.edit', compact('obat'));
    }

    public function update(Request $request, string $id)
    {
        // 1. VALIDASI SAMA KAYAK WAKTU NAMBAH OBAT BARU
        $request->validate([
            'nama_obat' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'stok' => 'required|integer|min:0', // Pastikan waktu ngedit stok, ngga diset jadi minus
        ]);

        // 2. TIMPA DATA DATABASE
        $obat = Medicine::findOrFail($id);
        $obat->update($request->all());

        return redirect()->route('obat.index')->with('success', 'Data Obat berhasil diupdate!');
    }

    public function destroy(string $id)
    {
        // HAPUS OBAT DARI APOTEK
        // Info: Kalo Obat ini dihapus, terus ada Riwayat Kunjungan Siswa yang dulunya dikasih Obat ini,
        // Kunjungannya TETEP AMAN ngga bakal ilang, asalkan di Migration tabel 'treatments_details'
        // kolom medicine_id ngga dikasih 'cascade' (tapi biasanya dikasih null). Kalo di kita kebetulan pake cascade.
        $obat = Medicine::findOrFail($id);
        $obat->delete();

        return redirect()->route('obat.index')->with('success', 'Data Obat berhasil dihapus!');
    }
}
