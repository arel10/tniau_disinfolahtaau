@extends('layouts.public')

@section('title', __('messages.home') . __('messages.site_title_suffix'))

@section('hero')
@php
    $heroRot   = (int) setting('hero_media_rotation', 180);
    $heroScale = ($heroRot === 90 || $heroRot === 270) ? ' scale(1.778)' : '';
    $heroTf    = $heroRot ? "transform:rotate({$heroRot}deg){$heroScale};" : '';
@endphp
<div class="hero-section">
    @if(setting('hero_media_type', 'video') === 'image')
        <img class="hero-video" src="{{ asset(setting('hero_image', 'assets/image/pesawat.jpg')) }}"
             alt="" style="{{ $heroTf }}">
    @else
        <video class="hero-video" autoplay muted loop playsinline style="{{ $heroTf }}">
            <source src="{{ asset(setting('hero_video', 'assets/video/backround.mp4')) }}" type="video/mp4">
        </video>
    @endif
    <div class="hero-overlay"></div>
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3">{{ localized_setting('hero_title', __('messages.site_name')) }}</h1>
        <p class="lead">{{ localized_setting('hero_subtitle', __('messages.site_subtitle')) }}</p>
    </div>
</div>
@endsection

@section('content')

<!-- ============================================================ -->
<!-- #1  TAGAR TERATAS (Top Hashtags) - Static                    -->
<!-- ============================================================ -->
<section class="tagar-section py-3">
    <div class="container">
        <div class="tagar-wrapper">
            <div class="tagar-label">
                <i class="fas fa-hashtag"></i> {{ __('messages.label_tagar_teratas') }}
            </div>
            <div class="tagar-list-static">
                @forelse($tagar_teratas as $tag)
                    <a href="{{ route('berita.kategori', $tag->slug) }}" class="tagar-item">
                        <span class="tagar-hash">#</span>{{ $tag->localized_nama_kategori }}
                        <span class="tagar-count">{{ $tag->beritas_count }}</span>
                    </a>
                @empty
                    <span class="text-muted">{{ __('messages.empty_tagar') }}</span>
                @endforelse
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- #1A  BERITA TERAKHIR (Latest News Ticker)                    -->
<!-- ============================================================ -->
<section class="berita-terakhir-section py-2">
    <div class="container">
        <div class="berita-terakhir-wrapper">
            <div class="berita-terakhir-label">
                <i class="fas fa-bolt"></i> {{ __('messages.label_berita_terakhir') }}
            </div>
            <div class="berita-terakhir-ticker">
                <div class="berita-terakhir-inner" id="beritaTerakhirScroll" translate="yes">
                    @foreach($berita_utama as $berita)
                        <a href="{{ route('berita.show', $berita->slug) }}" class="berita-terakhir-item">
                            <img src="{{ asset('storage/' . $berita->gambar_utama) }}" alt="{{ $berita->localized_judul }}" class="berita-terakhir-thumb">
                            <span class="berita-terakhir-title">{{ Str::limit($berita->localized_judul, 55) }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- #2  BERITA UTAMA (Main News Carousel – Auto Slide)           -->
<!-- ============================================================ -->
<section class="beranda-utama-row py-4">
    @php
        if(!isset($activeTab)) $activeTab = 'popular';
    @endphp
    <div class="container">
        <div class="row g-3 align-items-stretch">
            <!-- Berita Utama -->
            <div class="col-lg-5 col-md-12">
                <div class="berita-utama-box h-100">
                    <div class="section-header-inline mb-2">
                        <span class="section-badge">{{ __('messages.label_berita_utama') }}</span>
                        <div class="carousel-nav-sm">
                            <button class="nav-btn-sm" onclick="slideBeritaUtama(-1)"><i class="fas fa-chevron-left"></i></button>
                            <button class="nav-btn-sm" onclick="slideBeritaUtama(1)"><i class="fas fa-chevron-right"></i></button>
                        </div>
                    </div>
                    @if($berita_utama->count())
                    <div class="berita-utama-carousel" id="beritaUtamaCarousel">
                        <div class="bu-track" id="buTrack">
                            @foreach($berita_utama as $index => $berita)
                            <div class="bu-slide {{ $index === 0 ? 'active' : '' }}">
                                <a href="{{ route('berita.show', $berita->slug) }}" class="bu-card-overlay" style="display:block;text-decoration:none;color:inherit;cursor:pointer;">
                                    <div class="bu-image-wrapper">
                                        @if($berita->gambar_utama)
                                            <img src="{{ asset('storage/' . $berita->gambar_utama) }}" alt="{{ $berita->localized_judul }}" class="bu-image">
                                        @else
                                            <div class="bu-image-placeholder"><i class="fas fa-newspaper fa-4x"></i></div>
                                        @endif
                                        <div class="bu-gradient-overlay"></div>
                                        <div class="bu-content-overlay">
                                            <span class="bu-kategori-badge">{{ $berita->kategori->localized_nama_kategori }}</span>
                                            <h3 class="bu-title-overlay">{{ $berita->localized_judul }}</h3>
                                            <div class="bu-meta-overlay">
                                                <span><i class="fas fa-user"></i> {{ $berita->user->name ?? __('messages.author_default') }}</span>
                                                <span><i class="far fa-calendar"></i> {{ $berita->published_at->format('d M Y') }}</span>
                                                <span><i class="fas fa-eye"></i> {{ display_views($berita) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        </div>
                        <div class="bu-dots-inline" id="buDots">
                            @foreach($berita_utama as $index => $berita)
                                <button class="bu-dot {{ $index === 0 ? 'active' : '' }}" onclick="goToSlide({{ $index }})"></button>
                            @endforeach
                        </div>
                    </div>
                    @else
                        <div class="text-center py-4 text-muted"><i class="fas fa-newspaper fa-2x mb-2"></i><p class="small">{{ __('messages.empty_berita_utama') }}</p></div>
                    @endif
                </div>
            </div>
            <!-- Postingan Hari Ini -->
            <div class="col-lg-4 col-md-6">
                <div class="postingan-hariini-box h-100">
                    <div class="section-header-inline mb-2">
                        <span class="section-badge section-badge-green">{{ __('messages.label_postingan_hari_ini') }}</span>
                    </div>
                    <div class="postingan-stack">
                        @php
                            $postingan = $postingan_hari_ini;
                            if($postingan->count() === 0) $postingan = $berita_utama;
                        @endphp
                        @foreach($postingan->take(2) as $berita)
                        <div class="postingan-card-sm">
                            <div class="postingan-img-sm">
                                @if($berita->gambar_utama)
                                    <img src="{{ asset('storage/' . $berita->gambar_utama) }}" alt="{{ $berita->localized_judul }}">
                                @else
                                    <div class="postingan-img-placeholder"><i class="fas fa-image"></i></div>
                                @endif
                                <div class="postingan-badges">
                                    <span class="badge-kategori">{{ $berita->kategori->localized_nama_kategori }}</span>
                                </div>
                            </div>
                            <div class="postingan-body-sm">
                                <a href="{{ route('berita.show', $berita->slug) }}" class="postingan-title-sm">{{ Str::limit($berita->localized_judul, 50) }}</a>
                                    <div class="postingan-meta-sm">
                                    <span><i class="fas fa-user"></i> {{ $berita->user->name ?? __('messages.author_default') }}</span>
                                    <span><i class="far fa-calendar"></i> {{ $berita->published_at->format('d M Y') }}</span>
                                    <span><i class="fas fa-eye"></i> {{ display_views($berita) }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- Pembaharuan Hari Ini -->
            <div class="col-lg-3 col-md-6">
                <div class="pembaruan-hariini-box h-100" style="min-height: 440px;">
                    <div class="section-header-inline mb-2">
                        <span class="section-badge section-badge-orange">{{ __('messages.label_pembaharuan') }}</span>
                    </div>
                    <div class="pembaruan-tabs-sm mb-2">
                        <button class="tab-btn-sm {{ $activeTab === 'popular' ? 'active' : '' }}" data-tab="popular" onclick="switchTab('popular')"><i class="fas fa-fire"></i> {{ __('messages.tab_popular') }}</button>
                        <button class="tab-btn-sm {{ $activeTab === 'trending' ? 'active' : '' }}" data-tab="trending" onclick="switchTab('trending')"><i class="fas fa-chart-line"></i> {{ __('messages.tab_trending') }}</button>
                        <button class="tab-btn-sm {{ $activeTab === 'recent' ? 'active' : '' }}" data-tab="recent" onclick="switchTab('recent')"><i class="far fa-clock"></i> {{ __('messages.tab_recent') }}</button>
                    </div>
                    <div class="pembaruan-list-sm">
                        <div class="pembaruan-content {{ $activeTab === 'popular' ? 'active' : '' }}" id="tab-popular">
                            @foreach($popular_hari_ini->take(4) as $berita)
                            <div class="pembaruan-item-sm">
                                <div class="pembaruan-thumb">
                                    @if($berita->gambar_utama)
                                        <img src="{{ asset('storage/' . $berita->gambar_utama) }}" alt="{{ $berita->localized_judul }}">
                                    @else
                                        <div class="pembaruan-thumb-placeholder"><i class="fas fa-image"></i></div>
                                    @endif
                                </div>
                                <div class="pembaruan-info">
                                    <span class="pembaruan-kategori-sm">{{ $berita->kategori->localized_nama_kategori }}</span>
                                    <a href="{{ route('berita.show', $berita->slug) }}" class="pembaruan-title-sm">{{ Str::limit($berita->localized_judul, 45) }}</a>
                                    <span class="pembaruan-date-sm"><i class="far fa-calendar"></i> {{ $berita->published_at->format('d M Y') }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="pembaruan-content {{ $activeTab === 'trending' ? 'active' : '' }}" id="tab-trending">
                            @foreach($trending_hari_ini->take(4) as $berita)
                            <div class="pembaruan-item-sm">
                                <div class="pembaruan-thumb">
                                    @if($berita->gambar_utama)
                                        <img src="{{ asset('storage/' . $berita->gambar_utama) }}" alt="{{ $berita->localized_judul }}">
                                    @else
                                        <div class="pembaruan-thumb-placeholder"><i class="fas fa-image"></i></div>
                                    @endif
                                </div>
                                <div class="pembaruan-info">
                                    <span class="pembaruan-kategori-sm">{{ $berita->kategori->localized_nama_kategori }}</span>
                                    <a href="{{ route('berita.show', $berita->slug) }}" class="pembaruan-title-sm">{{ Str::limit($berita->localized_judul, 45) }}</a>
                                    <span class="pembaruan-date-sm"><i class="far fa-calendar"></i> {{ $berita->published_at->format('d M Y') }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="pembaruan-content {{ $activeTab === 'recent' ? 'active' : '' }}" id="tab-recent">
                            @foreach($berita_utama->take(4) as $berita)
                            <div class="pembaruan-item-sm">
                                <div class="pembaruan-thumb">
                                    @if($berita->gambar_utama)
                                        <img src="{{ asset('storage/' . $berita->gambar_utama) }}" alt="{{ $berita->localized_judul }}">
                                    @else
                                        <div class="pembaruan-thumb-placeholder"><i class="fas fa-image"></i></div>
                                    @endif
                                </div>
                                <div class="pembaruan-info">
                                    <span class="pembaruan-kategori-sm">{{ $berita->kategori->localized_nama_kategori }}</span>
                                    <a href="{{ route('berita.show', $berita->slug) }}" class="pembaruan-title-sm">{{ Str::limit($berita->localized_judul, 45) }}</a>
                                    <span class="pembaruan-date-sm"><i class="far fa-calendar"></i> {{ $berita->published_at->format('d M Y') }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- #5  BERITA FITUR (Featured News)                             -->
<!-- ============================================================ -->
<section class="fitur-section py-5">
    <div class="container">
        <div class="section-header mb-4">
            <h2 class="section-title">
                <span class="title-accent"></span>
                {{ __('messages.heading_berita_fitur') }}
            </h2>
            <div class="fitur-nav-arrows">
                <button class="fitur-nav-btn" onclick="slideBeritaFitur(-1)"><i class="fas fa-chevron-left"></i></button>
                <button class="fitur-nav-btn" onclick="slideBeritaFitur(1)"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
        <div class="fitur-grid-scroll" id="fiturGridScroll">
            @forelse($berita_fitur as $index => $berita)
            <div class="fitur-item">
                <div class="fitur-item-img">
                    @if($berita->gambar_utama)
                        <img src="{{ asset('storage/' . $berita->gambar_utama) }}" alt="{{ $berita->localized_judul }}">
                    @else
                        <div class="fitur-item-placeholder"><i class="fas fa-image"></i></div>
                    @endif
                    <span class="fitur-item-number">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="fitur-item-body">
                    <span class="fitur-item-kategori">{{ $berita->kategori->localized_nama_kategori }}</span>
                    <a href="{{ route('berita.show', $berita->slug) }}" class="fitur-item-title">{{ Str::limit($berita->localized_judul, 60) }}</a>
                    <div class="fitur-item-meta">
                        <span><i class="fas fa-user"></i> {{ $berita->user->name ?? __('messages.author_default') }}</span>
                        <span><i class="far fa-calendar"></i> {{ $berita->published_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="empty-state"><p>{{ __('messages.empty_berita_fitur') }}</p></div>
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- Portal Berita section removed --}}

{{-- Yang Terlewat, Galeri Kegiatan, Instansi Terkait now rendered from layouts.public via partials.shared-sections --}}

@push('styles')
<style>
/* ================================================================
   GENERAL HELPERS
================================================================ */
.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}
.section-title {
    font-size: 1.5rem;
    font-weight: 800;
    color: #1a1a2e;
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
    flex-wrap: wrap;
}
[data-theme="dark"] .section-title { color: #e0e0e0; }

.title-accent {
    width: 5px;
    height: 28px;
    background: linear-gradient(180deg, #003d82, #0066cc);
    border-radius: 3px;
    display: inline-block;
    flex-shrink: 0;
}
.see-all-link {
    color: #0066cc;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s;
    white-space: nowrap;
}
.see-all-link:hover { color: #003d82; gap: 6px; }
.see-all-link i { transition: transform 0.3s; }
.see-all-link:hover i { transform: translateX(4px); }

.empty-state {
    text-align: center;
    padding: 50px 20px;
    color: #999;
}
.empty-state i { color: #ccc; }

/* ================================================================
   BERANDA UTAMA ROW - 3 Columns Layout
================================================================ */
.beranda-utama-row { background: #f8f9fa; }
[data-theme="dark"] .beranda-utama-row { background: #1a1a2e; }

.section-header-inline { display: flex; align-items: center; justify-content: space-between; gap: 8px; }
.section-badge { background: #003d82; color: #fff; padding: 6px 14px; font-size: 0.85rem; font-weight: 700; border-radius: 4px; }
.section-badge-green { background: #28a745; }
.section-badge-orange { background: #fd7e14; }

.carousel-nav-sm { display: flex; gap: 4px; }
.nav-btn-sm { background: #fff; border: 1px solid #ddd; border-radius: 4px; width: 28px; height: 28px; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; }
.nav-btn-sm:hover { background: #003d82; color: #fff; border-color: #003d82; }

/* Berita Utama Box */
.berita-utama-box { background: #fff; border-radius: 8px; padding: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
[data-theme="dark"] .berita-utama-box { background: #16213e; }

.bu-card-overlay { position: relative; border-radius: 8px; overflow: hidden; height: 360px; }
.bu-card-overlay .bu-image-wrapper { height: 100%; position: relative; }
.bu-card-overlay .bu-image { width: 100%; height: 100%; object-fit: cover; }
.bu-card-overlay .bu-image-placeholder { width: 100%; height: 100%; background: #e9ecef; display: flex; align-items: center; justify-content: center; color: #aaa; }
.bu-gradient-overlay { position: absolute; bottom: 0; left: 0; right: 0; height: 70%; background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0) 100%); }
.bu-content-overlay { position: absolute; bottom: 0; left: 0; right: 0; padding: 16px; color: #fff; z-index: 2; }
.bu-kategori-badge { background: #003d82; color: #fff; padding: 4px 10px; font-size: 0.7rem; font-weight: 600; border-radius: 3px; display: inline-block; margin-bottom: 8px; }
.bu-title-overlay { font-size: 1.1rem; font-weight: 700; margin: 0 0 8px 0; line-height: 1.3; }
.bu-meta-overlay { font-size: 0.75rem; opacity: 0.9; display: flex; gap: 12px; flex-wrap: wrap; }
.bu-meta-overlay i { margin-right: 4px; }

.bu-dots-inline { display: flex; justify-content: center; gap: 6px; margin-top: 10px; }
.bu-dot { width: 10px; height: 10px; border-radius: 50%; background: #ccc; border: none; cursor: pointer; transition: background 0.3s; }
.bu-dot.active { background: #003d82; }

/* Postingan Hari Ini Box */
.postingan-hariini-box { background: #fff; border-radius: 8px; padding: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
[data-theme="dark"] .postingan-hariini-box { background: #16213e; }

.postingan-stack { display: flex; flex-direction: column; gap: 10px; }
.postingan-card-sm { display: flex; flex-direction: column; border-radius: 8px; overflow: hidden; background: #f8f9fa; }
[data-theme="dark"] .postingan-card-sm { background: #1a1a2e; }

.postingan-img-sm { position: relative; height: 140px; overflow: hidden; }
.postingan-img-sm img { width: 100%; height: 100%; object-fit: cover; }
.postingan-img-placeholder { width: 100%; height: 100%; background: #e9ecef; display: flex; align-items: center; justify-content: center; color: #aaa; }
.postingan-badges { position: absolute; top: 8px; left: 8px; display: flex; flex-wrap: wrap; gap: 4px; }
.badge-kategori { background: #003d82; color: #fff; padding: 3px 8px; font-size: 0.65rem; font-weight: 600; border-radius: 3px; }

.postingan-body-sm { padding: 10px; }
.postingan-title-sm { font-size: 0.85rem; font-weight: 700; color: #1a1a2e; text-decoration: none; display: block; margin-bottom: 6px; line-height: 1.3; }
.postingan-title-sm:hover { color: #003d82; }
[data-theme="dark"] .postingan-title-sm { color: #e0e0e0; }
[data-theme="dark"] .postingan-title-sm:hover { color: #4da3ff; }

.postingan-meta-sm { font-size: 0.7rem; color: #666; display: flex; flex-wrap: wrap; gap: 8px; }
.postingan-meta-sm i { margin-right: 3px; }
[data-theme="dark"] .postingan-meta-sm { color: #aaa; }

/* Pembaharuan Hari Ini Box */
.pembaruan-hariini-box { background: #fff; border-radius: 8px; padding: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
[data-theme="dark"] .pembaruan-hariini-box { background: #16213e; }

.pembaruan-tabs-sm { display: flex; gap: 4px; flex-wrap: wrap; }
.tab-btn-sm { background: #f0f0f0; border: none; padding: 5px 10px; font-size: 0.7rem; font-weight: 600; border-radius: 4px; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 4px; }
.tab-btn-sm:hover { background: #e0e0e0; }
.tab-btn-sm.active { background: #003d82; color: #fff; }
[data-theme="dark"] .tab-btn-sm { background: #2a2a4a; color: #ccc; }
[data-theme="dark"] .tab-btn-sm.active { background: #003d82; color: #fff; }

.pembaruan-list-sm { margin-top: 8px; }
.pembaruan-content { display: none; }
.pembaruan-content.active { display: block; }

.pembaruan-item-sm { display: flex; gap: 10px; padding: 8px 0; border-bottom: 1px solid #eee; }
.pembaruan-item-sm:last-child { border-bottom: none; }
[data-theme="dark"] .pembaruan-item-sm { border-bottom-color: #2a2a4a; }

.pembaruan-thumb { width: 70px; height: 55px; border-radius: 4px; overflow: hidden; flex-shrink: 0; }
.pembaruan-thumb img { width: 100%; height: 100%; object-fit: cover; }
.pembaruan-thumb-placeholder { width: 100%; height: 100%; background: #e9ecef; display: flex; align-items: center; justify-content: center; color: #aaa; font-size: 0.8rem; }

.pembaruan-info { flex: 1; min-width: 0; }
.pembaruan-kategori-sm { background: #003d82; color: #fff; padding: 2px 6px; font-size: 0.6rem; font-weight: 600; border-radius: 2px; display: inline-block; margin-bottom: 4px; }
.pembaruan-title-sm { font-size: 0.78rem; font-weight: 600; color: #1a1a2e; text-decoration: none; display: block; line-height: 1.3; margin-bottom: 4px; }
.pembaruan-title-sm:hover { color: #003d82; }
[data-theme="dark"] .pembaruan-title-sm { color: #e0e0e0; }
[data-theme="dark"] .pembaruan-title-sm:hover { color: #4da3ff; }

.pembaruan-date-sm { font-size: 0.65rem; color: #888; }
.pembaruan-date-sm i { margin-right: 3px; }

/* ================================================================
   #1 TAGAR TERATAS
================================================================ */
.tagar-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid rgba(0,0,0,0.05);
}
[data-theme="dark"] .tagar-section {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    border-bottom-color: rgba(255,255,255,0.05);
}

.tagar-wrapper {
    display: flex;
    align-items: center;
    gap: 16px;
}
.tagar-label {
    background: linear-gradient(135deg, #003d82, #0066cc);
    color: white;
    padding: 8px 18px;
    border-radius: 25px;
    font-weight: 700;
    font-size: 0.85rem;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 3px 12px rgba(0,61,130,0.3);
}
/* Static tagar list (no scroll) */
.tagar-list-static {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    padding: 4px 0;
}
/* Keep old scroll styles for backward compatibility */
.tagar-scroll-container {
    overflow-x: auto;
    flex: 1;
    scrollbar-width: none;
    -ms-overflow-style: none;
}
.tagar-scroll-container::-webkit-scrollbar { display: none; }
.tagar-scroll-inner {
    display: flex;
    gap: 10px;
    padding: 4px 0;
    animation: tagarAutoScroll 30s linear infinite;
}
.tagar-scroll-inner:hover { animation-play-state: paused; }

@keyframes tagarAutoScroll {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}

/* Berita Terakhir Ticker Section */
.berita-terakhir-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
}
[data-theme="dark"] .berita-terakhir-section {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    border-bottom: 1px solid #2a2a4a;
}
.berita-terakhir-wrapper {
    display: flex;
    align-items: center;
    gap: 15px;
}
.berita-terakhir-label {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 8px 18px;
    border-radius: 25px;
    font-weight: 700;
    font-size: 0.85rem;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 3px 12px rgba(40,167,69,0.3);
}
.berita-terakhir-ticker {
    flex: 1;
    overflow: hidden;
    position: relative;
}
.berita-terakhir-inner {
    display: flex;
    gap: 30px;
    animation: beritaTerakhirScroll 40s linear infinite;
}
.berita-terakhir-inner:hover {
    animation-play-state: paused;
}
@keyframes beritaTerakhirScroll {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
.berita-terakhir-item {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    white-space: nowrap;
    color: #333;
    transition: color 0.3s;
}
[data-theme="dark"] .berita-terakhir-item {
    color: #e0e0e0;
}
.berita-terakhir-item:hover {
    color: #003d82;
}
[data-theme="dark"] .berita-terakhir-item:hover {
    color: #ffc107;
}
.berita-terakhir-thumb {
    width: 40px;
    height: 40px;
    border-radius: 6px;
    object-fit: cover;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}
.berita-terakhir-title {
    font-size: 0.9rem;
    font-weight: 500;
}

.tagar-item {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 6px 16px;
    background: white;
    color: #003d82;
    border: 2px solid transparent;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.85rem;
    text-decoration: none;
    white-space: nowrap;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
[data-theme="dark"] .tagar-item {
    background: #16213e;
    color: #7db8ff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}
.tagar-item:hover {
    background: linear-gradient(135deg, #003d82, #0066cc);
    color: white;
    border-color: #003d82;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,61,130,0.3);
}
.tagar-hash { font-weight: 800; font-size: 1em; }
.tagar-count {
    background: rgba(0,61,130,0.12);
    color: #003d82;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 700;
}
.tagar-item:hover .tagar-count {
    background: rgba(255,255,255,0.25);
    color: white;
}

/* ================================================================
   #2 BERITA UTAMA CAROUSEL
================================================================ */
.berita-utama-section {
    background: #fff;
}
[data-theme="dark"] .berita-utama-section { background: #1a1a2e; }

.carousel-nav-custom {
    display: flex;
    gap: 8px;
}
.caro-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 2px solid #003d82;
    background: transparent;
    color: #003d82;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
}
.caro-btn:hover {
    background: #003d82;
    color: white;
    transform: scale(1.1);
}
[data-theme="dark"] .caro-btn { border-color: #7db8ff; color: #7db8ff; }
[data-theme="dark"] .caro-btn:hover { background: #7db8ff; color: #1a1a2e; }

.berita-utama-carousel {
    position: relative;
    overflow: hidden;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
}
[data-theme="dark"] .berita-utama-carousel {
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
}

.bu-track { position: relative; }
.bu-slide {
    display: none;
    animation: buFadeIn 1.2s ease;
}
.bu-slide.active { display: block; }

@keyframes buFadeIn {
    from { opacity: 0; transform: translateX(30px); }
    to { opacity: 1; transform: translateX(0); }
}

.bu-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
}
[data-theme="dark"] .bu-card { background: #16213e; }

.bu-image-wrapper {
    position: relative;
    height: 420px;
    overflow: hidden;
}
.bu-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s;
}
.bu-slide:hover .bu-image { transform: scale(1.03); }
.bu-image-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #003d82, #0066cc);
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(255,255,255,0.3);
}
.bu-overlay-gradient {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 50%;
    background: linear-gradient(to top, rgba(0,0,0,0.5), transparent);
}
.bu-slide-counter {
    position: absolute;
    top: 16px;
    right: 16px;
    background: rgba(0,0,0,0.6);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    backdrop-filter: blur(10px);
}

.bu-content {
    padding: 30px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    height: 100%;
    min-height: 420px;
}
.bu-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
    flex-wrap: wrap;
}
.bu-kategori {
    background: linear-gradient(135deg, #003d82, #0066cc);
    color: white;
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.bu-date {
    color: #888;
    font-size: 0.85rem;
}
[data-theme="dark"] .bu-date { color: #999; }

.bu-title {
    font-size: 1.6rem;
    font-weight: 800;
    line-height: 1.3;
    color: #1a1a2e;
    margin-bottom: 14px;
}
[data-theme="dark"] .bu-title { color: #e0e0e0; }

.bu-excerpt {
    color: #666;
    line-height: 1.7;
    font-size: 0.95rem;
    margin-bottom: 20px;
}
[data-theme="dark"] .bu-excerpt { color: #aaa; }

.bu-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: auto;
}
.bu-stats {
    display: flex;
    gap: 16px;
    color: #888;
    font-size: 0.85rem;
}
.bu-stats span { display: flex; align-items: center; gap: 5px; }
.bu-read-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 24px;
    background: linear-gradient(135deg, #003d82, #0066cc);
    color: white;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(0,61,130,0.3);
}
.bu-read-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,61,130,0.4);
    color: white;
}
.bu-read-btn i { transition: transform 0.3s; }
.bu-read-btn:hover i { transform: translateX(4px); }

.bu-dots {
    display: flex;
    justify-content: center;
    gap: 8px;
    padding: 16px 0;
    background: white;
}
[data-theme="dark"] .bu-dots { background: #16213e; }

.bu-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #003d82;
    background: transparent;
    cursor: pointer;
    transition: all 0.3s;
    padding: 0;
}
.bu-dot.active {
    background: #003d82;
    transform: scale(1.2);
}
.bu-dot:hover { background: rgba(0,61,130,0.3); }

.bu-progress {
    height: 4px;
    background: #e9ecef;
    border-radius: 0 0 16px 16px;
    overflow: hidden;
}
[data-theme="dark"] .bu-progress { background: #2a2a4a; }

.bu-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #003d82, #0066cc, #ffc107);
    width: 0%;
    transition: width 0.1s linear;
    border-radius: 4px;
}

/* ================================================================
   #3 POSTINGAN HARI INI
================================================================ */
.postingan-section {
    background: #f8f9fa;
}
[data-theme="dark"] .postingan-section { background: #16213e; }

.today-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #e74c3c;
    color: white;
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.post-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
}
[data-theme="dark"] .post-card {
    background: #1a1a2e;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
}
.post-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
}

.post-card-img-wrap {
    position: relative;
    height: 280px;
    overflow: hidden;
}
.post-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s;
}
.post-card:hover .post-card-img { transform: scale(1.05); }
.post-card-img-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #ddd, #eee);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #bbb;
}
.post-card-img-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 40%;
    background: linear-gradient(to top, rgba(0,0,0,0.4), transparent);
}
.post-badge-kategori {
    position: absolute;
    top: 16px;
    left: 16px;
    background: linear-gradient(135deg, #003d82, #0066cc);
    color: white;
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 700;
    z-index: 1;
}

.post-card-body {
    padding: 20px;
}
.post-card-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: #1a1a2e;
    line-height: 1.4;
    margin-bottom: 10px;
}
[data-theme="dark"] .post-card-title { color: #e0e0e0; }

.post-card-excerpt {
    color: #666;
    font-size: 0.9rem;
    line-height: 1.6;
    margin-bottom: 12px;
}
[data-theme="dark"] .post-card-excerpt { color: #aaa; }

.post-card-meta {
    display: flex;
    gap: 16px;
    color: #888;
    font-size: 0.82rem;
    margin-bottom: 12px;
}
.post-card-meta span { display: flex; align-items: center; gap: 5px; }

.post-card-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #003d82;
    font-weight: 600;
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.3s;
}
.post-card-link:hover { color: #0066cc; }
.post-card-link:hover i { transform: translateX(4px); }
.post-card-link i { transition: transform 0.3s; }

/* Side list */
.post-side-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
    height: 100%;
}
.post-side-item {
    display: flex;
    gap: 14px;
    background: white;
    border-radius: 12px;
    padding: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    transition: all 0.3s;
}
[data-theme="dark"] .post-side-item {
    background: #1a1a2e;
    box-shadow: 0 2px 12px rgba(0,0,0,0.2);
}
.post-side-item:hover {
    transform: translateX(6px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.post-side-img-wrap {
    width: 100px;
    height: 80px;
    border-radius: 10px;
    overflow: hidden;
    flex-shrink: 0;
}
.post-side-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.post-side-img-placeholder {
    width: 100%;
    height: 100%;
    background: #eee;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ccc;
}

.post-side-content { flex: 1; min-width: 0; }
.post-side-kategori {
    display: inline-block;
    background: rgba(0,61,130,0.1);
    color: #003d82;
    padding: 2px 10px;
    border-radius: 10px;
    font-size: 0.72rem;
    font-weight: 600;
    margin-bottom: 4px;
}
[data-theme="dark"] .post-side-kategori {
    background: rgba(125,184,255,0.15);
    color: #7db8ff;
}
.post-side-title {
    display: block;
    color: #1a1a2e;
    font-weight: 600;
    font-size: 0.88rem;
    line-height: 1.4;
    text-decoration: none;
    margin-bottom: 4px;
}
[data-theme="dark"] .post-side-title { color: #e0e0e0; }
.post-side-title:hover { color: #0066cc; }

.post-side-meta {
    display: flex;
    gap: 12px;
    color: #888;
    font-size: 0.78rem;
}
.post-side-meta span { display: flex; align-items: center; gap: 4px; }

/* ================================================================
   #4 PEMBARUAN HARI INI
================================================================ */
.pembaruan-section {
    background: #fff;
}
[data-theme="dark"] .pembaruan-section { background: #1a1a2e; }

.pembaruan-tabs {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.pembaruan-tab {
    padding: 10px 28px;
    border: 2px solid #e0e0e0;
    background: transparent;
    color: #666;
    font-weight: 700;
    font-size: 0.9rem;
    border-radius: 30px;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 8px;
}
.pembaruan-tab i { font-size: 0.9rem; }
.pembaruan-tab:hover {
    border-color: #003d82;
    color: #003d82;
}
.pembaruan-tab.active {
    background: linear-gradient(135deg, #003d82, #0066cc);
    color: white;
    border-color: #003d82;
    box-shadow: 0 4px 15px rgba(0,61,130,0.3);
}
[data-theme="dark"] .pembaruan-tab {
    border-color: #2a2a4a;
    color: #aaa;
}
[data-theme="dark"] .pembaruan-tab:hover {
    border-color: #7db8ff;
    color: #7db8ff;
}

.pembaruan-content { display: none; }
.pembaruan-content.active {
    display: block;
    animation: tabFadeIn 0.4s ease;
}

@keyframes tabFadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.update-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}
[data-theme="dark"] .update-card {
    background: #16213e;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
}
.update-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
}
.update-card.trending-glow:hover {
    box-shadow: 0 12px 40px rgba(255,193,7,0.2);
}

.update-card-rank {
    position: absolute;
    top: 16px;
    left: 16px;
    z-index: 2;
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #003d82, #0066cc);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(0,61,130,0.3);
}
.update-card-rank.trending-rank {
    background: linear-gradient(135deg, #ffc107, #ff9800);
    box-shadow: 0 4px 15px rgba(255,193,7,0.3);
}
.rank-number {
    color: white;
    font-weight: 800;
    font-size: 1rem;
}

.update-card-img-wrap {
    position: relative;
    height: 200px;
    overflow: hidden;
}
.update-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
}
.update-card:hover .update-card-img { transform: scale(1.05); }
.update-card-img-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #ddd, #eee);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #bbb;
}

.update-badge-kategori {
    position: absolute;
    bottom: 12px;
    left: 12px;
    background: linear-gradient(135deg, #003d82, #0066cc);
    color: white;
    padding: 3px 12px;
    border-radius: 15px;
    font-size: 0.72rem;
    font-weight: 700;
}
.update-badge-kategori.trending-badge {
    background: linear-gradient(135deg, #ffc107, #ff9800);
    color: #1a1a2e;
}

.update-views-overlay {
    position: absolute;
    bottom: 12px;
    right: 12px;
    background: rgba(0,0,0,0.6);
    color: white;
    padding: 3px 10px;
    border-radius: 15px;
    font-size: 0.72rem;
    font-weight: 600;
    backdrop-filter: blur(10px);
}

.update-card-body {
    padding: 18px;
}
.update-card-title {
    display: block;
    color: #1a1a2e;
    font-weight: 700;
    font-size: 0.95rem;
    line-height: 1.4;
    text-decoration: none;
    margin-bottom: 8px;
    transition: color 0.3s;
}
[data-theme="dark"] .update-card-title { color: #e0e0e0; }
.update-card-title:hover { color: #0066cc; }

.update-card-meta {
    display: flex;
    gap: 12px;
    color: #888;
    font-size: 0.78rem;
}
.update-card-meta span { display: flex; align-items: center; gap: 4px; }

/* ================================================================
   #5 BERITA FITUR
/* Fitur Carousel */
.fitur-carousel { position: relative; }
.fitur-track { position: relative; }
.fitur-slide { display: none; animation: fiturFadeIn 1.2s ease; }
.fitur-slide.active { display: block; }
@keyframes fiturFadeIn {
    from { opacity: 0; transform: translateX(30px); }
    to { opacity: 1; transform: translateX(0); }
}
.fitur-dots { display: flex; justify-content: center; gap: 8px; margin-top: 12px; }
.fitur-dot { width: 14px; height: 14px; border-radius: 50%; background: #ccc; border: none; cursor: pointer; transition: background 0.3s; }
.fitur-dot.active { background: #003d82; }
.fitur-nav-custom { display: flex; justify-content: center; gap: 16px; margin-top: 12px; }
.fitur-btn { background: #003d82; color: #fff; border: none; border-radius: 50%; width: 36px; height: 36px; font-size: 18px; cursor: pointer; transition: background 0.3s; }
.fitur-btn:hover { background: #0066cc; }
.fitur-progress { width: 100%; height: 4px; background: #e2e8f0; margin-top: 8px; border-radius: 2px; overflow: hidden; }
.fitur-progress-bar { height: 100%; width: 0%; background: #003d82; transition: width 0.3s; }
================================================================ */
.fitur-section {
    background: linear-gradient(135deg, #f0f4f8 0%, #e2e8f0 100%);
}
[data-theme="dark"] .fitur-section {
    background: linear-gradient(135deg, #16213e 0%, #1a1a2e 100%);
}

.fitur-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 24px;
}
@media (max-width: 991.98px) {
    .fitur-grid {
        grid-template-columns: 1fr;
    }
}

.fitur-main { height: 100%; }
.fitur-card-main {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    height: 100%;
    min-height: 450px;
}
.fitur-img-wrap {
    position: relative;
    height: 100%;
}
.fitur-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    min-height: 450px;
    transition: transform 0.6s;
}
.fitur-card-main:hover .fitur-img { transform: scale(1.03); }
.fitur-img-placeholder {
    width: 100%;
    height: 100%;
    min-height: 450px;
    background: linear-gradient(135deg, #003d82, #0066cc);
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(255,255,255,0.2);
}

.fitur-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 40px 30px 30px;
    background: linear-gradient(to top, rgba(0,0,0,0.85), rgba(0,0,0,0.3), transparent);
    color: white;
}
.fitur-kategori {
    display: inline-block;
    background: linear-gradient(135deg, #ffc107, #ff9800);
    color: #1a1a2e;
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 700;
    margin-bottom: 12px;
}
.fitur-title {
    font-size: 1.5rem;
    font-weight: 800;
    line-height: 1.3;
    margin-bottom: 10px;
}
.fitur-excerpt {
    font-size: 0.9rem;
    opacity: 0.85;
    line-height: 1.6;
    margin-bottom: 12px;
}
.fitur-meta {
    display: flex;
    gap: 16px;
    font-size: 0.82rem;
    opacity: 0.75;
    margin-bottom: 14px;
}
.fitur-meta span { display: flex; align-items: center; gap: 5px; }
.fitur-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #ffc107;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
}
.fitur-link:hover { color: #fff; }
.fitur-link:hover i { transform: translateX(4px); }
.fitur-link i { transition: transform 0.3s; }

.fitur-side {
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.fitur-card-side {
    display: flex;
    gap: 14px;
    background: white;
    border-radius: 14px;
    padding: 14px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    transition: all 0.3s;
}
[data-theme="dark"] .fitur-card-side {
    background: #16213e;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}
.fitur-card-side:hover {
    transform: translateX(6px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.fitur-side-img-wrap {
    width: 110px;
    height: 90px;
    border-radius: 10px;
    overflow: hidden;
    flex-shrink: 0;
}
.fitur-side-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s;
}
.fitur-card-side:hover .fitur-side-img { transform: scale(1.05); }
.fitur-side-img-placeholder {
    width: 100%;
    height: 100%;
    background: #eee;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ccc;
}

.fitur-side-content { flex: 1; min-width: 0; }
.fitur-side-kategori {
    display: inline-block;
    background: rgba(0,61,130,0.1);
    color: #003d82;
    padding: 2px 10px;
    border-radius: 10px;
    font-size: 0.72rem;
    font-weight: 600;
    margin-bottom: 4px;
}
[data-theme="dark"] .fitur-side-kategori {
    background: rgba(125,184,255,0.15);
    color: #7db8ff;
}
.fitur-side-title {
    display: block;
    color: #1a1a2e;
    font-weight: 700;
    font-size: 0.9rem;
    line-height: 1.4;
    text-decoration: none;
    margin-bottom: 6px;
}
[data-theme="dark"] .fitur-side-title { color: #e0e0e0; }
.fitur-side-title:hover { color: #0066cc; }

.fitur-side-meta {
    display: flex;
    gap: 12px;
    color: #888;
    font-size: 0.78rem;
}
.fitur-side-meta span { display: flex; align-items: center; gap: 4px; }

/* ================================================================
   #5 BERITA FITUR - Horizontal Grid Style
================================================================ */
.fitur-section { background: #fff; }
[data-theme="dark"] .fitur-section { background: #1a1a2e; }

.fitur-nav-arrows { display: flex; gap: 8px; }
.fitur-nav-btn { background: #fff; border: 1px solid #ddd; border-radius: 4px; width: 32px; height: 32px; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; }
.fitur-nav-btn:hover { background: #003d82; color: #fff; border-color: #003d82; }

.fitur-grid-scroll { display: flex; gap: 20px; overflow-x: auto; scroll-behavior: smooth; padding-bottom: 10px; }
.fitur-grid-scroll::-webkit-scrollbar { height: 6px; }
.fitur-grid-scroll::-webkit-scrollbar-track { background: #f0f0f0; border-radius: 3px; }
.fitur-grid-scroll::-webkit-scrollbar-thumb { background: #003d82; border-radius: 3px; }

.fitur-item { min-width: 200px; max-width: 200px; flex-shrink: 0; }
.fitur-item-img { position: relative; width: 70px; height: 70px; border-radius: 50%; overflow: hidden; margin: 0 auto 10px; border: 3px solid #003d82; }
.fitur-item-img img { width: 100%; height: 100%; object-fit: cover; }
.fitur-item-placeholder { width: 100%; height: 100%; background: #e9ecef; display: flex; align-items: center; justify-content: center; color: #aaa; }
.fitur-item-number { position: absolute; bottom: -5px; right: -5px; background: #003d82; color: #fff; width: 24px; height: 24px; border-radius: 50%; font-size: 0.7rem; font-weight: 700; display: flex; align-items: center; justify-content: center; border: 2px solid #fff; }

.fitur-item-body { text-align: center; }
.fitur-item-kategori { display: inline-block; background: #003d82; color: #fff; padding: 3px 10px; font-size: 0.65rem; font-weight: 600; border-radius: 3px; margin-bottom: 8px; }
.fitur-item-title { display: block; color: #1a1a2e; font-weight: 700; font-size: 0.85rem; line-height: 1.35; text-decoration: none; margin-bottom: 8px; }
.fitur-item-title:hover { color: #003d82; }
[data-theme="dark"] .fitur-item-title { color: #e0e0e0; }
[data-theme="dark"] .fitur-item-title:hover { color: #4da3ff; }

.fitur-item-meta { font-size: 0.7rem; color: #888; display: flex; flex-direction: column; gap: 2px; align-items: center; }
.fitur-item-meta i { margin-right: 4px; }

/* ================================================================
   #6 YANG TERLEWAT CAROUSEL - White Background
================================================================ */
.terlewat-section-white { background: #fff; }
[data-theme="dark"] .terlewat-section-white { background: #1a1a2e; }

.terlewat-card-white { min-width: 260px; max-width: 260px; background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 12px; overflow: hidden; transition: all 0.3s; flex-shrink: 0; }
.terlewat-card-white:hover { transform: translateY(-4px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); border-color: #003d82; }
[data-theme="dark"] .terlewat-card-white { background: #16213e; border-color: #2a2a4a; }
[data-theme="dark"] .terlewat-card-white:hover { border-color: #4da3ff; }

.terlewat-img-wrap-white { position: relative; height: 140px; overflow: hidden; }
.terlewat-img-wrap-white img { width: 100%; height: 100%; object-fit: cover; }

.terlewat-body-white { padding: 12px; }
.terlewat-kategori-white { display: inline-block; background: #003d82; color: #fff; padding: 3px 10px; font-size: 0.65rem; font-weight: 600; border-radius: 3px; margin-bottom: 8px; }
.terlewat-title-white { display: block; color: #1a1a2e; font-weight: 700; font-size: 0.9rem; line-height: 1.35; text-decoration: none; margin-bottom: 8px; }
.terlewat-title-white:hover { color: #003d82; }
[data-theme="dark"] .terlewat-title-white { color: #e0e0e0; }
[data-theme="dark"] .terlewat-title-white:hover { color: #4da3ff; }

.terlewat-meta-white { font-size: 0.72rem; color: #666; display: flex; flex-wrap: wrap; gap: 10px; }
.terlewat-meta-white i { margin-right: 4px; }
[data-theme="dark"] .terlewat-meta-white { color: #aaa; }

/* ================================================================
   #6 YANG TERLEWAT CAROUSEL (Legacy)
================================================================ */
.terlewat-section {
    background: linear-gradient(135deg, #001f3f 0%, #003366 100%);
    color: white;
}

.terlewat-section .section-title { color: white; }
.terlewat-section .title-accent { background: linear-gradient(180deg, #ffc107, #ff9800); }

.missed-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,193,7,0.2);
    color: #ffc107;
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.terlewat-section .caro-btn {
    border-color: rgba(255,255,255,0.4);
    color: rgba(255,255,255,0.7);
}
.terlewat-section .caro-btn:hover {
    background: rgba(255,255,255,0.2);
    border-color: white;
    color: white;
}

.terlewat-carousel {
    overflow: hidden;
    position: relative;
}
.terlewat-track {
    display: flex;
    gap: 20px;
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: grab;
}
.terlewat-track:active { cursor: grabbing; }

.terlewat-card {
    min-width: 280px;
    max-width: 280px;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.4s;
    backdrop-filter: blur(10px);
    flex-shrink: 0;
}
.terlewat-card:hover {
    transform: translateY(-6px);
    background: rgba(255,255,255,0.14);
    border-color: rgba(255,255,255,0.2);
    box-shadow: 0 12px 40px rgba(0,0,0,0.3);
}

.terlewat-img-wrap {
    position: relative;
    height: 170px;
    overflow: hidden;
}
.terlewat-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
}
.terlewat-card:hover .terlewat-img { transform: scale(1.05); }
.terlewat-img-placeholder {
    width: 100%;
    height: 100%;
    background: rgba(255,255,255,0.05);
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(255,255,255,0.2);
}

.terlewat-date {
    position: absolute;
    top: 12px;
    right: 12px;
    background: rgba(0,0,0,0.6);
    color: white;
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    backdrop-filter: blur(10px);
}

.terlewat-body {
    padding: 16px;
}
.terlewat-kategori {
    display: inline-block;
    background: rgba(255,193,7,0.2);
    color: #ffc107;
    padding: 2px 10px;
    border-radius: 10px;
    font-size: 0.72rem;
    font-weight: 600;
    margin-bottom: 8px;
}
.terlewat-title {
    display: block;
    color: white;
    font-weight: 700;
    font-size: 0.9rem;
    line-height: 1.4;
    text-decoration: none;
    margin-bottom: 8px;
    transition: color 0.3s;
}
.terlewat-title:hover { color: #ffc107; }

.terlewat-meta {
    display: flex;
    gap: 12px;
    color: rgba(255,255,255,0.5);
    font-size: 0.78rem;
}
.terlewat-meta span { display: flex; align-items: center; gap: 4px; }

/* ================================================================
   GALERI / INSTANSI
================================================================ */
.galeri-thumb {
    display: block;
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.3s;
}
.galeri-thumb:hover {
    transform: scale(1.05);
}

.instansi-logo {
    max-height: 120px;
    width: auto;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
    transition: transform 0.3s ease;
    margin: 0 auto;
}
.instansi-logo:hover {
    transform: scale(1.1);
}

/* ================================================================
   RESPONSIVE
================================================================ */
@media (max-width: 767.98px) {
    .bu-content { min-height: auto; padding: 20px; }
    .bu-image-wrapper { height: 250px; }
    .bu-title { font-size: 1.2rem; }
    .tagar-label { padding: 6px 14px; font-size: 0.78rem; }
    .section-title { font-size: 1.2rem; }
    /* Tagar: single scrolling row on mobile */
    .tagar-wrapper {
        flex-direction: column;
        align-items: flex-start;
        gap: 6px;
    }
    .tagar-list-static {
        flex-wrap: nowrap;
        overflow: hidden;
        width: 100%;
    }
    .tagar-mobile-scroll {
        display: flex;
        gap: 10px;
        animation: tagarAutoScroll 20s linear infinite;
        will-change: transform;
    }
    .tagar-mobile-scroll:hover { animation-play-state: paused; }
    /* Speed up berita-terakhir ticker on mobile */
    .berita-terakhir-inner { animation-duration: 12s !important; }
    .fitur-card-main { min-height: 300px; }
    .fitur-img { min-height: 300px; }
    .fitur-img-placeholder { min-height: 300px; }
    .fitur-title { font-size: 1.2rem; }
    .terlewat-card { min-width: 240px; max-width: 240px; }
    .post-card-img-wrap { height: 200px; }
}
</style>
@endpush

@push('scripts')
<script>
/* ================================================================
   BERITA FITUR HORIZONTAL SCROLL
================================================================ */

(function () {
    const fiturScroll = document.getElementById('fiturGridScroll');
    if (!fiturScroll) return;
    const itemWidth = 220; // item width + gap
    let fiturPos = 0;
    const totalItems = fiturScroll.children.length;
    const maxPos = Math.max(0, (totalItems * itemWidth) - fiturScroll.offsetWidth);
    let fiturAutoTimer = null;

    function setPosition(pos) {
        fiturPos = Math.max(0, Math.min(pos, maxPos));
        fiturScroll.scrollTo({ left: fiturPos, behavior: 'smooth' });
    }

    window.slideBeritaFitur = function (dir) {
        setPosition(fiturPos + dir * itemWidth * 2);
        restartAutoScroll();
    };

    // Auto scroll
    function autoScroll() {
        fiturAutoTimer = setInterval(function () {
            if (fiturPos >= maxPos) {
                fiturPos = 0;
                fiturScroll.scrollTo({ left: 0, behavior: 'auto' });
            } else {
                setPosition(fiturPos + itemWidth * 2);
            }
        }, 4000);
    }

    function restartAutoScroll() {
        clearInterval(fiturAutoTimer);
        autoScroll();
    }

    // Touch/Drag support
    let dragStart = 0, dragging = false;
    fiturScroll.addEventListener('mousedown', function (e) {
        dragStart = e.clientX;
        dragging = true;
        fiturScroll.style.cursor = 'grabbing';
        clearInterval(fiturAutoTimer);
    });
    document.addEventListener('mousemove', function (e) {
        if (!dragging) return;
        const diff = dragStart - e.clientX;
        setPosition(fiturPos + diff * 0.5);
    });
    document.addEventListener('mouseup', function () {
        if (dragging) {
            dragging = false;
            fiturScroll.style.cursor = 'grab';
            restartAutoScroll();
        }
    });

    fiturScroll.addEventListener('touchstart', function (e) {
        dragStart = e.touches[0].clientX;
        clearInterval(fiturAutoTimer);
    }, { passive: true });
    fiturScroll.addEventListener('touchmove', function (e) {
        const diff = dragStart - e.touches[0].clientX;
        setPosition(fiturPos + diff * 0.3);
    }, { passive: true });
    fiturScroll.addEventListener('touchend', function () {
        restartAutoScroll();
    }, { passive: true });

    // Hover pause
    fiturScroll.addEventListener('mouseenter', function () {
        clearInterval(fiturAutoTimer);
    });
    fiturScroll.addEventListener('mouseleave', restartAutoScroll);

    autoScroll();
})();
/* ================================================================
   BERITA UTAMA CAROUSEL
================================================================ */
(function () {
    let buCurrentSlide = 0;
    const buSlides = document.querySelectorAll('.bu-slide');
    const buDots = document.querySelectorAll('.bu-dot');
    const buProgressBar = document.getElementById('buProgressBar');
    const buTotalSlides = buSlides.length;
    const BU_INTERVAL = 4000; // 4 seconds
    let buTimer = null;
    let buProgress = 0;
    let buProgressTimer = null;

    if (buTotalSlides === 0) return;

    function showSlide(index) {
        buSlides.forEach(s => s.classList.remove('active'));
        buDots.forEach(d => d.classList.remove('active'));
        buCurrentSlide = (index + buTotalSlides) % buTotalSlides;
        buSlides[buCurrentSlide].classList.add('active');
        if (buDots[buCurrentSlide]) buDots[buCurrentSlide].classList.add('active');
        resetProgress();
    }

    function resetProgress() {
        buProgress = 0;
        if (buProgressBar) buProgressBar.style.width = '0%';
    }

    function startAutoPlay() {
        stopAutoPlay();
        buProgressTimer = setInterval(function () {
            buProgress += 100 / (BU_INTERVAL / 50);
            if (buProgressBar) buProgressBar.style.width = Math.min(buProgress, 100) + '%';
            if (buProgress >= 100) {
                showSlide(buCurrentSlide + 1);
            }
        }, 50);
    }

    function stopAutoPlay() {
        clearInterval(buProgressTimer);
    }

    window.slideBeritaUtama = function (dir) {
        showSlide(buCurrentSlide + dir);
        stopAutoPlay();
        startAutoPlay();
    };

    window.goToSlide = function (index) {
        showSlide(index);
        stopAutoPlay();
        startAutoPlay();
    };

    // Touch / swipe support
    let buStartX = 0;
    const buCarousel = document.getElementById('beritaUtamaCarousel');
    if (buCarousel) {
        buCarousel.addEventListener('touchstart', function (e) {
            buStartX = e.touches[0].clientX;
        }, { passive: true });
        buCarousel.addEventListener('touchend', function (e) {
            const diff = buStartX - e.changedTouches[0].clientX;
            if (Math.abs(diff) > 50) {
                slideBeritaUtama(diff > 0 ? 1 : -1);
            }
        }, { passive: true });

        // Pause on hover
        buCarousel.addEventListener('mouseenter', stopAutoPlay);
        buCarousel.addEventListener('mouseleave', startAutoPlay);
    }

    startAutoPlay();
})();

/* ================================================================
   PEMBARUAN TABS
================================================================ */
window.switchTab = function (tab) {
    document.querySelectorAll('.tab-btn-sm').forEach(function (t) {
        t.classList.toggle('active', t.dataset.tab === tab);
    });
    document.querySelectorAll('.pembaruan-content').forEach(function (c) {
        c.classList.toggle('active', c.id === 'tab-' + tab);
    });
};

/* ================================================================
   YANG TERLEWAT CAROUSEL (Drag + Auto Scroll)
================================================================ */
(function () {
    const track = document.getElementById('terlewatTrack');
    if (!track) return;

    let terlewatPos = 0;
    const cardWidth = 300; // min-width + gap
    const totalCards = track.children.length;
    const maxPos = Math.max(0, (totalCards * cardWidth) - track.parentElement.offsetWidth);
    let terlewatAutoTimer = null;

    function setPosition(pos) {
        terlewatPos = Math.max(0, Math.min(pos, maxPos));
        track.style.transform = 'translateX(-' + terlewatPos + 'px)';
    }

    window.slideTerlewat = function (dir) {
        setPosition(terlewatPos + dir * cardWidth);
        restartAutoScroll();
    };

    // Auto scroll
    function autoScroll() {
        terlewatAutoTimer = setInterval(function () {
            if (terlewatPos >= maxPos) {
                terlewatPos = 0;
                track.style.transition = 'none';
                track.style.transform = 'translateX(0px)';
                requestAnimationFrame(function () {
                    track.style.transition = 'transform 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
                });
            } else {
                setPosition(terlewatPos + cardWidth);
            }
        }, 4000);
    }

    function restartAutoScroll() {
        clearInterval(terlewatAutoTimer);
        autoScroll();
    }

    // Touch/Drag support
    let dragStart = 0, dragging = false;
    track.addEventListener('mousedown', function (e) {
        dragStart = e.clientX;
        dragging = true;
        track.style.cursor = 'grabbing';
    });
    document.addEventListener('mousemove', function (e) {
        if (!dragging) return;
        const diff = dragStart - e.clientX;
        setPosition(terlewatPos + diff * 0.5);
    });
    document.addEventListener('mouseup', function () {
        if (dragging) {
            dragging = false;
            track.style.cursor = 'grab';
        }
    });

    track.addEventListener('touchstart', function (e) {
        dragStart = e.touches[0].clientX;
        clearInterval(terlewatAutoTimer);
    }, { passive: true });
    track.addEventListener('touchmove', function (e) {
        const diff = dragStart - e.touches[0].clientX;
        setPosition(terlewatPos + diff * 0.3);
    }, { passive: true });
    track.addEventListener('touchend', function () {
        restartAutoScroll();
    }, { passive: true });

    // Hover pause
    track.parentElement.addEventListener('mouseenter', function () {
        clearInterval(terlewatAutoTimer);
    });
    track.parentElement.addEventListener('mouseleave', restartAutoScroll);

    autoScroll();
})();


/* ================================================================
   BERITA TERAKHIR AUTO SCROLL - Duplicate items for infinite loop
================================================================ */
(function () {
    const beritaTerakhirScroll = document.getElementById('beritaTerakhirScroll');
    if (!beritaTerakhirScroll || beritaTerakhirScroll.children.length < 2) return;
    // Clone items for seamless loop
    const items = beritaTerakhirScroll.innerHTML;
    beritaTerakhirScroll.innerHTML = items + items;
})();

/* ================================================================
   TAGAR MOBILE AUTO SCROLL
================================================================ */
(function () {
    if (window.innerWidth > 767) return;
    var tagarList = document.querySelector('.tagar-list-static');
    if (!tagarList) return;
    var inner = document.createElement('div');
    inner.className = 'tagar-mobile-scroll';
    inner.innerHTML = tagarList.innerHTML + tagarList.innerHTML;
    tagarList.innerHTML = '';
    tagarList.appendChild(inner);
})();
</script>
@endpush
@endsection
