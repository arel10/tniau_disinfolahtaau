@extends('layouts.public')

@section('title', $kategori->localized_nama_kategori . __('messages.site_title_suffix'))

@push('styles')
<style>
    .kategori-section {
        padding: 30px 0 60px;
        position: relative;
        z-index: 1;
        background: #f8f9fa;
    }
    [data-theme="dark"] .kategori-section { background: var(--bg-color); }
    .kategori-section .card {
        height: auto !important;
    }
    .kategori-section .card:hover {
        transform: none !important;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
    }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="container">
        <h2><i class="fas fa-tag me-2"></i>{{ $kategori->localized_nama_kategori }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('berita.index') }}">{{ __('messages.news') }}</a></li>
                <li class="breadcrumb-item active">{{ $kategori->localized_nama_kategori }}</li>
            </ol>
        </nav>
    </div>
</div>

<section class="kategori-section">
<div class="container">

    <div class="row g-4">
        @forelse($beritas as $berita)
        <div class="col-md-4">
            <div class="card">
                @if($berita->gambar_utama)
                    <img src="{{ asset('storage/' . $berita->gambar_utama) }}" class="card-img-top" alt="{{ $berita->localized_judul }}" style="height: 200px; object-fit: cover;">
                @else
                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="fas fa-image fa-3x"></i>
                    </div>
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ Str::limit($berita->localized_judul, 60) }}</h5>
                    <p class="card-text text-muted small">
                        <i class="fas fa-calendar"></i> {{ $berita->published_at->format('d M Y') }} | 
                        <i class="fas fa-eye"></i> {{ display_views($berita) }} {{ __('messages.label_views') }}
                    </p>
                    <p class="card-text">{{ Str::limit($berita->localized_ringkasan ?? strip_tags($berita->localized_konten), 100) }}</p>
                    <a href="{{ route('berita.show', $berita->slug) }}" class="btn btn-sm btn-outline-primary">{{ __('messages.btn_baca_selengkapnya') }}</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> {{ __('messages.empty_berita_kategori') }}
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $beritas->links() }}
    </div>
</div>
</section>
@endsection
