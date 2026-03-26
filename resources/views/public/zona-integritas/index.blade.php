@extends('layouts.public')

@section('title', __('messages.zona_integritas') . __('messages.site_title_suffix'))

@section('hero')
<div class="page-hero">
    <div class="container">
        <h2><i class="fas fa-shield-alt me-2"></i>{{ __('messages.hero_zi') }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item active">{{ __('messages.zona_integritas') }}</li>
            </ol>
        </nav>
    </div>
</div>
@endsection

@section('content')
@push('styles')
<style>
    /* === Content section === */
    .zi-section { padding: 40px 0 60px; }
    .zi-content-card {
        background: #fff;
        border-radius: 4px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        margin-bottom: 40px;
    }
    [data-theme="dark"] .zi-content-card { background: var(--card-bg); border-color: #2a2a4a; }
    .zi-content-card .card-title-bar {
        text-align: center;
        padding: 20px 24px 12px;
        font-weight: 700;
        font-size: 1rem;
        color: #1a1a1a;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    [data-theme="dark"] .zi-content-card .card-title-bar { color: #e0e0e0; }
    .zi-content-card img {
        width: 100%;
        height: auto;
        display: block;
        padding: 0 20px 20px;
    }
    .zi-content-card .card-body-text {
        padding: 0 24px 24px;
        font-size: 1rem;
        line-height: 1.8;
        color: #444;
        overflow-x: auto;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }
    [data-theme="dark"] .zi-content-card .card-body-text { color: #ccc; }
    .zi-content-card .card-body-text img,
    .zi-content-card .card-body-text iframe,
    .zi-content-card .card-body-text video,
    .zi-content-card .card-body-text table {
        max-width: 100% !important;
        height: auto;
    }
    .zi-nav-links {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        justify-content: center;
        margin-bottom: 40px;
    }
    .zi-nav-links a {
        padding: 10px 24px;
        border-radius: 30px;
        background: linear-gradient(135deg, #003d82, #0066cc);
        color: #fff;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    .zi-nav-links a:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
    }
</style>
@endpush

<section class="zi-section">
    <div class="container">
        {{-- Navigasi sub-halaman dihapus sesuai permintaan --}}

        @forelse($pages as $page)
        <div class="zi-content-card">
            @if($page->judul)
                <div class="card-title-bar">{{ $page->judul }}</div>
            @endif
            @if($page->gambar)
                <img src="{{ asset('storage/' . $page->gambar) }}" alt="{{ $page->judul }}">
            @endif
            @if($page->konten)
                <div class="card-body-text">{!! $page->konten !!}</div>
            @endif
        </div>
        @empty
        <div class="text-center py-5">
            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
            <p class="text-muted">{{ __('messages.empty_konten_zi') }}</p>
        </div>
        @endforelse
    </div>
</section>
@endsection
