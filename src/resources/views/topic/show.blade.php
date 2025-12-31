@extends('layouts.app')

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

        /* --- 2. CONTAINER UTAMA (KACA) --- */
        .space-container {
            position: relative;
            border-radius: 30px;
            /* overflow: hidden;  <-- HAPUS INI AGAR KARTU SWIPE TIDAK KEPOTONG */
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.5);
            background: linear-gradient(180deg, #162238 0%, #0a0f1c 100%);
            border: 2px solid rgba(255, 255, 255, 0.1);
            min-height: 600px;
        }

        /* Container khusus Story agar bintang tidak keluar */
        .story-wrapper {
            position: relative;
            border-radius: 30px;
            overflow: hidden;
            /* Hanya story yang di-crop */
            height: 100%;
            min-height: 600px;
        }

        /* Efek Bintang */
        .stars-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("https://www.transparenttextures.com/patterns/stardust.png");
            opacity: 0.6;
            animation: moveStars 100s linear infinite;
            pointer-events: none;
        }

        @keyframes moveStars {
            from {
                background-position: 0 0;
            }

            to {
                background-position: 1000px 1000px;
            }
        }

        /* --- 3. QUESTION BOX (MODEL BARU: LEBIH FRIENDLY) --- */
        .question-card {
            /* Ganti background putih jadi Gradasi Biru Malam */
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            border-radius: 25px;
            padding: 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
            border: 2px solid rgba(255, 255, 255, 0.1);
            position: relative;
            margin-bottom: 30px;
            color: #fff;
            /* Teks Putih */
        }

        .question-text {
            font-family: 'Fredoka', sans-serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: #fff;
            /* Pastikan teks pertanyaan putih */
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
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
                                    INSTRUKTUR MISI
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
                                    style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                <span id="swipe-no-image" class="text-muted small fw-bold">Mode Hologram Aktif</span>
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
        const slideElements = document.querySelectorAll('.story-slide');
        const totalSlides = slideElements.length;
        const defaultImage = "{{ $topic->image ? asset('storage/' . $topic->image) : '' }}";
        const quizzes = @json($topic->quizzes ?? []);

        let currentSlide = 0;
        let currentQIndex = 0;
        let score = 0;
        let isAnswering = false;

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
            document.getElementById('story-mode').style.display = 'none';
            document.getElementById('quiz-mode').style.display = 'block';
            startQuizGame();
        }
        if (totalSlides > 0) showSlide(0);

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
            document.getElementById('question-loading').style.display = 'none';
            document.getElementById('layout-multiple-choice').style.display = 'none';
            document.getElementById('layout-swipe').style.display = 'none';

            // Update Progress & Text
            let progressPercent = ((currentQIndex) / quizzes.length) * 100;
            document.getElementById('quiz-progress-bar').style.width = progressPercent + "%";
            document.getElementById('quiz-status').innerText = "Soal " + (currentQIndex + 1) + " dari " + quizzes.length;

            let isSwipeMode = (q.type === 'true_false') || (!q.option_a && !q.option_b);

            if (isSwipeMode) setupSwipeMode(q);
            else setupMultipleChoiceMode(q);
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
            resetCardPosition();
            initSwipeListeners();
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
                currentQIndex++;
                if (currentQIndex < quizzes.length) showQuestion();
                else showResult();
            }, 1500);
        }

        // --- SWIPE LOGIC ---
        const card = document.getElementById('swipe-card');
        let startX = 0,
            currentX = 0,
            isDragging = false;

        function initSwipeListeners() {
            card.addEventListener('mousedown', startDrag);
            card.addEventListener('touchstart', startDrag);
            document.addEventListener('mousemove', drag);
            document.addEventListener('touchmove', drag);
            document.addEventListener('mouseup', endDrag);
            document.addEventListener('touchend', endDrag);
        }

        function startDrag(e) {
            if (isAnswering) return;
            isDragging = true;
            startX = (e.type === 'touchstart') ? e.touches[0].clientX : e.clientX;
            card.style.transition = 'none';
        }

        function drag(e) {
            if (!isDragging) return;
            let clientX = (e.type === 'touchmove') ? e.touches[0].clientX : e.clientX;
            currentX = clientX - startX;
            let rotate = currentX * 0.1;
            card.style.transform = `translateX(${currentX}px) rotate(${rotate}deg)`;

            if (currentX > 0) {
                document.getElementById('stamp-fact').style.opacity = Math.min(currentX / 100, 1);
                document.getElementById('stamp-myth').style.opacity = 0;
            } else {
                document.getElementById('stamp-myth').style.opacity = Math.min(Math.abs(currentX) / 100, 1);
                document.getElementById('stamp-fact').style.opacity = 0;
            }
        }

        function endDrag(e) {
            if (!isDragging) return;
            isDragging = false;
            card.style.transition = 'transform 0.3s ease';
            if (currentX > 100) {
                card.style.transform = `translateX(1000px) rotate(30deg)`;
                checkAnswer('true');
            } else if (currentX < -100) {
                card.style.transform = `translateX(-1000px) rotate(-30deg)`;
                checkAnswer('false');
            } else {
                resetCardPosition();
            }
        }

        function resetCardPosition() {
            card.style.transform = 'translateX(0px) rotate(0deg)';
            document.getElementById('stamp-fact').style.opacity = 0;
            document.getElementById('stamp-myth').style.opacity = 0;
        }

        function triggerSwipe(direction) {
            if (isAnswering) return;
            card.style.transition = 'transform 0.5s ease';
            if (direction === 'right') {
                card.style.transform = `translateX(1000px) rotate(30deg)`;
                checkAnswer('true');
            } else {
                card.style.transform = `translateX(-1000px) rotate(-30deg)`;
                checkAnswer('false');
            }
        }

        // --- FINISH ---
        function showResult() {
            document.getElementById('layout-multiple-choice').style.display = 'none';
            document.getElementById('layout-swipe').style.display = 'none';
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
