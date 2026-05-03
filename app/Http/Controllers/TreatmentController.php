<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Treatment;
use App\Models\Medicine;
use App\Models\Student;
use App\Models\TreatmentDetail;
use Illuminate\Support\Facades\DB;

class TreatmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data kunjungan beserta relasi ke siswa
        $treatments = Treatment::with('student')->get();
        return view('pengobatan.index', compact('treatments'));
    }

    public function create()
    {
        // Ambil data siswa buat milih siapa yang sakit
        $students = Student::all();
        // Ambil data obat buat milih obat apa yang dikasih
        $medicines = Medicine::where('stok', '>', 0)->get(); // Cuma nampilin obat yang ada stoknya

        return view('pengobatan.create', compact('students', 'medicines'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Inputan Kunjungan & Obat
        $request->validate([
            'student_id' => 'required|integer',
            'keluhan' => 'required|string|max:255',
            'diagnosa' => 'required|string|max:255',
            // Validasi ini ga wajib kalo petugas ngga ngasih obat (cuma konsultasi)
            'medicine_id' => 'nullable|integer',
            'jumlah_obat' => 'nullable|integer|min:1',
        ]);

        // ==========================================
        // INI BAGIAN PUNCAKNYA: DATABASE TRANSACTION
        // ==========================================
        
        // Kita mulai "mode awas" Laravel. Kalau ada yg error di tengah jalan, batalin semua!
        DB::beginTransaction();

        try {
            // Langkah 1: Catat kunjungan ke tabel treatments
            $treatment = Treatment::create([
                'student_id' => $request->student_id,
                'keluhan' => $request->keluhan,
                'diagnosa' => $request->diagnosa,
                // Tanggal kunjungan otomatis pake waktu sekarang
                'tanggal_kunjungan' => now(), 
            ]);

            // Langkah 2: Kalo petugas milih obat (nggak dikosongin)
            if ($request->filled('medicine_id') && $request->filled('jumlah_obat')) {
                
                // Cari data obatnya buat dicek stoknya
                $obat = Medicine::findOrFail($request->medicine_id);

                // Cek: Stok cukup nggak?
                if ($obat->stok < $request->jumlah_obat) {
                    // Kalo nggak cukup, kita "LEMPAR" error pake Exception
                    throw new \Exception("Gagal: Stok obat {$obat->nama_obat} tidak mencukupi! Sisa stok: {$obat->stok}");
                }

                // Kalo stok cukup, catat ke tabel pivot (treatments_details)
                TreatmentDetail::create([
                    'treatment_id' => $treatment->id,
                    'medicine_id' => $request->medicine_id,
                    'jumlah_obat' => $request->jumlah_obat,
                ]);

                // OTOMATIS KURANGIN STOK OBAT
                $obat->stok -= $request->jumlah_obat;
                $obat->save();
            }

            // Kalo SEMUA langkah di atas berhasil tanpa kena Exception, kita ACC simpan permanen
            DB::commit();

            return redirect()->route('pengobatan.index')->with('success', 'Kunjungan dan pemberian obat berhasil dicatat!');

        } catch (\Exception $e) {
            // Kalo ada yg error (misal stok kurang, atau server putus), BATALIN SEMUA SIMPANANNYA!
            DB::rollBack();

            // Balikin user ke form dan kasih tau errornya apa (nge-print Exception di atas)
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show(string $id)
    {
        // Menampilkan detail lengkap 1 kunjungan beserta obat apa aja yang dikasih
        $treatment = Treatment::with(['student', 'treatment_details.medicine'])->findOrFail($id);
        return view('pengobatan.show', compact('treatment'));
    }

    public function edit(string $id)
    {
        // Opsional: Buat sistem UKS biasanya data kunjungan jarang diedit (rekam medis statis)
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        // Opsional: Logika hapus kunjungan (harus ngembaliin stok obat kalo dihapus)
    }
}
