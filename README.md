# 🏥 Web Manajemen UKS (Sistem Informasi Klinik Sekolah)

Aplikasi berbasis web untuk mengelola operasional Unit Kesehatan Sekolah (UKS). Aplikasi ini dirancang menggunakan **Laravel 11** dan **Tailwind CSS v3**, dengan fokus utama pada otomatisasi pengurangan stok obat saat terjadi kunjungan pasien, sistem multi-user berbasis *role*, serta antarmuka (UI/UX) *Premium CRM-Style* modern.

---

## 🚀 Panduan Instalasi (Cara Menjalankan Project dari GitHub)

Ikuti langkah-langkah di bawah ini untuk meng-import dan menjalankan aplikasi di komputer lokal (localhost) Anda:

### 1. Persiapan Sistem
Pastikan Anda sudah menginstall perangkat lunak berikut:
- **PHP** (Minimal versi 8.2)
- **Composer** (Manajer dependensi PHP)
- **Node.js & NPM** (Untuk kompilasi aset Tailwind CSS)
- **XAMPP / Laragon** (Untuk database MySQL)

### 2. Clone Repository & Install Dependensi
Buka terminal (Command Prompt/Git Bash) dan jalankan:
```bash
git clone https://github.com/Radiant213/web-uks-mapil.git
cd web-uks-mapil

# Install library PHP
composer install

# Install library Node.js (Tailwind & Vite)
npm install
```

### 3. Konfigurasi Environment (Database)
1. Salin file `.env.example` dan ubah namanya menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
2. Buka file `.env` di teks editor, lalu sesuaikan konfigurasi database berikut:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_uks   # Ganti dengan nama database Anda di MySQL
   DB_USERNAME=root
   DB_PASSWORD=         # Kosongkan jika pakai XAMPP/Laragon default
   ```
3. Buat database kosong bernama `db_uks` di phpMyAdmin (atau *database tool* lain).
4. Buat kunci aplikasi (Application Key):
   ```bash
   php artisan key:generate
   ```

### 4. Migrasi dan Seeding Database
Jalankan perintah ini untuk membuat struktur tabel dan mengisi data awal (termasuk akun Admin & Petugas):
```bash
php artisan migrate
php artisan db:seed
```

### 5. Build Assets & Jalankan Server Lokal
Kompilasi CSS dari Tailwind, lalu jalankan server:
```bash
npm run build
php artisan serve
```
Aplikasi bisa diakses di browser: 👉 **http://127.0.0.1:8000**

---

## 🔐 Akun Login Default (Seeder)

Gunakan akun berikut untuk ngetes aplikasinya:

**1. Akun Admin (Pembina)** - Akses Penuh:
- **Email:** `admin@uks.com`
- **Password:** `password`

**2. Akun Petugas (PMR)** - Akses Terbatas (Hanya Kunjungan & Stok Obat):
- **Email:** `petugas@uks.com`
- **Password:** `password`

---

## 📚 Tutorial & Penjelasan Logika Arsitektur

### 1. Desain UI/UX Modern (Tailwind CSS)
Aplikasi ini sudah meninggalkan kerangka Bootstrap lama dan beralih ke desain premium dengan **TailwindCSS**. Mulai dari *layouting* Sidebar dinamis, kartu metrik interaktif, *split-screen login*, hingga tabel-tabel bersih dan elegan semuanya digerakkan oleh kustomisasi *class* Tailwind (`rounded-2xl`, `bg-indigo-600`, dll) dan integrasi **Lucide Icons**.

### 2. Database Transactions (Otomatisasi Stok Obat)
Fitur paling penting ada di `TreatmentController@store`. Kita menggunakan `DB::beginTransaction()`, `DB::commit()`, dan `DB::rollBack()`. 
* **Logikanya:** Saat form kunjungan di-submit, sistem mencoba mencatat pasien -> mengecek stok obat -> menyimpan resep -> memotong stok. Jika di tengah jalan stok ternyata tidak cukup atau ada error database, **semua tindakan akan dibatalkan** (*Rollback*). Ini mencegah bug di mana pasien sudah tercatat tapi stok obat tidak berubah.

### 3. Otentikasi Custom & Middleware
Sistem login dibangun manual:
- **Tabel Login:** Menggunakan tabel custom `user__u_k_s` (bukan tabel `users` bawaan). Oleh karena itu `config/auth.php` diubah agar menunjuk ke `App\Models\User_UKS::class`.
- **Hak Akses:** File `RoleMiddleware.php` akan menendang user (redirect ke Dashboard) jika mereka mencoba mengakses URL yang bukan haknya (misal: Petugas mencoba menghapus data Siswa).

### 4. Eloquent Relationships (Relasi Antar Tabel)
- `Student` `belongsTo` `Kelas`: Siswa punya 1 kelas.
- `Kelas` `hasMany` `Student`: 1 Kelas bisa punya banyak siswa.
- Menggunakan *Eager Loading* (`with()`) untuk menampilkan data relasi tanpa memberatkan database (mengatasi N+1 Query Problem).

---

## 📂 FULL SOURCE CODE (ARSIP PRIBADI)

Berikut adalah salinan utuh (Full Source Code) dari seluruh file yang dibangun dalam sistem UKS ini, sebagai dokumentasi belajar:
### 📄 File: `tailwind.config.js`
**Penjelasan:** File konfigurasi Tailwind CSS untuk mengatur path pencarian class.

```javascript
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
```

---

### 📄 File: `config/auth.php`
**Penjelasan:** Mengatur tabel dan model otentikasi login default agar menggunakan model custom User_UKS kita.

```php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option defines the default authentication "guard" and password
    | reset "broker" for your application. You may change these values
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | which utilizes session storage plus the Eloquent user provider.
    |
    | All authentication guards have a user provider, which defines how the
    | users are actually retrieved out of your database or other storage
    | system used by the application. Typically, Eloquent is utilized.
    |
    | Supported: "session"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication guards have a user provider, which defines how the
    | users are actually retrieved out of your database or other storage
    | system used by the application. Typically, Eloquent is utilized.
    |
    | If you have multiple user tables or models you may configure multiple
    | providers to represent the model / table. These providers may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', App\Models\User_UKS::class),
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | These configuration options specify the behavior of Laravel's password
    | reset functionality, including the table utilized for token storage
    | and the user provider that is invoked to actually retrieve users.
    |
    | The expiry time is the number of minutes that each reset token will be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    | The throttle setting is the number of seconds a user must wait before
    | generating more password reset tokens. This prevents the user from
    | quickly generating a very large amount of password reset tokens.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of seconds before a password confirmation
    | window expires and users are asked to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];

```

---

### 📄 File: `routes/web.php`
**Penjelasan:** Jantung routing aplikasi. Di sinilah letak pembatasan akses middleware auth dan role.

```php
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

```

---

### 📄 File: `app/Http/Middleware/RoleMiddleware.php`
**Penjelasan:** Satpam aplikasi yang menendang user jika role tidak sesuai dengan parameter.

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Kalo belum login, atau role-nya nggak sesuai (misal petugas nyoba akses admin)
        if (!auth()->check() || auth()->user()->role !== $role) {
            // Tendang balik ke dashboard (bisa diganti abort(403) kalo mau)
            return redirect('/')->with('error', 'Akses Ditolak! Anda bukan ' . $role);
        }

        return $next($request);
    }
}

```

---

### 📄 File: `app/Models/User_UKS.php`
**Penjelasan:** Model Authenticatable untuk login.

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User_UKS extends Authenticatable
{
    // Kasih tau Laravel kalo nama tabel kita ini
    protected $table = 'user__u_k_s';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    // Password harus di-hash sama Laravel otomatis (fitur Laravel 10/11)
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}

```

---

### 📄 File: `app/Models/Kelas.php`
**Penjelasan:** Model Kelas dengan relasi hasMany ke Siswa.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    public function students() {
        return $this->hasMany(Student::class);
    }
    protected $guarded = ['id'];
    protected $fillable = ['kelas'];
}

```

---

### 📄 File: `app/Models/Student.php`
**Penjelasan:** Model Siswa dengan relasi belongsTo ke Kelas.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public function treatments() {
        return $this->hasMany(Treatment::class);
    }
    public function kelas() {
        return $this->belongsTo(Kelas::class);
    }
    protected $guarded = ['id'];
}

```

---

### 📄 File: `app/Models/Medicine.php`
**Penjelasan:** Model Obat.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    // Kosong karena di panggil di model lain
    protected $guarded = ['id'];
}

```

---

### 📄 File: `app/Models/Treatment.php`
**Penjelasan:** Model Kunjungan Utama.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function medicines() {
        return $this->belongsToMany(Medicine::class, 'treatments_details')->withPivot('jumlah_obat');
    }

    public function treatment_details() {
        return $this->hasMany(TreatmentDetail::class);
    }
    
    protected $guarded = ['id'];
}

```

---

### 📄 File: `app/Models/TreatmentDetail.php`
**Penjelasan:** Model Pivot Detail Kunjungan Obat.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TreatmentDetail extends Model
{
    public function treatment() {
        return $this->belongsTo(Treatment::class);  
    }

    public function medicine() {
        return $this->belongsTo(Medicine::class);
    }
    protected $table = 'treatments_details';
    protected $guarded = ['id'];
}

```

---

### 📄 File: `app/Http/Controllers/AuthController.php`
**Penjelasan:** Menangani logika manual login dan logout dengan Auth::attempt().

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Menampilkan view (tampilan HTML) form login yang ada di folder resources/views/auth/login.blade.php
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. VALIDASI INPUT
        // Memastikan form yang dikirim user nggak kosong dan format emailnya bener
        $credentials = $request->validate([
            'email' => 'required|email', // Wajib diisi dan harus format email (ada @ nya)
            'password' => 'required'     // Wajib diisi
        ]);

        // 2. PROSES OTENTIKASI (Pengecekan ke Database)
        // Auth::attempt() itu fungsi ajaib Laravel. Dia bakal otomatis ngecek ke tabel user kita.
        // Kalo email ada, dia ngecek hash password-nya. Kalo cocok = True. Kalo salah = False.
        if (Auth::attempt($credentials)) {
            
            // 3. JIKA BERHASIL LOGIN
            // Regenerate session ini penting banget buat keamanan (mencegah serangan Session Fixation)
            // Kasarnya: "Ganti nomor tiket antrian si user pake nomor baru yang valid"
            $request->session()->regenerate();
            
            // Pindahkan user (Redirect) ke halaman yang dituju sebelumnya, atau default-nya ke '/' (Dashboard)
            return redirect()->intended('/');
        }

        // 4. JIKA GAGAL LOGIN
        // Tendang balik (back) ke halaman form login tadi, terus bawa pesan error (withErrors)
        // onlyInput('email') fungsinya biar email yg tadi diketik tetep nempel di form, jd ngga usah ngetik ulang
        return back()->withErrors([
            'email' => 'Email atau password salah bang!',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // Logout user (Hapus status login-nya dari memori server)
        Auth::logout();
        
        // Hapus (invalidate) semua data session / tiket memori dia selama mainan web ini
        $request->session()->invalidate();
        
        // Bikin token keamanan (CSRF) baru biar sisa token lama ngga disalahgunain hacker
        $request->session()->regenerateToken();
        
        // Tendang balik ke halaman Login
        return redirect('/login');
    }
}

```

---

### 📄 File: `app/Http/Controllers/DashboardController.php`
**Penjelasan:** Menghitung statistik dan melakukan query Chart kunjungan per-bulan.

```php
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

```

---

### 📄 File: `app/Http/Controllers/TreatmentController.php`
**Penjelasan:** Controller paling krusial! Menangani pencatatan Kunjungan sekaligus memotong stok obat otomatis dengan mode Transaction.

```php
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

```

---

### 📄 File: `app/Http/Controllers/KelasController.php`
**Penjelasan:** CRUD standar Master Data Kelas.

```php
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

```

---

### 📄 File: `app/Http/Controllers/StudentController.php`
**Penjelasan:** CRUD Master Data Siswa dengan integrasi Dropdown Kelas.

```php
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

```

---

### 📄 File: `app/Http/Controllers/MedicineController.php`
**Penjelasan:** CRUD Master Data Obat.

```php
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

```

---

### 📄 File: `resources/views/layouts/app.blade.php`
**Penjelasan:** Layout Induk baru berbasis Tailwind yang berisi Sidebar dan Header untuk seluruh halaman aplikasi.

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem UKS - CRM Edition</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Vite & Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6; /* bg-gray-100 equivalent */
        }
    </style>
</head>
<body class="text-slate-800 antialiased flex h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col justify-between hidden md:flex z-10 shadow-sm">
        <div>
            <!-- Logo -->
            <div class="h-20 flex items-center px-8 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                        <i data-lucide="cross"></i>
                    </div>
                    <span class="font-bold text-xl tracking-tight text-slate-800">UKS<span class="text-indigo-600">App</span></span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1 mt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Menu Utama</p>
                
                <a href="{{ url('/') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->is('/') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-500 hover:bg-gray-50 hover:text-slate-900 transition-colors' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 {{ request()->is('/') ? 'text-indigo-600' : '' }}"></i>
                    Dashboard
                </a>

                <a href="{{ route('pengobatan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->is('pengobatan*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-500 hover:bg-gray-50 hover:text-slate-900 transition-colors' }}">
                    <i data-lucide="activity" class="w-5 h-5 {{ request()->is('pengobatan*') ? 'text-indigo-600' : '' }}"></i>
                    Kunjungan
                </a>

                <a href="{{ route('obat.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->is('obat*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-500 hover:bg-gray-50 hover:text-slate-900 transition-colors' }}">
                    <i data-lucide="pill" class="w-5 h-5 {{ request()->is('obat*') ? 'text-indigo-600' : '' }}"></i>
                    Obat & Stok
                </a>

                @if(auth()->user()->role === 'admin')
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-6">Master Data</p>
                    
                    <a href="{{ route('siswa.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->is('siswa*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-500 hover:bg-gray-50 hover:text-slate-900 transition-colors' }}">
                        <i class="w-5 h-5 {{ request()->is('siswa*') ? 'text-indigo-600' : '' }}" data-lucide="users"></i>
                        Siswa
                    </a>

                    <a href="{{ route('kelas.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->is('kelas*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-500 hover:bg-gray-50 hover:text-slate-900 transition-colors' }}">
                        <i class="w-5 h-5 {{ request()->is('kelas*') ? 'text-indigo-600' : '' }}" data-lucide="school"></i>
                        Kelas
                    </a>
                @endif
            </nav>
        </div>

        <!-- Logout Section -->
        <div class="p-4 border-t border-gray-100">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-4 py-3 w-full rounded-xl text-red-500 hover:bg-red-50 hover:text-red-600 transition-colors font-medium">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-[#f8fafc]">
        
        <!-- HEADER -->
        <header class="h-20 bg-white border-b border-gray-100 flex items-center justify-between px-8 z-10">
            <!-- Search Bar Dummy -->
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                    <input type="text" placeholder="Search..." class="w-full pl-10 pr-4 py-2 bg-gray-50 border-none rounded-full text-sm focus:ring-2 focus:ring-indigo-100 focus:bg-white transition-all outline-none text-slate-600">
                </div>
            </div>

            <!-- Profile Info -->
            <div class="flex items-center gap-6">
                <div class="hidden md:flex items-center gap-2 text-sm text-gray-500 bg-gray-50 px-4 py-1.5 rounded-full">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                    <span>{{ date('d M Y') }}</span>
                </div>
                
                <button class="relative p-2 text-gray-400 hover:text-indigo-600 transition-colors">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                </button>

                <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
                    <div class="flex flex-col items-end">
                        <span class="text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</span>
                        <span class="text-xs text-indigo-600 font-medium capitalize">{{ auth()->user()->role }}</span>
                    </div>
                    <!-- Avatar Dummy -->
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=4f46e5&color=fff" alt="Avatar" class="w-10 h-10 rounded-full shadow-sm border-2 border-white">
                </div>
            </div>
        </header>

        <!-- PAGE CONTENT -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-[#f8fafc] p-8">
            <!-- Notifikasi Session -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-100 flex items-center gap-3 text-green-700 shadow-sm">
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-green-500"></i>
                    <p class="font-medium text-sm">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error') || $errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 flex items-center gap-3 text-red-700 shadow-sm">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
                    <p class="font-medium text-sm">
                        {{ session('error') ?? $errors->first() }}
                    </p>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>

```

---

### 📄 File: `resources/views/dashboard.blade.php`
**Penjelasan:** Tampilan Dashboard dengan struktur grid Tailwind, menampung Chart.js dan deretan Metric Card CRM.

```php
@extends('layouts.app')

@section('content')
<!-- GREETING SECTION -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Hello, {{ auth()->user()->name }}! 👋</h1>
    <p class="text-gray-500 mt-1 text-sm">This is what's happening in your UKS today.</p>
</div>

<!-- METRIC CARDS GRID -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    
    <!-- Card 1: Total Kunjungan (The Vibrant Purple Card) -->
    <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-lg shadow-indigo-200 relative overflow-hidden group">
        <!-- Decorative shapes -->
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
        <div class="absolute -bottom-8 -left-8 w-32 h-32 bg-indigo-500/50 rounded-full blur-2xl"></div>
        
        <div class="relative z-10">
            <div class="flex justify-between items-start mb-4">
                <span class="text-indigo-100 text-sm font-medium">Total Kunjungan</span>
                <div class="w-8 h-8 rounded-full bg-white text-indigo-600 flex items-center justify-center">
                    <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                </div>
            </div>
            <h2 class="text-4xl font-bold mb-2">{{ $total_kunjungan }} <span class="text-lg font-normal text-indigo-200">Siswa</span></h2>
            <div class="flex items-center gap-2">
                <span class="bg-white/20 px-2 py-0.5 rounded-full text-xs font-semibold backdrop-blur-sm flex items-center gap-1">
                    <i data-lucide="trending-up" class="w-3 h-3"></i> +12%
                </span>
                <span class="text-xs text-indigo-200">Bulan ini</span>
            </div>
        </div>
    </div>

    <!-- Card 2: Total Siswa -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start mb-4">
            <span class="text-gray-500 text-sm font-medium">Total Siswa Aktif</span>
            <div class="w-8 h-8 rounded-full bg-gray-50 text-gray-400 flex items-center justify-center group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                <i data-lucide="users" class="w-4 h-4"></i>
            </div>
        </div>
        <h2 class="text-4xl font-bold text-slate-800 mb-2">{{ $total_siswa }}</h2>
        <div class="flex items-center gap-2">
            <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs font-semibold flex items-center gap-1">
                <i data-lucide="trending-up" class="w-3 h-3"></i> Aktif
            </span>
            <span class="text-xs text-gray-400">Terdaftar di sistem</span>
        </div>
    </div>

    <!-- Card 3: Macam Obat -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start mb-4">
            <span class="text-gray-500 text-sm font-medium">Macam Obat Tersedia</span>
            <div class="w-8 h-8 rounded-full bg-gray-50 text-gray-400 flex items-center justify-center group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                <i data-lucide="pill" class="w-4 h-4"></i>
            </div>
        </div>
        <h2 class="text-4xl font-bold text-slate-800 mb-2">{{ $total_obat }} <span class="text-lg font-normal text-gray-400">Jenis</span></h2>
        <div class="flex items-center gap-2">
            <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-xs font-semibold flex items-center gap-1">
                <i data-lucide="info" class="w-3 h-3"></i> Info
            </span>
            <span class="text-xs text-gray-400">Di Apotek UKS</span>
        </div>
    </div>

</div>

<!-- CHART SECTION -->
<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Grafik Kunjungan</h3>
            <p class="text-xs text-gray-400 mt-1">Rekapitulasi tahun {{ date('Y') }}</p>
        </div>
        <div class="p-2 bg-gray-50 rounded-lg text-gray-500 hover:bg-gray-100 cursor-pointer">
            <i data-lucide="more-horizontal" class="w-5 h-5"></i>
        </div>
    </div>
    
    <div class="h-[300px] w-full">
        <canvas id="kunjunganChart"></canvas>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('kunjunganChart').getContext('2d');
        const dataKunjungan = @json($visitsPerMonth);

        // Gradient for Line Chart (from CRM reference, we use sleek purple)
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(79, 70, 229, 0.5)'); // Indigo-600
        gradient.addColorStop(1, 'rgba(79, 70, 229, 0.0)');

        new Chart(ctx, {
            type: 'line', // or 'bar' to match the specific image, but user had a line chart earlier. Let's make it a sleek bar chart to match the CRM image exactly!
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Visits',
                    data: dataKunjungan,
                    backgroundColor: '#4f46e5', // indigo-600
                    borderRadius: 6, // rounded bars!
                    barThickness: 24,
                    hoverBackgroundColor: '#4338ca' // indigo-700
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        titleFont: { size: 13, family: 'Inter' },
                        bodyFont: { size: 14, weight: 'bold', family: 'Inter' },
                        displayColors: false,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9', // slate-100
                            drawBorder: false,
                        },
                        ticks: {
                            font: { family: 'Inter', size: 12 },
                            color: '#94a3b8', // slate-400
                            stepSize: 1
                        },
                        border: { display: false }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: {
                            font: { family: 'Inter', size: 12 },
                            color: '#94a3b8'
                        },
                        border: { display: false }
                    }
                }
            }
        });
    });
</script>
@endsection

```

---

### 📄 File: `resources/views/auth/login.blade.php`
**Penjelasan:** Tampilan form Login manual dengan struktur Split Screen estetik Tailwind.

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem UKS</title>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Vite & Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-slate-800 antialiased flex h-screen overflow-hidden">
    <!-- Left Side: Login Form -->
    <div class="w-full md:w-1/2 flex flex-col justify-center items-center px-8 sm:px-12 lg:px-24 bg-white z-10 shadow-2xl relative">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="flex items-center gap-3 mb-10">
                <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-indigo-200">
                    <i data-lucide="cross"></i>
                </div>
                <span class="font-bold text-2xl tracking-tight text-slate-800">UKS<span class="text-indigo-600">App</span></span>
            </div>

            <h1 class="text-3xl font-bold text-slate-800 mb-2 tracking-tight">Welcome back!</h1>
            <p class="text-gray-500 mb-8 text-sm">Please enter your credentials to access the system.</p>

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 flex items-center gap-3 text-red-700">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
                    <p class="font-medium text-sm">{{ $errors->first() }}</p>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
                    <div class="relative">
                        <i data-lucide="mail" class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="admin@uks.com" 
                            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-all outline-none text-slate-700">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                    <div class="relative">
                        <i data-lucide="lock" class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                        <input type="password" name="password" required placeholder="••••••••" 
                            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-all outline-none text-slate-700">
                    </div>
                </div>

                <div class="flex items-center justify-between mt-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <span class="text-sm text-gray-600 font-medium">Remember me</span>
                    </label>
                    <a href="#" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500">Forgot password?</a>
                </div>

                <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-medium py-3 rounded-xl transition-colors shadow-lg shadow-slate-200 mt-8 flex justify-center items-center gap-2">
                    Sign in to account
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Right Side: Decorative/Image (Hidden on mobile) -->
    <div class="hidden md:flex md:w-1/2 bg-indigo-600 relative overflow-hidden flex-col justify-center items-center p-12 text-center">
        <!-- Abstract Background Shapes -->
        <div class="absolute -right-20 -top-20 w-96 h-96 bg-indigo-500 rounded-full blur-3xl opacity-50"></div>
        <div class="absolute -left-20 -bottom-20 w-96 h-96 bg-indigo-700 rounded-full blur-3xl opacity-50"></div>
        
        <div class="relative z-10 max-w-md">
            <div class="bg-white/10 p-6 rounded-2xl backdrop-blur-sm border border-white/20 mb-8 shadow-2xl">
                <i data-lucide="activity" class="w-16 h-16 text-white mb-4 mx-auto"></i>
                <h2 class="text-3xl font-bold text-white mb-4 leading-tight">Manage Your UKS with Ease.</h2>
                <p class="text-indigo-100 text-sm leading-relaxed">Sistem Informasi Manajemen Unit Kesehatan Sekolah. Mencatat riwayat medis siswa dan mengontrol ketersediaan obat secara otomatis.</p>
            </div>
            
            <div class="flex gap-4 justify-center text-indigo-200 text-xs">
                <span>Admin: admin@uks.com</span>
                <span>•</span>
                <span>PMR: petugas@uks.com</span>
            </div>
        </div>
    </div>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>

```

---

### 📄 File: `resources/views/pengobatan/index.blade.php`
**Penjelasan:** Contoh tabel modern Tailwind CSS yang digunakan untuk menampilkan riwayat Kunjungan.

```php
@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Buku Kunjungan UKS</h1>
        <p class="text-sm text-gray-500 mt-1">Rekam medis dan riwayat kunjungan siswa.</p>
    </div>
    <a href="{{ route('pengobatan.create') }}" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition-colors shadow-sm">
        <i data-lucide="plus" class="w-4 h-4"></i>
        Catat Kunjungan
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <!-- Tabel Container dengan Scroll Horizontal untuk Mobile -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">Tanggal & Waktu</th>
                    <th class="px-6 py-4">Nama Pasien</th>
                    <th class="px-6 py-4">Keluhan</th>
                    <th class="px-6 py-4">Diagnosa</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($treatments as $index => $t)
                <tr class="hover:bg-gray-50/50 transition-colors group">
                    <td class="px-6 py-4 text-gray-500">{{ $index + 1 }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <i data-lucide="calendar-clock" class="w-4 h-4 text-gray-400"></i>
                            <span class="text-slate-700 font-medium">{{ \Carbon\Carbon::parse($t->tanggal_kunjungan)->format('d M Y') }}</span>
                        </div>
                        <span class="text-xs text-gray-400 ml-6">{{ \Carbon\Carbon::parse($t->tanggal_kunjungan)->format('H:i') }} WIB</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-medium text-slate-800">{{ $t->student->nama ?? 'Siswa Dihapus' }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-600 truncate max-w-[200px]" title="{{ $t->keluhan }}">
                        {{ $t->keluhan }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200/50">
                            {{ $t->diagnosa }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('pengobatan.show', $t->id) }}" class="p-2 text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors" title="Lihat Detail & Resep">
                                <i data-lucide="file-text" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center text-gray-400">
                            <i data-lucide="inbox" class="w-12 h-12 mb-3 text-gray-300"></i>
                            <p class="text-base font-medium text-gray-500">Belum ada kunjungan</p>
                            <p class="text-sm">Catatan kunjungan baru akan muncul di sini.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Footer Tabel / Pagination Area -->
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30 flex items-center justify-between text-sm text-gray-500">
        <span>Menampilkan total {{ $treatments->count() }} kunjungan.</span>
    </div>
</div>
@endsection

```

---

### 📄 File: `resources/views/pengobatan/create.blade.php`
**Penjelasan:** Tampilan Form Kunjungan. Form responsif penuh dengan kotak bayangan dan efek fokus ring.

```php
@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('pengobatan.index') }}" class="p-2 text-gray-400 hover:text-slate-800 hover:bg-gray-100 rounded-lg transition-colors">
        <i data-lucide="arrow-left" class="w-5 h-5"></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Catat Kunjungan Baru</h1>
        <p class="text-sm text-gray-500 mt-1">Buku rekam medis UKS harian.</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 max-w-3xl">
    <form action="{{ route('pengobatan.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Identitas Pasien & Diagnosa -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Pasien (Siswa)</label>
                <div class="relative">
                    <select name="student_id" required class="w-full pl-4 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none text-slate-700 appearance-none">
                        <option value="">-- Cari Nama Siswa --</option>
                        @foreach ($students as $siswa)
                            <option value="{{ $siswa->id }}">{{ $siswa->nama_siswa ?? $siswa->nama }} - {{ $siswa->kelas->kelas ?? '' }}</option>
                        @endforeach
                    </select>
                    <i data-lucide="chevron-down" class="absolute right-4 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                </div>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Keluhan Utama</label>
                <textarea name="keluhan" required rows="2" placeholder="Contoh: Pusing, mual, sakit perut..." 
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none text-slate-700"></textarea>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Diagnosa / Tindakan</label>
                <input type="text" name="diagnosa" required placeholder="Contoh: Maag, Istirahat di ranjang 1..." 
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none text-slate-700">
            </div>
        </div>

        <!-- Pemberian Obat (Opsional) -->
        <div class="pt-6 border-t border-gray-100">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Pemberian Obat (Opsional)</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Obat</label>
                    <div class="relative">
                        <select name="medicine_id" class="w-full pl-4 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none text-slate-700 appearance-none">
                            <option value="">-- Tidak dikasih obat --</option>
                            @foreach ($medicines as $obat)
                                <option value="{{ $obat->id }}">{{ $obat->nama_obat }} (Stok: {{ $obat->stok }})</option>
                            @endforeach
                        </select>
                        <i data-lucide="chevron-down" class="absolute right-4 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah</label>
                    <input type="number" name="jumlah_obat" min="1" placeholder="0" 
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none text-slate-700">
                </div>
            </div>
        </div>

        <div class="pt-6 flex justify-end gap-3">
            <a href="{{ route('pengobatan.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">Batal</a>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-sm shadow-indigo-200 transition-colors">Simpan Kunjungan</button>
        </div>
    </form>
</div>
@endsection

```

---

### 📄 File: `resources/views/pengobatan/show.blade.php`
**Penjelasan:** Tampilan Detail Kunjungan yang dipoles mirip invoice, melooping relasi pivot obat.

```php
@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('pengobatan.index') }}" class="p-2 text-gray-400 hover:text-slate-800 hover:bg-gray-100 rounded-lg transition-colors">
        <i data-lucide="arrow-left" class="w-5 h-5"></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Detail Rekam Medis</h1>
        <p class="text-sm text-gray-500 mt-1">Rincian kunjungan dan obat yang diberikan.</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Left Column: Patient Details -->
    <div class="md:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <i data-lucide="user" class="w-5 h-5 text-indigo-600"></i> Informasi Pasien
                </h3>
                <span class="text-sm text-gray-400">ID Kunjungan: #{{ str_pad($treatment->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Nama Siswa</p>
                        <p class="text-sm font-medium text-slate-800">{{ $treatment->student->nama ?? $treatment->student->nama_siswa ?? 'Dihapus' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Kelas</p>
                        <p class="text-sm font-medium text-slate-800">{{ $treatment->student->kelas->kelas ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Keluhan</p>
                        <p class="text-sm text-slate-700">{{ $treatment->keluhan }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Diagnosa</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200/50">
                            {{ $treatment->diagnosa }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Medicine List -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <i data-lucide="pill" class="w-5 h-5 text-indigo-600"></i> Resep Obat Diberikan
                </h3>
            </div>
            
            <div class="divide-y divide-gray-100">
                @forelse ($treatment->treatment_details as $detail)
                <div class="p-4 px-6 flex items-center justify-between hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                            <i data-lucide="tablets" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-800">{{ $detail->medicine->nama_obat ?? 'Obat Dihapus' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-slate-800">{{ $detail->jumlah_obat }} <span class="text-xs text-gray-400 font-normal">{{ $detail->medicine->satuan ?? 'pcs' }}</span></p>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center">
                    <i data-lucide="shield-check" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                    <p class="text-sm font-medium text-gray-500">Pasien tidak diberikan obat.</p>
                    <p class="text-xs text-gray-400">Hanya melakukan konsultasi atau istirahat.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column: Meta Info -->
    <div class="space-y-6">
        <div class="bg-indigo-600 rounded-2xl shadow-sm overflow-hidden text-white p-6 relative">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
            
            <h3 class="text-indigo-100 text-xs font-semibold uppercase tracking-wider mb-4 relative z-10">Waktu Kunjungan</h3>
            
            <div class="flex flex-col gap-1 relative z-10">
                <span class="text-4xl font-bold">{{ \Carbon\Carbon::parse($treatment->tanggal_kunjungan)->format('H:i') }}</span>
                <span class="text-indigo-200">{{ \Carbon\Carbon::parse($treatment->tanggal_kunjungan)->format('l, d F Y') }}</span>
            </div>
            
            <div class="mt-6 pt-6 border-t border-indigo-500/50 relative z-10">
                <div class="flex items-center gap-2 text-sm text-indigo-100">
                    <i data-lucide="check-circle" class="w-4 h-4 text-green-300"></i>
                    Status: Selesai
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

```

---

