<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bumi Explorer - Petualangan Sains</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;700&family=Fredoka:wght@400;600&display=swap" rel="stylesheet">
    
    <style>
        /* --- 1. BACKGROUND NEBULA ANIMASI --- */
        body {
            font-family: 'Fredoka', sans-serif; /* Font lebih bulat & ramah anak */
            background: linear-gradient(135deg, #1a0b2e 0%, #291848 30%, #46255a 70%, #1a0b2e 100%);
            color: #ffffff;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Trik CSS membuat Bintang tanpa gambar */
        .stars, .stars2, .stars3 {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: transparent;
            z-index: -1; /* Taruh di belakang konten */
        }
        
        /* Bintang Kecil */
        .stars {
            width: 1px; height: 1px;
            box-shadow: 10vw 10vh #fff, 20vw 40vh #fff, 40vw 10vh #fff, 60vw 80vh #fff, 80vw 20vh #fff, 30vw 90vh #fff; 
            animation: animStar 50s linear infinite;
        }
        /* Bintang Sedang */
        .stars2 {
            width: 2px; height: 2px;
            box-shadow: 15vw 15vh rgba(255,255,255,0.5), 35vw 45vh rgba(255,255,255,0.5), 75vw 25vh rgba(255,255,255,0.5); 
            animation: animStar 100s linear infinite;
        }
        
        @keyframes animStar {
            from { transform: translateY(0px); }
            to { transform: translateY(-2000px); }
        }

        /* --- 2. GLASSMORPHISM UI (Efek Kaca) --- */
        .navbar {
            background: rgba(41, 24, 72, 0.6); /* Transparan ungu */
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
        }

        .card-custom {
            background: rgba(255, 255, 255, 0.08); /* Sangat transparan */
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px; /* Sudut lebih bulat (Friendly) */
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .card-custom:hover {
            transform: translateY(-10px) scale(1.02);
            background: rgba(255, 255, 255, 0.15);
            border-color: #ff9ff3; /* Glow Pink saat hover */
            box-shadow: 0 0 25px rgba(255, 159, 243, 0.4);
        }

        /* Tombol yang lebih "Tasty" (seperti permen) */
        .btn-sci-fi {
            background: linear-gradient(45deg, #00d2ff, #3a7bd5);
            border: none;
            color: white;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(0, 210, 255, 0.4);
            transition: all 0.3s;
        }
        .btn-sci-fi:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 210, 255, 0.6);
            color: white;
        }

        /* Judul yang lebih Tech tapi Fun */
        h1, h2, h3, .navbar-brand {
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>

    <div class="stars"></div>
    <div class="stars2"></div>

    <nav class="navbar navbar-expand-lg navbar-dark py-3 sticky-top">
        <div class="container">
            <a class="navbar-brand fs-2 text-info" href="{{ route('home') }}">
                🚀 BUMI EXPLORER
            </a>
            
            <div class="d-flex align-items-center">
                <a href="{{ route('leaderboard') }}" class="btn btn-sm btn-outline-warning me-3 rounded-pill px-3">
                    🏆 Peringkat
                </a>

                @auth
                    <div class="dropdown">
                        <button class="btn btn-outline-light btn-sm dropdown-toggle rounded-pill px-3" type="button" data-bs-toggle="dropdown">
                            👨‍🚀 {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark shadow-lg border-0">
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger small">Keluar Markas</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-sm btn-sci-fi rounded-pill px-4">Mulai Misi</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container mt-5 pb-5">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Kita tambahkan bintang acak via JS agar background tidak sepi
        const starsContainer = document.querySelector('.stars');
        let boxShadowValue = '';
        for(let i = 0; i < 200; i++) {
            let x = Math.floor(Math.random() * 100);
            let y = Math.floor(Math.random() * 100);
            boxShadowValue += `${x}vw ${y}vh #FFF, `;
        }
        // Hapus koma terakhir
        boxShadowValue = boxShadowValue.slice(0, -2);
        starsContainer.style.boxShadow = boxShadowValue;
    </script>
</body>
</html>