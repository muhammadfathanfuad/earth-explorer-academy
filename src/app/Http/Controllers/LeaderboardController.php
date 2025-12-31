<?php

namespace App\Http\Controllers;

use App\Models\Score;
use App\Models\User;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index()
    {
        // 1. Ambil semua User beserta data Scores-nya
    // Pastikan di Model User sudah ada fungsi public function scores() { return $this->hasMany(Score::class); }
    $users = User::with('scores')->get();

    // 2. Hitung Total Skor untuk setiap User
    // Kita tambahkan properti baru 'total_score' ke setiap user secara manual
    $users = $users->map(function ($user) {
        $user->total_score = $user->scores->sum('score'); // Menjumlahkan kolom 'score'
        return $user;
    });

    // 3. Urutkan User berdasarkan Total Skor Tertinggi (Descending)
    // values() digunakan untuk mereset urutan array key agar mulai dari 0 lagi (Penting untuk podium)
    $users = $users->sortByDesc('total_score')->values();

    // 4. Kirim variabel '$users' ke view (BUKAN '$scores')
    return view('leaderboard', compact('users'));
    }
}
