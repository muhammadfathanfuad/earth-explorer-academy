<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    // Daftar Pilihan Gambar (Emoji)
    protected $badges = [
        'rocket' => '🚀',
        'cat' => '🐱',
        'pizza' => '🍕',
        'ball' => '⚽',
        'star' => '⭐',
        'car' => '🚗',
        'ghost' => '👻',
        'robot' => '🤖',
        'dragon' => '🐉',
        'alien' => '👽',
        'diamond' => '💎',
        'earth' => '🌍'
    ];

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // TAHAP 1: VALIDASI INPUT NAMA ATAU EMAIL
        if (!$request->has('current_stage')) {
            $request->validate(['login_id' => 'required|string|max:50']);
            $name = trim($request->login_id);

            // Cek apakah Admin? (Ada @)
            if (str_contains($name, '@')) {
                $user = User::where('email', $name)->first();

                if ($user) {
                    // Asumsi user dengan email adalah admin, minta password
                    return back()->with([
                        'stage' => 'admin_password', // Tahap password admin
                        'temp_name' => $name,
                        'message' => 'Selamat datang, Admin! Silakan masukkan password Anda.'
                    ]);
                } else {
                    return back()->withErrors(['login_id' => 'Admin dengan email ini tidak ditemukan.']);
                }
            }

            // Cek apakah User biasa sudah ada di database?
            $user = User::where('name', $name)->first();

            if ($user) {
                // --- SKENARIO A: USER LAMA (LOGIN) ---
                $realBadge = $user->secret_badge;
                $distractors = array_keys($this->badges);
                $distractors = array_diff($distractors, [$realBadge]);
                shuffle($distractors);
                $options = array_slice($distractors, 0, 5);
                $options[] = $realBadge;
                shuffle($options);

                return back()->with([
                    'stage' => 'challenge',
                    'temp_name' => $name,
                    'options' => $options,
                    'badges_map' => $this->badges,
                    'message' => 'Selamat datang kembali, Kapten! Buktikan identitasmu.'
                ]);
            } else {
                // --- SKENARIO B: USER BARU (DAFTAR) ---
                return back()->with([
                    'stage' => 'register',
                    'temp_name' => $name,
                    'options' => array_keys($this->badges),
                    'badges_map' => $this->badges,
                    'message' => 'Halo Kapten Baru! Pilih 1 gambar rahasia untuk mengunci akunmu.'
                ]);
            }
        }

        // TAHAP 2: EKSEKUSI PILIHAN GAMBAR ATAU PASSWORD
        $request->validate([
            'login_id' => 'required|string',
            'current_stage' => 'required|string'
        ]);

        $name = $request->input('login_id');
        $stage = $request->input('current_stage');

        if ($stage === 'register') {
            $request->validate(['selected_badge' => 'required|string']);
            $badge = $request->input('selected_badge');

            if (User::where('name', $name)->exists()) {
                return redirect()->route('login')->with('error', 'Yah, nama itu baru saja diambil orang lain! Coba nama lain.');
            }

            $newUser = User::create([
                'name' => $name,
                'email' => Str::slug($name) . '-' . rand(1000, 9999) . '@siswa.com',
                'password' => bcrypt('rahasia'),
                'secret_badge' => $badge
            ]);

            Auth::login($newUser);
            return redirect()->intended('/home');

        } elseif ($stage === 'challenge') {
            $request->validate(['selected_badge' => 'required|string']);
            $badge = $request->input('selected_badge');
            $user = User::where('name', $name)->first();

            if ($user && $user->secret_badge === $badge) {
                Auth::login($user);
                return redirect()->intended('/home');
            } else {
                return redirect()->route('login')->with('error', '⛔ Akses Ditolak! Gambar salah. Jangan coba-coba menyusup!');
            }

        } elseif ($stage === 'admin_password') {
            $request->validate(['password' => 'required|string']);

            if (Auth::attempt(['email' => $name, 'password' => $request->password])) {
                return redirect()->intended('/home');
            } else {
                return back()->withErrors(['login_id' => 'Password Admin salah.'])->with([
                    'stage' => 'admin_password',
                    'temp_name' => $name,
                    'message' => 'Selamat datang, Admin! Silakan masukkan password Anda.'
                ]);
            }
        }
    }
}
