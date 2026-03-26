@extends('layouts.public')

@section('title', __('messages.gallery') . ' - Album')

@section('content')
<div class="container my-5">
    <div class="d-flex align-items-center mb-4">
        <h3 class="mb-0">Album Galeri</h3>
        <small class="text-muted ms-3">Menampilkan semua foto/video dari satu kali upload</small>
    </div>

    <div class="row g-3">
        @foreach($items as $item)
        <div class="col-lg-3 col-md-4 col-6">
            <a href="{{ route('galeri.album.item', ['group' => $group, 'galeri' => $item->id]) }}" class="d-block position-relative album-thumb" data-large="{{ $item->gambar ? asset('storage/' . $item->gambar) : $item->thumbnail_url }}">
                @if($item->thumbnail_url)
                <img src="{{ $item->thumbnail_url }}" alt="{{ $item->judul }}" class="img-fluid rounded" style="height:200px;object-fit:cover;">
                @else
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height:200px;">
                    <i class="fas fa-image fa-3x"></i>
                </div>
                @endif
                @if($item->tipe == 'video')
                <div class="position-absolute top-50 start-50 translate-middle">
                    <i class="fas fa-play-circle fa-3x text-white"></i>
                </div>
                @endif
                <div class="position-absolute top-0 end-0 m-2">
                    <span class="badge bg-dark">{{ $items->count() }} item</span>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection