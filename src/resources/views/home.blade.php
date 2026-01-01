@extends('layouts.app')

@section('content')

<style>
    /* --- GLASSMORPHISM CARD --- */
    .glass-panel {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
    }

    /* --- HERO PROFILE SECTION --- */
    .profile-avatar {
        width: 100px; height: 100px;
        border: 4px solid #00d2ff;
        border-radius: 50%;
        background: #000;
        object-fit: cover;
        box-shadow: 0 0 20px rgba(0, 210, 255, 0.5);
    }

    /* --- MISSION CARD (3D TILT EFFECT) --- */
    .mission-card {
        transition: transform 0.3s, box-shadow 0.3s;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        border-top: 1px solid rgba(255,255,255,0.2);
    }
    .mission-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 15px 30px rgba(0, 210, 255, 0.2);
        border-color: #00d2ff;
    }
    .mission-card .mission-img {
        height: 180px;
        width: 100%;
        object-fit: cover;
        border-radius: 15px;
        filter: brightness(0.8);
        transition: filter 0.3s;
    }
    .mission-card:hover .mission-img { filter: brightness(1.1); }

    /* Badge Status Misi */
    .mission-status {
        position: absolute; top: 20px; right: 20px;
        padding: 5px 15px; border-radius: 20px;
        font-weight: bold; font-size: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }

    /* --- LEADERBOARD WIDGET --- */
    .rank-item {
        border-bottom: 1px solid rgba(255,255,255,0.1);
        padding: 10px 0;
    }
    .rank-item:last-child { border-bottom: none; }
    .medal { font-size: 20px; margin-right: 10px; }
</style>

<div class="row g-4">
    
    <div class="col-lg-4">
        
        <div class="glass-panel p-4 text-center mb-4 animate__animated animate__fadeInLeft">
            @auth
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ Auth::user()->name }}" class="profile-avatar mb-3">
                <h3 class="fw-bold mb-0 text-white">{{ Auth::user()->name }}</h3>
                
                @php
                    $rank = 'Kadet';
                    $badgeColor = 'secondary';
                    if($myTotalScore > 500) { $rank = 'Kapten'; $badgeColor = 'warning'; }
                    elseif($myTotalScore > 100) { $rank = 'Letnan'; $badgeColor = 'info'; }
                @endphp
                
                <span class="badge bg-{{ $badgeColor }} rounded-pill px-3 mt-2">Rank: {{ $rank }} 🚀</span>
                
                <div class="row mt-4 text-white">
                    <div class="col-6 border-end border-secondary">
                        <h2 class="fw-bold mb-0 text-warning">{{ $myTotalScore }}</h2>
                        <small class="text-white-50">Total XP</small>
                    </div>
                    <div class="col-6">
                        <h2 class="fw-bold mb-0 text-success">{{ $topics->count() }}</h2>
                        <small class="text-white-50">Misi Tersedia</small>
                    </div>
                </div>
            @else
                <div class="py-4">
                    <h1>👋</h1>
                    <h4 class="fw-bold">Selamat Datang!</h4>
                    <p class="text-white-50 mb-4">Login untuk mulai mengumpulkan poin dan naik pangkat!</p>
                    <a href="{{ route('login') }}" class="btn btn-sci-fi w-100 rounded-pill">Login Pasukan</a>
                </div>
            @endauth
        </div>

        <div class="glass-panel p-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
            <h5 class="fw-bold text-warning mb-3">🏆 Top 3 Rangers Hari Ini</h5>
            
            @foreach($leaderboard as $index => $ranger)
            <div class="d-flex align-items-center rank-item">
                <div class="medal">
                    @if($index == 0) 🥇
                    @elseif($index == 1) 🥈
                    @else 🥉
                    @endif
                </div>
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ $ranger->name }}" class="rounded-circle me-2" width="35">
                <div class="flex-grow-1">
                    <h6 class="mb-0 text-white small">{{ $ranger->name }}</h6>
                </div>
                <span class="fw-bold text-info small">{{ $ranger->scores_sum_score ?? 0 }} XP</span>
            </div>
            @endforeach

            <div class="text-center mt-3">
                <a href="{{ route('leaderboard') }}" class="btn btn-sm btn-outline-light rounded-pill px-4">Lihat Semua</a>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <h4 class="text-white fw-bold mb-4 animate__animated animate__fadeInDown">
            🛸 Dek Misi Tersedia
        </h4>

        {{-- JIKA KOSONG --}}
        @if($groupedTopics->isEmpty())
            <div class="glass-panel p-5 text-center">
                <h3>😴 Belum ada misi aktif.</h3>
                <p>Tunggu instruksi dari Pusat Komando (Admin).</p>
            </div>
        @else
            {{-- LOOP PER KATEGORI/TEMA --}}
            @foreach($groupedTopics as $category => $topics)
                
                @php
                    // Cek apakah ini kategori Tantangan
                    $isChallenge = ($category == 'Tantangan');
                    $headerColor = $isChallenge ? '#ff416c' : '#00d2ff'; // Merah jika Tantangan, Biru jika biasa
                    $shadowColor = $isChallenge ? 'rgba(255, 65, 108, 0.5)' : 'rgba(0, 210, 255, 0.5)';
                    $icon = $isChallenge ? '🔥' : 'SEKTOR:';
                @endphp

                {{-- Judul Kategori Dinamis --}}
                <div class="d-flex align-items-center mb-3 mt-4 animate__animated animate__fadeInLeft">
                    <div class="rounded-pill me-3" style="width: 5px; height: 30px; background: {{ $headerColor }}; box-shadow: 0 0 10px {{ $headerColor }};"></div>
                    <h4 class="fw-bold text-uppercase m-0" style="color: {{ $headerColor }}; letter-spacing: 2px; text-shadow: 0 0 10px {{ $shadowColor }};">
                        {{ $icon }} {{ $category }}
                    </h4>
                    <div class="flex-grow-1 ms-3 border-bottom border-secondary opacity-50"></div>
                </div>

                <div class="row g-4 mb-5">
                    {{-- LOOP KARTU MISI --}}
                    @foreach($topics as $index => $topic)
                    <div class="col-md-6 animate__animated animate__zoomIn" style="animation-delay: {{ $index * 0.1 }}s;">
                        
                        <a href="{{ route('topic.show', $topic->slug) }}" class="text-decoration-none">
                            <div class="glass-panel mission-card p-3 h-100">
                                
                                <img src="{{ $topic->image ? asset('storage/' . $topic->image) : 'https://source.unsplash.com/400x300/?space,planet' }}" class="mission-img mb-3">
                                
                                @php
                                    $userHighScore = $topic->scores->max('score');
                                @endphp

                                @if($userHighScore)
                                    <div class="mission-status bg-success text-white">
                                        ✅ Selesai ({{ $userHighScore }} XP)
                                    </div>
                                @else
                                    <div class="mission-status bg-secondary text-white-50">
                                        🔒 Belum Dimulai
                                    </div>
                                @endif

                                <h4 class="fw-bold text-white mb-2">{{ $topic->title }}</h4>
                                <p class="text-white-50 small mb-0">{{ Str::limit($topic->summary, 60) }}</p>
                                
                                <div class="mt-3 text-end">
                                    <span class="text-info small fw-bold">Mulai Misi ➜</span>
                                </div>
                            </div>
                        </a>

                    </div>
                    @endforeach
                </div>
            @endforeach
        @endif

    </div>
</div>

@endsection