@extends('layouts.public')
@section('title', $event->localized_nama_kegiatan)

@push('styles')
<style>
/* ===== HERO CAROUSEL ===== */
.event-hero {
    position: relative;
    width: 100%;
    height: 70vh;
    min-height: 400px;
    max-height: 700px;
    overflow: hidden;
    background: #001f3f;
}
.event-hero .carousel-inner,
.event-hero .carousel-item {
    height: 100%;
}
.event-hero .carousel-item img,
.event-hero .carousel-item video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.event-hero .carousel-item::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(0deg, rgba(0,31,63,0.85) 0%, rgba(0,31,63,0.3) 40%, transparent 70%);
}
.event-hero .hero-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 10;
    padding: 2.5rem 0;
}
.event-hero .hero-overlay h1 {
    font-size: 2.8rem;
    font-weight: 800;
    color: #fff;
    text-shadow: 0 2px 20px rgba(0,0,0,0.5);
    letter-spacing: -0.5px;
}
.event-hero .hero-overlay .hero-meta {
    color: rgba(255,255,255,0.85);
    font-size: 1rem;
}
.event-hero .carousel-control-prev,
.event-hero .carousel-control-next {
    z-index: 15;
    width: 60px;
    opacity: 0.6;
}
.event-hero .carousel-control-prev:hover,
.event-hero .carousel-control-next:hover { opacity: 1; }
.event-hero .carousel-indicators {
    z-index: 15;
    margin-bottom: 1rem;
}
.event-hero .carousel-indicators button {
    width: 12px; height: 12px;
    border-radius: 50%;
    margin: 0 4px;
    border: 2px solid rgba(255,255,255,0.7);
    background: transparent;
    opacity: 0.6;
}
.event-hero .carousel-indicators button.active {
    background: #ffc107;
    border-color: #ffc107;
    opacity: 1;
}
.hero-no-media {
    height: 300px;
    background: linear-gradient(135deg, #001f3f 0%, #003d82 50%, #0066cc 100%);
    display: flex;
    align-items: flex-end;
}

/* ===== SECTION TITLES ===== */
.section-title {
    position: relative;
    font-size: 1.6rem;
    font-weight: 800;
    color: #001f3f;
    text-transform: uppercase;
    letter-spacing: 1px;
    padding-bottom: 0.75rem;
    margin-bottom: 2rem;
}
[data-theme="dark"] .section-title { color: #e0e0e0; }
.section-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, #003d82, #0066cc);
    border-radius: 2px;
}
.section-title.text-center::after {
    left: 50%;
    transform: translateX(-50%);
}

/* ===== GALERI FOTO ===== */
.galeri-section { background: #f8f9fa; }
[data-theme="dark"] .galeri-section { background: var(--bg-color); }
.galeri-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1rem;
}
.galeri-item {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
    aspect-ratio: 4/3;
}
.galeri-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.2);
}
.galeri-item img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
}
.galeri-item:hover img { transform: scale(1.08); }
.galeri-item .galeri-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(0deg, rgba(0,31,63,0.7) 0%, transparent 50%);
    display: flex;
    align-items: flex-end;
    padding: 1rem;
    opacity: 0;
    transition: opacity 0.3s;
}
.galeri-item:hover .galeri-overlay { opacity: 1; }
.galeri-item .galeri-overlay span {
    color: #fff; font-size: 0.85rem; font-weight: 600;
}
.galeri-item .zoom-icon {
    position: absolute; top: 50%; left: 50%;
    transform: translate(-50%, -50%) scale(0);
    color: #fff; font-size: 2rem;
    transition: transform 0.3s;
    text-shadow: 0 2px 10px rgba(0,0,0,0.5);
}
.galeri-item:hover .zoom-icon { transform: translate(-50%, -50%) scale(1); }

/* ===== GALERI VIDEO ===== */
.video-section { background: #001f3f; }
.video-card {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    transition: transform 0.3s, box-shadow 0.3s;
    background: #0a1628;
}
.video-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.5);
}
.video-card .ratio { border-radius: 12px 12px 0 0; }
.video-card video { border-radius: 12px; }

/* ===== BERITA ===== */
.berita-section { background: #f8f9fa; }
[data-theme="dark"] .berita-section { background: var(--bg-color); }
.berita-card {
    border: none;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transition: transform 0.3s, box-shadow 0.3s;
    height: 100%;
}
.berita-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}
.berita-card .card-img-top {
    height: 200px;
    object-fit: cover;
}
.berita-card .card-title {
    font-size: 1rem;
    font-weight: 700;
    color: #001f3f;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
[data-theme="dark"] .berita-card .card-title { color: #e0e0e0; }
.berita-card .card-text {
    font-size: 0.85rem;
    color: #666;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
[data-theme="dark"] .berita-card .card-text { color: #aaa; }

/* ===== BACK BUTTON ===== */
.back-btn {
    position: fixed;
    top: 160px;
    left: 20px;
    z-index: 1000;
    width: 45px; height: 45px;
    border-radius: 50%;
    background: rgba(0,61,130,0.85);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    text-decoration: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    transition: background 0.3s, transform 0.3s;
    backdrop-filter: blur(10px);
}
.back-btn:hover { background: #003d82; color: #fff; transform: scale(1.1); }

/* ===== LIGHTBOX ===== */
.lightbox-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.92);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    cursor: zoom-out;
}
.lightbox-overlay.active { display: flex; }
.lightbox-overlay img {
    max-width: 90vw;
    max-height: 90vh;
    object-fit: contain;
    border-radius: 8px;
    box-shadow: 0 0 40px rgba(0,0,0,0.5);
}
.lightbox-close {
    position: absolute; top: 20px; right: 25px;
    color: #fff; font-size: 2rem; cursor: pointer;
    z-index: 10000;
    transition: transform 0.2s;
}
.lightbox-close:hover { transform: scale(1.2); }
.lightbox-nav {
    position: absolute; top: 50%;
    transform: translateY(-50%);
    color: #fff; font-size: 2.5rem;
    cursor: pointer; z-index: 10000;
    padding: 10px;
    transition: opacity 0.2s;
    opacity: 0.7;
}
.lightbox-nav:hover { opacity: 1; }
.lightbox-prev { left: 20px; }
.lightbox-next { right: 20px; }

/* ===== MOBILE: fallback hero title ===== */
@media (max-width: 767.98px) {
    .event-hero-fallback-title { font-size: 1.5rem !important; }
}

/* ===== DESKRIPSI ===== */
.deskripsi-section { background: #fff; }
[data-theme="dark"] .deskripsi-section { background: var(--bg-color); }
.deskripsi-box {
    max-width: 900px;
    margin: 0 auto;
    font-size: 1.1rem;
    line-height: 1.8;
    color: #444;
}
[data-theme="dark"] .deskripsi-box { color: #ccc; }

/* ===== ANIMATIONS ===== */
.fade-up {
    opacity: 0; transform: translateY(30px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}
.fade-up.visible { opacity: 1; transform: translateY(0); }

@media (max-width: 768px) {
    .event-hero { height: 50vh; min-height: 280px; }
    .event-hero .hero-overlay h1 { font-size: 1.6rem; }
    .galeri-grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 0.5rem; }
    .galeri-item { border-radius: 8px; }
    .section-title { font-size: 1.2rem; }
    .back-btn { top: 120px; left: 10px; width: 38px; height: 38px; font-size: 0.9rem; }
}
</style>
@endpush

@section('content')
{{-- Back Button --}}
<a href="{{ route('events.index') }}" class="back-btn" title="{{ __('messages.btn_kembali') }}">
    <i class="fas fa-arrow-left"></i>
</a>

{{-- ======================== HERO CAROUSEL ======================== --}}
@if($event->heroes->count())
<section class="event-hero">
    <div id="heroCarousel" class="carousel slide h-100" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-indicators">
            @foreach($event->heroes as $i => $hero)
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $i }}" @if($i === 0) class="active" @endif></button>
            @endforeach
        </div>
        <div class="carousel-inner h-100">
            @foreach($event->heroes as $i => $hero)
            <div class="carousel-item h-100 @if($i === 0) active @endif">
                @if($hero->type === 'video' && $hero->file_path)
                    <video autoplay muted loop playsinline>
                        <source src="{{ asset('storage/' . $hero->file_path) }}">
                    </video>
                @elseif($hero->type === 'video' && $hero->video_url)
                    @php
                        $embedUrl = $hero->video_url;
                        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $hero->video_url, $m)) {
                            $embedUrl = 'https://www.youtube.com/embed/' . $m[1] . '?autoplay=1&mute=1&loop=1&controls=0&showinfo=0';
                        }
                    @endphp
                    <iframe src="{{ $embedUrl }}" class="w-100 h-100" style="border:0;" allowfullscreen></iframe>
                @else
                    <img src="{{ asset('storage/' . $hero->file_path) }}" alt="{{ $event->localized_nama_kegiatan }}">
                @endif
            </div>
            @endforeach
        </div>
        @if($event->heroes->count() > 1)
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
        @endif
    </div>
    <div class="hero-overlay">
        <div class="container">
            <h1 class="mb-2">{{ $event->localized_nama_kegiatan }}</h1>
            @if($event->tanggal_kegiatan)
            <div class="hero-meta">
                <i class="fas fa-calendar-alt me-2"></i>{{ $event->tanggal_kegiatan->translatedFormat('d F Y') }}
            </div>
            @endif
        </div>
    </div>
</section>
@else
{{-- Fallback hero tanpa media --}}
<section class="hero-no-media">
    <div class="container pb-4">
        <h1 class="text-white fw-bold mb-2 event-hero-fallback-title" style="font-size:2.5rem;text-shadow:0 2px 15px rgba(0,0,0,0.4);">{{ $event->localized_nama_kegiatan }}</h1>
        @if($event->tanggal_kegiatan)
        <div class="text-white-50"><i class="fas fa-calendar-alt me-2"></i>{{ $event->tanggal_kegiatan->translatedFormat('d F Y') }}</div>
        @endif
    </div>
</section>
@endif

{{-- ======================== DESKRIPSI ======================== --}}
@if($event->localized_deskripsi)
<section class="deskripsi-section py-5">
    <div class="container">
        <div class="deskripsi-box fade-up text-center">
            <h2 class="section-title text-center">{{ __('messages.heading_tentang_event') }} {{ $event->localized_nama_kegiatan }}</h2>
            <p>{{ $event->localized_deskripsi }}</p>
        </div>
    </div>
</section>
@endif

{{-- ======================== GALERI FOTO ======================== --}}
@if($event->galeriFotos->count())
<section class="galeri-section py-5">
    <div class="container">
        <h2 class="section-title text-center fade-up">
            <i class="fas fa-images me-2" style="color:#0066cc;"></i>{{ __('messages.heading_galeri_prestasi') }} {{ $event->localized_nama_kegiatan }}
        </h2>
        <div class="galeri-grid fade-up">
            @foreach($event->galeriFotos as $foto)
            <div class="galeri-item" data-lightbox="{{ asset('storage/' . $foto->file_path) }}">
                <img src="{{ asset('storage/' . $foto->file_path) }}" alt="{{ $foto->keterangan ?? $event->localized_nama_kegiatan }}" loading="lazy">
                <i class="fas fa-search-plus zoom-icon"></i>
                @if($foto->keterangan)
                <div class="galeri-overlay">
                    <span>{{ $foto->keterangan }}</span>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ======================== GALERI VIDEO ======================== --}}
@if($event->galeriVideos->count())
<section class="video-section py-5">
    <div class="container">
        <h2 class="section-title text-center fade-up" style="color:#fff;">
            <i class="fas fa-play-circle me-2" style="color:#ffc107;"></i>{{ __('messages.heading_galeri_video') }}
        </h2>
        <div class="row g-4 justify-content-center">
            @foreach($event->galeriVideos as $vid)
            <div class="col-md-6 col-lg-4 fade-up">
                <div class="video-card">
                    @if($vid->file_path)
                        <video controls class="w-100" style="border-radius:12px;" preload="metadata">
                            <source src="{{ asset('storage/' . $vid->file_path) }}">
                        </video>
                    @elseif($vid->video_url)
                        @php
                            $vUrl = $vid->video_url;
                            $eUrl = $vUrl;
                            if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $vUrl, $m)) {
                                $eUrl = 'https://www.youtube.com/embed/' . $m[1];
                            }
                        @endphp
                        <div class="ratio ratio-16x9">
                            <iframe src="{{ $eUrl }}" allowfullscreen style="border:0;border-radius:12px 12px 0 0;"></iframe>
                        </div>
                    @endif
                    @if($vid->keterangan)
                    <div class="p-3">
                        <p class="mb-0 text-white small">{{ $vid->keterangan }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ======================== BERITA ======================== --}}
@if($berita->count())
<section class="berita-section py-5">
    <div class="container">
        <h2 class="section-title text-center fade-up">
            <i class="fas fa-newspaper me-2" style="color:#003d82;"></i>{{ __('messages.heading_berita_terkini') }}
        </h2>
        <div class="row g-4 justify-content-center">
            @foreach($berita as $item)
            <div class="col-sm-6 col-lg-4 fade-up">
                <a href="{{ route('berita.show', $item->slug) }}" class="text-decoration-none">
                    <div class="berita-card card">
                        @if($item->gambar_utama)
                        <img src="{{ asset('storage/' . $item->gambar_utama) }}" class="card-img-top" alt="{{ $item->localized_judul }}" loading="lazy">
                        @else
                        <div class="card-img-top d-flex align-items-center justify-content-center" style="height:200px;background:linear-gradient(135deg,#003d82,#0066cc);">
                            <i class="fas fa-newspaper fa-3x text-white" style="opacity:0.3;"></i>
                        </div>
                        @endif
                        <div class="card-body">
                            <h6 class="card-title">{{ $item->localized_judul }}</h6>
                            @if($item->ringkasan)
                            <p class="card-text">{{ $item->localized_ringkasan }}</p>
                            @endif
                            <div class="d-flex align-items-center justify-content-between mt-2">
                                <small class="text-muted"><i class="fas fa-calendar-alt me-1"></i>{{ $item->published_at?->translatedFormat('d M Y') }}</small>
                                <small class="text-primary fw-bold">{{ __('messages.btn_baca') }} <i class="fas fa-arrow-right ms-1"></i></small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ======================== LIGHTBOX ======================== --}}
<div class="lightbox-overlay" id="lightbox">
    <span class="lightbox-close">&times;</span>
    <span class="lightbox-nav lightbox-prev"><i class="fas fa-chevron-left"></i></span>
    <span class="lightbox-nav lightbox-next"><i class="fas fa-chevron-right"></i></span>
    <img src="" alt="Preview" id="lightboxImg">
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ===== Scroll Animations =====
    const obs = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); }});
    }, { threshold: 0.15 });
    document.querySelectorAll('.fade-up').forEach(el => obs.observe(el));

    // ===== Lightbox =====
    const items = document.querySelectorAll('[data-lightbox]');
    const overlay = document.getElementById('lightbox');
    const lbImg = document.getElementById('lightboxImg');
    let currentIdx = 0;
    const urls = Array.from(items).map(el => el.dataset.lightbox);

    function showLightbox(idx) {
        if (idx < 0 || idx >= urls.length) return;
        currentIdx = idx;
        lbImg.src = urls[idx];
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeLightbox() {
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }
    items.forEach((el, i) => el.addEventListener('click', () => showLightbox(i)));
    overlay.querySelector('.lightbox-close').addEventListener('click', closeLightbox);
    overlay.addEventListener('click', (e) => { if (e.target === overlay) closeLightbox(); });
    overlay.querySelector('.lightbox-prev').addEventListener('click', (e) => { e.stopPropagation(); showLightbox(currentIdx - 1); });
    overlay.querySelector('.lightbox-next').addEventListener('click', (e) => { e.stopPropagation(); showLightbox(currentIdx + 1); });
    document.addEventListener('keydown', (e) => {
        if (!overlay.classList.contains('active')) return;
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') showLightbox(currentIdx - 1);
        if (e.key === 'ArrowRight') showLightbox(currentIdx + 1);
    });
});
</script>
@endpush
