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

        // Ambil topik dan langsung GRUP berdasarkan category
        $groupedTopics = Topic::with(['scores' => function ($query) use ($user) {
            $query->where('user_id', $user ? $user->id : null);
        }])
        ->orderBy('category') // Urutkan biar rapi
        ->get()
        ->groupBy('category'); // <--- INI KUNCINYA

        // Ambil Leaderboard Top 3
        $leaderboard = User::withSum('scores', 'score')
            ->orderByDesc('scores_sum_score')
            ->take(3)
            ->get();

        // Hitung Total Skor User
        $myTotalScore = $user ? $user->scores()->sum('score') : 0;

        // Kirim $groupedTopics (bukan $topics biasa)
        return view('home', compact('groupedTopics', 'leaderboard', 'myTotalScore'));
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