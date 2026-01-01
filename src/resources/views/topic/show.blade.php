@extends('layouts.app')

@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/topic-show.css') }}">

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

    {{-- Pass data dari Blade ke JavaScript --}}
    <script>
        // Data yang perlu di-pass dari Blade ke JS
        window.topicData = {
            quizzes: @json($topic->quizzes ?? []),
            defaultImage: "{{ $topic->image ? asset('storage/' . $topic->image) : '' }}",
            storagePath: "{{ asset('storage') }}",
            scoreStoreRoute: "{{ route('score.store') }}",
            topicId: {{ $topic->id }},
            csrfToken: "{{ csrf_token() }}"
        };
    </script>
    <script src="{{ asset('js/topic-show.js') }}"></script>
@endsection
