@extends('layouts.app')

<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* --- 1. SETTING WARNA & FONT --- */
        :root {
            --color-primary: #4FACFE;
            /* Biru Langit */
            --color-secondary: #00F2FE;
            /* Cyan */
            --color-correct: #00b09b;
            /* Hijau Sukses */
            --color-wrong: #ff5f6d;
            /* Merah Salah */
            --card-bg: rgba(255, 255, 255, 0.1);
        }

        /* --- 2. CONTAINER UTAMA (MODEL KACA/GLASS) --- */
        .space-container {
            position: relative;
            border-radius: 30px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);

            /* Ganti background solid gelap menjadi Efek Kaca (Glassmorphism) */
            background: rgba(255, 255, 255, 0.15);
            /* Putih transparan */
            backdrop-filter: blur(20px);
            /* Efek blur background belakang */
            -webkit-backdrop-filter: blur(20px);

            border: 1px solid rgba(255, 255, 255, 0.4);
            /* Garis tepi lebih terang */
            min-height: 600px;
        }

        /* HAPUS .stars-overlay agar tidak menumpuk dan kotor */
        .stars-overlay {
            display: none;
        }

        /* --- 3. QUESTION BOX (LEBIH CERAH & FRIENDLY) --- */
        .question-card {
            /* Ganti gradasi biru gelap menjadi gradasi yang lebih ceria tapi tetap kontras */
            background: linear-gradient(135deg, #2563eb 0%, #06b6d4 100%);
            border-radius: 25px;
            padding: 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            position: relative;
            margin-bottom: 30px;
            color: #fff;
        }

        /* Update Bubble Chat agar senada dengan Glass Theme */
        .bubble-chat {
            /* Warna biru laut gelap transparan agar teks putih terbaca jelas */
            background: rgba(15, 23, 42, 0.8);
            border: 2px solid rgba(100, 200, 255, 0.5);
            border-radius: 20px;
            border-top-left-radius: 0;
            padding: 30px;
            position: relative;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            color: #fff;
        }

        /* --- 4. TOMBOL JAWABAN (WARNA-WARNI) --- */
        .answer-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            color: white;
            font-weight: 600;
            transition: all 0.2s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
            overflow: hidden;
        }

        .answer-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* STATE: BENAR (HIJAU) */
        .answer-correct {
            background: linear-gradient(45deg, #11998e, #38ef7d) !important;
            border-color: #fff !important;
            color: white !important;
            box-shadow: 0 0 20px rgba(56, 239, 125, 0.6) !important;
            transform: scale(1.02);
            z-index: 10;
        }

        /* STATE: SALAH (MERAH) */
        .answer-wrong {
            background: linear-gradient(45deg, #ff416c, #ff4b2b) !important;
            border-color: #fff !important;
            color: white !important;
            box-shadow: 0 0 20px rgba(255, 75, 43, 0.6) !important;
            animation: shake 0.5s;
        }

        @keyframes shake {
            0% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            50% {
                transform: translateX(5px);
            }

            75% {
                transform: translateX(-5px);
            }

            100% {
                transform: translateX(0);
            }
        }

        /* --- UBAH BACKGROUND GAMBAR --- */
        /* Agar gambar tidak punya kotak putih di belakangnya */
        .img-bg-fix {
            background: rgba(0, 0, 0, 0.2);
            /* Gelap transparan */
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        /* --- 5. SWIPE CARD (FIXED) --- */
        /* Pastikan container ini TIDAK overflow hidden */
        .swipe-game-area {
            position: relative;
            height: 450px;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: visible !important;
            /* KUNCI AGAR TIDAK KEPOTONG */
            z-index: 10;
        }

        .tinder-card {
            width: 100%;
            max-width: 360px;
            height: 420px;
            background: #ffffff;
            /* Kartu Putih Solid agar kontras */
            border: 6px solid #e0e0e0;
            border-radius: 35px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            position: absolute;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            cursor: grab;
            user-select: none;
            transition: transform 0.1s;
            /* Responsif saat drag */
        }

        .tinder-card:active {
            cursor: grabbing;
            border-color: #4FACFE;
        }

        /* Icon Floating di atas kartu */
        .floating-icon {
            position: absolute;
            top: -35px;
            background: #FFD700;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 35px;
            border: 5px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            z-index: 20;
        }

        /* Stempel Jawaban */
        .stamp-badge {
            position: absolute;
            top: 40px;
            padding: 10px 20px;
            border: 4px solid;
            border-radius: 15px;
            font-size: 28px;
            font-weight: 900;
            text-transform: uppercase;
            opacity: 0;
            z-index: 5;
            background: rgba(255, 255, 255, 0.9);
        }

        .stamp-fact {
            color: #2ecc71;
            border-color: #2ecc71;
            transform: rotate(-15deg);
            left: 20px;
        }

        .stamp-myth {
            color: #e74c3c;
            border-color: #e74c3c;
            transform: rotate(15deg);
            right: 20px;
        }

        /* --- UBAH TOMBOL KEMBALI --- */
        .btn-back-custom {
            background: rgba(255, 255, 255, 0.1);
            /* Transparan */
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            backdrop-filter: blur(5px);
            transition: all 0.3s;
        }

        .btn-back-custom:hover {
            background: rgba(0, 243, 255, 0.2);
            /* Biru Neon saat hover */
            border-color: #00f3ff;
            color: #fff;
            transform: translateX(-5px);
        }

        /* --- 6. TOMBOL SWIPE MANUAL (FIXED ALIGNMENT) --- */
        .btn-swipe-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            border: 4px solid white;
            display: flex !important;
            /* Paksa Flexbox */
            justify-content: center;
            /* Tengah Horizontal */
            align-items: center;
            /* Tengah Vertikal */
            font-size: 30px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            transition: transform 0.2s;
            padding: 0;
            /* Hapus padding bawaan bootstrap */
        }

        .btn-swipe-circle:hover {
            transform: scale(1.1);
        }

        .btn-no {
            background: #ff416c;
            color: white;
        }

        .btn-yes {
            background: #00b09b;
            color: white;
        }

        /* --- 7. PROGRESS BAR RAINBOW --- */
        .progress-container {
            height: 12px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .rainbow-bar {
            background: linear-gradient(90deg, #f093fb 0%, #f5576c 100%);
            box-shadow: 0 0 15px #f5576c;
            transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
        }

        /* Bar Emas saat Selesai */
        .bar-completed {
            background: linear-gradient(90deg, #FDC830 0%, #F37335 100%) !important;
            box-shadow: 0 0 20px #FDC830 !important;
            animation: shimmer 2s infinite linear;
        }

        @keyframes shimmer {
            0% {
                filter: brightness(100%);
            }

            50% {
                filter: brightness(120%);
            }

            100% {
                filter: brightness(100%);
            }
        }

        /* --- 8. STORY ELEMENTS --- */
        .bubble-chat {
            /* Ganti background putih jadi Kaca Gelap Biru */
            background: rgba(16, 28, 50, 0.85);
            border: 2px solid #00f3ff;
            /* Border Neon */
            border-radius: 20px;
            border-top-left-radius: 0;
            padding: 30px;
            position: relative;
            box-shadow: 0 0 30px rgba(0, 243, 255, 0.15);
            color: #e2e8f0;
            /* Teks jadi putih terang */
        }

        /* Segitiga Chat ikut berubah warna */
        .bubble-chat::before {
            content: "";
            position: absolute;
            left: -12px;
            top: 20px;
            border-top: 10px solid transparent;
            border-bottom: 10px solid transparent;
            border-right: 12px solid #00f3ff;
            /* Sesuaikan warna border */
        }

        /* --- STYLE PUZZLE URUTAN (CARD STYLE) --- */
        .seq-item {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.9) 100%);
            border: 2px solid rgba(79, 172, 254, 0.3);
            border-radius: 20px;
            padding: 0;
            margin-bottom: 15px;
            color: #1a1a2e;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: grab;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            position: relative;
        }

        .seq-item:active {
            cursor: grabbing;
            transform: scale(1.02);
            box-shadow: 0 12px 30px rgba(79, 172, 254, 0.4);
            border-color: #4FACFE;
        }

        .seq-item:hover {
            border-color: #4FACFE;
            box-shadow: 0 10px 25px rgba(79, 172, 254, 0.3);
            transform: translateY(-2px);
        }

        /* Efek saat sedang didrag (SortableJS Class) */
        .sortable-ghost {
            opacity: 0.5;
            background: linear-gradient(135deg, rgba(79, 172, 254, 0.3) 0%, rgba(0, 242, 254, 0.3) 100%);
            transform: scale(0.95);
        }

        .sortable-drag {
            opacity: 1;
            background: linear-gradient(135deg, rgba(79, 172, 254, 0.2) 0%, rgba(0, 242, 254, 0.2) 100%);
            transform: scale(1.05) rotate(2deg);
            box-shadow: 0 15px 35px rgba(79, 172, 254, 0.5);
            z-index: 1000;
        }

        /* Container untuk konten card */
        .seq-item-content {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 15px 20px;
        }

        /* Drag handle di kiri */
        .seq-drag-handle {
            color: #4FACFE;
            font-size: 1.5rem;
            margin-right: 15px;
            cursor: grab;
            display: flex;
            align-items: center;
        }

        .seq-drag-handle:active {
            cursor: grabbing;
        }

        /* Container untuk gambar */
        .seq-image-container {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            overflow: hidden;
            margin-right: 15px;
            background: linear-gradient(135deg, #4FACFE 0%, #00F2FE 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .seq-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .seq-image-placeholder {
            color: white;
            font-size: 2rem;
        }

        /* Teks di tengah */
        .seq-text {
            flex: 1;
            color: #1a1a2e;
            font-size: 1.1rem;
            font-weight: 600;
        }

        /* Icon panah di kanan */
        .seq-arrow {
            color: #4FACFE;
            font-size: 1.3rem;
            margin-left: 15px;
        }

        .seq-number {
            background: linear-gradient(135deg, #4FACFE 0%, #00F2FE 100%);
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: bold;
            color: white;
            margin-right: 15px;
            box-shadow: 0 4px 10px rgba(79, 172, 254, 0.3);
        }

        /* Responsive untuk mobile */
        @media (max-width: 768px) {
            .seq-item {
                padding: 0;
                margin-bottom: 12px;
            }

            .seq-item-content {
                padding: 12px 15px;
            }

            .seq-image-container {
                width: 60px;
                height: 60px;
                margin-right: 12px;
            }

            .seq-text {
                font-size: 1rem;
            }

            .seq-drag-handle {
                font-size: 1.2rem;
                margin-right: 10px;
            }
        }

        /* --- STYLE KHUSUS RESULT SCREEN --- */
        .result-card {
            /* Background Gelap Transparan + Border Emas */
            background: rgba(10, 15, 30, 0.9);
            border: 3px solid #FFD700;
            /* Warna Emas */
            border-radius: 30px;
            box-shadow: 0 0 50px rgba(255, 215, 0, 0.3);
            /* Cahaya Emas di belakang */
            position: relative;
            overflow: hidden;
        }

        /* Efek Kilau Emas pada Skor */
        .score-glow {
            font-size: 5rem;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 800;
            background: linear-gradient(to bottom, #FFD700, #FDB931);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 0 15px rgba(255, 215, 0, 0.5));
            animation: pulseScore 2s infinite;
        }

        @keyframes pulseScore {
            0% {
                transform: scale(1);
                filter: drop-shadow(0 0 15px rgba(255, 215, 0, 0.5));
            }

            50% {
                transform: scale(1.05);
                filter: drop-shadow(0 0 30px rgba(255, 215, 0, 0.8));
            }

            100% {
                transform: scale(1);
                filter: drop-shadow(0 0 15px rgba(255, 215, 0, 0.5));
            }
        }
    </style>

    <div class="row justify-content-center">
        <div class="col-md-10">

            <a href="{{ route('home') }}" class="btn btn-sm btn-back-custom mb-4 rounded-pill px-4 fw-bold shadow-sm">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Markas
            </a>

            <div id="story-mode" class="space-container" style="display: block;">
                <div class="story-wrapper p-4 p-md-5">
                    <div class="stars-overlay"></div>

                    <div class="d-flex justify-content-between text-white-50 small mb-1 fw-bold">
                        <span>DATA BRIEFING</span><span id="progress-text">0%</span>
                    </div>
                    <div class="progress-container">
                        <div id="read-progress" class="rainbow-bar" style="width: 0%"></div>
                    </div>

                    <div class="row align-items-center h-100 position-relative" style="z-index: 2;">
                        <div class="col-md-4 text-center mb-5 mb-md-0">
                            <div
                                style="filter: drop-shadow(0 0 20px rgba(79, 172, 254, 0.6)); animation: floatMascot 4s infinite;">
                                <img id="dynamic-image" src=""
                                    class="rounded-circle border border-4 border-white shadow-lg bg-dark"
                                    style="width: 200px; height: 200px; object-fit: cover; display: none;">
                                <div id="default-mascot" style="font-size: 140px; display: none;">👨‍🚀</div>
                            </div>
                            <div class="mt-4">
                                <span class="badge bg-white text-primary rounded-pill px-4 py-2 fw-bold shadow">
                                    VISUALISASI
                                </span>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="bubble-chat">
                                @foreach ($slides as $index => $slide)
                                    <div class="story-slide animate__animated animate__fadeIn"
                                        id="slide-{{ $index }}" data-image="{{ $slide['slide_image'] ?? '' }}"
                                        style="display: none;">
                                        {{-- <h2 class="text-primary fw-bold mb-3">
                                            {{ $slide['title'] ?? 'Briefing Bagian ' . ($index + 1) }}</h2> --}}
                                        <div class="fs-5 lh-lg text-white fw-light">
                                            {!! $slide['slide_text'] ?? ($slide['content'] ?? '') !!}
                                        </div>
                                    </div>
                                @endforeach

                                <div class="d-flex justify-content-between align-items-center mt-5 pt-3 border-top">
                                    <button onclick="prevSlide()" id="btn-prev"
                                        class="btn btn-outline-secondary rounded-pill px-4" style="display: none;">←
                                        Mundur</button>
                                    <button onclick="nextSlide()" id="btn-next"
                                        class="btn btn-primary rounded-pill px-5 fw-bold ms-auto shadow">Lanjut →</button>
                                    <button onclick="enterQuizMode()" id="btn-quiz"
                                        class="btn btn-warning text-dark rounded-pill px-5 fw-bold ms-auto shadow-lg animate__animated animate__pulse animate__infinite"
                                        style="display: none;">
                                        🚀 MULAI TANTANGAN
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="quiz-mode" class="space-container border-0 mb-5 animate__animated animate__zoomIn"
                style="display: none; background: transparent; box-shadow: none; border: none;">

                <div class="d-flex justify-content-between text-white small mb-2 fw-bold">
                    <span>PROGRESS MISI</span> <span id="quiz-status">Soal 1</span>
                </div>
                <div class="progress-container shadow-sm" style="background: rgba(0,0,0,0.5);">
                    <div id="quiz-progress-bar" class="rainbow-bar" style="width: 0%"></div>
                </div>

                <div id="question-loading" class="text-center text-white py-5">
                    <div class="spinner-border text-info" role="status"></div>
                    <div class="mt-3">Menghubungkan ke Pusat Data...</div>
                </div>

                <div id="layout-multiple-choice" style="display: none;">

                    <div class="question-card text-center">
                        <img id="mc-image" src=""
                            class="img-fluid rounded-4 shadow-lg mb-3 border border-info img-bg-fix"
                            style="max-height: 200px; object-fit: contain; display: none;">
                        <h3 class="question-text" id="mc-question-text">...</h3>
                    </div>

                    <div class="row g-3 justify-content-center">
                        <div class="col-md-6"><button onclick="checkAnswer('a')" id="btn-a"
                                class="btn w-100 p-4 fs-5 text-start answer-btn"></button></div>
                        <div class="col-md-6"><button onclick="checkAnswer('b')" id="btn-b"
                                class="btn w-100 p-4 fs-5 text-start answer-btn"></button></div>
                        <div class="col-md-6"><button onclick="checkAnswer('c')" id="btn-c"
                                class="btn w-100 p-4 fs-5 text-start answer-btn"></button></div>
                        <div class="col-md-6"><button onclick="checkAnswer('d')" id="btn-d"
                                class="btn w-100 p-4 fs-5 text-start answer-btn"></button></div>
                    </div>
                </div>

                <div id="layout-swipe" style="display: none;">
                    <div class="text-center text-white mb-2">
                        <h3 class="fw-bold" style="text-shadow: 0 2px 5px rgba(0,0,0,0.5);">GESER KARTUNYA! 👆</h3>
                        <div class="d-flex justify-content-center gap-5 small opacity-75">
                            <span class="text-danger fw-bold">⬅ SALAH / MITOS</span>
                            <span class="text-success fw-bold">BENAR / FAKTA ➡</span>
                        </div>
                    </div>

                    <div class="swipe-game-area">
                        <div id="swipe-card" class="tinder-card">
                            <div class="floating-icon">🤔</div>

                            <div class="stamp-badge stamp-fact" id="stamp-fact">FAKTA!</div>
                            <div class="stamp-badge stamp-myth" id="stamp-myth">MITOS!</div>

                            <div class="mt-4 w-100 rounded-4 overflow-hidden d-flex align-items-center justify-content-center img-bg-fix"
                                style="height: 180px;">

                                <img id="swipe-image" src=""
                                    style="width: 100%; height: 100%; object-fit: cover; display: none; pointer-events: none;"
                                    draggable="false" onmousedown="return false"> <span id="swipe-no-image"
                                    class="text-muted small fw-bold">Mode Hologram Aktif</span>
                            </div>

                            <div class="text-center mt-3 px-1">
                                <h4 class="fw-bold text-dark lh-sm" id="swipe-question-text"
                                    style="font-family: 'Fredoka', sans-serif;">...</h4>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center gap-5 mt-2 position-relative" style="z-index: 20;">
                        <button onclick="triggerSwipe('left')" class="btn btn-swipe-circle btn-no">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        <button onclick="triggerSwipe('right')" class="btn btn-swipe-circle btn-yes">
                            <i class="bi bi-check-lg"></i>
                        </button>
                    </div>
                </div>

                {{-- LAYOUT 3: PUZZLE URUTAN (SEQUENCE) --}}
                <div id="layout-sequence" style="display: none;">
                    <div class="text-center text-white mb-4">
                        <h3 class="fw-bold" id="seq-question-text">...</h3>
                        <p class="small text-info"><i class="bi bi-hand-index-thumb"></i> Geser kotak di bawah untuk
                            mengurutkan!</p>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            {{-- Container Sortable --}}
                            <ul id="sequence-list" class="list-unstyled">
                                {{-- Item akan di-generate via JS --}}
                            </ul>

                            <button onclick="checkSequenceAnswer()"
                                class="btn btn-warning w-100 rounded-pill fw-bold py-3 mt-3 shadow-lg">
                                ✅ CEK URUTAN
                            </button>
                        </div>
                    </div>
                </div>

                <div id="result-screen" class="text-center py-5 result-card animate__animated animate__fadeInUp"
                    style="display: none;">

                    <div class="mb-3 animate__animated animate__bounceIn animate__delay-1s">
                        <img src="https://cdn-icons-png.flaticon.com/512/3112/3112946.png" width="120" alt="Trophy"
                            style="filter: drop-shadow(0 0 20px rgba(255,215,0,0.6));">
                    </div>

                    <h2 class="fw-bold text-white mb-2 text-uppercase" style="letter-spacing: 2px;">Misi Selesai!</h2>
                    <p class="fs-5 text-info mb-4">Laporan Kinerja Kapten:</p>

                    <div class="py-2">
                        <div class="score-glow" id="final-score">0</div>
                        <span class="badge bg-dark border border-warning text-warning rounded-pill px-3">POIN XP</span>
                    </div>

                    <div class="d-flex justify-content-center gap-3 mt-5">
                        <button onclick="location.reload()"
                            class="btn btn-outline-light rounded-pill px-4 py-2 fw-bold border-2">
                            <i class="bi bi-arrow-counterclockwise me-2"></i> Main Lagi
                        </button>

                        <a href="{{ route('leaderboard') }}"
                            class="btn btn-warning text-dark rounded-pill px-5 py-2 fw-bold shadow-lg"
                            style="box-shadow: 0 0 20px rgba(255, 193, 7, 0.4);">
                            <i class="bi bi-trophy-fill me-2"></i> Peringkat
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // --- 1. SETUP DATA ---
        const quizzes = @json($topic->quizzes ?? []);
        console.log('Quiz Data:', quizzes); // <-- DEBUGGING LINE

        const slideElements = document.querySelectorAll('.story-slide');
        const totalSlides = slideElements.length;
        const defaultImage = "{{ $topic->image ? asset('storage/' . $topic->image) : '' }}";

        let currentSlide = 0;
        let currentQIndex = 0;
        let score = 0;
        let isAnswering = false;

        // --- SWIPE LOGIC (REFACTORED INTO AN OBJECT) ---
        // HARUS didefinisikan di awal untuk menghindari ReferenceError
        // Menggunakan var untuk menghindari Temporal Dead Zone issue
        var swipeGame = {
            cardElement: null,
            startX: 0,
            currentX: 0,
            isDragging: false,
            // Simpan referensi event handler untuk bisa dihapus nanti
            boundStartDrag: null,
            boundDrag: null,
            boundEndDrag: null,

            init() {
                // Hapus event listener lama jika ada
                this.destroy();

                this.cardElement = document.getElementById('swipe-card');
                if (!this.cardElement) return;

                // Bind event handlers dan simpan referensinya
                this.boundStartDrag = this.startDrag.bind(this);
                this.boundDrag = this.drag.bind(this);
                this.boundEndDrag = this.endDrag.bind(this);

                // Add mouse and touch event listeners
                this.cardElement.addEventListener('mousedown', this.boundStartDrag);
                this.cardElement.addEventListener('touchstart', this.boundStartDrag);

                document.addEventListener('mousemove', this.boundDrag);
                document.addEventListener('touchmove', this.boundDrag);

                document.addEventListener('mouseup', this.boundEndDrag);
                document.addEventListener('touchend', this.boundEndDrag);
                
                this.resetPosition();
            },

            destroy() {
                // Hapus event listener jika ada
                if (this.cardElement && this.boundStartDrag) {
                    this.cardElement.removeEventListener('mousedown', this.boundStartDrag);
                    this.cardElement.removeEventListener('touchstart', this.boundStartDrag);
                }

                if (this.boundDrag) {
                    document.removeEventListener('mousemove', this.boundDrag);
                    document.removeEventListener('touchmove', this.boundDrag);
                }

                if (this.boundEndDrag) {
                    document.removeEventListener('mouseup', this.boundEndDrag);
                    document.removeEventListener('touchend', this.boundEndDrag);
                }

                // Reset state
                this.isDragging = false;
                this.currentX = 0;
                this.startX = 0;
            },

            startDrag(e) {
                if (isAnswering || !this.cardElement) return;
                this.isDragging = true;
                this.startX = (e.type === 'touchstart') ? e.touches[0].clientX : e.clientX;
                this.cardElement.style.transition = 'none';
            },

            drag(e) {
                if (!this.isDragging || !this.cardElement) return;
                let clientX = (e.type === 'touchmove') ? e.touches[0].clientX : e.clientX;
                this.currentX = clientX - this.startX;
                let rotate = this.currentX * 0.1;
                this.cardElement.style.transform = `translateX(${this.currentX}px) rotate(${rotate}deg)`;

                if (this.currentX > 0) {
                    document.getElementById('stamp-fact').style.opacity = Math.min(this.currentX / 100, 1);
                    document.getElementById('stamp-myth').style.opacity = 0;
                } else {
                    document.getElementById('stamp-myth').style.opacity = Math.min(Math.abs(this.currentX) / 100, 1);
                    document.getElementById('stamp-fact').style.opacity = 0;
                }
            },

            endDrag(e) {
                if (!this.isDragging || !this.cardElement) return;
                this.isDragging = false;
                this.cardElement.style.transition = 'transform 0.3s ease';

                if (this.currentX > 100) {
                    this.cardElement.style.transform = `translateX(1000px) rotate(30deg)`;
                    setTimeout(() => {
                        checkAnswer('true');
                    }, 300);
                } else if (this.currentX < -100) {
                    this.cardElement.style.transform = `translateX(-1000px) rotate(-30deg)`;
                    setTimeout(() => {
                        checkAnswer('false');
                    }, 300);
                } else {
                    this.resetPosition();
                }
                this.currentX = 0; // Reset currentX
            },

            resetPosition() {
                if (!this.cardElement) return;
                this.cardElement.style.transform = 'translateX(0px) rotate(0deg)';
                document.getElementById('stamp-fact').style.opacity = 0;
                document.getElementById('stamp-myth').style.opacity = 0;
            },

            trigger(direction) {
                if (isAnswering || !this.cardElement) return;
                this.cardElement.style.transition = 'transform 0.5s ease';
                if (direction === 'right') {
                    this.cardElement.style.transform = `translateX(1000px) rotate(30deg)`;
                    setTimeout(() => {
                        checkAnswer('true');
                    }, 500);
                } else {
                    this.cardElement.style.transform = `translateX(-1000px) rotate(-30deg)`;
                    setTimeout(() => {
                        checkAnswer('false');
                    }, 500);
                }
            }
        };

        // --- FUNCTIONS TO BE CALLED FROM HTML ---
        function triggerSwipe(direction) {
            swipeGame.trigger(direction);
        }

        // --- LOGIKA STORY MODE ---
        function showSlide(index) {
            slideElements.forEach(el => el.style.display = 'none');
            const activeSlide = document.getElementById('slide-' + index);
            if (activeSlide) {
                activeSlide.style.display = 'block';
                const imgElement = document.getElementById('dynamic-image');
                const defaultMascot = document.getElementById('default-mascot');
                let slideImageSrc = activeSlide.getAttribute('data-image');

                if (slideImageSrc && slideImageSrc.trim() !== "") {
                    imgElement.src = "{{ asset('storage') }}/" + slideImageSrc;
                    imgElement.style.display = 'inline-block';
                    defaultMascot.style.display = 'none';
                } else if (defaultImage) {
                    imgElement.src = defaultImage;
                    imgElement.style.display = 'inline-block';
                    defaultMascot.style.display = 'none';
                } else {
                    imgElement.style.display = 'none';
                    defaultMascot.style.display = 'inline-block';
                }
            }
            document.getElementById('btn-prev').style.display = index === 0 ? 'none' : 'inline-block';
            if (index >= totalSlides - 1) {
                document.getElementById('btn-next').style.display = 'none';
                document.getElementById('btn-quiz').style.display = 'inline-block';
            } else {
                document.getElementById('btn-next').style.display = 'inline-block';
                document.getElementById('btn-quiz').style.display = 'none';
            }
            let percent = Math.round(((index + 1) / totalSlides) * 100);
            document.getElementById('read-progress').style.width = percent + '%';
            document.getElementById('progress-text').innerText = percent + '%';
        }

        function nextSlide() {
            if (currentSlide < totalSlides - 1) {
                currentSlide++;
                showSlide(currentSlide);
            }
        }

        function prevSlide() {
            if (currentSlide > 0) {
                currentSlide--;
                showSlide(currentSlide);
            }
        }

        function enterQuizMode() {
            document.getElementById('story-mode').style.display = 'none'; // Sembunyikan Story
            document.getElementById('quiz-mode').style.display = 'block'; // Tampilkan Kuis
            startQuizGame();
        }
        // --- LOGIKA BARU: CEK MATERI ---
        if (totalSlides > 0) {
            // Jika ada slide, mulai Story Mode dari slide 0
            showSlide(0);
        } else {
            document.getElementById('story-mode').style.display = 'none';
            enterQuizMode();
        }

        // --- LOGIKA KUIS ---
        const sfxCorrect = new Audio('https://actions.google.com/sounds/v1/cartoon/clang_and_wobble.ogg');
        const sfxWrong = new Audio('https://actions.google.com/sounds/v1/cartoon/cartoon_boing.ogg');

        function startQuizGame() {
            if (!quizzes || quizzes.length === 0) {
                alert("Belum ada soal!");
                return;
            }
            showQuestion();
        }

        function showQuestion() {
            let q = quizzes[currentQIndex];
            isAnswering = false;

            // Sembunyikan semua layout dulu termasuk result screen
            document.getElementById('question-loading').style.display = 'none';
            document.getElementById('layout-multiple-choice').style.display = 'none';
            document.getElementById('layout-swipe').style.display = 'none';
            document.getElementById('layout-sequence').style.display = 'none';
            document.getElementById('result-screen').style.display = 'none'; // Pastikan result screen disembunyikan

            // Update Progress
            let progressPercent = ((currentQIndex) / quizzes.length) * 100;
            document.getElementById('quiz-progress-bar').style.width = progressPercent + "%";
            document.getElementById('quiz-status').innerText = "Soal " + (currentQIndex + 1) + " dari " + quizzes.length;

            // --- PILIH LAYOUT SESUAI TIPE ---
            if (q.type === 'sequence') {
                setupSequenceMode(q); // <--- Fungsi Baru
            } else if (q.type === 'true_false' || (!q.option_a && !q.option_b)) {
                setupSwipeMode(q);
            } else {
                setupMultipleChoiceMode(q);
            }
        }

        function setupMultipleChoiceMode(q) {
            document.getElementById('layout-multiple-choice').style.display = 'block';

            let imageUrl = q.image ? "{{ asset('storage') }}/" + q.image : null;
            const mcImg = document.getElementById('mc-image');
            if (imageUrl) {
                mcImg.src = imageUrl;
                mcImg.style.display = 'inline-block';
            } else {
                mcImg.style.display = 'none';
            }

            document.getElementById('mc-question-text').innerText = q.question;
            updateButton('btn-a', q.option_a);
            updateButton('btn-b', q.option_b);
            updateButton('btn-c', q.option_c);
            updateButton('btn-d', q.option_d);

            // RESET STYLE TOMBOL (FIXED)
            document.querySelectorAll('.answer-btn').forEach(btn => {
                btn.className = 'btn w-100 p-4 fs-5 text-start answer-btn'; // Reset ke class dasar
                btn.disabled = false;
            });
        }

        function setupSwipeMode(q) {
            document.getElementById('layout-swipe').style.display = 'block';
            let imageUrl = q.image ? "{{ asset('storage') }}/" + q.image : null;
            const swipeImg = document.getElementById('swipe-image');
            const noImgText = document.getElementById('swipe-no-image');

            if (imageUrl) {
                swipeImg.src = imageUrl;
                swipeImg.style.display = 'block';
                noImgText.style.display = 'none';
            } else {
                swipeImg.style.display = 'none';
                noImgText.style.display = 'block';
            }

            document.getElementById('swipe-question-text').innerText = q.question;
            
            // Reset kartu sebelum inisialisasi
            const cardElement = document.getElementById('swipe-card');
            if (cardElement) {
                cardElement.style.transform = 'translateX(0px) rotate(0deg)';
                cardElement.style.transition = 'none';
            }
            document.getElementById('stamp-fact').style.opacity = 0;
            document.getElementById('stamp-myth').style.opacity = 0;
            
            // Initialize the swipe game object
            swipeGame.init();
        }

        function updateButton(btnId, text) {
            const btn = document.getElementById(btnId);
            if (text && text.trim() !== "") {
                btn.innerText = text;
                btn.parentElement.style.display = 'block';
            } else {
                btn.parentElement.style.display = 'none';
            }
        }

        // --- CHECK ANSWER (FIXED COLOR LOGIC) ---
        function checkAnswer(choice) {
            if (isAnswering) return;
            isAnswering = true;

            let q = quizzes[currentQIndex];
            let correctAnswer = String(q.correct_answer).toLowerCase();
            let isCorrect = false;

            if (choice === correctAnswer) isCorrect = true;
            else if (choice === 'true' && (correctAnswer === 'a' || correctAnswer === 'true' || correctAnswer === 'benar'))
                isCorrect = true;
            else if (choice === 'false' && (correctAnswer === 'b' || correctAnswer === 'false' || correctAnswer ===
                    'salah')) isCorrect = true;

            if (isCorrect) {
                score += 10;
                sfxCorrect.play().catch(e => {});
            } else {
                sfxWrong.play().catch(e => {});
            }

            // VISUAL FEEDBACK TOMBOL (PENTING!)
            if (document.getElementById('layout-multiple-choice').style.display !== 'none') {
                let btn = document.getElementById('btn-' + choice);
                if (btn) {
                    // Hapus style default, tambah style baru
                    if (isCorrect) {
                        btn.classList.add('answer-correct');
                    } else {
                        btn.classList.add('answer-wrong');

                        // Highlight jawaban benar
                        if (['a', 'b', 'c', 'd'].includes(correctAnswer)) {
                            document.getElementById('btn-' + correctAnswer).classList.add('answer-correct');
                        }
                    }
                }
            }

            setTimeout(() => {
                isAnswering = false; // Reset flag
                currentQIndex++;
                if (currentQIndex < quizzes.length) {
                    showQuestion();
                } else {
                    showResult();
                }
            }, 1500);
        }

        // --- LOGIKA PUZZLE URUTAN (SEQUENCE) ---
        let sortableInstance = null;

        function setupSequenceMode(q) {
            document.getElementById('layout-sequence').style.display = 'block';
            document.getElementById('seq-question-text').innerText = q.question;

            const listEl = document.getElementById('sequence-list');
            listEl.innerHTML = ''; // Reset isi list

            // 1. Siapkan Data Item (A, B, C, D)
            let items = [{
                    id: 'a',
                    text: q.option_a,
                    image: q.option_a_image || null
                },
                {
                    id: 'b',
                    text: q.option_b,
                    image: q.option_b_image || null
                },
                {
                    id: 'c',
                    text: q.option_c,
                    image: q.option_c_image || null
                },
                {
                    id: 'd',
                    text: q.option_d,
                    image: q.option_d_image || null
                }
            ].filter(i => i.text); // Hanya ambil yang tidak kosong

            // 2. Acak Urutan (Shuffle) agar user menyusun ulang
            items = items.sort(() => Math.random() - 0.5);

            // 3. Render ke HTML dengan card style
            items.forEach((item, index) => {
                let li = document.createElement('li');
                li.className = 'seq-item';
                li.setAttribute('data-id', item.id); // ID ini kunci jawabannya
                
                // Siapkan gambar atau placeholder
                let imageHtml = '';
                if (item.image) {
                    let imageUrl = "{{ asset('storage') }}/" + item.image;
                    imageHtml = `<img src="${imageUrl}" alt="${item.text}" onerror="this.parentElement.innerHTML='<div class=\\'seq-image-placeholder\\'>📦</div>'">`;
                } else {
                    // Placeholder dengan emoji berdasarkan teks atau index
                    const getEmoji = (text, idx) => {
                        const textLower = text.toLowerCase();
                        if (textLower.includes('evaporasi') || textLower.includes('uap') || textLower.includes('air')) return '💨';
                        if (textLower.includes('kondensasi') || textLower.includes('awan')) return '☁️';
                        if (textLower.includes('presipitasi') || textLower.includes('hujan')) return '🌧️';
                        if (textLower.includes('langkah') || textLower.includes('step')) return '👣';
                        // Default emoji berdasarkan index
                        const defaults = ['1️⃣', '2️⃣', '3️⃣', '4️⃣', '🌊', '☁️', '💧', '🌧️'];
                        return defaults[idx % defaults.length];
                    };
                    imageHtml = `<div class="seq-image-placeholder">${getEmoji(item.text, index)}</div>`;
                }
                
                li.innerHTML = `
                    <div class="seq-item-content">
                        <div class="seq-drag-handle">
                            <i class="bi bi-grip-vertical"></i>
                        </div>
                        <div class="seq-image-container">
                            ${imageHtml}
                        </div>
                        <div class="seq-text">${item.text}</div>
                        <div class="seq-arrow">
                            <i class="bi bi-arrows-move"></i>
                        </div>
                    </div>
                `;
                listEl.appendChild(li);
            });

            // 4. Aktifkan SortableJS
            if (sortableInstance) sortableInstance.destroy(); // Hapus instance lama jika ada
            sortableInstance = new Sortable(listEl, {
                animation: 200,
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
                handle: '.seq-drag-handle' // Hanya bisa drag dari handle
            });
        }

        function checkSequenceAnswer() {
            if (isAnswering) return;

            // 1. Ambil urutan saat ini
            const listEl = document.getElementById('sequence-list');
            const currentOrder = Array.from(listEl.children).map(li => li.getAttribute('data-id'));

            // 2. Kunci Jawaban yang Benar (Selalu A -> B -> C -> D)
            // Kita cek apakah urutan arraynya ['a', 'b', 'c', 'd'] (sesuai jumlah item)
            const correctOrder = ['a', 'b', 'c', 'd'].slice(0, currentOrder.length);

            // 3. Bandingkan
            const isCorrect = JSON.stringify(currentOrder) === JSON.stringify(correctOrder);

            isAnswering = true;
            if (isCorrect) {
                score += 10;
                sfxCorrect.play().catch(e => {});

                // Efek Hijau dengan animasi
                Array.from(listEl.children).forEach((li, index) => {
                    setTimeout(() => {
                        li.style.background = 'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)';
                        li.style.borderColor = '#38ef7d';
                        li.style.boxShadow = '0 0 20px rgba(56, 239, 125, 0.5)';
                        li.style.transform = 'scale(1.02)';
                        li.style.color = '#fff';
                        // Update text color
                        const textEl = li.querySelector('.seq-text');
                        if (textEl) textEl.style.color = '#fff';
                    }, index * 100);
                });
            } else {
                sfxWrong.play().catch(e => {});

                // Efek Merah dengan animasi shake
                Array.from(listEl.children).forEach((li, index) => {
                    setTimeout(() => {
                        li.style.background = 'linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%)';
                        li.style.borderColor = '#ff4b2b';
                        li.style.boxShadow = '0 0 20px rgba(255, 75, 43, 0.5)';
                        li.style.animation = 'shake 0.5s';
                        li.style.color = '#fff';
                        // Update text color
                        const textEl = li.querySelector('.seq-text');
                        if (textEl) textEl.style.color = '#fff';
                    }, index * 50);
                });
            }

            // Lanjut soal berikutnya
            setTimeout(() => {
                isAnswering = false; // Reset flag
                currentQIndex++;
                if (currentQIndex < quizzes.length) {
                    showQuestion();
                } else {
                    showResult();
                }
            }, 2000); // Tunggu agak lama biar puas lihat hasilnya
        }

        // --- FINISH ---
        function showResult() {
            document.getElementById('layout-multiple-choice').style.display = 'none';
            document.getElementById('layout-swipe').style.display = 'none';
            document.getElementById('layout-sequence').style.display = 'none'; // Sembunyikan layout sequence juga
            document.getElementById('result-screen').style.display = 'block';
            document.getElementById('final-score').innerText = score;
            document.getElementById('quiz-progress-bar').style.width = "100%";
            document.getElementById('quiz-progress-bar').classList.add('bar-completed'); // Ubah warna jadi emas
            saveScoreToDatabase(score);
        }

        function saveScoreToDatabase(finalScore) {
            fetch("{{ route('score.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    topic_id: {{ $topic->id }},
                    score: finalScore
                })
            });
        }
    </script>
@endsection
