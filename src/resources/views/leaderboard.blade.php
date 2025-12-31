@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    /* --- 1. GLOBAL SPACE THEME --- */
    .leaderboard-container {
        min-height: 100vh;
        /* Background Gelap + Aksen Ungu/Biru */
        background: radial-gradient(circle at top, #2b1055, #000); 
        position: relative;
        overflow-x: hidden;
        padding-bottom: 50px;
    }
    
    /* Efek Bintang Jatuh */
    .star-bg {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background-image: url("https://www.transparenttextures.com/patterns/stardust.png");
        opacity: 0.5; pointer-events: none;
    }

    /* --- 2. PODIUM JUARA (TOP 3) --- */
    .podium-wrapper {
        display: flex;
        justify-content: center;
        align-items: flex-end; /* Agar podium naik turun seperti tangga */
        margin-bottom: 50px;
        gap: 20px;
        padding-top: 30px;
    }

    .podium-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        text-align: center;
        padding: 20px;
        position: relative;
        transition: transform 0.3s;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        border: 2px solid rgba(255,255,255,0.1);
    }

    .podium-card:hover { transform: translateY(-10px); }

    /* Avatar Juara */
    .podium-avatar {
        width: 80px; height: 80px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 10px;
        border: 4px solid white;
    }

    /* JUARA 1 (EMAS) */
    .rank-1 {
        order: 2; /* Tengah */
        width: 180px; height: 320px; /* Paling Tinggi */
        background: linear-gradient(180deg, rgba(255, 215, 0, 0.2) 0%, rgba(255, 215, 0, 0.05) 100%);
        border-color: #FFD700;
        z-index: 10;
    }
    .rank-1 .podium-avatar { width: 100px; height: 100px; border-color: #FFD700; box-shadow: 0 0 20px #FFD700; }
    .crown-icon { position: absolute; top: -35px; left: 50%; transform: translateX(-50%); font-size: 40px; filter: drop-shadow(0 0 10px gold); animation: floatCrown 2s infinite; }
    
    /* JUARA 2 (PERAK) */
    .rank-2 {
        order: 1; /* Kiri */
        width: 160px; height: 260px;
        background: linear-gradient(180deg, rgba(192, 192, 192, 0.2) 0%, rgba(192, 192, 192, 0.05) 100%);
        border-color: #C0C0C0;
    }
    .rank-2 .podium-avatar { border-color: #C0C0C0; box-shadow: 0 0 15px #C0C0C0; }

    /* JUARA 3 (PERUNGGU) */
    .rank-3 {
        order: 3; /* Kanan */
        width: 160px; height: 230px;
        background: linear-gradient(180deg, rgba(205, 127, 50, 0.2) 0%, rgba(205, 127, 50, 0.05) 100%);
        border-color: #CD7F32;
    }
    .rank-3 .podium-avatar { border-color: #CD7F32; box-shadow: 0 0 15px #CD7F32; }

    @keyframes floatCrown { 0%, 100% { transform: translate(-50%, 0); } 50% { transform: translate(-50%, -10px); } }

    /* --- 3. LIST PERINGKAT (RANK 4+) --- */
    .list-wrapper {
        max-width: 800px;
        margin: 0 auto;
    }

    .rank-item {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 15px 25px;
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        transition: all 0.2s;
        backdrop-filter: blur(5px);
    }

    .rank-item:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: scale(1.02);
        border-color: rgba(255, 255, 255, 0.5);
    }

    .rank-number {
        font-family: 'Rajdhani', sans-serif;
        font-size: 24px;
        font-weight: bold;
        color: #fff;
        width: 40px;
    }

    .rank-avatar { width: 50px; height: 50px; border-radius: 50%; margin-right: 20px; border: 2px solid rgba(255,255,255,0.3); }

    .rank-info { flex-grow: 1; }
    .rank-name { color: white; font-weight: bold; font-size: 1.1rem; display: block; }
    .rank-score { color: #00d2ff; font-weight: bold; font-size: 1.2rem; }

    /* Highlight User Sendiri */
    .my-rank {
        border: 2px solid #00d2ff;
        background: rgba(0, 210, 255, 0.1);
        box-shadow: 0 0 15px rgba(0, 210, 255, 0.2);
    }
</style>

<div class="leaderboard-container pt-4">
    <div class="star-bg"></div>

    <div class="container">
        <div class="text-center mb-5">
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-light rounded-pill mb-3 px-3">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <h1 class="fw-bold text-white text-uppercase" style="text-shadow: 0 0 20px #bc13fe;">
                🏆 Hall of Fame
            </h1>
            <p class="text-white-50">Kapten terhebat di seluruh galaksi</p>
        </div>

        @if($users->count() > 0)
        <div class="podium-wrapper animate__animated animate__fadeInUp">
            
            @if(isset($users[1]))
            <div class="podium-card rank-2">
                <div class="fw-bold text-white-50 mb-2">#2</div>
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ $users[1]->name }}" class="podium-avatar bg-white">
                <h5 class="text-white fw-bold text-truncate">{{ $users[1]->name }}</h5>
                <div class="badge bg-secondary rounded-pill px-3">{{ $users[1]->total_score ?? 0 }} XP</div>
            </div>
            @endif

            @if(isset($users[0]))
            <div class="podium-card rank-1">
                <div class="crown-icon">👑</div>
                <div class="fw-bold text-warning mb-2" style="margin-top: 10px;">#1 JUARA</div>
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ $users[0]->name }}" class="podium-avatar bg-white">
                <h4 class="text-white fw-bold mt-2 text-truncate">{{ $users[0]->name }}</h4>
                <div class="fs-4 fw-bold text-warning">{{ $users[0]->total_score ?? 0 }} XP</div>
                <small class="text-white-50 d-block mt-2">Legenda Antariksa</small>
            </div>
            @endif

            @if(isset($users[2]))
            <div class="podium-card rank-3">
                <div class="fw-bold text-white-50 mb-2">#3</div>
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ $users[2]->name }}" class="podium-avatar bg-white">
                <h5 class="text-white fw-bold text-truncate">{{ $users[2]->name }}</h5>
                <div class="badge bg-danger rounded-pill px-3" style="background: #CD7F32 !important;">{{ $users[2]->total_score ?? 0 }} XP</div>
            </div>
            @endif

        </div>
        @else
            <div class="text-center text-white p-5">
                <h3>Belum ada data peringkat. Jadilah yang pertama! 🚀</h3>
            </div>
        @endif

        <div class="list-wrapper animate__animated animate__fadeInUp animate__delay-1s">
            <h5 class="text-white-50 mb-3 ms-2">Peringkat Lainnya</h5>

            @foreach($users->skip(3) as $index => $user)
                <div class="rank-item {{ Auth::id() == $user->id ? 'my-rank' : '' }}">
                    <div class="rank-number">{{ $index + 4 }}</div>
                    
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ $user->name }}" class="rank-avatar bg-white">
                    
                    <div class="rank-info">
                        <span class="rank-name">
                            {{ $user->name }} 
                            @if(Auth::id() == $user->id) <span class="badge bg-info ms-2 tiny">Saya</span> @endif
                        </span>
                        <small class="text-white-50">Kapten Pemula</small>
                    </div>
                    
                    <div class="rank-score">{{ $user->total_score ?? 0 }} XP</div>
                </div>
            @endforeach
            
            @if($users->count() <= 3 && $users->count() > 0)
                <div class="text-center text-white-50 py-4 border border-secondary rounded-4" style="background: rgba(255,255,255,0.05)">
                    <p class="m-0">Belum ada kapten lain di daftar ini.</p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection