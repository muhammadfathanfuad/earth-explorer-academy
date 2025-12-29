<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;

class EarthController extends Controller
{
    public function index()
    {
        $topics = Topic::all();
        return view('home', compact('topics'));
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