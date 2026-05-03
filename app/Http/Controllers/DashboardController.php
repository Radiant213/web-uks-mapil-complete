<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Medicine;
use App\Models\Treatment;
class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Ngitung total data dari masing-masing tabel database
        $total_siswa = Student::count(); 
        $total_obat = Medicine::count(); 
        $total_kunjungan = Treatment::count(); 

        // 2. NGAMBIL DATA BUAT GRAFIK (Laporan Kunjungan Bulanan)
        // Kita ambil data kunjungan tahun ini, terus dikelompokin per bulan
        $chartData = Treatment::selectRaw('MONTH(tanggal_kunjungan) as bulan, COUNT(*) as jumlah')
            ->whereYear('tanggal_kunjungan', date('Y'))
            ->groupBy('bulan')
            ->pluck('jumlah', 'bulan')
            ->toArray();

        // Siapin array kosong 12 bulan (Jan-Des), isi 0 kalo bulan itu ga ada yang sakit
        $visitsPerMonth = [];
        for ($i = 1; $i <= 12; $i++) {
            $visitsPerMonth[] = $chartData[$i] ?? 0;
        }

        // 3. Ngirim data ke file tampilan (view) 'dashboard'
        return view('dashboard', compact('total_siswa', 'total_obat', 'total_kunjungan', 'visitsPerMonth'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
