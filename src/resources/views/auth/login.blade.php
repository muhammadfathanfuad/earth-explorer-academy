@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-5">
            
            <div class="card glass-login border-0 animate__animated animate__fadeInUp">
                <div class="card-body p-5">
                    
                    <div class="text-center mb-4">
                        <div class="avatar-container mb-3">
                            <img src="https://cdn-icons-png.flaticon.com/512/3063/3063823.png" width="80" alt="Rocket">
                        </div>
                        <h2 class="fw-bold text-white font-rajdhani">POS IDENTIFIKASI</h2>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger text-center fw-bold animate__animated animate__shakeX">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" id="loginForm">
                        @csrf

                        @if(!session('stage'))
                            <div class="mb-4 position-relative">
                                <label class="form-label text-info fw-bold ps-2">👋 Siapa Namamu?</label>
                                <input type="text" 
                                       class="form-control form-control-space form-control-lg text-center fw-bold text-white" 
                                       name="login_id" required autofocus autocomplete="off"
                                       placeholder="Ketik Nama Panggilan...">
                                <div class="form-text text-white-50 text-center mt-2">
                                    *Jika baru, ketik namamu untuk mendaftar.
                                </div>
                            </div>

                            <div class="mb-4 d-none" id="adminPass">
                                <input type="password" name="password" class="form-control form-control-space" placeholder="Password Admin...">
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-launch btn-lg">CEK IDENTITAS ➡️</button>
                            </div>

                        @else
                            <input type="hidden" name="login_id" value="{{ session('temp_name') }}">
                            <input type="hidden" name="current_stage" value="{{ session('stage') }}">
                            
                            <div class="text-center mb-4 animate__animated animate__fadeIn">
                                <h4 class="text-warning fw-bold">{{ session('temp_name') }}</h4>
                                <p class="text-white lh-sm">{{ session('message') }}</p>
                            </div>

                            <div class="row g-2 justify-content-center animate__animated animate__zoomIn">
                                @foreach(session('options') as $badgeKey)
                                    <div class="col-3">
                                        <button type="submit" name="selected_badge" value="{{ $badgeKey }}" 
                                                class="btn btn-outline-light w-100 p-2 badge-btn h-100">
                                            <div style="font-size: 2rem;">
                                                {{ session('badges_map')[$badgeKey] }}
                                            </div>
                                        </button>
                                    </div>
                                @endforeach
                            </div>

                            <div class="text-center mt-4">
                                <a href="{{ route('login') }}" class="text-white-50 small text-decoration-none">
                                    <i class="bi bi-arrow-counterclockwise"></i> Ganti Nama
                                </a>
                            </div>
                        @endif

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // Deteksi Email Admin (Otomatis munculkan password)
    const inputField = document.querySelector('input[name="login_id"]');
    const adminPass = document.getElementById('adminPass');
    if(inputField) {
        inputField.addEventListener('input', function() {
            if (this.value.includes('@')) {
                adminPass.classList.remove('d-none');
            } else {
                adminPass.classList.add('d-none');
            }
        });
    }
</script>

<style>
    /* CSS Kaca & Antariksa */
    .glass-login {
        background: rgba(20, 30, 50, 0.85);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(0, 243, 255, 0.3);
        border-radius: 30px;
        box-shadow: 0 0 50px rgba(0, 0, 0, 0.5);
    }
    .form-control-space {
        background: rgba(0, 0, 0, 0.3);
        border: 2px solid rgba(255, 255, 255, 0.1);
        color: #fff !important;
        border-radius: 15px;
        padding: 15px;
    }
    .form-control-space:focus {
        background: rgba(0, 0, 0, 0.5);
        border-color: #00f3ff;
        box-shadow: 0 0 15px rgba(0, 243, 255, 0.3);
    }
    .btn-launch {
        background: linear-gradient(90deg, #00f3ff, #0066ff);
        border: none; color: white;
        font-family: 'Rajdhani', sans-serif; font-weight: 800;
        padding: 15px; border-radius: 50px;
        box-shadow: 0 0 20px rgba(0, 102, 255, 0.5);
    }
    .btn-launch:hover { transform: scale(1.02); color:white; }
    
    .badge-btn {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 12px;
        transition: all 0.2s;
    }
    .badge-btn:hover {
        background: rgba(255, 215, 0, 0.2); /* Efek Emas saat hover */
        border-color: #FFD700;
        transform: scale(1.1);
        z-index: 10;
    }
    .font-rajdhani { font-family: 'Rajdhani', sans-serif; letter-spacing: 2px; }
    .avatar-container { animation: float 3s infinite ease-in-out; }
    @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
</style>
@endsection