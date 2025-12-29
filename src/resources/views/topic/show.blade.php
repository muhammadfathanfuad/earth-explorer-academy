@extends('layouts.app')

@section('content')
    <style>
        /* --- STYLE UMUM --- */
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

        /* Bubble Chat */
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

        /* --- STYLE KHUSUS SWIPE CARD (MITOS vs FAKTA) --- */
        /* --- STYLE KHUSUS GAME CARD (RE-DESIGN) --- */
        .tinder-card {
            background: linear-gradient(180deg, #ffffff 0%, #f0f8ff 100%);
            /* Gradasi halus */
            border: 4px solid #ffffff;
            border-radius: 30px;
            /* Sudut sangat bulat */
            box-shadow:
                0 10px 25px rgba(0, 0, 0, 0.2),
                /* Bayangan utama */
                0 -5px 0px rgba(0, 0, 0, 0.02) inset;
            /* Highlight dalam */
            height: 500px;
            /* Sedikit lebih tinggi */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            cursor: grab;
            transition: transform 0.1s, box-shadow 0.2s;
            user-select: none;
            overflow: visible;
            /* Biar ornamen bisa keluar kotak */
        }

        .tinder-card:active {
            cursor: grabbing;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            /* Efek ditekan */
        }

        /* Ikon Tanya Mengambang */
        .floating-icon {
            position: absolute;
            top: -40px;
            background: #ffcc00;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            border: 4px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            animation: floatIcon 3s ease-in-out infinite;
            z-index: 20;
        }

        @keyframes floatIcon {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        /* Indikator Arah (Kiri/Kanan) */
        .swipe-hint {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-weight: bold;
            font-size: 14px;
            opacity: 0.3;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .hint-left {
            left: 15px;
            color: #e74c3c;
        }

        .hint-right {
            right: 15px;
            color: #2ecc71;
        }

        .hint-arrow {
            font-size: 24px;
            margin-bottom: 5px;
        }

        /* Badge Stempel (Dipercantik) */
        .stamp-badge {
            position: absolute;
            top: 60px;
            /* Turunkan sedikit */
            padding: 10px 30px;
            border: 5px solid;
            border-radius: 15px;
            font-size: 35px;
            /* Lebih besar */
            font-weight: 800;
            /* Lebih tebal */
            text-transform: uppercase;
            opacity: 0;
            z-index: 10;
            background: rgba(255, 255, 255, 0.9);
            /* Background semi transparan */
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            font-family: 'Black Ops One', cursive;
            /* Font stempel (opsional, fallback ke sans-serif) */
        }

        .stamp-fact {
            border-color: #2ecc71;
            color: #2ecc71;
            transform: rotate(-15deg) scale(1.2);
        }

        .stamp-myth {
            border-color: #e74c3c;
            color: #e74c3c;
            transform: rotate(15deg) scale(1.2);
        }

        /* Tombol Kontrol Bawah */
        .swipe-actions .btn-action {
            width: 70px;
            height: 70px;
            /* Lebih besar */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            border: 4px solid white;
            transition: transform 0.2s;
        }

        .btn-myth {
            background: linear-gradient(135deg, #ff7675, #d63031);
            color: white;
        }

        .btn-fact {
            background: linear-gradient(135deg, #55efc4, #00b894);
            color: white;
        }

        .swipe-actions .btn-action:hover {
            transform: scale(1.1) rotate(5deg);
        }

        .swipe-actions .btn-action:active {
            transform: scale(0.95);
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
                                class="img-fluid rounded-circle border border-4 border-info shadow-lg"
                                style="width: 200px; height: 200px; object-fit: cover; display: none;">
                            <div id="default-mascot" style="font-size: 100px; display: none;">👨‍🚀</div>
                        </div>
                        <h4 class="mt-3 text-info fw-bold">{{ $topic->title }}</h4>
                    </div>
                    <div class="col-md-8">
                        <div class="bubble-chat p-4 text-white">
                            @foreach ($slides as $index => $slide)
                                <div class="story-slide" id="slide-{{ $index }}"
                                    data-image="{{ $slide['slide_image'] ?? '' }}" style="display: none;">
                                    <div class="fs-4 lh-lg mb-0 animate__animated animate__fadeIn story-content">
                                        {!! $slide['slide_text'] !!}</div>
                                </div>
                            @endforeach
                            <div class="d-flex justify-content-between mt-4">
                                <button onclick="prevSlide()" id="btn-prev"
                                    class="btn btn-outline-secondary rounded-pill px-4"
                                    style="display: none;">Mundur</button>
                                <button onclick="nextSlide()" id="btn-next"
                                    class="btn btn-sci-fi rounded-pill px-4 ms-auto">Lanjut <span
                                        class="ms-2">▶</span></button>
                                <button onclick="enterQuizMode()" id="btn-quiz"
                                    class="btn btn-warning fw-bold rounded-pill px-5 ms-auto animate__animated animate__pulse animate__infinite"
                                    style="display: none;">SIAP UJI NYALI? 🚀</button>
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

                <div class="card-body p-4 p-md-5 text-center">
                    <div class="progress mb-4" style="height: 5px;">
                        <div id="quiz-progress-bar" class="progress-bar bg-warning" style="width: 0%"></div>
                    </div>

                    <div id="question-loading">Loading Soal...</div>

                    <div id="layout-multiple-choice" style="display: none;">
                        <div class="mb-3 text-center">
                            <img id="mc-image" src="" class="img-fluid rounded-3 shadow-sm border border-secondary"
                                style="max-height: 250px; object-fit: contain; display: none;">
                        </div>
                        <h3 class="mb-4 fw-bold text-white lh-base" id="mc-question-text">...</h3>
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

                    <div id="layout-swipe" style="display: none;">

                        <h4 class="text-info mb-4 fw-bold" style="text-shadow: 0 2px 4px rgba(0,0,0,0.5);">
                            ✨ MITOS ATAU FAKTA? ✨
                        </h4>

                        <div class="d-flex justify-content-center position-relative" style="margin-top: 40px;">
                            <div id="swipe-card" class="tinder-card p-4 col-md-6 col-11">

                                <div class="floating-icon">🤔</div>

                                <div class="swipe-hint hint-left animate__animated animate__pulse animate__infinite">
                                    <span class="hint-arrow">⬅</span>
                                    <span>MITOS</span>
                                </div>
                                <div class="swipe-hint hint-right animate__animated animate__pulse animate__infinite">
                                    <span class="hint-arrow">➡</span>
                                    <span>FAKTA</span>
                                </div>

                                <div class="stamp-badge stamp-fact" id="stamp-fact">FAKTA!</div>
                                <div class="stamp-badge stamp-myth" id="stamp-myth">MITOS!</div>

                                <div class="mt-3 mb-2"
                                    style="width: 100%; height: 180px; overflow: hidden; border-radius: 15px; display: none;"
                                    id="swipe-image-container">
                                    <img id="swipe-image" src=""
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <div class="mt-4">
                                    <h2 class="fw-bold mb-3 text-dark lh-sm" id="swipe-question-text"
                                        style="font-family: 'Fredoka', sans-serif;">...</h2>
                                    <p class="text-secondary small mt-3">Geser kartu ke jawabanmu!</p>
                                </div>
                            </div>
                        </div>

                        <div class="swipe-actions d-flex justify-content-center gap-5 mt-5">
                            <button onclick="triggerSwipe('left')" class="btn btn-myth btn-action">
                                ✖
                            </button>
                            <button onclick="triggerSwipe('right')" class="btn btn-fact btn-action">
                                ✔
                            </button>
                        </div>
                    </div>

                    <div id="result-screen" style="display: none;">
                        <div class="display-1 mb-3">🏆</div>
                        <h2 class="fw-bold mb-3 text-white">Misi Selesai!</h2>
                        <p class="fs-4 text-white">Skor Akhir: <span id="final-score"
                                class="text-warning fw-bold"></span></p>
                        <button onclick="location.reload()" class="btn btn-outline-light mt-3">Main Lagi</button>
                        <a href="{{ route('leaderboard') }}" class="btn btn-sci-fi mt-3 ms-2">Lihat Peringkat</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        // --- 1. SETUP LOGIKA STORY MODE (SAMA SEPERTI SEBELUMNYA) ---
        const starContainer = document.getElementById('stars-container');
        for (let i = 0; i < 50; i++) {
            let star = document.createElement('div');
            star.className = 'star';
            star.style.left = Math.random() * 100 + '%';
            star.style.top = Math.random() * 100 + '%';
            star.style.animationDelay = Math.random() * 2 + 's';
            starContainer.appendChild(star);
        }

        const slideElements = document.querySelectorAll('.story-slide');
        const totalSlides = slideElements.length;
        const defaultImage = "{{ $topic->image ? asset('storage/' . $topic->image) : '' }}";
        let currentSlide = 0;

        function showSlide(index) {
            slideElements.forEach(el => el.style.display = 'none');
            const activeSlide = document.getElementById('slide-' + index);
            if (activeSlide) {
                activeSlide.style.display = 'block';
                const imgElement = document.getElementById('dynamic-image');
                const mascotPlaceholder = document.getElementById('default-mascot');
                let slideImageSrc = activeSlide.getAttribute('data-image');

                if (slideImageSrc && slideImageSrc.trim() !== "") {
                    imgElement.src = slideImageSrc;
                    imgElement.style.display = 'inline-block';
                    mascotPlaceholder.style.display = 'none';
                } else if (defaultImage) {
                    imgElement.src = defaultImage;
                    imgElement.style.display = 'inline-block';
                    mascotPlaceholder.style.display = 'none';
                } else {
                    imgElement.style.display = 'none';
                    mascotPlaceholder.style.display = 'block';
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
            document.getElementById('story-mode').style.display = 'none';
            document.getElementById('quiz-mode').style.display = 'block';
            startQuizGame();
        }
        if (totalSlides > 0) showSlide(0);
        else document.querySelector('.col-md-8').innerHTML = '<p>Belum ada materi.</p>';


        // --- 2. SETUP LOGIKA HYBRID QUIZ ---
        const quizzes = @json($topic->quizzes);
        const sfxCorrect = new Audio('https://actions.google.com/sounds/v1/cartoon/clang_and_wobble.ogg');
        const sfxWrong = new Audio('https://actions.google.com/sounds/v1/cartoon/cartoon_boing.ogg');

        let currentQIndex = 0;
        let score = 0;
        let isAnswering = false;

        function startQuizGame() {
            if (quizzes.length === 0) {
                alert("Belum ada soal!");
                return;
            }
            showQuestion();
        }

        function showQuestion() {
            let q = quizzes[currentQIndex];
            isAnswering = false;

            // Reset Tampilan
            document.getElementById('layout-multiple-choice').style.display = 'none';
            document.getElementById('layout-swipe').style.display = 'none';
            document.getElementById('question-loading').style.display = 'none';
            document.getElementById('quiz-progress-bar').style.width = ((currentQIndex) / quizzes.length * 100) + "%";

            // --- LOGIKA GAMBAR (BARU) ---
            let imageUrl = q.image ? "{{ asset('storage') }}/" + q.image : null;

            // --- CEK TIPE SOAL ---
            if (q.type === 'true_false') {
                // MODE SWIPE
                document.getElementById('layout-swipe').style.display = 'block';
                document.getElementById('swipe-question-text').innerText = q.question;

                // Set Gambar Swipe
                const swipeImgContainer = document.getElementById('swipe-image-container');
                const swipeImg = document.getElementById('swipe-image');

                if (imageUrl) {
                    swipeImg.src = imageUrl;
                    swipeImgContainer.style.display = 'block';
                } else {
                    swipeImgContainer.style.display = 'none'; // Sembunyikan kalau tidak ada gambar
                }

                resetCardPosition();
                initSwipeCard();
            } else {
                // MODE PILIHAN GANDA
                document.getElementById('layout-multiple-choice').style.display = 'block';
                document.getElementById('mc-question-text').innerText = q.question;

                // Set Gambar MC
                const mcImg = document.getElementById('mc-image');
                if (imageUrl) {
                    mcImg.src = imageUrl;
                    mcImg.style.display = 'inline-block';
                } else {
                    mcImg.style.display = 'none';
                }

                // Set Tombol
                document.getElementById('btn-a').innerText = q.option_a;
                document.getElementById('btn-b').innerText = q.option_b;
                document.getElementById('btn-c').innerText = q.option_c;
                document.getElementById('btn-d').innerText = q.option_d;

                // Reset warna tombol... (kode lama)
                document.querySelectorAll('.answer-btn').forEach(btn => {
                    btn.className = 'btn btn-outline-light w-100 p-4 fs-5 text-start answer-btn h-100';
                    btn.disabled = false;
                });
            }
        }

        // --- LOGIKA JAWABAN (UMUM) ---
        function handleAnswer(userAnswer) {
            if (isAnswering) return;
            isAnswering = true;
            let q = quizzes[currentQIndex];

            let isCorrect = (userAnswer === q.correct_answer);

            if (isCorrect) {
                sfxCorrect.currentTime = 0;
                sfxCorrect.play();
                score += 10;
            } else {
                sfxWrong.currentTime = 0;
                sfxWrong.play();
            }
            document.getElementById('score-display').innerText = score;

            // Visual Feedback (Beda tiap mode)
            if (q.type === 'multiple_choice') {
                let correctBtnId = 'btn-' + q.correct_answer;
                let userBtnId = 'btn-' + userAnswer;
                if (isCorrect) {
                    document.getElementById(userBtnId).classList.add('btn-success', 'animate__animated', 'animate__tada');
                } else {
                    document.getElementById(userBtnId).classList.add('btn-danger', 'animate__animated', 'animate__shakeX');
                    document.getElementById(correctBtnId).classList.add('btn-success');
                }
            } else {
                // Visual Feedback Swipe sudah terjadi di animasi kartu
            }

            // Lanjut Soal Berikutnya
            setTimeout(() => {
                currentQIndex++;
                if (currentQIndex < quizzes.length) {
                    showQuestion();
                } else {
                    showResult();
                }
            }, 1500);
        }

        // --- LOGIKA KHUSUS PILIHAN GANDA ---
        function checkAnswer(choice) {
            handleAnswer(choice);
        }

        // --- LOGIKA KHUSUS SWIPE CARD (TOUCH & DRAG) ---
        const card = document.getElementById('swipe-card');
        let startX = 0,
            currentX = 0,
            isDragging = false;

        function initSwipeCard() {
            // Support Mouse & Touch
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
            card.style.transition = 'none'; // Matikan transisi biar responsif
        }

        function drag(e) {
            if (!isDragging) return;
            e.preventDefault(); // Cegah scroll layar HP
            let clientX = (e.type === 'touchmove') ? e.touches[0].clientX : e.clientX;
            currentX = clientX - startX;

            // Rotasi kartu sedikit saat digeser
            let rotate = currentX * 0.1;
            card.style.transform = `translateX(${currentX}px) rotate(${rotate}deg)`;

            // Tampilkan Stempel (Opacity)
            if (currentX > 0) { // Geser Kanan (Fakta)
                document.getElementById('stamp-fact').style.opacity = Math.min(currentX / 100, 1);
                document.getElementById('stamp-myth').style.opacity = 0;
                card.style.borderColor = '#2ecc71';
            } else { // Geser Kiri (Mitos)
                document.getElementById('stamp-myth').style.opacity = Math.min(Math.abs(currentX) / 100, 1);
                document.getElementById('stamp-fact').style.opacity = 0;
                card.style.borderColor = '#e74c3c';
            }
        }

        function endDrag(e) {
            if (!isDragging) return;
            isDragging = false;
            card.style.transition = 'transform 0.3s ease'; // Hidupkan transisi lagi

            // Cek Threshold (Apakah user menggeser cukup jauh?)
            if (currentX > 100) {
                // Swipe Kanan -> Jawab "true" (FAKTA)
                card.style.transform = `translateX(1000px) rotate(30deg)`; // Lempar kartu keluar
                handleAnswer('true');
            } else if (currentX < -100) {
                // Swipe Kiri -> Jawab "false" (MITOS)
                card.style.transform = `translateX(-1000px) rotate(-30deg)`; // Lempar kartu keluar
                handleAnswer('false');
            } else {
                // Batal Geser (Kembali ke tengah)
                resetCardPosition();
            }
        }

        function resetCardPosition() {
            card.style.transform = 'translateX(0px) rotate(0deg)';
            document.getElementById('stamp-fact').style.opacity = 0;
            document.getElementById('stamp-myth').style.opacity = 0;
            card.style.borderColor = 'transparent';
        }

        // Tombol Manual untuk Swipe (Versi Desktop/Klik)
        function triggerSwipe(direction) {
            if (isAnswering) return;
            card.style.transition = 'transform 0.5s ease';

            if (direction === 'right') { // Fakta
                card.style.transform = `translateX(1000px) rotate(30deg)`;
                handleAnswer('true');
            } else { // Mitos
                card.style.transform = `translateX(-1000px) rotate(-30deg)`;
                handleAnswer('false');
            }
        }

        // --- FINISH ---
        function showResult() {
            document.getElementById('layout-multiple-choice').style.display = 'none';
            document.getElementById('layout-swipe').style.display = 'none';
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
