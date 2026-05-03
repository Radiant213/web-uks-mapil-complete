<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TreatmentController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

// Routes buat Login/Logout
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Semua routes di bawah ini wajib harus Login dulu
Route::middleware('auth')->group(function () {
    
    // Semua role (Admin & Petugas) bisa akses ini
    Route::get('/', [DashboardController::class, 'index']);
    Route::resource('/pengobatan', TreatmentController::class);
    
    // Khusus Petugas: Cuma bisa nampilin daftar obat (index) dan liat detail (show)
    // Supaya mereka bisa ngecek stok obat.
    Route::get('/obat', [MedicineController::class, 'index'])->name('obat.index');
    Route::get('/obat/{obat}', [MedicineController::class, 'show'])->name('obat.show');

    // Khusus Admin: Akses Master Data Penuh
    Route::middleware('role:admin')->group(function () {
        Route::resource('/siswa', StudentController::class);
        Route::resource('/kelas', KelasController::class);
        
        // Admin bisa nambah, ngedit, ngehapus Obat
        Route::resource('/obat', MedicineController::class)->except(['index', 'show']);
    });
});
