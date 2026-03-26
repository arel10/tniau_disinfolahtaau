@extends('layouts.public')

@section('title', __('messages.news') . __('messages.site_title_suffix'))

@push('styles')
<style>
    .berita-section {
        padding: 30px 0 60px;
        position: relative;
        z-index: 1;
        background: #f8f9fa;
    }
    [data-theme="dark"] .berita-section { background: var(--bg-color); }
    .berita-section .card {
        height: auto !important;
    }
    .berita-section .card:hover {
        transform: none !important;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
    }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="container">
        <h2><i class="fas fa-newspaper me-2"></i>{{ __('messages.hero_berita') }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item active">{{ __('messages.news') }}</li>
            </ol>
        </nav>
    </div>
</div>

<section class="berita-section">
<div class="container">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Filter & Search -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('berita.index') }}">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <input type="text" name="search" class="form-control" placeholder="{{ __('messages.filter_cari_berita') }}" value="{{ request('search') }}">
                            </div>
                            <div class="col-md-4">
                                <select name="kategori" class="form-select">
                                    <option value="">{{ __('messages.label_semua_kategori') }}</option>
                                    @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori->slug }}" {{ request('kategori') == $kategori->slug ? 'selected' : '' }}>
                                            {{ $kategori->localized_nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> {{ __('messages.btn_cari') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Berita List -->
            @forelse($beritas as $berita)
            <div class="card mb-4">
                <div class="row g-0">
                    <div class="col-md-4">
                        @if($berita->gambar_utama)
                            <img src="{{ asset('storage/' . $berita->gambar_utama) }}" class="img-fluid h-100 object-fit-cover rounded-start" alt="{{ $berita->localized_judul }}">
                        @else
                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center h-100">
                                <i class="fas fa-image fa-3x"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <span class="badge badge-kategori mb-2">{{ $berita->kategori->localized_nama_kategori }}</span>
                            <h5 class="card-title">{{ $berita->localized_judul }}</h5>
                            <p class="text-muted small">
                                <i class="fas fa-calendar"></i> {{ $berita->published_at->format('d M Y') }} | 
                                <i class="fas fa-user"></i> {{ $berita->user->name }} | 
                                <i class="fas fa-eye"></i> {{ display_views($berita) }} {{ __('messages.label_views') }}
                            </p>
                            <p class="card-text">{{ Str::limit($berita->localized_ringkasan ?? strip_tags($berita->localized_konten), 150) }}</p>
                            <a href="{{ route('berita.show', $berita->slug) }}" class="btn btn-sm btn-primary">{{ __('messages.btn_baca_selengkapnya') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> {{ __('messages.empty_berita_not_found') }}
            </div>
            @endforelse

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $beritas->links() }}
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Berita Populer -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-fire"></i> {{ __('messages.heading_berita_populer') }}</h6>
                </div>
                <div class="card-body">
                    @forelse($berita_populer as $berita)
                    <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <a href="{{ route('berita.show', $berita->slug) }}" class="text-decoration-none text-dark">
                            <h6 class="mb-1">{{ Str::limit($berita->localized_judul, 60) }}</h6>
                            <small class="text-muted">
                                <i class="fas fa-eye"></i> {{ display_views($berita) }} {{ __('messages.label_views') }}
                            </small>
                        </a>
                    </div>
                    @empty
                    <p class="text-muted mb-0">{{ __('messages.empty_berita_populer') }}</p>
                    @endforelse
                </div>
            </div>

            <!-- Kategori -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-tags"></i> {{ __('messages.heading_kategori') }}</h6>
                </div>
                <div class="card-body">
                    @foreach($kategoris as $kategori)
                    <a href="{{ route('berita.kategori', $kategori->slug) }}" class="btn btn-outline-secondary btn-sm mb-2">
                        {{ $kategori->localized_nama_kategori }} ({{ $kategori->beritas_count }})
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
</section>
@endsection
