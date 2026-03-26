@extends('layouts.public')

@section('title', $berita->localized_judul . __('messages.site_title_suffix'))

@push('styles')
<style>
    .berita-detail-section {
        padding: 30px 0 60px;
        position: relative;
        z-index: 1;
        background: #f8f9fa;
    }
    [data-theme="dark"] .berita-detail-section { background: var(--bg-color); }
    .berita-detail-section .card {
        height: auto !important;
    }
    .berita-detail-section .card:hover {
        transform: none !important;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
    }

    /* ===== SHARE SECTION ===== */
    .share-card { border:none; border-radius:12px; box-shadow:0 2px 12px rgba(0,61,130,0.08); overflow:hidden; }
    .share-card .card-body { padding:20px 24px; }
    .share-title { font-weight:700; color:#001f3f; font-size:1rem; margin-bottom:12px; }
    [data-theme="dark"] .share-title { color:#e0e0e0; }
    .share-status-input {
        width:100%; border:1px solid #dee2e6; border-radius:8px; padding:10px 14px;
        font-size:0.9rem; resize:none; transition:border-color 0.2s;
        min-height: 60px;
    }
    [data-theme="dark"] .share-status-input { background:#1e293b; border-color:#2a2a4a; color:#e0e0e0; }
    .share-status-input:focus { border-color:#003d82; outline:none; box-shadow:0 0 0 3px rgba(0,61,130,0.1); }
    .share-char-count { font-size:0.75rem; color:#999; text-align:right; margin-top:4px; }
    .share-buttons { display:flex; flex-wrap:wrap; gap:8px; margin-top:14px; }
    .share-btn {
        display:inline-flex; align-items:center; gap:6px;
        padding:8px 16px; border-radius:8px; font-size:0.85rem; font-weight:600;
        text-decoration:none; color:#fff; border:none; cursor:pointer;
        transition:transform 0.15s, box-shadow 0.15s;
    }
    .share-btn:hover { transform:translateY(-2px); box-shadow:0 4px 12px rgba(0,0,0,0.2); color:#fff; }
    .share-btn-facebook { background:#1877f2; }
    .share-btn-twitter { background:#1da1f2; }
    .share-btn-whatsapp { background:#25d366; }
    .share-btn-telegram { background:#0088cc; }
    .share-btn-linkedin { background:#0a66c2; }
    .share-btn-email { background:#6c757d; }
    .share-btn-copy { background:linear-gradient(135deg,#001f3f,#003d82); }
    .share-btn-copy.copied { background:#198754; }
    .share-divider { border-top:1px solid #eee; margin:14px 0; }
    [data-theme="dark"] .share-divider { border-top-color:#2a2a4a; }
    .share-url-row { display:flex; gap:8px; align-items:center; }
    .share-url-input {
        flex:1; border:1px solid #dee2e6; border-radius:8px; padding:8px 12px;
        font-size:0.82rem; color:#555; background:#f8f9fa;
    }
    [data-theme="dark"] .share-url-input { background:#1e293b; border-color:#2a2a4a; color:#ccc; }
    .share-url-input:focus { outline:none; border-color:#003d82; }

    /* ===== GALERI TAMBAHAN ===== */
    .galeri-tambahan-grid {
        display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr));
        gap:12px; margin-top:12px;
    }
    .galeri-tambahan-item { border-radius:8px; overflow:hidden; cursor:pointer; position:relative; }
    .galeri-tambahan-item img { width:100%; height:180px; object-fit:cover; transition:transform 0.3s; display:block; }
    .galeri-tambahan-item video { width:100%; height:180px; object-fit:cover; display:block; }
    .galeri-tambahan-item:hover img { transform:scale(1.05); }
    .galeri-tambahan-item .play-overlay {
        position:absolute; inset:0; display:flex; align-items:center; justify-content:center;
        background:rgba(0,0,0,0.3); pointer-events:none;
    }
    .galeri-tambahan-item .play-overlay i { font-size:2rem; color:#fff; text-shadow:0 2px 8px rgba(0,0,0,0.5); }

    /* ===== LIGHTBOX ===== */
    .berita-lightbox {
        position:fixed; inset:0; background:rgba(0,0,0,0.9); z-index:9999;
        display:none; align-items:center; justify-content:center;
    }
    .berita-lightbox.active { display:flex; }
    .berita-lightbox img, .berita-lightbox video { max-width:90vw; max-height:85vh; border-radius:8px; }
    .berita-lightbox-close {
        position:absolute; top:20px; right:20px; color:#fff; font-size:2rem;
        cursor:pointer; z-index:10000; transition:transform 0.2s;
    }
    .berita-lightbox-close:hover { transform:scale(1.2); }
    .berita-lightbox-nav {
        position:absolute; top:50%; transform:translateY(-50%);
        color:#fff; font-size:2.5rem; cursor:pointer; z-index:10000;
        padding:10px; opacity:0.7; transition:opacity 0.2s;
    }
    .berita-lightbox-nav:hover { opacity:1; }
    .berita-lightbox-prev { left:20px; }
    .berita-lightbox-next { right:20px; }

    @media (max-width: 767.98px) {
        .share-buttons { gap:6px; }
        .share-btn { padding:7px 12px; font-size:0.8rem; }
        .galeri-tambahan-grid { grid-template-columns:repeat(auto-fill, minmax(140px, 1fr)); }
        .galeri-tambahan-item img, .galeri-tambahan-item video { height:120px; }
    }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="container">
        <h2><i class="fas fa-newspaper me-2"></i>{{ __('messages.news') }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('berita.index') }}">{{ __('messages.news') }}</a></li>
                <li class="breadcrumb-item active">{{ Str::limit($berita->localized_judul, 50) }}</li>
            </ol>
        </nav>
    </div>
</div>

<section class="berita-detail-section">
<div class="container">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-12">
            <article>
                <span class="badge badge-kategori mb-3">{{ $berita->kategori->localized_nama_kategori }}</span>
                <h1 class="mb-3">{{ $berita->localized_judul }}</h1>
                
                <div class="text-muted mb-4">
                    <i class="fas fa-user"></i> {{ $berita->user->name }} | 
                    <i class="fas fa-calendar"></i> {{ $berita->published_at->format('d F Y, H:i') }} {{ __('messages.label_wib') }} | 
                    <i class="fas fa-eye"></i> {{ display_views($berita) }} {{ __('messages.label_views') }}
                </div>

                @if($berita->gambar_utama)
                <img src="{{ asset('storage/' . $berita->gambar_utama) }}" class="img-fluid rounded mb-4 w-100" alt="{{ $berita->localized_judul }}">
                @endif

                @if($berita->localized_ringkasan)
                <div class="alert alert-light border">
                    <strong>{{ __('messages.label_ringkasan') }}</strong> {{ $berita->localized_ringkasan }}
                </div>
                @endif

                <div class="content">
                    {!! nl2br(e($berita->localized_konten)) !!}
                </div>

                {{-- Foto / Video Tambahan --}}
                @if($berita->gambar_tambahan && count($berita->gambar_tambahan))
                <div class="mt-4">
                    <h5 class="fw-bold mb-2"><i class="fas fa-images me-2 text-primary"></i>{{ __('messages.label_foto_video_tambahan') }}</h5>
                    <div class="galeri-tambahan-grid">
                        @foreach($berita->gambar_tambahan as $idx => $media)
                        @php
                            $ext = strtolower(pathinfo($media, PATHINFO_EXTENSION));
                            $isVideo = in_array($ext, ['mp4','mov','avi','mkv','webm']);
                        @endphp
                        <div class="galeri-tambahan-item" data-lightbox="{{ $idx }}" onclick="openBeritaLightbox({{ $idx }})">
                            @if($isVideo)
                                <video src="{{ asset('storage/'.$media) }}" muted></video>
                                <div class="play-overlay"><i class="fas fa-play-circle"></i></div>
                            @else
                                <img src="{{ asset('storage/'.$media) }}" alt="{{ __('messages.alt_foto') }} {{ $idx + 1 }}">
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </article>

            <!-- Bagikan / Share Section -->
            <div class="card share-card mt-4">
                <div class="card-body">
                    <h6 class="share-title"><i class="fas fa-share-alt me-2"></i>{{ __('messages.label_bagikan') }}</h6>

                    {{-- Status / Pesan kustom --}}
                    <textarea id="shareStatus" class="share-status-input"
                              placeholder="{{ __('messages.placeholder_share_status') }}"
                              maxlength="500">{{ $berita->localized_judul }}</textarea>
                    <div class="share-char-count"><span id="shareCharCount">{{ Str::length($berita->localized_judul) }}</span>/500</div>

                    {{-- Share Buttons --}}
                    <div class="share-buttons">
                        <button type="button" class="share-btn share-btn-facebook" onclick="shareToFacebook()">
                            <i class="fab fa-facebook-f"></i> Facebook
                        </button>
                        <button type="button" class="share-btn share-btn-twitter" onclick="shareToTwitter()">
                            <i class="fab fa-twitter"></i> Twitter / X
                        </button>
                        <button type="button" class="share-btn share-btn-whatsapp" onclick="shareToWhatsApp()">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </button>
                        <button type="button" class="share-btn share-btn-telegram" onclick="shareToTelegram()">
                            <i class="fab fa-telegram-plane"></i> Telegram
                        </button>
                        <button type="button" class="share-btn share-btn-linkedin" onclick="shareToLinkedIn()">
                            <i class="fab fa-linkedin-in"></i> LinkedIn
                        </button>
                        <button type="button" class="share-btn share-btn-email" onclick="shareToEmail()">
                            <i class="fas fa-envelope"></i> Email
                        </button>
                    </div>

                    <div class="share-divider"></div>

                    {{-- Copy Link --}}
                    <div class="share-url-row">
                        <input type="text" class="share-url-input" id="shareUrlInput" value="{{ url()->current() }}" readonly>
                        <button type="button" class="share-btn share-btn-copy" id="copyLinkBtn" onclick="copyShareLink()">
                            <i class="fas fa-copy"></i> <span id="copyLinkText">{{ __('messages.btn_salin_link') }}</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Berita Terkait -->
            @if($berita_terkait->count() > 0)
            <div class="mt-5">
                <h4 class="mb-4">{{ __('messages.heading_berita_terkait') }}</h4>
                <div class="row g-4">
                    @foreach($berita_terkait as $related)
                    <div class="col-md-6">
                        <div class="card">
                            @if($related->gambar_utama)
                                <img src="{{ asset('storage/' . $related->gambar_utama) }}" class="card-img-top" alt="{{ $related->localized_judul }}" style="height: 150px; object-fit: cover;">
                            @endif
                            <div class="card-body">
                                <h6 class="card-title">{{ Str::limit($related->localized_judul, 60) }}</h6>
                                <p class="text-muted small mb-2">
                                    <i class="fas fa-calendar"></i> {{ $related->published_at->format('d M Y') }}
                                </p>
                                <a href="{{ route('berita.show', $related->slug) }}" class="btn btn-sm btn-outline-primary">{{ __('messages.btn_baca') }}</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
</section>

{{-- Lightbox Overlay --}}
<div class="berita-lightbox" id="beritaLightbox">
    <span class="berita-lightbox-close" onclick="closeBeritaLightbox()">&times;</span>
    <span class="berita-lightbox-nav berita-lightbox-prev" onclick="navigateLightbox(-1)"><i class="fas fa-chevron-left"></i></span>
    <span class="berita-lightbox-nav berita-lightbox-next" onclick="navigateLightbox(1)"><i class="fas fa-chevron-right"></i></span>
    <div id="beritaLightboxContent"></div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    // ===== CHARACTER COUNTER =====
    const statusEl  = document.getElementById('shareStatus');
    const countEl   = document.getElementById('shareCharCount');
    if (statusEl && countEl) {
        statusEl.addEventListener('input', function() {
            countEl.textContent = this.value.length;
        });
    }

    // Helper: get share data
    function getShareData() {
        const status = document.getElementById('shareStatus').value.trim();
        const url    = document.getElementById('shareUrlInput').value;
        return { status, url };
    }

    // ===== SHARE FUNCTIONS =====
    window.shareToFacebook = function() {
        const { status, url } = getShareData();
        const fbUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(url) + '&quote=' + encodeURIComponent(status);
        window.open(fbUrl, '_blank', 'width=600,height=400');
    };

    window.shareToTwitter = function() {
        const { status, url } = getShareData();
        const twUrl = 'https://twitter.com/intent/tweet?url=' + encodeURIComponent(url) + '&text=' + encodeURIComponent(status);
        window.open(twUrl, '_blank', 'width=600,height=400');
    };

    window.shareToWhatsApp = function() {
        const { status, url } = getShareData();
        const text = status ? status + '\n' + url : url;
        const waUrl = 'https://wa.me/?text=' + encodeURIComponent(text);
        window.open(waUrl, '_blank');
    };

    window.shareToTelegram = function() {
        const { status, url } = getShareData();
        const tgUrl = 'https://t.me/share/url?url=' + encodeURIComponent(url) + '&text=' + encodeURIComponent(status);
        window.open(tgUrl, '_blank', 'width=600,height=400');
    };

    window.shareToLinkedIn = function() {
        const { url } = getShareData();
        const liUrl = 'https://www.linkedin.com/sharing/share-offsite/?url=' + encodeURIComponent(url);
        window.open(liUrl, '_blank', 'width=600,height=400');
    };

    window.shareToEmail = function() {
        const { status, url } = getShareData();
        const subject = @json($berita->localized_judul);
        const body = status + '\n\n' + url;
        window.location.href = 'mailto:?subject=' + encodeURIComponent(subject) + '&body=' + encodeURIComponent(body);
    };

    window.copyShareLink = function() {
        const urlInput = document.getElementById('shareUrlInput');
        const btn      = document.getElementById('copyLinkBtn');
        const btnText  = document.getElementById('copyLinkText');

        if (navigator.clipboard) {
            navigator.clipboard.writeText(urlInput.value).then(function() {
                showCopied();
            });
        } else {
            urlInput.select();
            document.execCommand('copy');
            showCopied();
        }

        function showCopied() {
            btn.classList.add('copied');
            btnText.textContent = '{{ __('messages.btn_tersalin') }}';
            setTimeout(function() {
                btn.classList.remove('copied');
                btnText.textContent = '{{ __('messages.btn_salin_link') }}';
            }, 2000);
        }
    };

    // ===== LIGHTBOX =====
    const lightbox    = document.getElementById('beritaLightbox');
    const lbContent   = document.getElementById('beritaLightboxContent');
    const mediaItems  = document.querySelectorAll('.galeri-tambahan-item');
    let currentIdx    = 0;

    window.openBeritaLightbox = function(idx) {
        currentIdx = idx;
        renderLightboxItem();
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
    };

    window.closeBeritaLightbox = function() {
        lightbox.classList.remove('active');
        document.body.style.overflow = '';
        // Stop any playing video
        const vid = lbContent.querySelector('video');
        if (vid) vid.pause();
    };

    window.navigateLightbox = function(dir) {
        // Stop current video if any
        const vid = lbContent.querySelector('video');
        if (vid) vid.pause();

        currentIdx += dir;
        if (currentIdx < 0) currentIdx = mediaItems.length - 1;
        if (currentIdx >= mediaItems.length) currentIdx = 0;
        renderLightboxItem();
    };

    function renderLightboxItem() {
        if (!mediaItems.length) return;
        const item = mediaItems[currentIdx];
        const img  = item.querySelector('img');
        const vid  = item.querySelector('video');

        if (vid) {
            lbContent.innerHTML = '<video src="' + vid.src + '" controls autoplay style="max-width:90vw;max-height:85vh;border-radius:8px;"></video>';
        } else if (img) {
            lbContent.innerHTML = '<img src="' + img.src + '" alt="' + (img.alt || '') + '" style="max-width:90vw;max-height:85vh;border-radius:8px;">';
        }
    }

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (!lightbox.classList.contains('active')) return;
        if (e.key === 'Escape') closeBeritaLightbox();
        if (e.key === 'ArrowLeft')  navigateLightbox(-1);
        if (e.key === 'ArrowRight') navigateLightbox(1);
    });

    // Close on backdrop click
    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox) closeBeritaLightbox();
    });
})();
</script>
@endpush
