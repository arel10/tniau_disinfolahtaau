{{-- ============================================================ --}}
{{-- SHARED SECTIONS: Yang Terlewat, Galeri Kegiatan, Instansi   --}}
{{-- Included on ALL public pages via layouts.public             --}}
{{-- ============================================================ --}}

<style>
/* === Shared Section Header === */
.shared-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}
.shared-section-title {
    font-size: 1.5rem;
    font-weight: 800;
    color: #1a1a2e;
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
    flex-wrap: wrap;
}
[data-theme="dark"] .shared-section-title { color: #e0e0e0; }
.shared-title-accent {
    width: 5px;
    height: 28px;
    background: linear-gradient(180deg, #003d82, #0066cc);
    border-radius: 3px;
    display: inline-block;
    flex-shrink: 0;
}
.shared-see-all-link {
    color: #0066cc;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s;
    white-space: nowrap;
}
.shared-see-all-link:hover { color: #003d82; }
.shared-see-all-link i { transition: transform 0.3s; }
.shared-see-all-link:hover i { transform: translateX(4px); }

/* === Yang Terlewat === */
.shared-terlewat-section { background: #fff; }
[data-theme="dark"] .shared-terlewat-section { background: #1a1a2e; }

.shared-terlewat-carousel { overflow: hidden; position: relative; }
.shared-terlewat-track {
    display: flex;
    gap: 20px;
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: grab;
}
.shared-terlewat-track:active { cursor: grabbing; }

.shared-terlewat-card {
    min-width: 260px; max-width: 260px;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s;
    flex-shrink: 0;
}
.shared-terlewat-card:hover { transform: translateY(-4px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); border-color: #003d82; }
[data-theme="dark"] .shared-terlewat-card { background: #16213e; border-color: #2a2a4a; }
[data-theme="dark"] .shared-terlewat-card:hover { border-color: #4da3ff; }

.shared-terlewat-img-wrap { position: relative; height: 140px; overflow: hidden; }
.shared-terlewat-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s; }
.shared-terlewat-card:hover .shared-terlewat-img-wrap img { transform: scale(1.05); }
.shared-terlewat-img-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    background: #e9ecef; color: #999;
}
[data-theme="dark"] .shared-terlewat-img-placeholder { background: #2a2a4a; color: #666; }

.shared-terlewat-body { padding: 12px; }
.shared-terlewat-kategori {
    display: inline-block; background: #003d82; color: #fff;
    padding: 3px 10px; font-size: 0.65rem; font-weight: 600; border-radius: 3px; margin-bottom: 8px;
}
.shared-terlewat-title {
    display: block; color: #1a1a2e; font-weight: 700; font-size: 0.9rem;
    line-height: 1.35; text-decoration: none; margin-bottom: 8px;
}
.shared-terlewat-title:hover { color: #003d82; }
[data-theme="dark"] .shared-terlewat-title { color: #e0e0e0; }
[data-theme="dark"] .shared-terlewat-title:hover { color: #4da3ff; }

.shared-terlewat-meta { font-size: 0.72rem; color: #666; display: flex; flex-wrap: wrap; gap: 10px; }
.shared-terlewat-meta i { margin-right: 4px; }
[data-theme="dark"] .shared-terlewat-meta { color: #aaa; }

.shared-nav-btn {
    background: #fff; border: 1px solid #ddd; border-radius: 4px;
    width: 32px; height: 32px; cursor: pointer; transition: all 0.2s;
    display: flex; align-items: center; justify-content: center;
}
.shared-nav-btn:hover { background: #003d82; color: #fff; border-color: #003d82; }
[data-theme="dark"] .shared-nav-btn { background: #2a2a4a; border-color: #3a3a5a; color: #ccc; }
[data-theme="dark"] .shared-nav-btn:hover { background: #003d82; color: #fff; border-color: #003d82; }

/* === Galeri Carousel === */
.shared-galeri-carousel { overflow: hidden; position: relative; }
.shared-galeri-track {
    display: flex;
    gap: 16px;
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: grab;
}
.shared-galeri-track:active { cursor: grabbing; }

.shared-galeri-card {
    min-width: 200px; max-width: 200px;
    flex-shrink: 0;
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.3s, box-shadow 0.3s;
}
.shared-galeri-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}
.shared-galeri-card img {
    width: 100%; height: 160px; object-fit: cover; display: block;
    border-radius: 12px;
}
.shared-galeri-card .galeri-placeholder {
    width: 100%; height: 160px;
    display: flex; align-items: center; justify-content: center;
    background: #dee2e6; color: #999; border-radius: 12px;
}
[data-theme="dark"] .shared-galeri-card .galeri-placeholder { background: #2a2a4a; color: #666; }

/* === Compact horizontally-scrollable gallery grid (5 rows) === */
.galeri-scroll-grid {
    display: grid;
    grid-auto-flow: column;
    grid-auto-columns: 200px; /* restored to larger columns */
    grid-template-rows: repeat(3, 220px); /* larger rows to fit thumbnails */
    gap: 16px; /* restore spacing */
    overflow-x: auto;
    padding: 10px 8px;
    -webkit-overflow-scrolling: touch;
    scroll-snap-type: x mandatory;
    max-width: 100%;
}
.galeri-scroll-grid::-webkit-scrollbar { height: 9px; }
.galeri-scroll-grid::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.12); border-radius: 8px; }
.galeri-item { display: block; scroll-snap-align: start; }
.galeri-item .card { display: block; border-radius: 8px; overflow: hidden; background: transparent; border: none; }
.galeri-item .card-img-top { width: 100%; height: 160px; display:block; object-fit: cover; border-top-left-radius: 8px; border-top-right-radius: 8px; }
.galeri-item .card-body { padding: .45rem; font-size: .85rem; }
/* Ensure no caption block shows under thumbnails in the gallery */
.galeri-item .card-body { display: none !important; }
@media (max-width: 992px) {
    .galeri-scroll-grid { grid-auto-columns: 150px; grid-template-rows: repeat(3, 180px); }
    .galeri-item .card-img-top { height: 120px; }
}

/* Drag-to-scroll for main gallery */
.galeri-scroll-grid.dragging { cursor: grabbing; }

[data-theme="dark"] .shared-see-all-link { color: #7db8ff; }
[data-theme="dark"] .shared-see-all-link:hover { color: #4da3ff; }

/* === Galeri Video Section === */
.galeri-video-shared-section {
    background: linear-gradient(135deg, #001a3a 0%, #003d82 100%);
}
.shared-galeri-video-carousel { overflow: hidden; position: relative; }
.shared-galeri-video-track {
    display: flex;
    gap: 16px;
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: grab;
}
.shared-galeri-video-track:active { cursor: grabbing; }
.shared-video-card {
    min-width: 220px; max-width: 220px;
    flex-shrink: 0;
    border-radius: 12px;
    overflow: hidden;
    text-decoration: none;
    display: block;
    transition: transform 0.3s, box-shadow 0.3s;
}
.shared-video-card:hover { transform: translateY(-4px); box-shadow: 0 8px 25px rgba(0,0,0,.4); }
.shared-video-thumb {
    position: relative;
    height: 140px;
    border-radius: 12px;
    overflow: hidden;
    background: #0a1628;
}
.shared-video-thumb-img { width:100%; height:100%; object-fit:cover; display:block; }
.shared-video-no-thumb { width:100%; height:100%; display:flex; align-items:center; justify-content:center; }
.shared-video-play-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,.3);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background .2s;
}
.shared-video-card:hover .shared-video-play-overlay { background: rgba(0,0,0,.5); }
.shared-video-play-overlay i { font-size: 2.5rem; color: #ffc107; filter: drop-shadow(0 2px 8px rgba(0,0,0,.5)); transition: transform .2s; }
.shared-video-card:hover .shared-video-play-overlay i { transform: scale(1.1); }
.shared-video-label { color: rgba(255,255,255,.8); font-size:.8rem; margin:.6rem 0 0; padding: 0 2px; }
/* === Galeri Foto Section Background === */
.shared-galeri-foto-section { background: #f8f9fa; }
[data-theme="dark"] .shared-galeri-foto-section {
    background: #1a1a2e !important;
}
[data-theme="dark"] .shared-galeri-foto-section .shared-galeri-card .galeri-placeholder {
    background: #2a2a4a; color: #666;
}

/* === Instansi Section Background === */
.shared-instansi-section { background: #f8f9fa; }
[data-theme="dark"] .shared-instansi-section {
    background: #16213e !important;
}
[data-theme="dark"] .shared-instansi-section h3 {
    color: #e0e0e0 !important;
}
[data-theme="dark"] .shared-instansi-logo {
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.4)) brightness(0.9);
}

/* === Video Gallery – dark mode nav buttons === */
[data-theme="dark"] .galeri-video-shared-section .shared-nav-btn {
    background: rgba(255,255,255,.1);
    border-color: rgba(255,255,255,.2);
    color: #e0e0e0;
}
[data-theme="dark"] .galeri-video-shared-section .shared-nav-btn:hover {
    background: #ffc107;
    color: #001a3a;
    border-color: #ffc107;
}

/* === Instansi === */
.shared-instansi-logo {
    max-height: 120px; width: auto;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
    transition: transform 0.3s ease; margin: 0 auto;
}
.shared-instansi-logo:hover { transform: scale(1.1); }

/* === Responsive === */
@media (max-width: 767.98px) {
    .shared-section-title { font-size: 1.2rem; }
}
</style>

{{-- ======================== YANG TERLEWAT ======================== --}}
@if(isset($yang_terlewat) && $yang_terlewat->count())
<section class="shared-terlewat-section py-5">
    <div class="container">
        <div class="shared-section-header mb-4">
            <h2 class="shared-section-title">
                <span class="shared-title-accent"></span>
                {{ __('messages.yang_terlewat') }}
            </h2>
            <div class="d-flex gap-1" id="sharedTerlewatNav">
                <button class="shared-nav-btn" onclick="sharedSlideTerlewat(-1)"><i class="fas fa-chevron-left"></i></button>
                <button class="shared-nav-btn" onclick="sharedSlideTerlewat(1)"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>

        <div class="shared-terlewat-carousel" id="sharedTerlewatCarousel">
            <div class="shared-terlewat-track" id="sharedTerlewatTrack">
                @foreach($yang_terlewat as $berita)
                <div class="shared-terlewat-card">
                    <div class="shared-terlewat-img-wrap">
                        @if($berita->gambar_utama)
                            <img src="{{ asset('storage/' . $berita->gambar_utama) }}" alt="{{ $berita->localized_judul }}">
                        @else
                            <div class="shared-terlewat-img-placeholder"><i class="fas fa-image fa-2x"></i></div>
                        @endif
                    </div>
                    <div class="shared-terlewat-body">
                        <span class="shared-terlewat-kategori">{{ $berita->kategori->localized_nama_kategori ?? __('messages.category_default') }}</span>
                        <a href="{{ route('berita.show', $berita->slug) }}" class="shared-terlewat-title">{{ Str::limit($berita->localized_judul, 55) }}</a>
                        <div class="shared-terlewat-meta">
                            <span><i class="fas fa-user"></i> {{ $berita->user->name ?? __('messages.author_default') }}</span>
                            <span><i class="far fa-calendar"></i> {{ $berita->published_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

{{-- ======================== GALERI VIDEO ======================== --}}
{{-- Video gallery disabled here — only shown on PTM AU custom page via show.blade.php --}}
@php $galeriVideoItems = $galeri_video ?? collect(); @endphp
@if(false)
<section class="galeri-video-shared-section py-5">
    <div class="container">
        <div class="shared-section-header mb-4">
            <h2 class="shared-section-title" style="color:#fff;">
                <span class="shared-title-accent" style="background:linear-gradient(180deg,#ffc107,#ff8c00);"></span>
                <span>{{ __('messages.heading_galeri_video') }}</span>
            </h2>
            <div class="d-flex align-items-center gap-2">
                <div class="d-flex gap-1">
                    <button class="shared-nav-btn" onclick="sharedSlideGaleriVideo(-1)"><i class="fas fa-chevron-left"></i></button>
                    <button class="shared-nav-btn" onclick="sharedSlideGaleriVideo(1)"><i class="fas fa-chevron-right"></i></button>
                </div>
                <a href="{{ route('galeri.index') }}" class="shared-see-all-link" style="color:#ffc107;">{{ __('messages.see_all') }} <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
        <div class="shared-galeri-video-carousel" id="sharedGaleriVideoCarousel">
            <div class="shared-galeri-video-track" id="sharedGaleriVideoTrack">
                @foreach($galeriVideoItems as $gvid)
                @php
                    $gvEmbed = null; $gvIsYt = false;
                    if ($gvid->video_url) {
                        $gvEmbed = $gvid->embed_url;
                        if (str_contains($gvEmbed, 'youtube.com/embed') || str_contains($gvEmbed, 'youtu.be')) $gvIsYt = true;
                    }
                @endphp
                <a href="{{ route('galeri.show', $gvid->id) }}" class="shared-video-card" title="{{ $gvid->judul }}">
                    <div class="shared-video-thumb">
                        @if($gvid->thumbnail_url)
                            <img src="{{ $gvid->thumbnail_url }}" alt="{{ $gvid->judul }}" class="shared-video-thumb-img">
                        @elseif($gvid->video_file)
                            <video src="{{ asset('storage/'.$gvid->video_file) }}" class="shared-video-thumb-img" muted preload="metadata"></video>
                        @else
                            <div class="shared-video-no-thumb"><i class="fas fa-film fa-2x" style="color:rgba(255,255,255,.3);"></i></div>
                        @endif
                        <div class="shared-video-play-overlay"><i class="fas fa-play-circle"></i></div>
                    </div>
                    @if($gvid->judul)
                    <p class="shared-video-label">{{ Str::limit($gvid->judul, 38) }}</p>
                    @endif
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

{{-- ======================== GALERI FOTO ======================== --}}
@php $galeriFotoItems = isset($galeri_foto) && $galeri_foto->count() ? $galeri_foto : ($galeri_terbaru ?? collect()); @endphp
@if($galeriFotoItems->count())
<div class="shared-galeri-foto-section py-5">
    <div class="container">
        <div class="shared-section-header mb-4">
            <h2 class="shared-section-title">
                <span class="shared-title-accent"></span>
                {{ __('messages.heading_galeri_prestasi') }}
            </h2>
            <div class="d-flex align-items-center gap-2">
                <div class="d-flex gap-1" id="sharedGaleriNav">
                    <button class="shared-nav-btn" onclick="sharedSlideGaleri(-1)"><i class="fas fa-chevron-left"></i></button>
                    <button class="shared-nav-btn" onclick="sharedSlideGaleri(1)"><i class="fas fa-chevron-right"></i></button>
                </div>
                <a href="{{ route('galeri.index') }}" class="shared-see-all-link">{{ __('messages.see_all') }} <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        <div class="shared-galeri-carousel" id="sharedGaleriCarousel">
            <div class="shared-galeri-track" id="sharedGaleriTrack">
                @foreach($galeriFotoItems as $galeri)
                <a href="{{ route('galeri.show', $galeri->id) }}" class="shared-galeri-card">
                    @if($galeri->thumbnail_url)
                        <img src="{{ $galeri->thumbnail_url }}" alt="{{ $galeri->judul }}">
                    @else
                        <div class="galeri-placeholder"><i class="fas fa-image fa-2x"></i></div>
                    @endif
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

{{-- ======================== INSTANSI TERKAIT ======================== --}}
<div class="shared-instansi-section py-5">
    <div class="container">
        <h3 class="text-center fw-bold mb-5" style="letter-spacing: 2px;">{{ __('messages.instansi_terkait') }}</h3>
        <div class="row align-items-center justify-content-center g-4">
            @forelse($instansi_terkait ?? collect() as $inst)
            <div class="col-6 col-md-2 text-center">
                @if($inst->link)
                    <a href="{{ $inst->link }}" target="_blank" title="{{ $inst->nama ?? $inst->link }}">
                        <img src="{{ asset($inst->logo) }}" alt="{{ $inst->nama }}" class="img-fluid shared-instansi-logo">
                    </a>
                @else
                    <img src="{{ asset($inst->logo) }}" alt="{{ $inst->nama }}" class="img-fluid shared-instansi-logo">
                @endif
            </div>
            @empty
            {{-- fallback static logos --}}
            <div class="col-6 col-md-2 text-center">
                <a href="https://www.kemhan.go.id" target="_blank" title="Kementerian Pertahanan RI">
                    <img src="{{ asset('assets/image/instansi/kemhan.png') }}" alt="Kementerian Pertahanan" class="img-fluid shared-instansi-logo">
                </a>
            </div>
            <div class="col-6 col-md-2 text-center">
                <a href="https://www.tni.mil.id" target="_blank" title="TNI">
                    <img src="{{ asset('assets/image/instansi/tni.png') }}" alt="TNI" class="img-fluid shared-instansi-logo">
                </a>
            </div>
            <div class="col-6 col-md-2 text-center">
                <a href="https://www.tni-ad.mil.id" target="_blank" title="TNI AD">
                    <img src="{{ asset('assets/image/instansi/tni-ad.png') }}" alt="TNI AD" class="img-fluid shared-instansi-logo">
                </a>
            </div>
            <div class="col-6 col-md-2 text-center">
                <a href="https://www.tni-al.mil.id" target="_blank" title="TNI AL">
                    <img src="{{ asset('assets/image/instansi/tni-al.png') }}" alt="TNI AL" class="img-fluid shared-instansi-logo">
                </a>
            </div>
            <div class="col-6 col-md-2 text-center">
                <a href="https://www.tni-au.mil.id" target="_blank" title="TNI AU">
                    <img src="{{ asset('assets/image/instansi/tni-au.png') }}" alt="TNI AU" class="img-fluid shared-instansi-logo">
                </a>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
/* ================================================================
   YANG TERLEWAT CAROUSEL (Shared - all pages)
================================================================ */
(function () {
    const track = document.getElementById('sharedTerlewatTrack');
    if (!track) return;

    let pos = 0;
    const cardWidth = 280; // min-width + gap
    const totalCards = track.children.length;
    const maxPos = Math.max(0, (totalCards * cardWidth) - track.parentElement.offsetWidth);
    let autoTimer = null;

    function setPos(p) {
        pos = Math.max(0, Math.min(p, maxPos));
        track.style.transform = 'translateX(-' + pos + 'px)';
    }

    window.sharedSlideTerlewat = function (dir) {
        setPos(pos + dir * cardWidth);
        restartAuto();
    };

    function autoScroll() {
        autoTimer = setInterval(function () {
            if (pos >= maxPos) {
                pos = 0;
                track.style.transition = 'none';
                track.style.transform = 'translateX(0px)';
                requestAnimationFrame(function () {
                    track.style.transition = 'transform 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
                });
            } else {
                setPos(pos + cardWidth);
            }
        }, 4000);
    }

    function restartAuto() {
        clearInterval(autoTimer);
        autoScroll();
    }

    // Touch/Drag
    let dragStart = 0, dragging = false;
    track.addEventListener('mousedown', function (e) { dragStart = e.clientX; dragging = true; track.style.cursor = 'grabbing'; });
    document.addEventListener('mousemove', function (e) { if (!dragging) return; setPos(pos + (dragStart - e.clientX) * 0.5); });
    document.addEventListener('mouseup', function () { if (dragging) { dragging = false; track.style.cursor = 'grab'; } });

    track.addEventListener('touchstart', function (e) { dragStart = e.touches[0].clientX; clearInterval(autoTimer); }, { passive: true });
    track.addEventListener('touchmove', function (e) { setPos(pos + (dragStart - e.touches[0].clientX) * 0.3); }, { passive: true });
    track.addEventListener('touchend', restartAuto, { passive: true });

    // Hover pause
    track.parentElement.addEventListener('mouseenter', function () { clearInterval(autoTimer); });
    track.parentElement.addEventListener('mouseleave', restartAuto);

    autoScroll();
})();

/* ================================================================
   GALERI VIDEO CAROUSEL (Shared - all pages)
================================================================ */
(function () {
    const track = document.getElementById('sharedGaleriVideoTrack');
    if (!track) return;

    let pos = 0;
    const cardWidth = 236; // 220px card + 16px gap
    let autoTimer = null;

    function maxPos() {
        return Math.max(0, track.scrollWidth - (track.parentElement ? track.parentElement.offsetWidth : window.innerWidth));
    }
    function setPos(p) {
        pos = Math.max(0, Math.min(p, maxPos()));
        track.style.transform = 'translateX(-' + pos + 'px)';
    }

    window.sharedSlideGaleriVideo = function (dir) {
        setPos(pos + dir * cardWidth * 2);
        restartAuto();
    };

    function autoScroll() {
        autoTimer = setInterval(function () {
            if (pos >= maxPos()) {
                pos = 0;
                track.style.transition = 'none';
                track.style.transform = 'translateX(0px)';
                requestAnimationFrame(function () {
                    track.style.transition = 'transform 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
                });
            } else {
                setPos(pos + cardWidth);
            }
        }, 4000);
    }

    function restartAuto() { clearInterval(autoTimer); autoScroll(); }

    let dragStart = 0, dragging = false;
    track.addEventListener('mousedown', function (e) { dragStart = e.clientX; dragging = true; track.style.cursor = 'grabbing'; });
    document.addEventListener('mousemove', function (e) { if (!dragging) return; setPos(pos + (dragStart - e.clientX) * 0.5); });
    document.addEventListener('mouseup', function () { if (dragging) { dragging = false; track.style.cursor = 'grab'; } });
    track.addEventListener('touchstart', function (e) { dragStart = e.touches[0].clientX; clearInterval(autoTimer); }, { passive: true });
    track.addEventListener('touchmove', function (e) { setPos(pos + (dragStart - e.touches[0].clientX) * 0.3); }, { passive: true });
    track.addEventListener('touchend', restartAuto, { passive: true });
    track.parentElement.addEventListener('mouseenter', function () { clearInterval(autoTimer); });
    track.parentElement.addEventListener('mouseleave', restartAuto);

    autoScroll();
})();

/* ================================================================
   GALERI KEGIATAN CAROUSEL (Shared - all pages)
================================================================ */
(function () {
    const track = document.getElementById('sharedGaleriTrack');
    if (!track) return;

    let pos = 0;
    const cardWidth = 216; // 200px card + 16px gap
    const totalCards = track.children.length;
    const maxPos = Math.max(0, (totalCards * cardWidth) - track.parentElement.offsetWidth);
    let autoTimer = null;

    function setPos(p) {
        pos = Math.max(0, Math.min(p, maxPos));
        track.style.transform = 'translateX(-' + pos + 'px)';
    }

    window.sharedSlideGaleri = function (dir) {
        setPos(pos + dir * cardWidth * 2);
        restartAuto();
    };

    function autoScroll() {
        autoTimer = setInterval(function () {
            if (pos >= maxPos) {
                pos = 0;
                track.style.transition = 'none';
                track.style.transform = 'translateX(0px)';
                requestAnimationFrame(function () {
                    track.style.transition = 'transform 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
                });
            } else {
                setPos(pos + cardWidth);
            }
        }, 1500);
    }

    function restartAuto() {
        clearInterval(autoTimer);
        autoScroll();
    }

    // Touch/Drag
    let dragStart = 0, dragging = false;
    track.addEventListener('mousedown', function (e) { e.preventDefault(); dragStart = e.clientX; dragging = true; track.style.cursor = 'grabbing'; });
    document.addEventListener('mousemove', function (e) { if (!dragging) return; setPos(pos + (dragStart - e.clientX) * 0.5); });
    document.addEventListener('mouseup', function () { if (dragging) { dragging = false; track.style.cursor = 'grab'; } });

    track.addEventListener('touchstart', function (e) { dragStart = e.touches[0].clientX; clearInterval(autoTimer); }, { passive: true });
    track.addEventListener('touchmove', function (e) { setPos(pos + (dragStart - e.touches[0].clientX) * 0.3); }, { passive: true });
    track.addEventListener('touchend', restartAuto, { passive: true });

    // Hover pause
    track.parentElement.addEventListener('mouseenter', function () { clearInterval(autoTimer); });
    track.parentElement.addEventListener('mouseleave', restartAuto);

    autoScroll();
})();
</script>

<!-- Lightbox modal for gallery images -->
<style>
/* Lightbox */
.lb-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.85); display: none; align-items: center; justify-content: center; z-index: 99999; }
.lb-overlay.open { display: flex; }
.lb-content { position: relative; max-width: 60vw; max-height: 50vh; width: 60vw; display: flex; align-items: center; justify-content: center; }
.lb-img { max-width: 100%; max-height: 100%; border-radius: 6px; box-shadow: 0 12px 30px rgba(0,0,0,0.45); }
.lb-caption { position: absolute; bottom: 12px; left: 50%; transform: translateX(-50%); color: #fff; background: rgba(0,0,0,0.4); padding: 6px 12px; border-radius: 6px; font-size: 0.95rem; max-width: 90%; text-align: center; }
.lb-btn { position: absolute; top: 50%; transform: translateY(-50%); width: 56px; height: 56px; border-radius: 50%; background: rgba(0,0,0,0.35); color: #fff; display:flex; align-items:center; justify-content:center; cursor: pointer; transition: background .15s; }
.lb-btn:hover { background: rgba(0,0,0,0.6); }
.lb-prev { left: 18px; }
.lb-next { right: 18px; }
.lb-close { width: 44px; height: 44px; border-radius: 50%; background: rgba(0,0,0,0.35); display:flex; align-items:center; justify-content:center; cursor:pointer; box-shadow: 0 8px 18px rgba(0,0,0,0.45); border: none; }
.lb-loading { color: rgba(255,255,255,0.7); }

/* Lightbox control buttons container */
.lb-controls { position: absolute; top: 6px; right: 12px; display: flex; gap: 10px; align-items: center; z-index: 1001; }

/* Unified circular button size shared by download/close/prev/next */
:root { --lb-btn-size: 40px; --lb-btn-shadow: 0 8px 18px rgba(0,0,0,0.45); }
.lb-action-btn {
    color: #fff;
    background: rgba(0,0,0,0.35);
    width: var(--lb-btn-size);
    height: var(--lb-btn-size);
    padding: 0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    box-shadow: var(--lb-btn-shadow);
    border: none;
    cursor: pointer;
}
.lb-action-btn i { font-size: 1rem; line-height: 1; }

/* Make left/right nav buttons match same circular style */
.lb-btn, .lb-prev, .lb-next {
    width: var(--lb-btn-size);
    height: var(--lb-btn-size);
    border-radius: 50%;
    background: rgba(0,0,0,0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: var(--lb-btn-shadow);
    border: none;
    cursor: pointer;
}
.lb-prev i, .lb-next i { font-size: 1rem; }
@media (max-width: 768px) { .lb-btn { width:44px;height:44px; } .lb-content { width: calc(100% - 40px); } }
</style>

<div class="lb-overlay" id="lbOverlay" aria-hidden="true">
    <div class="lb-content" role="dialog" aria-modal="true">
        <button class="lb-btn lb-prev" id="lbPrev" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>
        <img src="" alt="" id="lbImg" class="lb-img" draggable="false">
        <button class="lb-btn lb-next" id="lbNext" aria-label="Next"><i class="fas fa-chevron-right"></i></button>
        <div class="lb-controls">
            <a id="lbDownload" class="lb-action-btn" href="#" download title="Download"><i class="fas fa-download"></i></a>
            <button class="lb-action-btn lb-close" id="lbClose" aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <div class="lb-caption" id="lbCaption" style="display:none;"></div>
    </div>
</div>

<script>
(function () {
    const selectors = [
        '.galeri-scroll-grid a',
        '.shared-galeri-track a',
        '.shared-galeri-card a',
        '.shared-galeri-foto-section img',
        '.shared-galeri-card img'
    ];

    function collectItems() {
        const items = [];
        // prefer anchors that contain images
        document.querySelectorAll(selectors.join(',')).forEach(function (el) {
            let img = null, src = null, title = '';
            if (el.tagName === 'IMG') {
                img = el;
            } else {
                img = el.querySelector('img');
            }
            if (!img) return;
            src = img.getAttribute('data-full') || img.src || img.getAttribute('data-src');
            title = img.alt || img.title || '';
            if (!src) return;
            // avoid duplicates
            if (!items.some(i => i.src === src)) items.push({ src: src, title: title, el: el });
        });
        return items;
    }

    const overlay = document.getElementById('lbOverlay');
    const lbImg = document.getElementById('lbImg');
    const lbCaption = document.getElementById('lbCaption');
    const lbPrev = document.getElementById('lbPrev');
    const lbNext = document.getElementById('lbNext');
    const lbClose = document.getElementById('lbClose');
    const lbDownload = document.getElementById('lbDownload');

    let items = collectItems();
    let idx = 0;
    let isDragging = false, dragStartX = 0, dragDelta = 0;

    function showIndex(i) {
        if (!items.length) return;
        idx = (i + items.length) % items.length;
        const it = items[idx];
        overlay.classList.add('open');
        overlay.setAttribute('aria-hidden','false');
        document.documentElement.style.overflow = 'hidden';
        lbImg.src = '';
        lbCaption.textContent = '';
        lbDownload.href = it.src;
        lbImg.alt = it.title || '';
        lbCaption.textContent = it.title || '';
        // show low-res immediately, then preload high-res
        lbImg.src = it.src;
        // preload next
        const next = new Image(); next.src = items[(idx+1)%items.length].src;
    }

    function hideLightbox() {
        overlay.classList.remove('open');
        overlay.setAttribute('aria-hidden','true');
        document.documentElement.style.overflow = '';
        lbImg.src = '';
    }

    function onPrev() { showIndex(idx - 1); }
    function onNext() { showIndex(idx + 1); }

    // Attach click handlers to current gallery items (rebuild list before open)
    function attachHandlers() {
        items = collectItems();
        document.querySelectorAll(selectors.join(',')).forEach(function (el) {
            // avoid attaching twice
            if (el.__lb_attached) return; el.__lb_attached = true;
            el.addEventListener('click', function (e) {
                // if anchor points to route instead of image, still open using contained image
                e.preventDefault();
                items = collectItems();
                let src = null; let title = '';
                if (el.tagName === 'IMG') { src = el.src; title = el.alt || ''; }
                else { const img = el.querySelector('img'); if (img) { src = img.getAttribute('data-full') || img.src; title = img.alt || ''; } }
                const index = items.findIndex(i => i.src === src);
                if (index >= 0) showIndex(index); else { items.push({ src: src, title: title, el: el }); showIndex(items.length-1); }
            });
        });
    }

    // Initial attach and also re-attach on dynamic changes (MutationObserver)
    attachHandlers();
    const observer = new MutationObserver(function () { attachHandlers(); });
    observer.observe(document.body, { childList: true, subtree: true });

    // Controls
    lbPrev.addEventListener('click', function (e) { e.stopPropagation(); onPrev(); });
    lbNext.addEventListener('click', function (e) { e.stopPropagation(); onNext(); });
    lbClose.addEventListener('click', hideLightbox);
    // removed left-back button (navigation handled by browser)
    overlay.addEventListener('click', function (e) { if (e.target === overlay) hideLightbox(); });

    // Keyboard
    document.addEventListener('keydown', function (e) {
        if (!overlay.classList.contains('open')) return;
        if (e.key === 'Escape') hideLightbox();
        if (e.key === 'ArrowLeft') onPrev();
        if (e.key === 'ArrowRight') onNext();
    });

    // Drag / swipe inside lightbox
    lbImg.addEventListener('mousedown', function (e) { isDragging = true; dragStartX = e.clientX; e.preventDefault(); });
    window.addEventListener('mousemove', function (e) { if (!isDragging) return; dragDelta = e.clientX - dragStartX; });
    window.addEventListener('mouseup', function () { if (!isDragging) return; isDragging = false; if (dragDelta > 40) onPrev(); else if (dragDelta < -40) onNext(); dragDelta = 0; });
    // touch
    lbImg.addEventListener('touchstart', function (e) { isDragging = true; dragStartX = e.touches[0].clientX; }, { passive: true });
    lbImg.addEventListener('touchmove', function (e) { if (!isDragging) return; dragDelta = e.touches[0].clientX - dragStartX; }, { passive: true });
    lbImg.addEventListener('touchend', function () { if (!isDragging) return; isDragging = false; if (dragDelta > 40) onPrev(); else if (dragDelta < -40) onNext(); dragDelta = 0; }, { passive: true });

})();
</script>

    <script>
    // Drag-to-scroll support for the main gallery grid (mouse + wheel)
    (function () {
        const grid = document.querySelector('.galeri-scroll-grid');
        if (!grid) return;

        let isDown = false, startX, scrollLeft;

        grid.addEventListener('mousedown', function (e) {
            isDown = true;
            grid.classList.add('dragging');
            startX = e.pageX - grid.offsetLeft;
            scrollLeft = grid.scrollLeft;
            e.preventDefault();
        });
        grid.addEventListener('mouseleave', function () { isDown = false; grid.classList.remove('dragging'); });
        grid.addEventListener('mouseup', function () { isDown = false; grid.classList.remove('dragging'); });
        grid.addEventListener('mousemove', function (e) {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - grid.offsetLeft;
            const walk = (x - startX) * 1.5;
            grid.scrollLeft = scrollLeft - walk;
        });

        // Do not hijack vertical wheel: keep normal page scroll when cursor is over gallery
    })();
    </script>

    <script>
    // Infinite-load / load-more for gallery grid (append pages to the right)
    (function () {
        const grid = document.querySelector('.galeri-scroll-grid');
        if (!grid) return;

        let currentPage = 1;
        let loading = false;
        let noMore = false;

        function buildPageUrl(page) {
            const url = new URL(window.location.href);
            url.searchParams.set('page', page);
            return url.toString();
        }

        async function loadPage(page) {
            if (loading || noMore) return;
            loading = true;
            const loadIndicator = document.getElementById('galeriLoadIndicator') || createLoadIndicator();
            loadIndicator.style.display = 'block';
            try {
                const res = await fetch(buildPageUrl(page), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!res.ok) { noMore = true; return; }
                const text = await res.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(text, 'text/html');
                // Try to find the gallery grid in response
                let newGrid = doc.querySelector('.galeri-scroll-grid');
                let newItems = [];
                if (newGrid) newItems = Array.from(newGrid.children).filter(n => n.classList.contains('galeri-item') || n.matches('.galeri-item'));
                else {
                    // Fallback: find Bootstrap card columns
                    newItems = Array.from(doc.querySelectorAll('.col-lg-3, .col-md-4, .col-6')).map(n => n.cloneNode(true));
                }
                if (!newItems || newItems.length === 0) { noMore = true; return; }
                // Append cloned nodes to current grid
                newItems.forEach(function (ni) {
                    const clone = ni.cloneNode(true);
                    // Ensure it has class galeri-item (normalize)
                    if (!clone.classList.contains('galeri-item')) clone.classList.add('galeri-item');
                    grid.appendChild(clone);
                });
                // Re-attach lightbox handlers for new items
                if (window.__attachLightboxHandlers) window.__attachLightboxHandlers();
                currentPage = page;
            } catch (err) {
                console.error('Failed loading gallery page', err);
                noMore = true;
            } finally {
                loading = false;
                const loadIndicator2 = document.getElementById('galeriLoadIndicator'); if (loadIndicator2) loadIndicator2.style.display = noMore ? 'none' : 'none';
            }
        }

        function createLoadIndicator() {
            const wrap = document.createElement('div');
            wrap.id = 'galeriLoadIndicator';
            wrap.style.cssText = 'position:absolute;right:8px;bottom:8px;background:rgba(0,0,0,0.6);color:#fff;padding:6px 8px;border-radius:6px;display:none;z-index:1000;font-size:0.9rem;';
            wrap.textContent = 'Memuat...';
            grid.parentElement.style.position = 'relative';
            grid.parentElement.appendChild(wrap);
            return wrap;
        }

        // Load next page when near right edge
        grid.addEventListener('scroll', function () {
            const nearRight = (grid.scrollWidth - grid.clientWidth - grid.scrollLeft) < 300;
            if (nearRight && !loading && !noMore) loadPage(currentPage + 1);
        });

        // Preload additional pages on init so many thumbnails appear without user clicking
        (async function preloadPages() {
            const maxPreload = 4; // attempt to load up to 4 extra pages
            try {
                for (let i = 2; i <= maxPreload + 1; i++) {
                    if (noMore) break;
                    // stop if grid already has horizontal overflow enough for viewing
                    if (grid.scrollWidth > grid.clientWidth * 1.2) break;
                    await loadPage(i);
                    // small delay to avoid hammering server
                    await new Promise(r => setTimeout(r, 250));
                }
            } catch (e) { /* ignore */ }
        })();

        // Manual load button removed — automatic append on scroll is used instead

        // expose attach handler for appended items
        window.__attachLightboxHandlers = function () { /* lightbox attachHandlers is invoked automatically via MutationObserver */ };
    })();
    </script>
