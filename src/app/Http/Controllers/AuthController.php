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
    public function login(Request $request) {
        $request->validate([
            'name' => 'required|string|max:50'
        ]);

        // Cari user berdasarkan nama, kalau tidak ada buat baru
        // Kita buat email palsu otomatis agar database tidak error
        $dummyEmail = Str::slug($request->name) . '@siswa.com';

        $user = User::firstOrCreate(
            ['name' => $request->name], // Cek nama
            [
                'email' => $dummyEmail,
                'password' => bcrypt('rahasia123') // Password default (tidak dipakai user)
            ]
        );

        // Langsung Login
        Auth::login($user);

        return redirect()->route('home');
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('login');
    }
}