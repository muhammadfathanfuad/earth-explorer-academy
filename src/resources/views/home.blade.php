@extends('layouts.app')

@section('content')

<div class="text-center mb-5">
    <h1 class="display-4 fw-bold">Selamat Datang, Penjelajah!</h1>
    <p class="lead text-secondary">Pilih misi belajar kamu hari ini:</p>
</div>

<div class="row">
    @foreach($topics as $topic)
    <div class="col-md-6 mb-4">
        <div class="card card-custom h-100 p-4">
            <div class="card-body text-center">
                <div class="mb-3 display-1">
                    @if($topic->slug == 'struktur-bumi') 🌋 @else ☁️ @endif
                </div>
                
                <h3 class="card-title fw-bold text-info">{{ $topic->title }}</h3>
                <p class="card-text text-secondary">{{ $topic->summary }}</p>
                
                <a href="{{ route('topic.show', $topic->slug) }}" class="btn btn-sci-fi px-4 py-2 mt-3 rounded-pill">
                    MULAI MISI
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection