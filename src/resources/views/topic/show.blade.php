@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        /* --- 1. SETTING FONT & WARNA DASAR --- */
        :root {
            --neon-cyan: #00f3ff;
            --neon-pink: #bc13fe;
            --glass-bg: rgba(12, 20, 40, 0.75); /* Gelap Transparan */
            --glass-border: 1px solid rgba(0, 243, 255, 0.3);
        }

        /* --- 2. BACKGROUND & ORNAMEN --- */
        .space-container {
            position: relative;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.5);
            /* Background Lapis (Gradient + Pattern) */
            background: 
                radial-gradient(circle at top right, rgba(188, 19, 254, 0.15), transparent 40%),
                radial-gradient(circle at bottom left, rgba(0, 243, 255, 0.1), transparent 40%),
                linear-gradient(180deg, #0f172a 0%, #020617 100%);
            border: var(--glass-border);
        }

        /* Efek Bintang Bergerak */
        .stars-overlay {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background-image: url("https://www.transparenttextures.com/patterns/stardust.png");
            opacity: 0.6;
            animation: moveStars 100s linear infinite;
            z-index: 0;
            pointer-events: none;
        }
        @keyframes moveStars { from { background-position: 0 0; } to { background-position: 1000px 1000px; } }

        /* --- 3. BUBBLE CHAT (MODIFIKASI HOLOGRAM) --- */
        .hologram-box {
            background: rgba(255, 255, 255, 0.05); /* Sangat bening */
            backdrop-filter: blur(15px); /* Efek Kaca */
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-left: 5px solid var(--neon-cyan); /* Aksen Neon di Kiri */
            border-radius: 20px;
            color: #e2e8f0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            position: relative;
        }
        
        /* Segitiga panah hologram */
        .hologram-box::before {
            content: ""; position: absolute;
            left: -15px; top: 40px;
            border-top: 10px solid transparent;
            border-bottom: 10px solid transparent;
            border-right: 15px solid var(--neon-cyan);
            filter: drop-shadow(0 0 5px var(--neon-cyan));
        }

        /* --- 4. MASCOT ANIMATION --- */
        .mascot-glow {
            filter: drop-shadow(0 0 20px rgba(0, 243, 255, 0.4));
            animation: floatMascot 4s ease-in-out infinite;
        }
        @keyframes floatMascot {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        /* --- 5. PROGRESS BAR --- */
        .neon-progress {
            background: rgba(255,255,255,0.1);
            height: 8px;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .neon-bar {
            background: linear-gradient(90deg, var(--neon-cyan), var(--neon-pink));
            box-shadow: 0 0 10px var(--neon-cyan);
            transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* --- 6. BUTTONS --- */
        .btn-hologram {
            background: rgba(0, 243, 255, 0.1);
            border: 1px solid var(--neon-cyan);
            color: var(--neon-cyan);
            font-family: 'Rajdhani', sans-serif;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
        }
        .btn-hologram:hover {
            background: var(--neon-cyan);
            color: #000;
            box-shadow: 0 0 20px var(--neon-cyan);
            transform: scale(1.05);
        }
        
        .btn-start-quiz {
            background: linear-gradient(45deg, #ff00cc, #333399);
            border: none; color: white;
            box-shadow: 0 0 15px rgba(255, 0, 204, 0.5);
            animation: pulseBtn 2s infinite;
        }
        @keyframes pulseBtn { 0% { box-shadow: 0 0 0 0 rgba(255, 0, 204, 0.7); } 70% { box-shadow: 0 0 0 15px rgba(255, 0, 204, 0); } 100% { box-shadow: 0 0 0 0 rgba(255, 0, 204, 0); } }

        /* Teks Judul Keren */
        .neon-title {
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            color: white;
            text-shadow: 0 0 10px rgba(0, 243, 255, 0.8);
        }
    </style>

    <div class="row justify-content-center">
        <div class="col-md-10">

            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-light mb-4 rounded-pill px-3 border-opacity-50">
                <i class="bi bi-arrow-left"></i> Kembali ke Markas
            </a>

            <div id="story-mode" class="space-container p-4 p-md-5 mb-5" style="min-height: 550px;">
                <div class="stars-overlay"></div>

                <div class="d-flex justify-content-between align-items-center mb-2 text-white-50 small">
                    <span>STATUS BRIEFING</span>
                    <span id="progress-text">0%</span>
                </div>
                <div class="neon-progress mb-5">
                    <div id="read-progress" class="neon-bar h-100" style="width: 0%"></div>
                </div>

                <div class="row align-items-center h-100 position-relative" style="z-index: 2;">
                    
                    <div class="col-md-4 text-center mb-5 mb-md-0">
                        <div class="mascot-glow">
                            <img id="dynamic-image" src="" 
                                 class="rounded-circle border border-3 border-info shadow-lg bg-dark" 
                                 style="width: 200px; height: 200px; object-fit: cover; display: none;">
                            <div id="default-mascot" style="font-size: 140px; display: none; filter: drop-shadow(0 0 10px rgba(255,255,255,0.5));">👨‍🚀</div>
                        </div>
                        
                        <div class="mt-4">
                            <span class="badge bg-black border border-info text-info rounded-pill px-4 py-2 shadow-lg">
                                INSTRUKTUR MISI
                            </span>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="hologram-box p-4 p-lg-5">
                            
                            @foreach ($slides as $index => $slide)
                                <div class="story-slide animate__animated animate__fadeIn" id="slide-{{ $index }}" 
                                     data-image="{{ $slide['slide_image'] ?? '' }}" 
                                     style="display: none;">
                                    
                                    <h2 class="neon-title mb-3 fs-3">
                                        {{ $slide['title'] ?? 'Briefing Bagian ' . ($index + 1) }}
                                    </h2>
                                    
                                    <div class="fs-5 lh-lg text-light fw-light">
                                        {!! $slide['slide_text'] ?? $slide['content'] ?? '' !!}
                                    </div>
                                </div>
                            @endforeach

                            <div class="d-flex justify-content-between align-items-center mt-5 pt-3 border-top border-secondary border-opacity-25">
                                <button onclick="prevSlide()" id="btn-prev" class="btn btn-outline-light rounded-pill px-4" style="display: none;">
                                    ← Mundur
                                </button>
                                
                                <button onclick="nextSlide()" id="btn-next" class="btn btn-hologram rounded-pill px-5 py-2 fw-bold ms-auto">
                                    Lanjut Membaca →
                                </button>
                                
                                <button onclick="enterQuizMode()" id="btn-quiz" class="btn btn-start-quiz rounded-pill px-5 py-3 fw-bold ms-auto" style="display: none;">
                                    🚀 MULAI TANTANGAN
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div id="quiz-mode" class="card space-container border-0 mb-5 animate__animated animate__zoomIn" style="display: none;">
                <div class="card-header bg-transparent border-bottom border-secondary py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="m-0 fw-bold neon-title">🚀 UJI KOMPETENSI</h3>
                        <span class="badge bg-warning text-dark fs-6 rounded-pill">Skor: <span id="score-display">0</span></span>
                    </div>
                </div>

                <div class="card-body p-5 text-center position-relative" style="z-index: 2;">
                    <div class="neon-progress mb-4" style="height: 5px;">
                        <div id="quiz-progress-bar" class="neon-bar bg-warning" style="width: 0%"></div>
                    </div>

                    <div id="question-loading" class="text-white">Menghubungkan ke Pusat Data...</div>

                    <div id="layout-multiple-choice" style="display: none;">
                        <div class="mb-4 text-center">
                            <img id="mc-image" src="" class="img-fluid rounded-3 shadow border border-info" style="max-height: 250px; object-fit: contain; display: none;">
                        </div>
                        <h3 class="mb-5 fw-bold text-white lh-base" id="mc-question-text">...</h3>
                        <div class="row g-3 justify-content-center">
                            <div class="col-md-6"><button onclick="checkAnswer('a')" id="btn-a" class="btn btn-outline-light w-100 p-4 fs-5 text-start answer-btn h-100 rounded-4 border-opacity-50"></button></div>
                            <div class="col-md-6"><button onclick="checkAnswer('b')" id="btn-b" class="btn btn-outline-light w-100 p-4 fs-5 text-start answer-btn h-100 rounded-4 border-opacity-50"></button></div>
                            <div class="col-md-6"><button onclick="checkAnswer('c')" id="btn-c" class="btn btn-outline-light w-100 p-4 fs-5 text-start answer-btn h-100 rounded-4 border-opacity-50"></button></div>
                            <div class="col-md-6"><button onclick="checkAnswer('d')" id="btn-d" class="btn btn-outline-light w-100 p-4 fs-5 text-start answer-btn h-100 rounded-4 border-opacity-50"></button></div>
                        </div>
                    </div>

                    <div id="layout-swipe" style="display: none;">
                        <h4 class="text-info mb-4">Mode Swipe Aktif</h4>
                         <p class="text-white-50">Gunakan gesture untuk menjawab.</p>
                         <div id="swipe-card"></div> </div>

                    <div id="result-screen" style="display: none;">
                        <div class="display-1 mb-3">🏆</div>
                        <h2 class="fw-bold mb-3 text-white">Misi Selesai!</h2>
                        <p class="fs-4 text-white">Skor Akhir: <span id="final-score" class="text-warning fw-bold"></span></p>
                        <button onclick="location.reload()" class="btn btn-outline-light mt-3 rounded-pill px-4">Main Lagi</button>
                        <a href="{{ route('leaderboard') }}" class="btn btn-info text-white mt-3 ms-2 rounded-pill px-4">Lihat Peringkat</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        // --- SETUP DATA ---
        const slideElements = document.querySelectorAll('.story-slide');
        const totalSlides = slideElements.length;
        const defaultImage = "{{ $topic->image ? asset('storage/' . $topic->image) : '' }}";
        
        // Ambil data kuis, jika kosong set array kosong
        const quizzes = @json($topic->quizzes ?? []); 

        let currentSlide = 0;
        let currentQIndex = 0;
        let score = 0;
        let isAnswering = false; // Pengaman agar tidak bisa klik 2x

        // --- 1. LOGIKA STORY MODE (Materi) ---
        function showSlide(index) {
            slideElements.forEach(el => el.style.display = 'none');
            const activeSlide = document.getElementById('slide-' + index);
            
            if (activeSlide) {
                activeSlide.style.display = 'block';
                
                // Update Gambar Mascot
                const imgElement = document.getElementById('dynamic-image');
                const mascotPlaceholder = document.getElementById('default-mascot');
                let slideImageSrc = activeSlide.getAttribute('data-image');

                if (slideImageSrc && slideImageSrc.trim() !== "") {
                    imgElement.src = "{{ asset('storage') }}/" + slideImageSrc;
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

            // Atur tombol Navigasi
            document.getElementById('btn-prev').style.display = index === 0 ? 'none' : 'inline-block';
            
            if (index >= totalSlides - 1) {
                document.getElementById('btn-next').style.display = 'none';
                document.getElementById('btn-quiz').style.display = 'inline-block';
            } else {
                document.getElementById('btn-next').style.display = 'inline-block';
                document.getElementById('btn-quiz').style.display = 'none';
            }

            // Update Progress Bar
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

        // Jalankan slide pertama saat loading
        if (totalSlides > 0) showSlide(0);
        else document.querySelector('.hologram-box').innerHTML = '<p class="text-white">Materi belum tersedia.</p>';


        // --- 2. LOGIKA KUIS (PERBAIKAN RESET) ---
        const sfxCorrect = new Audio('https://actions.google.com/sounds/v1/cartoon/clang_and_wobble.ogg');
        const sfxWrong = new Audio('https://actions.google.com/sounds/v1/cartoon/cartoon_boing.ogg');

        function startQuizGame() {
            if (!quizzes || quizzes.length === 0) {
                alert("Misi ini belum memiliki soal latihan!");
                return;
            }
            showQuestion();
        }

        function showQuestion() {
            let q = quizzes[currentQIndex];
            isAnswering = false; // Buka kunci jawaban

            // Reset Tampilan Container
            document.getElementById('question-loading').style.display = 'none';
            document.getElementById('layout-multiple-choice').style.display = 'block';
            
            // Update Progress Bar Kuis
            let progressPercent = ((currentQIndex + 1) / quizzes.length) * 100;
            document.getElementById('quiz-progress-bar').style.width = progressPercent + "%";

            // Update Gambar Soal
            let imageUrl = q.image ? "{{ asset('storage') }}/" + q.image : null;
            const mcImg = document.getElementById('mc-image');
            if (imageUrl) {
                mcImg.src = imageUrl;
                mcImg.style.display = 'inline-block';
            } else {
                mcImg.style.display = 'none';
            }

            // Update Teks Soal & Pilihan
            document.getElementById('mc-question-text').innerText = q.question;
            
            // Set Teks Tombol (Cek apakah opsi ada isinya)
            updateButton('btn-a', q.option_a);
            updateButton('btn-b', q.option_b);
            updateButton('btn-c', q.option_c);
            updateButton('btn-d', q.option_d);
            
            // --- INI BAGIAN PENTING: CUCI BERSIH TOMBOL ---
            // Kita kembalikan tombol ke kondisi 'btn-outline-light' murni
            document.querySelectorAll('.answer-btn').forEach(btn => {
                // Hapus kelas warna & animasi lama
                btn.classList.remove('btn-success', 'btn-danger', 'animate__animated', 'animate__pulse', 'animate__shakeX');
                
                // Tambahkan kelas default
                btn.classList.add('btn-outline-light');
                
                // Pastikan tombol bisa diklik lagi
                btn.disabled = false;
                btn.style.opacity = "1";
            });
        }

        // Helper untuk update teks tombol (sembunyikan jika kosong)
        function updateButton(btnId, text) {
            const btn = document.getElementById(btnId);
            if(text && text.trim() !== "") {
                btn.innerText = text;
                btn.parentElement.style.display = 'block'; // Tampilkan kolom
            } else {
                btn.parentElement.style.display = 'none'; // Sembunyikan jika opsi kosong
            }
        }

        function checkAnswer(choice) {
            if (isAnswering) return; // Cegah klik ganda
            isAnswering = true;

            let q = quizzes[currentQIndex];
            let isCorrect = (choice === q.correct_answer);

            let userBtn = document.getElementById('btn-' + choice);
            let correctBtn = document.getElementById('btn-' + q.correct_answer);

            // Efek Visual
            if (isCorrect) {
                // sfxCorrect.play().catch(e => {}); // Un-comment jika ingin suara
                score += 10;
                userBtn.classList.remove('btn-outline-light');
                userBtn.classList.add('btn-success', 'animate__animated', 'animate__pulse');
            } else {
                // sfxWrong.play().catch(e => {}); // Un-comment jika ingin suara
                userBtn.classList.remove('btn-outline-light');
                userBtn.classList.add('btn-danger', 'animate__animated', 'animate__shakeX');
                
                // Beri tahu jawaban yang benar
                if(correctBtn) {
                    correctBtn.classList.remove('btn-outline-light');
                    correctBtn.classList.add('btn-success');
                }
            }
            
            document.getElementById('score-display').innerText = score;

            // Jeda 1.5 detik sebelum ganti soal
            setTimeout(() => {
                currentQIndex++;
                if (currentQIndex < quizzes.length) {
                    showQuestion();
                } else {
                    showResult();
                }
            }, 1500);
        }

        function showResult() {
            document.getElementById('layout-multiple-choice').style.display = 'none';
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
            }).catch(e => console.log("Gagal simpan skor:", e));
        }
    </script>
@endsection