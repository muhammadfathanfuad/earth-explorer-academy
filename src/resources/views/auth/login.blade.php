@extends('layouts.app')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card card-custom p-5 animate__animated animate__zoomIn">
            <div class="card-body text-center">
                <div class="display-1 mb-3">👨‍🚀</div>
                <h2 class="fw-bold mb-3 text-info">SIAPA NAMAMU?</h2>
                <p class="text-secondary mb-4">Masukkan nama panggilanmu untuk mulai misi.</p>
                
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <input type="text" name="name" class="form-control form-control-lg bg-dark text-white border-info text-center" placeholder="Contoh: Budi" required autocomplete="off">
                    </div>
                    <button type="submit" class="btn btn-sci-fi btn-lg w-100 rounded-pill">
                        MULAI PETUALANGAN 🚀
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection