@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-5">
            
            <div class="card glass-login border-0">
                <div class="card-body p-5">
                    
                    <div class="text-center mb-4">
                        <div class="avatar-container mb-3">
                            <img src="https://cdn-icons-png.flaticon.com/512/3063/3063823.png" width="80" alt="Rocket">
                        </div>
                        <h2 class="fw-bold text-white font-rajdhani">Identifikasi Kapten</h2>
                        <p class="text-white-50">Masukkan data untuk masuk ke kokpit!</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" id="loginForm">
                        @csrf

                        <div class="mb-4 position-relative">
                            <label for="login_id" class="form-label text-warning fw-bold ps-2">
                                🚀 Kode Kapten / Nama
                            </label>
                            
                            <input id="login_id" type="text" 
                                   class="form-control form-control-space form-control-lg text-center fw-bold @error('login_id') is-invalid @enderror" 
                                   name="login_id" value="{{ old('login_id') }}" required autofocus
                                   placeholder="Ketik Kodemu (Misal: KING01)">
                            
                            <div class="form-text text-white-50 text-center mt-2">
                                *Admin/Guru silakan masukkan Email lengkap.
                            </div>

                            @error('login_id')
                                <span class="invalid-feedback fw-bold bg-danger text-white px-2 py-1 rounded mt-1 text-center d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4 position-relative d-none" id="passwordContainer">
                            <label for="password" class="form-label text-info fw-bold ps-2">🔑 Password Admin</label>
                            <input id="password" type="password" 
                                   class="form-control form-control-space" 
                                   name="password"
                                   placeholder="Password Guru...">
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-launch btn-lg">
                                🚀 MASUK MISI SEKARANG!
                            </button>
                        </div>
                    </form>

                    @if(session('found_user'))
                    <div class="modal fade show d-block" tabindex="-1" style="z-index: 1050;">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content text-white" style="background: #1a1a2e; border: 2px solid #FFD700; border-radius: 20px;">
                                <div class="modal-header border-0 justify-content-center">
                                    <h5 class="modal-title font-rajdhani fw-bold text-warning">🚀 PENGENDALIAN MISI</h5>
                                </div>
                                <div class="modal-body text-center">
                                    <p>Kami menemukan Kapten dengan nama ini:</p>
                                    
                                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ session('found_user')->name }}" 
                                         class="rounded-circle border border-3 border-info mb-3 bg-white" 
                                         width="100" height="100">
                                    
                                    <h3 class="fw-bold">{{ session('found_user')->name }}</h3>
                                    
                                    <p class="text-white-50">Apakah ini akunmu?</p>
                                </div>
                                <div class="modal-footer border-0 justify-content-center gap-2 pb-4">
                                    <a href="{{ route('login') }}" class="btn btn-outline-light rounded-pill px-4">
                                        ❌ Bukan, Saya Baru
                                    </a>

                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <input type="hidden" name="login_id" value="{{ session('input_name') }}">
                                        <input type="hidden" name="confirm_login" value="yes">
                                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">
                                            ✅ Ya, Masuk!
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Font Judul */
    .font-rajdhani {
        font-family: 'Rajdhani', sans-serif;
        letter-spacing: 2px;
        text-transform: uppercase;
        text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
    }

    /* Kartu Kaca (Glassmorphism) */
    .glass-login {
        background: rgba(255, 255, 255, 0.1); /* Putih transparan */
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 2px solid rgba(255, 255, 255, 0.2);
        border-radius: 30px;
        box-shadow: 0 0 50px rgba(0, 0, 0, 0.5);
        animation: floatUp 1s ease-out; /* Animasi muncul */
    }

    @keyframes floatUp {
        from { opacity: 0; transform: translateY(50px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Input Field Antariksa */
    .form-control-space {
        background: rgba(0, 0, 0, 0.3); /* Hitam transparan */
        border: 2px solid rgba(255, 255, 255, 0.1);
        color: #fff !important;
        border-radius: 15px;
        padding: 12px 20px;
        transition: all 0.3s;
    }
    .form-control-space:focus {
        background: rgba(0, 0, 0, 0.5);
        border-color: #00d2ff; /* Warna Cyan saat diketik */
        box-shadow: 0 0 15px rgba(0, 210, 255, 0.3);
    }
    .form-control-space::placeholder {
        color: rgba(255, 255, 255, 0.4);
    }

    /* Tombol Luncurkan */
    .btn-launch {
        background: linear-gradient(90deg, #ff512f, #dd2476); /* Gradasi Merah-Pink Semangat */
        border: none;
        color: white;
        font-family: 'Rajdhani', sans-serif;
        font-weight: 800;
        font-size: 1.2rem;
        padding: 15px;
        border-radius: 50px;
        box-shadow: 0 10px 20px rgba(221, 36, 118, 0.4);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .btn-launch:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 15px 30px rgba(221, 36, 118, 0.6);
        color: white;
    }

    /* Avatar Container */
    .avatar-container {
        width: 100px; height: 100px;
        margin: 0 auto;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        border: 2px dashed rgba(255, 255, 255, 0.3);
        animation: spinSlow 20s linear infinite;
    }
    @keyframes spinSlow { 100% { transform: rotate(360deg); } }
    .avatar-container img { animation: none; transform: rotate(-360deg); /* Agar gambar tidak ikut muter */ }
</style>
@endsection