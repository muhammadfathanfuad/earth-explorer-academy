<?php

namespace App\Http\Controllers;

use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScoreController extends Controller
{
    public function store(Request $request)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return response()->json(['message' => 'Login required'], 401);
        }

        // Simpan / Update skor tertinggi user untuk topik ini
        Score::updateOrCreate(
            ['user_id' => Auth::id(), 'topic_id' => $request->topic_id],
            ['score' => $request->score]
        );

        return response()->json(['message' => 'Skor tersimpan!']);
    }
}
