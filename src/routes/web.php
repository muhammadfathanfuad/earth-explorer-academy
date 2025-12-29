<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EarthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\LeaderboardController;

// Auth Routes (Cukup Login & Logout)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Halaman Utama (Daftar Materi)
Route::get('/', [EarthController::class, 'index'])->name('home');

// Halaman Detail Materi (Nanti kita buat)
Route::get('/materi/{slug}', [EarthController::class, 'show'])->name('topic.show');

// Score & Leaderboard
Route::post('/score', [ScoreController::class, 'store'])->name('score.store')->middleware('auth');
Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');