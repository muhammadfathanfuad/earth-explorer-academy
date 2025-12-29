<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Tampilkan Form Simpel
    public function showLogin() {
        return view('auth.login');
    }

    // Proses Login Tanpa Password
    public function login(Request $request)
    {
        $request->validate(['login_id' => 'required|string']);
        $input = $request->login_id;

        // 1. Cek Admin (Wajib Password)
        if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
            if (Auth::attempt(['email' => $input, 'password' => $request->password])) {
                return redirect()->intended('/');
            }
            return back()->withErrors(['login_id' => 'Email admin atau password salah.']);
        }

        // 2. LOGIKA UNTUK SISWA
        // Cek apakah tombol "KONFIRMASI: YA" ditekan?
        if ($request->has('confirm_login') && $request->confirm_login == 'yes') {
            $user = User::where('name', $input)->first();
            if ($user) {
                Auth::login($user);
                return redirect()->intended('/');
            }
        }

        // 3. Cek Database: Apakah nama ini sudah ada?
        $existingUser = User::where('name', $input)->first();

        if ($existingUser) {
            // JIKA ADA: Jangan login dulu. Kembalikan ke halaman login dengan data user tersebut
            // Agar user bisa melihat: "Oh ini akun Budi yang level 10, bukan saya!"
            return back()->with([
                'found_user' => $existingUser, // Kirim data user yg ditemukan
                'input_name' => $input
            ]);
        }

        // 4. JIKA TIDAK ADA (User Baru): Buat Akun Baru Otomatis
        $newUser = User::create([
            'name' => $input,
            'email' => str_replace(' ', '', strtolower($input)) . rand(100,999) . '@siswa.com', // Email dummy
            'password' => bcrypt('rahasia'), // Password dummy
            // Tambahkan default level/xp jika perlu
        ]);

        Auth::login($newUser);
        return redirect()->intended('/');
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('login');
    }
}