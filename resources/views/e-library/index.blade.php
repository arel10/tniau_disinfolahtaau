@extends('layouts.public')
@section('title', __('messages.e_library') . __('messages.site_title_suffix'))

@section('hero')
<div class="page-hero">
    <div class="container-fluid px-3">
        <h2><i class="fas fa-book-open me-2"></i>{{ __('messages.e_library') }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item active">{{ __('messages.e_library') }}</li>
            </ol>
        </nav>
    </div>
</div>
@endsection

@section('content')
@push('styles')
<style>
.elibrary-section {
    padding: 50px 0 70px;
    background: #f4f6f9;
}
[data-theme="dark"] .elibrary-section { background: #111827; }

.elibrary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 28px;
}

.elibrary-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 16px rgba(0,31,63,0.08);
    overflow: hidden;
    transition: transform 0.3s, box-shadow 0.3s;
    border: 1px solid rgba(0,61,130,0.06);
}
.elibrary-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 32px rgba(0,31,63,0.15);
}
[data-theme="dark"] .elibrary-card { background: #1e293b; border-color: rgba(255,255,255,0.06); }

.elibrary-viewer {
    background: #6b7280;
    position: relative;
    width: 100%;
    height: 440px;
    overflow: hidden;
}
.elibrary-viewer iframe,
.elibrary-viewer embed,
.elibrary-viewer object {
    width: 100%;
    height: 100%;
    border: none;
    display: block;
}

.elibrary-card-body {
    padding: 16px 20px;
}
.elibrary-card-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1a1a2e;
    margin: 0 0 8px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
[data-theme="dark"] .elibrary-card-title { color: #f1f5f9; }

.elibrary-card-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.elibrary-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
    border: none;
    cursor: pointer;
}
.elibrary-btn-view {
    background: linear-gradient(135deg, #001f3f, #0066cc);
    color: #fff;
}
.elibrary-btn-view:hover { color: #fff; box-shadow: 0 4px 12px rgba(0,102,204,0.4); }
.elibrary-btn-download {
    background: linear-gradient(135deg, #16a34a, #22c55e);
    color: #fff;
}
.elibrary-btn-download:hover { color: #fff; box-shadow: 0 4px 12px rgba(22,163,74,0.4); }
.elibrary-btn-share {
    background: #f1f5f9;
    color: #334155;
    border: 1px solid #e2e8f0;
}
.elibrary-btn-share:hover { background: #e2e8f0; }
[data-theme="dark"] .elibrary-btn-share { background: #334155; color: #e2e8f0; border-color: #475569; }

.elibrary-empty {
    text-align: center;
    padding: 80px 20px;
}
.elibrary-empty i { font-size: 3rem; color: #94a3b8; margin-bottom: 16px; }
.elibrary-empty p { color: #64748b; font-size: 1.05rem; }

/* Share Modal */
.share-overlay {
    position: fixed; inset: 0;
    background: rgba(0,0,0,0.5);
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
}
.share-overlay.active { display: flex; }
.share-box {
    background: #fff;
    border-radius: 14px;
    padding: 28px;
    width: 380px;
    max-width: 90%;
    text-align: center;
    box-shadow: 0 16px 48px rgba(0,0,0,0.2);
    position: relative;
}
[data-theme="dark"] .share-box { background: #1e293b; color: #f1f5f9; }
.share-box h5 { font-weight: 700; margin-bottom: 16px; }
.share-box .close-share {
    position: absolute; top: 12px; right: 16px;
    background: none; border: none; font-size: 1.4rem; color: #64748b; cursor: pointer;
}
</style>
@endpush

<section class="elibrary-section">
    <div class="container">
        @if($documents->count())
        <div class="elibrary-grid">
            @foreach($documents as $doc)
            <div class="elibrary-card">
                <div class="elibrary-viewer">
                    @php
                        $ext = strtolower(pathinfo($doc->pdf_path, PATHINFO_EXTENSION));
                        $fileUrl = asset('storage/' . $doc->pdf_path);
                    @endphp
                    @if($ext === 'pdf')
                        <iframe src="{{ $fileUrl }}#toolbar=1&navpanes=0&scrollbar=1" loading="lazy"></iframe>
                    @else
                        <div class="d-flex flex-column align-items-center justify-content-center h-100 text-white">
                            <i class="fas fa-file fa-4x mb-3 opacity-75"></i>
                            <span class="fw-semibold">{{ strtoupper($ext) }} File</span>
                        </div>
                    @endif
                </div>
                <div class="elibrary-card-body">
                    <h6 class="elibrary-card-title">{{ $doc->title }}</h6>
                    <div class="elibrary-card-actions">
                        <a href="{{ route('e-library.show', $doc->slug) }}" class="elibrary-btn elibrary-btn-view">
                            <i class="fas fa-eye"></i> {{ __('messages.btn_baca') }}
                        </a>
                        <a href="{{ route('e-library.download', $doc->slug) }}" class="elibrary-btn elibrary-btn-download">
                            <i class="fas fa-download"></i> {{ __('messages.btn_unduh') }}
                        </a>
                        <button type="button" class="elibrary-btn elibrary-btn-share btn-share-trigger" data-url="{{ route('e-library.show', $doc->slug) }}">
                            <i class="fas fa-share-alt"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="elibrary-empty">
            <i class="fas fa-book-open d-block"></i>
            <p>{{ __('messages.empty_elibrary') }}</p>
        </div>
        @endif
    </div>
</section>

{{-- Share Modal --}}
<div class="share-overlay" id="shareOverlay">
    <div class="share-box">
        <button class="close-share" id="closeShare">&times;</button>
        <h5><i class="fas fa-share-alt me-2"></i>{{ __('messages.label_bagikan') }}</h5>
        <div class="input-group mb-3">
            <input type="text" id="shareUrlInput" class="form-control" readonly>
            <button class="btn btn-primary" id="copyShareBtn"><i class="fas fa-copy"></i></button>
        </div>
        <div class="d-flex justify-content-center gap-2">
            <a id="shareWA" href="#" target="_blank" class="btn btn-success btn-sm"><i class="fab fa-whatsapp"></i> WhatsApp</a>
            <a id="shareFB" href="#" target="_blank" class="btn btn-primary btn-sm"><i class="fab fa-facebook"></i> Facebook</a>
            <a id="shareTW" href="#" target="_blank" class="btn btn-info btn-sm text-white"><i class="fab fa-twitter"></i> Twitter</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.getElementById('shareOverlay');
    const urlInput = document.getElementById('shareUrlInput');

    document.querySelectorAll('.btn-share-trigger').forEach(btn => {
        btn.addEventListener('click', function() {
            const url = this.dataset.url;
            urlInput.value = url;
            document.getElementById('shareWA').href = 'https://wa.me/?text=' + encodeURIComponent(url);
            document.getElementById('shareFB').href = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(url);
            document.getElementById('shareTW').href = 'https://twitter.com/intent/tweet?url=' + encodeURIComponent(url);
            overlay.classList.add('active');
        });
    });

    document.getElementById('closeShare').addEventListener('click', () => overlay.classList.remove('active'));
    overlay.addEventListener('click', e => { if (e.target === overlay) overlay.classList.remove('active'); });

    document.getElementById('copyShareBtn').addEventListener('click', function() {
        urlInput.select();
        navigator.clipboard.writeText(urlInput.value);
        this.innerHTML = '<i class="fas fa-check"></i>';
        setTimeout(() => this.innerHTML = '<i class="fas fa-copy"></i>', 2000);
    });
});
</script>
@endpush
@endsection
