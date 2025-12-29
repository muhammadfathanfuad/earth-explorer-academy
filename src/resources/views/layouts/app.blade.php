<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Bumi Explorer') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;600&family=Rajdhani:wght@500;700&display=swap" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        /* --- 1. GLOBAL SPACE THEME --- */
        body {
            background-color: #0b0d17;
            font-family: 'Fredoka', sans-serif; /* Font default yang ramah anak */
            color: #fff;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Background Nebula Bergerak */
        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: 
                radial-gradient(circle at 20% 30%, #2a1b3d 0%, transparent 40%),
                radial-gradient(circle at 80% 70%, #1a2a40 0%, transparent 40%),
                url('https://www.transparenttextures.com/patterns/stardust.png');
            z-index: -1;
            animation: spacePulse 10s ease-in-out infinite alternate;
        }
        @keyframes spacePulse { 0% { opacity: 0.8; } 100% { opacity: 1; } }

        /* --- 2. HUD NAVBAR (MODIFIKASI UTAMA) --- */
        .navbar-glass {
            background: rgba(11, 13, 23, 0.7); /* Gelap Transparan */
            backdrop-filter: blur(15px); /* Efek Blur Kaca */
            -webkit-backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(0, 210, 255, 0.2); /* Garis Neon Tipis */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .navbar-brand {
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            font-size: 1.8rem;
            color: #00d2ff !important; /* Warna Cyan Neon */
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 0 10px rgba(0, 210, 255, 0.5);
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.7) !important;
            font-weight: 600;
            transition: all 0.3s;
            position: relative;
        }
        .nav-link:hover, .nav-link.active {
            color: #fff !important;
            text-shadow: 0 0 8px rgba(255, 255, 255, 0.5);
        }
        /* Garis bawah saat hover */
        .nav-link::after {
            content: ''; position: absolute; bottom: 0; left: 0; width: 0%; height: 2px;
            background: #00d2ff; transition: width 0.3s;
        }
        .nav-link:hover::after { width: 100%; }

        /* Dropdown Menu Gelap */
        .dropdown-menu-dark-custom {
            background: rgba(20, 20, 35, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            margin-top: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .dropdown-item {
            color: #ccc;
            padding: 10px 20px;
            font-weight: 600;
        }
        .dropdown-item:hover {
            background: rgba(0, 210, 255, 0.1);
            color: #fff;
        }

        /* Tombol Khusus */
        .btn-sci-fi {
            background: linear-gradient(45deg, #00d2ff, #3a7bd5);
            border: none; color: white;
            font-family: 'Rajdhani', sans-serif;
            font-weight: bold; letter-spacing: 1px;
            text-transform: uppercase;
            box-shadow: 0 0 15px rgba(0, 210, 255, 0.4);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-sci-fi:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 25px rgba(0, 210, 255, 0.6);
            color: white;
        }

        /* Fix Container agar tidak ketabrak navbar */
        main.py-4 {
            padding-top: 2rem !important; 
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-glass sticky-top">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    🪐 BUMI EXPLORER
                </a>
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">Markas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('leaderboard') }}">Peringkat</a>
                        </li>
                    </ul>

                    <ul class="navbar-nav ms-auto align-items-center gap-3">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">Masuk Misi</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="btn btn-sm btn-sci-fi rounded-pill px-4" href="{{ route('register') }}">Daftar Anggota</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item d-flex align-items-center">
                                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ Auth::user()->name }}" 
                                     class="rounded-circle border border-2 border-warning shadow-sm" 
                                     width="40" height="40" alt="Avatar">
                                <div class="ms-2 d-none d-md-block lh-1 text-start">
                                    <div class="fw-bold text-white">{{ Auth::user()->name }}</div>
                                    <small class="text-warning" style="font-size: 0.75rem;">Kapten 🚀</small>
                                </div>
                            </li>

                            <li class="nav-item">
                                <a class="btn btn-sm btn-danger rounded-pill px-3 fw-bold" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    🚪 Keluar
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4 container">
            @yield('content')
        </main>
    </div>
</body>
</html>