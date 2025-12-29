<?php

namespace App\Http\Controllers;

use App\Models\Score;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index()
    {
        // Ambil 10 Skor Tertinggi, urutkan dari yang terbesar
        // with('user') & with('topic') agar kita bisa tampilkan nama anak & nama materi
        $scores = Score::with(['user', 'topic'])
            ->where('is_visible', true)
            ->orderBy('score', 'desc')
            ->take(10)
            ->get();

        return view('leaderboard', compact('scores'));
    }
}
