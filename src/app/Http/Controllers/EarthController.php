<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\User; // Penting: Import Model User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EarthController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Ambil semua topik. Selalu eager load relasi 'scores'
        //    dengan kondisi user_id saat ini (atau null jika guest).
        //    Ini mencegah N+1 problem & error 'property of non-object' di view.
        $topics = Topic::with(['scores' => function ($query) use ($user) {
            $query->where('user_id', $user ? $user->id : null);
        }])->get();

        // 2. Ambil Leaderboard Top 3
        $leaderboard = User::withSum('scores', 'score')
            ->orderByDesc('scores_sum_score')
            ->take(3)
            ->get();

        // 3. Hitung Total Skor User Sendiri (Untuk Badge Pangkat)
        $myTotalScore = $user ? $user->scores()->sum('score') : 0;

        return view('home', compact('topics', 'leaderboard', 'myTotalScore'));
    }

    public function show($slug)
    {
        $topic = Topic::with('quizzes')->where('slug', $slug)->firstOrFail();
        
        // Ambil data content. Karena sudah di-cast 'array' di Model, 
        // ini otomatis jadi Array Slide.
        $slides = $topic->content;

        // Cek jika kosong (Admin belum isi slide)
        if (empty($slides)) {
            $slides = []; // Kosongkan biar tidak error
        }

        return view('topic.show', compact('topic', 'slides'));
    }
}