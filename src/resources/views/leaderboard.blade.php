@extends('layouts.app')

@section('content')
<div class="text-center mb-5 animate__animated animate__fadeInDown">
    <h1 class="fw-bold text-warning">🏆 HALL OF FAME 🏆</h1>
    <p class="lead text-white">Para Penjelajah Terbaik Bumi</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-custom p-4">
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle">
                    <thead>
                        <tr class="text-info">
                            <th>#</th>
                            <th>Nama Penjelajah</th>
                            <th>Misi</th>
                            <th class="text-end">Skor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($scores as $index => $data)
                        <tr>
                            <td class="fw-bold">{{ $index + 1 }}</td>
                            <td>
                                <span class="fw-bold">{{ $data->user->name }}</span>
                                @if($index == 0) 👑 @endif
                            </td>
                            <td>{{ $data->topic->title }}</td>
                            <td class="text-end fw-bold text-warning">{{ $data->score }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($scores->isEmpty())
                <div class="text-center py-4 text-muted">
                    Belum ada data skor. Jadilah yang pertama!
                </div>
            @endif
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('home') }}" class="btn btn-outline-light">Kembali ke Misi</a>
        </div>
    </div>
</div>
@endsection