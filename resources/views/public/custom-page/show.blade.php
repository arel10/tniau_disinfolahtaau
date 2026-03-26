@extends('layouts.public')

@section('title', $menu->name . __('messages.site_title_suffix'))

@push('styles')
<style>
@keyframes tickerScroll {
    0%   { transform: translateX(0%); }
    100% { transform: translateX(-100%); }
}
.ticker-inner { display:inline-block; white-space:nowrap; animation: tickerScroll 22s linear infinite; }
.ticker-slow  { animation-duration: 70s; }
.ticker-normal{ animation-duration: 36s; }
.ticker-fast  { animation-duration: 16s; }
.ticker-wrap  { overflow:hidden; padding:6px 0; }
@keyframes galeriScroll {
    0%   { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
.galeri-scroll-inner { will-change: transform; }
.galeri-scroll-wrap:hover .galeri-scroll-inner { animation-play-state: paused; }
/* 10-per-row grid */
.row-galeri-10 > * { flex: 0 0 10%; max-width: 10%; }
@media (max-width: 767.98px) { .row-galeri-10 > * { flex: 0 0 25%; max-width: 25%; } }
@media (max-width: 575.98px) { .row-galeri-10 > * { flex: 0 0 33.33%; max-width: 33.33%; } }

/* ================================================================
   DARK MODE – Custom Page (halaman sub-menu)
================================================================ */
[data-theme="dark"] .card.shadow-sm.border-0 {
    background-color: var(--card-bg, #16213e) !important;
    color: var(--text-color, #e0e0e0) !important;
    border-color: #2a2a4a !important;
}
[data-theme="dark"] .card-body {
    background-color: var(--card-bg, #16213e) !important;
    color: var(--text-color, #e0e0e0) !important;
}
[data-theme="dark"] .card-body h3,
[data-theme="dark"] .card-body h4,
[data-theme="dark"] .card-body h5,
[data-theme="dark"] .card-body h6 { color: #e0e0e0 !important; }
[data-theme="dark"] .card-body p,
[data-theme="dark"] .card-body span,
[data-theme="dark"] .card-body div:not(.alert):not(.btn) { color: #ccc; }
[data-theme="dark"] .card-body .text-muted { color: #999 !important; }
[data-theme="dark"] .card-body a:not(.btn):not(.nav-link):not(.dropdown-item):not(.list-group-item) { color: #7db8ff; }
[data-theme="dark"] .card-body a:not(.btn):not(.nav-link):not(.dropdown-item):not(.list-group-item):hover { color: #4da3ff; }
[data-theme="dark"] .card-body hr { border-color: #3a3a5a !important; }
[data-theme="dark"] .card-body blockquote { border-left-color: #4a6fa5 !important; color: #bbb !important; }
[data-theme="dark"] .card-body .list-group-item { background-color: #1e293b !important; color: #e0e0e0 !important; border-color: #2a2a4a !important; }
[data-theme="dark"] .card-body .list-group-item:hover { background-color: #263548 !important; }
[data-theme="dark"] .card-body .accordion-item { background-color: #1e293b !important; border-color: #2a2a4a !important; }
[data-theme="dark"] .card-body .accordion-button { background-color: #1e293b !important; color: #e0e0e0 !important; border-color: #2a2a4a !important; }
[data-theme="dark"] .card-body .accordion-button:not(.collapsed) { background-color: #263548 !important; color: #7db8ff !important; }
[data-theme="dark"] .card-body .accordion-button::after { filter: invert(1) brightness(2); }
[data-theme="dark"] .card-body .accordion-body { background-color: #1e293b !important; color: #ccc !important; }
[data-theme="dark"] .card-body .nav-tabs { border-bottom-color: #2a2a4a !important; }
[data-theme="dark"] .card-body .nav-tabs .nav-link { color: #aaa !important; }
[data-theme="dark"] .card-body .nav-tabs .nav-link.active { background-color: var(--card-bg, #16213e) !important; color: #7db8ff !important; border-color: #2a2a4a #2a2a4a var(--card-bg, #16213e) !important; }
[data-theme="dark"] .card-body .tab-content { color: #ccc !important; }
[data-theme="dark"] .card-body .card { background-color: #1e293b !important; border-color: #2a2a4a !important; }
[data-theme="dark"] .card-body .card .card-body { background-color: #1e293b !important; }
[data-theme="dark"] .card-body .card .card-title { color: #e0e0e0 !important; }
[data-theme="dark"] .card-body .card .card-text { color: #999 !important; }
[data-theme="dark"] .card-body .bg-light { background-color: #1e293b !important; color: #e0e0e0 !important; }
[data-theme="dark"] .card-body .btn-outline-primary { color: #7db8ff !important; border-color: #4a6fa5 !important; }
[data-theme="dark"] .card-body .btn-outline-primary:hover { background-color: #1a3a6a !important; color: #fff !important; }
[data-theme="dark"] .text-muted { color: #999 !important; }
[data-theme="dark"] i[style*="color:#dee2e6"] { color: #3a3a5a !important; }
[data-theme="dark"] .container.my-5 { color: var(--text-color, #e0e0e0); }
[data-theme="dark"] .page-hero { background: linear-gradient(rgba(10, 15, 30, 0.75), rgba(10, 15, 30, 0.9)) !important; }
[data-theme="dark"] .page-hero-overlay { background: linear-gradient(rgba(10, 15, 30, 0.6), rgba(10, 15, 30, 0.8)) !important; }
[data-theme="dark"] .page-hero .breadcrumb-item a { color: rgba(200,220,255,0.85) !important; }
[data-theme="dark"] .page-hero .breadcrumb-item.active { color: rgba(200,220,255,0.55) !important; }
[data-theme="dark"] .widget-video-card { background: #0a0f1e !important; border: 1px solid #1e3a6a; }

/* ================================================================
   TEMPLATE SECTION (tabbed layout with sidebars)
================================================================ */
.ptm-template-section { margin-bottom: 28px; }

/* Tab navigation */
.ptm-tabs { border: none !important; gap: 0; flex-wrap: nowrap; overflow-x: auto; -webkit-overflow-scrolling: touch; scrollbar-width: none; }
.ptm-tabs::-webkit-scrollbar { display: none; }
.ptm-tabs .nav-link {
    border: 1px solid #d0d7e2 !important; border-bottom: none !important;
    border-radius: 6px 6px 0 0 !important; background: #e9ecef; color: #333;
    font-weight: 700; font-size: .85rem; padding: 10px 18px; white-space: nowrap; transition: all .2s;
}
.ptm-tabs .nav-link:hover { background: #dce4f0; color: #003d82; }
.ptm-tabs .nav-link.active {
    background: #fff !important; color: #003d82 !important;
    border-color: #003d82 !important; border-bottom: 2px solid #fff !important;
    position: relative; z-index: 1;
}
[data-theme="dark"] .ptm-tabs .nav-link { background: #1e293b; color: #aaa; border-color: #2a2a4a !important; }
[data-theme="dark"] .ptm-tabs .nav-link:hover { background: #263548; color: #7db8ff; }
[data-theme="dark"] .ptm-tabs .nav-link.active { background: #16213e !important; color: #7db8ff !important; border-color: #4a6fa5 !important; border-bottom-color: #16213e !important; }

/* Tab content area */
.ptm-tab-content {
    border: 1px solid #003d82; border-top: 2px solid #003d82;
    border-radius: 0 0 8px 8px; background: #fff;
    padding: 24px; min-height: 350px; max-height: 600px; overflow-y: auto;
}
[data-theme="dark"] .ptm-tab-content { background: #16213e !important; border-color: #4a6fa5 !important; color: #e0e0e0; }
[data-theme="dark"] .ptm-tab-content h3, [data-theme="dark"] .ptm-tab-content h5 { color: #e0e0e0 !important; }
[data-theme="dark"] .ptm-tab-content p, [data-theme="dark"] .ptm-tab-content span, [data-theme="dark"] .ptm-tab-content div { color: #ccc; }

/* Sidebars */
.ptm-sidebar-img-wrap { border-radius: 10px; overflow: hidden; margin-bottom: 16px; position: relative; background: #f0f2f5; }
.ptm-sidebar-img-wrap img { width: 100%; display: block; object-fit: cover; }
.ptm-sidebar-img-wrap.bg-removed img { mix-blend-mode: multiply; }
[data-theme="dark"] .ptm-sidebar-img-wrap.bg-removed img { mix-blend-mode: screen; }
.ptm-sidebar-placeholder {
    border-radius: 10px; background: linear-gradient(135deg, #f0f2f5, #e9ecef);
    min-height: 200px; display: flex; align-items: center; justify-content: center;
    flex-direction: column; color: #adb5bd; margin-bottom: 16px; border: 2px dashed #dee2e6;
}
.ptm-sidebar-placeholder i { font-size: 32px; margin-bottom: 8px; }
.ptm-sidebar-placeholder span { font-size: .75rem; font-weight: 600; }
[data-theme="dark"] .ptm-sidebar-placeholder { background: linear-gradient(135deg, #1e293b, #16213e); border-color: #2a2a4a; color: #555; }
[data-theme="dark"] .ptm-sidebar-img-wrap { background: #1e293b; }
.ptm-sidebar-label { text-align: center; font-weight: 800; font-size: .82rem; color: #003d82; margin-top: 4px; margin-bottom: 12px; }
[data-theme="dark"] .ptm-sidebar-label { color: #7db8ff; }
/* Left sidebar: PHOTOS (portrait) */
.ptm-sidebar-left {
    display: flex !important;
    flex-direction: column;
    justify-content: flex-start;
    gap: 10px;
}
.ptm-sidebar-left .ptm-sidebar-img-wrap img { object-fit: contain; max-height: 280px; border-radius: 8px; }
.ptm-sidebar-left .ptm-sidebar-placeholder { min-height: 120px; }
/* Right sidebar: LOGOS */
.ptm-sidebar-right { display: flex !important; flex-direction: column; justify-content: flex-start; gap: 10px; }
.ptm-sidebar-right .ptm-sidebar-img-wrap img { object-fit: contain; max-height: 140px; }
/* ── Mobile: hide sidebars in template, shown separately above ticker ── */
@media (max-width: 991.98px) {
    .ptm-sidebar-left, .ptm-sidebar-right { display: none !important; }
    .ptm-content-col { flex: 0 0 100% !important; max-width: 100% !important; }
}
/* ── Mobile sidebar top section ── */
.ptm-mobile-sidebar-top { display: none; }
@media (max-width: 991.98px) {
    .ptm-mobile-sidebar-top {
        display: flex !important;
        gap: 12px;
        margin-bottom: 10px;
    }
    .ptm-mobile-sidebar-top .ptm-mob-col {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }
    .ptm-mobile-sidebar-top .ptm-mob-col img {
        max-height: 90px;
        object-fit: contain;
        border-radius: 6px;
    }
    .ptm-mobile-sidebar-top .ptm-mob-col .ptm-mob-photo img {
        object-fit: cover;
        max-height: 100px;
        border-radius: 8px;
    }
}
@media (max-width: 575.98px) {
    .ptm-tabs .nav-link { font-size: .75rem; padding: 8px 12px; }
    .ptm-tab-content { padding: 16px; min-height: 250px; }
}

/* ================================================================
   STANDALONE GALLERY SECTIONS
================================================================ */
.standalone-video-section { background: #f0f4fa; border: 1px solid #d0dae8; border-radius: 12px; }
.standalone-video-section .galeri-video-lokal-widget { background: transparent !important; }
.standalone-video-section .galeri-video-lokal-widget .ratio { border-radius: 10px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,.12); }
.standalone-video-section .galeri-video-lokal-widget video { border-radius: 10px; box-shadow: 0 4px 16px rgba(0,0,0,.12); }
.standalone-video-section .galeri-video-lokal-widget .text-muted { color: #555 !important; }
[data-theme="dark"] .standalone-video-section { background: linear-gradient(135deg,#0a0f1e,#001a3a) !important; border: 1px solid #1e3a6a; }
[data-theme="dark"] .standalone-video-section .galeri-video-lokal-widget { color: #e0e0e0 !important; }
[data-theme="dark"] .standalone-video-section .galeri-video-lokal-widget .text-muted { color: rgba(200,220,255,.5) !important; }
.standalone-video-heading { color: #003d82; }
[data-theme="dark"] .standalone-video-heading { color: #ffc107 !important; }
.standalone-foto-section { background: #fff; border: 1px solid #e9ecef; }
[data-theme="dark"] .standalone-foto-section { background: #16213e !important; border-color: #2a2a4a !important; }
[data-theme="dark"] .standalone-foto-section > div,
[data-theme="dark"] .standalone-foto-section .galeri-scroll-wrap,
[data-theme="dark"] .standalone-foto-section .row { background-color: transparent !important; }
[data-theme="dark"] .standalone-foto-section [style*="background-color"] { background-color: transparent !important; }
.standalone-foto-heading { color: #003d82; }
[data-theme="dark"] .standalone-foto-heading { color: #7db8ff !important; }
[data-theme="dark"] .standalone-foto-section h5 { color: #e0e0e0 !important; }
[data-theme="dark"] .standalone-foto-section p, [data-theme="dark"] .standalone-foto-section span { color: #ccc !important; }
[data-theme="dark"] .standalone-foto-section [style*="color:#212529"],
[data-theme="dark"] .standalone-foto-section [style*="color: #212529"] { color: #e0e0e0 !important; }
[data-theme="dark"] .standalone-foto-section img { opacity: .92; }
[data-theme="dark"] .standalone-foto-section .shadow-sm { box-shadow: 0 2px 8px rgba(0,0,0,.4) !important; }

/* ================================================================
   ARTICLE CAROUSEL (horizontal scroll)
================================================================ */
.article-carousel-wrap { overflow: hidden; position: relative; }
.article-carousel-track {
    display: flex; gap: 20px;
    transition: transform 0.45s cubic-bezier(0.4,0,0.2,1);
    cursor: grab;
}
.article-carousel-track:active { cursor: grabbing; }
.article-news-card {
    min-width: 260px; max-width: 260px; flex-shrink: 0;
    border-radius: 12px; overflow: hidden; background: #f8f9fa;
    border: 1px solid #e9ecef; transition: transform .3s, box-shadow .3s, border-color .3s;
    cursor: pointer; display: flex; flex-direction: column;
}
.article-news-card:hover { transform: translateY(-4px); box-shadow: 0 8px 25px rgba(0,0,0,.1); border-color: #003d82; }
.article-card-thumb { height: 160px; overflow: hidden; flex-shrink: 0; }
.article-card-thumb img { width:100%; height:100%; object-fit:cover; transition:transform .3s; }
.article-news-card:hover .article-card-thumb img { transform: scale(1.05); }
.article-card-no-img {
    height: 160px; background: linear-gradient(135deg,#001f3f,#003d82);
    display: flex; align-items: center; justify-content: center;
    color: rgba(255,255,255,.25); font-size: 42px;
}
.article-card-info { padding: 12px; flex: 1; display: flex; flex-direction: column; }
.article-card-kategori { display: inline-block; background: #003d82; color: #fff; padding: 3px 10px; font-size: .65rem; font-weight: 600; border-radius: 3px; margin-bottom: 8px; }
.article-card-title { font-weight:700; font-size:.9rem; color:#1a1a2e; line-height:1.35; margin-bottom:6px; }
.article-card-date  { font-size:.72rem; color:#666; margin-bottom:6px; }
.article-card-desc  { font-size:.82rem; color:#666; line-height:1.45; margin-bottom:8px; flex:1; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; }
.article-card-readmore { font-size:.78rem; color:#0066cc; font-weight:600; margin-top:auto; }
.article-carousel-nav-btn {
    background:#fff; border:1px solid #ddd; border-radius:4px;
    width:32px; height:32px; cursor:pointer; transition:all .2s;
    display:flex; align-items:center; justify-content:center; flex-shrink:0;
}
.article-carousel-nav-btn:hover { background:#003d82; color:#fff; border-color:#003d82; }
[data-theme="dark"] .article-news-card { background:#16213e; border-color:#2a2a4a; }
[data-theme="dark"] .article-news-card:hover { border-color:#4da3ff; }
[data-theme="dark"] .article-card-title { color:#e0e0e0; }
[data-theme="dark"] .article-card-date  { color:#aaa; }
[data-theme="dark"] .article-card-desc  { color:#aaa; }
[data-theme="dark"] .article-card-readmore { color:#7db8ff; }
[data-theme="dark"] .article-card-no-img { background: linear-gradient(135deg, #0a0f1e, #16213e) !important; }
[data-theme="dark"] .article-carousel-nav-btn { background:#2a2a4a; border-color:#3a3a5a; color:#ccc; }
[data-theme="dark"] .article-carousel-nav-btn:hover { background:#003d82; color:#fff; border-color:#003d82; }
.article-section-title { color: #1a1a2e; }
[data-theme="dark"] .article-section-title { color: #e0e0e0 !important; }

/* Normal linear mode gallery wrappers */
[data-theme="dark"] .linear-video-wrap { background: linear-gradient(135deg,#0a0f1e,#001a3a) !important; border: 1px solid #1e3a6a; }
[data-theme="dark"] .linear-foto-wrap { background: #16213e !important; border-color: #2a2a4a !important; }
[data-theme="dark"] .linear-foto-wrap .card-body { background: #16213e !important; }

/* ================================================================
   ARTICLE FULL-PAGE OVERLAY
================================================================ */
#articleOverlay {
    position: fixed; inset: 0; z-index: 9999;
    display: none; flex-direction: column; background: #fff;
    overflow: hidden; animation: overlayIn .3s ease;
}
[data-theme="dark"] #articleOverlay { background: #0f172a; color: #e0e0e0; }
@keyframes overlayIn { from { opacity:0; transform:translateY(24px); } to { opacity:1; transform:translateY(0); } }
#articleOverlay.visible { display:flex; }
.article-overlay-bar {
    display: flex; align-items: center; gap: 12px; padding: 12px 20px;
    background: linear-gradient(135deg,#001f3f,#003d82); color: #fff;
    flex-shrink: 0; box-shadow: 0 2px 12px rgba(0,0,0,.25);
}
.article-overlay-bar .overlay-back-btn {
    background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.3);
    border-radius: 8px; color: #fff; padding: 5px 14px; font-size: .85rem;
    font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px; flex-shrink: 0;
}
.article-overlay-bar .overlay-back-btn:hover { background: rgba(255,255,255,.28); }
.article-overlay-bar .overlay-title { flex:1; font-weight:700; font-size:1rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.article-overlay-close {
    width:36px; height:36px; border-radius:50%; background:rgba(255,255,255,.18);
    border:1px solid rgba(255,255,255,.35); color:#fff; font-size:1.1rem; font-weight:700;
    display:flex; align-items:center; justify-content:center; cursor:pointer; flex-shrink:0;
}
.article-overlay-close:hover { background: rgba(220,53,69,.7); transform: scale(1.1); }
.article-overlay-body { flex:1; overflow-y:auto; padding:32px 0; }
.article-overlay-inner { max-width:860px; margin:0 auto; padding:0 20px; }
[data-theme="dark"] .article-overlay-body { background: #0f172a; }
[data-theme="dark"] .article-overlay-inner { color: #e0e0e0; }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="container">
        <h2><i class="{{ $menu->icon }} me-2"></i>{{ $menu->name }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.breadcrumb_home') }}</a></li>
                @if($parent)
                    <li class="breadcrumb-item"><a href="{{ url('/halaman/' . $parent->slug) }}">{{ $parent->name }}</a></li>
                @endif
                <li class="breadcrumb-item active">{{ $menu->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container my-5">
@php
    $allWidgets = $menu->activeWidgets;

    // ── Gallery widgets handled as standalone sections ──────────────
    $standaloneFotoWidgets  = $allWidgets->filter(fn($w) => $w->widget_type === 'galeri_foto_lokal');
    $standaloneVideoWidgets = $allWidgets->filter(fn($w) => $w->widget_type === 'galeri_video_lokal');

    // ── Ticker / running-text widgets ───────────────────────────────
    $tickerWidgets = $allWidgets->filter(fn($w) => $w->widget_type === 'teks_berjalan');

    // ── Template widgets ────────────────────────────────────────────
    $tabFrameWidget      = $allWidgets->firstWhere('widget_type', 'tab_frame');
    $sidebarLeftWidgets  = $allWidgets->filter(fn($w) => $w->widget_type === 'gambar_sidebar' && ($w->settings['sidebar_position'] ?? 'left') === 'left');
    $sidebarRightWidgets = $allWidgets->filter(fn($w) => $w->widget_type === 'gambar_sidebar' && ($w->settings['sidebar_position'] ?? 'left') === 'right');
    $hasTemplate         = true; // Always show template section in card mode; placeholders appear when empty

    // ── Article grouping: skip gallery + template + ticker widgets ──
    $skipTypes = ['galeri_foto_lokal', 'galeri_video_lokal', 'gambar_sidebar', 'tab_frame', 'teks_berjalan'];
    $articles = [];
    $curIdx   = -1;
    foreach ($allWidgets as $w) {
        if (in_array($w->widget_type, $skipTypes)) continue;
        if ($w->widget_type === 'judul') {
            $curIdx++;
            $articles[$curIdx] = ['title' => $w->text_content, 'widgets' => collect()];
        }
        if ($curIdx >= 0) {
            $articles[$curIdx]['widgets']->push($w);
        }
    }

    // Filter out articles without visual content
    $visualTypes = ['foto', 'video', 'video_url', 'logo'];
    $articles = array_values(array_filter($articles, function($art) use ($visualTypes) {
        return $art['widgets']->contains(fn($w) => in_array($w->widget_type, $visualTypes));
    }));

    // Card mode: triggered by 2+ article groups OR by presence of template/sidebar/ticker widgets
    $isCardMode = count($articles) >= 2 || $tickerWidgets->count() || $tabFrameWidget !== null
                  || $sidebarLeftWidgets->count() || $sidebarRightWidgets->count();
@endphp

@if($isCardMode)

    {{-- ===============================================================
         MOBILE-ONLY SIDEBAR IMAGES (above ticker)
    ================================================================ --}}
    @if($sidebarLeftWidgets->count() || $sidebarRightWidgets->count())
    <div class="ptm-mobile-sidebar-top">
        <div class="ptm-mob-col">
            @foreach($sidebarLeftWidgets as $sbw)
                @foreach($sbw->media as $sbImg)
                <div class="ptm-mob-photo"><img src="{{ asset('storage/'.$sbImg->file_path) }}" alt="{{ $sbImg->original_name }}"></div>
                @endforeach
            @endforeach
        </div>
        <div class="ptm-mob-col">
            @foreach($sidebarRightWidgets as $sbw)
                @foreach($sbw->media as $sbImg)
                <img src="{{ asset('storage/'.$sbImg->file_path) }}" alt="{{ $sbImg->original_name }}">
                @endforeach
            @endforeach
        </div>
    </div>
    @endif

    {{-- ===============================================================
         TICKER / RUNNING TEXT
    ================================================================ --}}
    @if($tickerWidgets->count())
    @foreach($tickerWidgets as $widget)
        @include('public.custom-page._widget_render')
    @endforeach
    @endif

    {{-- ===============================================================
         TEMPLATE SECTION (tabbed layout + sidebars) — above berita
    ================================================================ --}}
    @if($hasTemplate)
    <div class="ptm-template-section">
        <div class="row g-3">
            {{-- LEFT SIDEBAR --}}
            <div class="col-lg-2 ptm-sidebar-left">
                @if($sidebarLeftWidgets->count())
                    @foreach($sidebarLeftWidgets as $sbWidget)
                    @php $sbImages = $sbWidget->media; $removeBg = !empty($sbWidget->settings['remove_background']); @endphp
                    @if($sbImages->count())
                        @foreach($sbImages as $sbImg)
                        <div class="ptm-sidebar-img-wrap {{ $removeBg ? 'bg-removed' : '' }}">
                            <img src="{{ asset('storage/'.$sbImg->file_path) }}" alt="{{ $sbImg->original_name }}">
                        </div>
                        @endforeach
                    @endif
                    @endforeach
                @else
                    <div class="ptm-sidebar-placeholder">
                        <i class="fas fa-image"></i>
                        <span>{{ __('messages.label_gambar_kiri') }}</span>
                    </div>
                @endif
            </div>

            {{-- CENTER: TABS + CONTENT --}}
            <div class="col-lg-7 ptm-content-col">
                @if($tabFrameWidget && $tabFrameWidget->text_content)
                    @php
                        // Support both JSON (new) and pipe format (legacy)
                        $rawTabContent = $tabFrameWidget->text_content;
                        $decodedTabs   = json_decode($rawTabContent, true);
                        $tabs = [];
                        if (is_array($decodedTabs)) {
                            foreach ($decodedTabs as $t) {
                                $tabs[] = [
                                    'label'   => $t['name'] ?? '',
                                    'type'    => $t['type'] ?? 'text',
                                    'content' => $t['content'] ?? '',
                                ];
                            }
                        } else {
                            foreach (array_filter(array_map('trim', explode("\n", $rawTabContent))) as $line) {
                                $parts = explode('|', $line, 2);
                                $tabs[] = ['label' => trim($parts[0] ?? ''), 'type' => 'text', 'content' => trim($parts[1] ?? '')];
                            }
                        }
                        // Group tab media by tab index
                        $tabMedia = [];
                        foreach ($tabFrameWidget->media as $m) {
                            $tidx = (int)floor($m->position / 1000);
                            $tabMedia[$tidx][] = $m;
                        }
                    @endphp
                    @if(count($tabs))
                    <ul class="nav ptm-tabs mb-0" role="tablist">
                        @foreach($tabs as $tIdx => $tab)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $tIdx === 0 ? 'active' : '' }}"
                                    id="ptmTab{{ $tIdx }}" data-bs-toggle="tab"
                                    data-bs-target="#ptmPane{{ $tIdx }}" type="button"
                                    role="tab" aria-selected="{{ $tIdx === 0 ? 'true' : 'false' }}">
                                {{ $tab['label'] }}
                            </button>
                        </li>
                        @endforeach
                    </ul>
                    <div class="tab-content ptm-tab-content">
                        @foreach($tabs as $tIdx => $tab)
                        <div class="tab-pane fade {{ $tIdx === 0 ? 'show active' : '' }}"
                             id="ptmPane{{ $tIdx }}" role="tabpanel">
                            @if($tab['type'] === 'photo')
                                @if(!empty($tabMedia[$tIdx]))
                                @php $photoCount = count($tabMedia[$tIdx]); @endphp
                                @if($photoCount === 1)
                                {{-- Single photo: fill the full tab frame --}}
                                <div class="text-center">
                                    <img src="{{ asset('storage/'.$tabMedia[$tIdx][0]->file_path) }}"
                                         alt="{{ $tabMedia[$tIdx][0]->original_name }}"
                                         class="img-fluid rounded shadow-sm"
                                         style="width:100%;max-height:520px;object-fit:contain;cursor:pointer;"
                                         onclick="window.open(this.src,'_blank')">
                                </div>
                                @else
                                {{-- Multiple photos: responsive grid --}}
                                <div class="row g-2">
                                    @foreach($tabMedia[$tIdx] as $tm)
                                    <div class="{{ $photoCount <= 2 ? 'col-6' : ($photoCount <= 6 ? 'col-6 col-md-4' : 'col-4 col-md-3') }}">
                                        <img src="{{ asset('storage/'.$tm->file_path) }}" alt="{{ $tm->original_name }}"
                                             class="img-fluid rounded shadow-sm w-100" style="aspect-ratio:4/3;object-fit:cover;cursor:pointer;"
                                             onclick="window.open(this.src,'_blank')">
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                                @else
                                <p class="text-muted fst-italic"><i class="fas fa-image me-1"></i>{{ __('messages.empty_foto_belum_diunggah') }}</p>
                                @endif
                            @elseif($tab['type'] === 'pdf')
                                @if(!empty($tabMedia[$tIdx]))
                                <ul class="list-unstyled">
                                    @foreach($tabMedia[$tIdx] as $tm)
                                    <li class="mb-2">
                                        <a href="{{ asset('storage/'.$tm->file_path) }}" target="_blank" class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-file-pdf me-1"></i> {{ $tm->original_name }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                                @else
                                <p class="text-muted fst-italic"><i class="fas fa-file-pdf me-1"></i>{{ __('messages.empty_pdf_belum_diunggah') }}</p>
                                @endif
                            @else
                                @if($tab['content'])
                                    {!! nl2br(e($tab['content'])) !!}
                                @else
                                    <p class="text-muted fst-italic">{{ __('messages.empty_konten_belum_diisi') }}</p>
                                @endif
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif
                @else
                    {{-- Empty template skeleton with placeholder tabs --}}
                    <ul class="nav ptm-tabs mb-0" role="tablist">
                        @foreach(['Tab 1', 'Tab 2', 'Tab 3'] as $tIdx => $tLabel)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $tIdx === 0 ? 'active' : '' }}"
                                    id="ptmTab{{ $tIdx }}" data-bs-toggle="tab"
                                    data-bs-target="#ptmPane{{ $tIdx }}" type="button"
                                    role="tab" aria-selected="{{ $tIdx === 0 ? 'true' : 'false' }}">
                                {{ $tLabel }}
                            </button>
                        </li>
                        @endforeach
                    </ul>
                    <div class="tab-content ptm-tab-content">
                        @foreach(['Tab 1', 'Tab 2', 'Tab 3'] as $tIdx => $tLabel)
                        <div class="tab-pane fade {{ $tIdx === 0 ? 'show active' : '' }}"
                             id="ptmPane{{ $tIdx }}" role="tabpanel">
                            <p class="text-muted fst-italic">{{ __('messages.empty_konten_belum_diisi') }}</p>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- RIGHT SIDEBAR --}}
            <div class="col-lg-3 ptm-sidebar-right">
                @if($sidebarRightWidgets->count())
                    @foreach($sidebarRightWidgets as $sbWidget)
                    @php $sbImages = $sbWidget->media; $removeBg = !empty($sbWidget->settings['remove_background']); @endphp
                    @if($sbImages->count())
                        @foreach($sbImages as $sbImg)
                        <div class="ptm-sidebar-img-wrap {{ $removeBg ? 'bg-removed' : '' }}">
                            <img src="{{ asset('storage/'.$sbImg->file_path) }}" alt="{{ $sbImg->original_name }}">
                        </div>
                        @endforeach
                    @endif
                    @endforeach
                @else
                    <div class="ptm-sidebar-placeholder">
                        <i class="fas fa-image"></i>
                        <span>{{ __('messages.label_gambar_kanan') }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- ===============================================================
         STANDALONE VIDEO GALLERY (PTM AU only)
    ================================================================ --}}
    @if($standaloneVideoWidgets->count() && $menu->slug === 'ptm-au')
    <div class="standalone-video-section mb-4 rounded-3 overflow-hidden p-4">
        <h6 class="fw-bold mb-3 standalone-video-heading"><i class="fas fa-play-circle me-2"></i>{{ __('messages.heading_galeri_video') }}</h6>
        <div class="row g-3">
            @foreach($standaloneVideoWidgets as $widget)
            <div class="col-12 col-md-6 col-lg-4">
                @include('public.custom-page._widget_render')
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ===============================================================
         BERITA CAROUSEL (horizontal scroll — DO NOT MODIFY)
    ================================================================ --}}
    @if(count($articles) > 0)
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <h5 class="fw-bold mb-0 article-section-title">
            <span style="display:inline-block;width:4px;height:22px;background:linear-gradient(180deg,#003d82,#0066cc);border-radius:2px;vertical-align:middle;margin-right:8px;"></span>
            {{ __('messages.news') }} {{ $menu->name }}
        </h5>
        <div class="d-flex gap-1">
            <button class="article-carousel-nav-btn" onclick="articleCarouselSlide(-1)" title="{{ __('messages.btn_sebelumnya') }}"><i class="fas fa-chevron-left" style="font-size:12px;"></i></button>
            <button class="article-carousel-nav-btn" onclick="articleCarouselSlide(1)"  title="{{ __('messages.btn_berikutnya') }}"><i class="fas fa-chevron-right" style="font-size:12px;"></i></button>
        </div>
    </div>

    <div class="article-carousel-wrap mb-2" id="articleCarouselWrap">
        <div class="article-carousel-track" id="articleCarouselTrack">
            @foreach($articles as $artIdx => $article)
            @php
                $thumbMedia = null;
                $dateVal    = null;
                $descText   = null;
                foreach ($article['widgets'] as $aw) {
                    if (!$thumbMedia && in_array($aw->widget_type, ['foto','galeri_foto_lokal','logo']) && $aw->media->count()) {
                        $thumbMedia = $aw->media->first();
                    }
                    if (!$dateVal  && $aw->widget_type === 'tanggal'   && $aw->text_content) $dateVal  = $aw->text_content;
                    if (!$descText && $aw->widget_type === 'deskripsi' && $aw->text_content) $descText = $aw->text_content;
                }
            @endphp
            <div class="article-news-card" onclick="openArticle({{ $artIdx }})">
                <div class="article-card-thumb">
                    @if($thumbMedia)
                        <img src="{{ asset('storage/' . $thumbMedia->file_path) }}" alt="{{ $article['title'] }}">
                    @else
                        <div class="article-card-no-img"><i class="fas fa-newspaper"></i></div>
                    @endif
                </div>
                <div class="article-card-info">
                    <span class="article-card-kategori">{{ __('messages.label_artikel') }}</span>
                    <div class="article-card-title">{{ $article['title'] }}</div>
                    @if($dateVal)
                    <div class="article-card-date"><i class="far fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($dateVal)->translatedFormat('d F Y') }}</div>
                    @endif
                    @if($descText)
                    <div class="article-card-desc">{{ \Illuminate\Support\Str::limit($descText, 110) }}</div>
                    @endif
                    <span class="article-card-readmore">{{ __('messages.btn_baca_selengkapnya') }} <i class="fas fa-arrow-right ms-1"></i></span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Hidden full article contents --}}
    @foreach($articles as $artIdx => $article)
    <div id="art_content_{{ $artIdx }}" class="d-none">
        @foreach($article['widgets'] as $widget)
            @include('public.custom-page._widget_render')
        @endforeach
    </div>
    @endforeach

    {{-- Full-page Article Overlay --}}
    <div id="articleOverlay" role="dialog" aria-modal="true">
        <div class="article-overlay-bar">
            <button class="overlay-back-btn" onclick="closeArticle()">
                <i class="fas fa-arrow-left"></i> {{ __('messages.btn_kembali') }}
            </button>
            <span class="overlay-title" id="overlayTitle"></span>
            <button class="article-overlay-close" onclick="closeArticle()" title="Tutup">&#x2715;</button>
        </div>
        <div class="article-overlay-body" id="overlayBody">
            <div class="article-overlay-inner" id="overlayContent"></div>
        </div>
    </div>

    @endif {{-- end berita carousel guard --}}

    {{-- ===============================================================
         STANDALONE FOTO GALLERIES (each as its own section)
    ================================================================ --}}
    @foreach($standaloneFotoWidgets as $widget)
    <div class="standalone-foto-section rounded-3 mt-4 mb-4 p-4">
        @include('public.custom-page._widget_render')
    </div>
    @endforeach

@elseif($menu->activeWidgets->count())
    {{-- ========== NORMAL LINEAR MODE ========== --}}
    @if($standaloneVideoWidgets->count() && $menu->slug === 'ptm-au')
    <div class="standalone-video-section linear-video-wrap mb-4 rounded-3 overflow-hidden p-4">
        <h6 class="fw-bold mb-3 standalone-video-heading"><i class="fas fa-play-circle me-2"></i>{{ __('messages.heading_galeri_video') }}</h6>
        <div class="row g-3">
            @foreach($standaloneVideoWidgets as $widget)
            <div class="col-12 col-md-6 col-lg-4">
                @include('public.custom-page._widget_render')
            </div>
            @endforeach
        </div>
    </div>
    @endif
    @foreach($standaloneFotoWidgets as $widget)
    <div class="standalone-foto-section linear-foto-wrap rounded-3 mb-4 p-4">
        @include('public.custom-page._widget_render')
    </div>
    @endforeach
    @php
        $nonGaleriWidgets = $menu->activeWidgets->reject(fn($w) => in_array($w->widget_type, ['galeri_foto_lokal', 'galeri_video_lokal', 'gambar_sidebar']));
    @endphp
    @if($nonGaleriWidgets->count())
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            @foreach($nonGaleriWidgets as $widget)
                @include('public.custom-page._widget_render')
            @endforeach
        </div>
    </div>
    @endif
@else
    {{-- ========== EMPTY STATE ========== --}}
    <div class="text-center py-5">
        <i class="fas fa-file-alt d-block mb-3" style="font-size:64px;color:#dee2e6;"></i>
        <h5 class="text-muted">{{ __('messages.empty_halaman_kosong') }}</h5>
    </div>
@endif
</div>

@push('scripts')
<script>
/* ================================================================
   ARTICLE CAROUSEL
================================================================ */
(function () {
    const track = document.getElementById('articleCarouselTrack');
    if (!track) return;

    let pos = 0;
    const GAP = 20;
    const CARD_W = 260 + GAP;

    function maxPos() {
        const wrap = document.getElementById('articleCarouselWrap');
        return Math.max(0, track.scrollWidth - (wrap ? wrap.offsetWidth : window.innerWidth));
    }
    function setPos(p) {
        pos = Math.max(0, Math.min(p, maxPos()));
        track.style.transform = 'translateX(-' + pos + 'px)';
    }

    window.articleCarouselSlide = function (dir) { setPos(pos + dir * CARD_W * 2); };

    // Drag / swipe
    let dragging = false, startX = 0, startPos = 0;
    track.addEventListener('mousedown',  e => { dragging=true; startX=e.clientX; startPos=pos; track.style.transition='none'; });
    document.addEventListener('mousemove', e => { if(!dragging) return; setPos(startPos - (e.clientX - startX)); });
    document.addEventListener('mouseup',   () => { dragging=false; track.style.transition=''; });
    track.addEventListener('touchstart',  e => { startX=e.touches[0].clientX; startPos=pos; track.style.transition='none'; }, {passive:true});
    track.addEventListener('touchmove',   e => { setPos(startPos - (e.touches[0].clientX - startX)); }, {passive:true});
    track.addEventListener('touchend',    () => { track.style.transition=''; });
})();

/* ================================================================
   ARTICLE OVERLAY  (open / close)
================================================================ */
function openArticle(idx) {
    const src     = document.getElementById('art_content_' + idx);
    const content = document.getElementById('overlayContent');
    const title   = document.getElementById('overlayTitle');
    const body    = document.getElementById('overlayBody');
    if (!src) return;

    content.innerHTML = src.innerHTML;
    const h3 = content.querySelector('h3');
    title.textContent = h3 ? h3.textContent.trim() : '';

    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    content.style.color = isDark ? '#e0e0e0' : '';

    document.getElementById('articleOverlay').classList.add('visible');
    body.scrollTop = 0;
    document.body.style.overflow = 'hidden';
}

function closeArticle() {
    document.getElementById('articleOverlay').classList.remove('visible');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeArticle();
});
</script>
@endpush
@endsection
