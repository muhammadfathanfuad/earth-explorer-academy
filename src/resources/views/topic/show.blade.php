@extends('layouts.app')

@section('content')
    <style>
        /* Background Animasi Bintang */
        .space-bg {
            background: radial-gradient(ellipse at bottom, #1b2735 0%, #090a0f 100%);
            overflow: hidden;
            position: relative;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.2);
        }

        .star {
            position: absolute;
            width: 2px;
            height: 2px;
            background: white;
            border-radius: 50%;
            animation: twinkle 2s infinite;
        }

        @keyframes twinkle {
            0% {
                opacity: 0;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }

        /* Mascot Floating */
        .mascot-float {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        /* Bubble Chat Style */
        .bubble-chat {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 2px solid #00d4ff;
            border-radius: 20px;
            border-top-left-radius: 0;
            position: relative;
        }

        .bubble-chat::before {
            content: "";
            position: absolute;
            left: -12px;
            top: 20px;
            border-top: 10px solid transparent;
            border-bottom: 10px solid transparent;
            border-right: 15px solid #00d4ff;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <div class="row justify-content-center">
        <div class="col-md-10">

            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-light mb-3">← Kembali ke Markas</a>

            <div id="story-mode" class="space-bg p-5 mb-5" style="min-height: 500px;">
                <div id="stars-container"></div>

                <div class="progress mb-4" style="height: 5px; background: rgba(255,255,255,0.1);">
                    <div id="read-progress" class="progress-bar bg-info" style="width: 0%"></div>
                </div>

                <div class="row align-items-center h-100">
                    <div class="col-md-4 text-center mb-4 mb-md-0">
                        <div class="mascot-float">
                            <img id="dynamic-image" src=""
                                class="img-fluid rounded-circle border border-4 border-info shadow-lg animate__animated"
                                style="width: 200px; height: 200px; object-fit: cover; display: none;">

                            <div id="default-mascot" style="font-size: 100px; display: none;">👨‍🚀</div>
                        </div>
                        <h4 class="mt-3 text-info fw-bold">{{ $topic->title }}</h4>
                    </div>

                    <div class="col-md-8">
                        <div class="bubble-chat p-4 text-white">

                            @foreach ($slides as $index => $slide)
                                <div class="story-slide" id="slide-{{ $index }}"
                                    data-image="{{ isset($slide['slide_image']) ? asset('storage/' . $slide['slide_image']) : '' }}"
                                    style="display: none;">

                                    <div class="fs-4 lh-lg mb-0 animate__animated animate__fadeIn story-content">
                                        {!! $slide['slide_text'] !!}
                                    </div>
                                </div>
                            @endforeach

                            <div class="d-flex justify-content-between mt-4">
                                <button onclick="prevSlide()" id="btn-prev"
                                    class="btn btn-outline-secondary rounded-pill px-4"
                                    style="display: none;">Mundur</button>

                                <button onclick="nextSlide()" id="btn-next"
                                    class="btn btn-sci-fi rounded-pill px-4 ms-auto">
                                    Lanjut <span class="ms-2">▶</span>
                                </button>

                                <button onclick="enterQuizMode()" id="btn-quiz"
                                    class="btn btn-warning fw-bold rounded-pill px-5 ms-auto animate__animated animate__pulse animate__infinite"
                                    style="display: none;">
                                    SIAP UJI NYALI? 🚀
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div id="quiz-mode" class="card card-custom border-info mb-5 animate__animated animate__zoomIn"
                style="display: none;">
                <div class="card-header bg-transparent border-bottom border-secondary py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="m-0 fw-bold text-warning">🚀 Kuis: {{ $topic->title }}</h3>
                        <span class="badge bg-primary fs-6">Skor: <span id="score-display">0</span></span>
                    </div>
                </div>

                <div class="card-body p-5 text-center">
                    <div id="question-screen">
                        <div class="progress mb-4" style="height: 5px;">
                            <div id="quiz-progress-bar" class="progress-bar bg-warning" style="width: 0%"></div>
                        </div>
                        <h3 class="mb-5 fw-bold text-white lh-base" id="question-text">Loading Soal...</h3>
                        <div class="row g-3 justify-content-center">
                            <div class="col-md-6"><button onclick="checkAnswer('a')" id="btn-a"
                                    class="btn btn-outline-light w-100 p-4 fs-5 text-start answer-btn h-100"></button></div>
                            <div class="col-md-6"><button onclick="checkAnswer('b')" id="btn-b"
                                    class="btn btn-outline-light w-100 p-4 fs-5 text-start answer-btn h-100"></button></div>
                            <div class="col-md-6"><button onclick="checkAnswer('c')" id="btn-c"
                                    class="btn btn-outline-light w-100 p-4 fs-5 text-start answer-btn h-100"></button></div>
                            <div class="col-md-6"><button onclick="checkAnswer('d')" id="btn-d"
                                    class="btn btn-outline-light w-100 p-4 fs-5 text-start answer-btn h-100"></button></div>
                        </div>
                    </div>

                    <div id="result-screen" style="display: none;">
                        <div class="display-1 mb-3">🏆</div>
                        <h2 class="fw-bold mb-3 text-white">Misi Selesai!</h2>
                        <p class="fs-4 text-white">Skor Akhir: <span id="final-score" class="text-warning fw-bold"></span>
                        </p>
                        <button onclick="location.reload()" class="btn btn-outline-light mt-3">Main Lagi</button>
                        <a href="{{ route('leaderboard') }}" class="btn btn-sci-fi mt-3 ms-2">Lihat Peringkat</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        // --- SETUP VISUAL BINTANG ---
        const starContainer = document.getElementById('stars-container');
        for (let i = 0; i < 50; i++) {
            let star = document.createElement('div');
            star.className = 'star';
            star.style.left = Math.random() * 100 + '%';
            star.style.top = Math.random() * 100 + '%';
            star.style.animationDelay = Math.random() * 2 + 's';
            starContainer.appendChild(star);
        }

        // --- LOGIKA STORY MODE (VERSI DOM - LEBIH STABIL) ---
        // Kita ambil elemen slide langsung dari HTML, tidak lewat JSON
        const slideElements = document.querySelectorAll('.story-slide');
        const totalSlides = slideElements.length;

        // Gambar Default
        const defaultImage = "{{ $topic->image ? asset('storage/' . $topic->image) : '' }}";

        let currentSlide = 0;

        function showSlide(index) {
            // Sembunyikan semua slide
            slideElements.forEach(el => el.style.display = 'none');

            // Ambil elemen slide aktif
            const activeSlide = document.getElementById('slide-' + index);

            if (activeSlide) {
                // Tampilkan Slide
                activeSlide.style.display = 'block';

                // Restart animasi teks
                const content = activeSlide.querySelector('.story-content');
                if (content) {
                    content.classList.remove('animate__fadeIn');
                    void content.offsetWidth;
                    content.classList.add('animate__fadeIn');
                }

                // --- UPDATE GAMBAR DINAMIS ---
                const imgElement = document.getElementById('dynamic-image');
                const mascotPlaceholder = document.getElementById('default-mascot');

                // Ambil URL gambar dari atribut data-image di HTML
                let slideImageSrc = activeSlide.getAttribute('data-image');

                if (slideImageSrc && slideImageSrc.trim() !== "") {
                    // KASUS 1: Ada gambar khusus di slide ini
                    imgElement.src = slideImageSrc;
                    imgElement.style.display = 'inline-block';
                    mascotPlaceholder.style.display = 'none';

                    // Efek Pop
                    imgElement.classList.remove('animate__zoomIn');
                    void imgElement.offsetWidth;
                    imgElement.classList.add('animate__zoomIn');
                } else if (defaultImage) {
                    // KASUS 2: Pakai cover default topik
                    imgElement.src = defaultImage;
                    imgElement.style.display = 'inline-block';
                    mascotPlaceholder.style.display = 'none';
                } else {
                    // KASUS 3: Tidak ada gambar sama sekali -> Emoji
                    imgElement.style.display = 'none';
                    mascotPlaceholder.style.display = 'block';
                }
            }

            // --- ATUR TOMBOL NAVIGASI ---
            document.getElementById('btn-prev').style.display = index === 0 ? 'none' : 'inline-block';

            if (index >= totalSlides - 1) {
                document.getElementById('btn-next').style.display = 'none';
                document.getElementById('btn-quiz').style.display = 'inline-block';
            } else {
                document.getElementById('btn-next').style.display = 'inline-block';
                document.getElementById('btn-quiz').style.display = 'none';
            }

            // Update Progress Bar
            let percent = ((index + 1) / totalSlides) * 100;
            document.getElementById('read-progress').style.width = percent + '%';
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
            document.getElementById('story-mode').classList.add('animate__animated', 'animate__fadeOutUp');
            setTimeout(() => {
                document.getElementById('story-mode').style.display = 'none';
                document.getElementById('quiz-mode').style.display = 'block';
                startGame();
            }, 800);
        }

        // Jalankan Slide Pertama
        if (totalSlides > 0) {
            showSlide(0);
        } else {
            // Fallback jika tidak ada slide
            document.querySelector('.col-md-8').innerHTML = '<p class="text-white text-center">Belum ada materi.</p>';
        }

        // --- LOGIKA QUIZ (SAMA SEPERTI SEBELUMNYA) ---
        const quizzes = @json($topic->quizzes);
        const sfxCorrect = new Audio('https://actions.google.com/sounds/v1/cartoon/clang_and_wobble.ogg');
        const sfxWrong = new Audio('https://actions.google.com/sounds/v1/cartoon/cartoon_boing.ogg');

        let currentQuestionIndex = 0;
        let score = 0;
        let isAnswering = false;

        function startGame() {
            if (quizzes.length === 0) {
                alert("Belum ada soal!");
                return;
            }
            showQuestion();
        }

        function showQuestion() {
            let q = quizzes[currentQuestionIndex];
            document.getElementById('question-text').innerText = q.question;
            document.getElementById('btn-a').innerText = q.option_a;
            document.getElementById('btn-b').innerText = q.option_b;
            document.getElementById('btn-c').innerText = q.option_c;
            document.getElementById('btn-d').innerText = q.option_d;
            document.querySelectorAll('.answer-btn').forEach(btn => {
                btn.className = 'btn btn-outline-light w-100 p-4 fs-5 text-start answer-btn h-100';
                btn.disabled = false;
            });
            document.getElementById('quiz-progress-bar').style.width = ((currentQuestionIndex) / quizzes.length * 100) +
            "%";
            isAnswering = false;
        }

        function checkAnswer(userChoice) {
            if (isAnswering) return;
            isAnswering = true;
            let q = quizzes[currentQuestionIndex];
            let correctBtnId = 'btn-' + q.correct_answer;
            let userBtnId = 'btn-' + userChoice;

            if (userChoice === q.correct_answer) {
                sfxCorrect.currentTime = 0;
                sfxCorrect.play();
                document.getElementById(userBtnId).classList.add('btn-success', 'animate__animated', 'animate__tada');
                score += 10;
            } else {
                sfxWrong.currentTime = 0;
                sfxWrong.play();
                document.getElementById(userBtnId).classList.add('btn-danger', 'animate__animated', 'animate__shakeX');
                document.getElementById(correctBtnId).classList.add('btn-success');
            }
            document.getElementById('score-display').innerText = score;

            setTimeout(() => {
                currentQuestionIndex++;
                if (currentQuestionIndex < quizzes.length) {
                    showQuestion();
                } else {
                    showResult();
                }
            }, 1500);
        }

        function showResult() {
            document.getElementById('question-screen').style.display = 'none';
            document.getElementById('result-screen').style.display = 'block';
            document.getElementById('final-score').innerText = score;
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
