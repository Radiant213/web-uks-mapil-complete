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
