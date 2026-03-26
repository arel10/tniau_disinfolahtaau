@extends('layouts.public')

@section('title', (isset($kategoriLabel) ? __('messages.gallery') . ' ' . $kategoriLabel : __('messages.gallery')) . __('messages.site_title_suffix'))

@section('content')
<div class="page-hero">
    <div class="container">
        <h2><i class="fas fa-images me-2"></i>{{ isset($kategoriLabel) ? __('messages.galeri_prefix') . ' ' . $kategoriLabel : __('messages.hero_galeri') }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('galeri.index') }}">{{ __('messages.gallery') }}</a></li>
                @if(isset($kategoriLabel))
                <li class="breadcrumb-item active">{{ $kategoriLabel }}</li>
                @endif
            </ol>
        </nav>
    </div>
</div>

<div class="container my-5">
    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <form method="GET" action="{{ isset($currentKategori) ? route('galeri.kategori', $currentKategori) : route('galeri.index') }}">
                        <input type="text" name="search" class="form-control" placeholder="{{ __('messages.filter_cari_galeri') }}" value="{{ request('search') }}">
                    </form>
                </div>
                <div class="col-md-8">
                    <div class="btn-group" role="group">
                        @if(isset($currentKategori))
                            <a href="{{ route('galeri.kategori', $currentKategori) }}" class="btn {{ !request('tipe') ? 'btn-primary' : 'btn-outline-primary' }}">
                                {{ __('messages.filter_semua') }} ({{ $foto_count + $video_count }})
                            </a>
                            <a href="{{ route('galeri.kategori', $currentKategori) }}?tipe=video" class="btn {{ request('tipe') == 'video' ? 'btn-primary' : 'btn-outline-primary' }}">
                                <i class="fas fa-video"></i> {{ __('messages.filter_video') }} ({{ $video_count }})
                            </a>
                        @else
                            <a href="{{ route('galeri.index') }}" class="btn {{ !request('tipe') ? 'btn-primary' : 'btn-outline-primary' }}">
                                {{ __('messages.filter_semua') }} ({{ $foto_count + $video_count }})
                            </a>
                            <a href="{{ route('galeri.index', ['tipe' => 'video']) }}" class="btn {{ request('tipe') == 'video' ? 'btn-primary' : 'btn-outline-primary' }}">
                                <i class="fas fa-video"></i> {{ __('messages.filter_video') }} ({{ $video_count }})
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Galeri Grid -->
    @php
        $isVideoPage = (isset($currentKategori) && $currentKategori === 'video') || request('tipe') === 'video';
    @endphp

    @php
        // Group paginated galeris by upload group so one upload appears as one album
        $groups = $galeris->getCollection()->groupBy('group_id');
    @endphp

    @if($isVideoPage)
    {{-- === VIDEO LAYOUT: Centered cards with YouTube embed === --}}
    <div class="row justify-content-center g-4">
        @forelse($groups as $groupId => $items)
        @php $galeri = $items->first(); $count = $items->count(); @endphp
        <div class="col-lg-8 col-md-10 col-12">
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                @if($galeri->video_url && $galeri->embed_url)
                    <div class="ratio ratio-16x9">
                        <iframe src="{{ $galeri->embed_url }}" allowfullscreen style="border:0;"></iframe>
                    </div>
                @elseif($galeri->gambar)
                    <a href="{{ route('galeri.show', $galeri->id) }}" class="position-relative d-block">
                        <img src="{{ $galeri->thumbnail_url }}" class="card-img-top" alt="{{ $galeri->localized_judul }}" style="height: 360px; object-fit: cover;">
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <i class="fas fa-play-circle fa-4x text-white" style="opacity:0.85;text-shadow:0 2px 12px rgba(0,0,0,0.5);"></i>
                        </div>
                    </a>
                @else
                    <a href="{{ route('galeri.show', $galeri->id) }}">
                        <div class="bg-dark text-white d-flex align-items-center justify-content-center" style="height: 300px;">
                            <i class="fas fa-video fa-4x" style="opacity:0.4;"></i>
                        </div>
                    </a>
                @endif
                {{-- video items: no caption or date to keep clean video gallery view --}}
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle"></i> {{ __('messages.empty_galeri') }}
            </div>
        </div>
        @endforelse
    </div>
    @else
    {{-- === FOTO / SEMUA LAYOUT: Compact horizontally-scrollable grid (5 rows) === --}}
    <div class="galeri-scroll-grid">
        @forelse($groups as $groupId => $items)
        @php $galeri = $items->first(); $count = $items->count(); @endphp
        <div class="galeri-item">
            <div class="card h-100">
                <a href="{{ route('galeri.show', $galeri->id) }}" class="position-relative d-block">
                    @if($galeri->thumbnail_url)
                        <img src="{{ $galeri->thumbnail_url }}" class="card-img-top" alt="{{ $galeri->localized_judul }}">
                    @else
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 140px;">
                            <i class="fas fa-image fa-2x"></i>
                        </div>
                    @endif
                    @if($galeri->tipe == 'video')
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <i class="fas fa-play-circle fa-2x text-white"></i>
                        </div>
                    @endif
                    @if($galeri->pdf_path)
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-danger"><i class="fas fa-file-pdf"></i> PDF</span>
                        </div>
                    @endif
                    @if($count > 1)
                        <div class="position-absolute bottom-0 start-0 m-2">
                            <span class="badge bg-dark">{{ $count }} item</span>
                        </div>
                    @endif
                </a>
                {{-- No caption/body for gallery thumbnails to keep clean grid (intentionally empty) --}}
            </div>
        </div>
        @empty
        <div>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> {{ __('messages.empty_galeri') }}
            </div>
        </div>
        @endforelse
    </div>
    @endif

    <!-- Pagination removed: gallery now horizontally scrollable -->
</div>
@endsection
