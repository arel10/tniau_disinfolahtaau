@extends('layouts.public')

@section('title', __('messages.pembangunan') . ' - ' . __('messages.zona_integritas'))

@section('hero')
<div class="page-hero">
    <div class="container-fluid px-3">
        <h2><i class="fas fa-hard-hat me-2"></i>{{ __('messages.hero_pembangunan') }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('zona.index') }}">{{ __('messages.zona_integritas') }}</a></li>
                <li class="breadcrumb-item active">{{ __('messages.pembangunan') }}</li>
            </ol>
        </nav>
    </div>
</div>
@endsection

@section('content')
@push('styles')
<style>
/* ================================================================
   ZI PAGE - Modern Layout (Pembangunan / Pemantauan)
================================================================ */
.zi-page-section {
    padding: 50px 0 70px;
    background: #f4f6f9;
}
[data-theme="dark"] .zi-page-section { background: #111827; }

.zi-post {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 20px rgba(0,31,63,0.07);
    margin-bottom: 48px;
    overflow: hidden;
    border: 1px solid rgba(0,61,130,0.06);
    transition: box-shadow 0.3s;
}
.zi-post:hover { box-shadow: 0 8px 32px rgba(0,31,63,0.12); }
[data-theme="dark"] .zi-post { background: #1e293b; border-color: rgba(255,255,255,0.06); }

.zi-post-header {
    padding: 28px 32px 0;
    position: relative;
}
.zi-post-header::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, #001f3f, #0066cc, #00a8ff);
    border-radius: 16px 16px 0 0;
}
.zi-post-header h3 {
    font-size: 1.5rem;
    font-weight: 800;
    color: #1a1a2e;
    margin: 0;
    line-height: 1.4;
}
[data-theme="dark"] .zi-post-header h3 { color: #f1f5f9; }

.zi-post-date {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.82rem;
    color: #64748b;
    margin-top: 8px;
    padding: 4px 12px;
    background: #f1f5f9;
    border-radius: 20px;
}
[data-theme="dark"] .zi-post-date { background: #334155; color: #94a3b8; }

.zi-post-body { padding: 24px 32px 32px; }

.zi-row {
    display: flex;
    gap: 28px;
    margin-bottom: 24px;
}
.zi-col-text { flex: 1; min-width: 0; }
.zi-col-pdf  { flex: 1; min-width: 0; }

@media (max-width: 991.98px) {
    .zi-row { flex-direction: column; gap: 20px; }
    .zi-post-header { padding: 24px 20px 0; }
    .zi-post-body { padding: 20px 20px 24px; }
}

.zi-content {
    font-size: 0.98rem;
    line-height: 1.85;
    color: #334155;
}
[data-theme="dark"] .zi-content { color: #cbd5e1; }

/* PDF */
.zi-pdf-wrap {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
}
[data-theme="dark"] .zi-pdf-wrap { background: #0f172a; border-color: #334155; }

.zi-pdf-embed {
    width: 100%;
    min-height: 480px;
    border: none;
    display: block;
}

.zi-pdf-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    padding: 12px 16px;
    background: #f1f5f9;
    border-top: 1px solid #e2e8f0;
}
[data-theme="dark"] .zi-pdf-footer { background: #1e293b; border-color: #334155; }

.zi-pdf-name {
    display: flex; align-items: center; gap: 8px;
    color: #dc2626; font-size: 0.85rem; font-weight: 600;
    text-decoration: none; max-width: 60%; overflow: hidden;
    text-overflow: ellipsis; white-space: nowrap;
}
.zi-pdf-name:hover { color: #b91c1c; }
.zi-pdf-name i { font-size: 1.1rem; }

.zi-btn-download {
    display: inline-flex; align-items: center; gap: 6px;
    background: linear-gradient(135deg, #c0392b, #e74c3c);
    color: #fff; border: none; border-radius: 8px;
    padding: 8px 20px; font-size: 0.85rem; font-weight: 700;
    text-decoration: none; transition: all 0.3s;
    box-shadow: 0 2px 8px rgba(192,57,43,0.25);
}
.zi-btn-download:hover {
    color: #fff; transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(192,57,43,0.4);
}

/* Media (gambar/video) */
.zi-media-wrap {
    border-radius: 10px;
    overflow: hidden;
    display: inline-block;
    transition: transform 0.3s, box-shadow 0.3s;
}
.zi-media-wrap:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
}
.zi-media-wrap img, .zi-media-wrap video {
    width: 100%;
    height: auto;
    max-height: 500px;
    object-fit: contain;
    display: block;
    background: #f8fafc;
}

/* Empty */
.zi-empty {
    text-align: center; padding: 80px 20px;
}
.zi-empty i { font-size: 3rem; color: #94a3b8; margin-bottom: 16px; }
.zi-empty p { color: #64748b; font-size: 1.05rem; }
</style>
@endpush

<section class="zi-page-section">
    <div class="container">
        @forelse($pages as $page)
        <article class="zi-post">
            <div class="zi-post-header">
                @if($page->judul)
                    <h3>{{ $page->judul }}</h3>
                @endif
                <div class="zi-post-date">
                    <i class="far fa-calendar-alt"></i>
                    {{ $page->created_at->format('d F Y') }}
                </div>
            </div>

            <div class="zi-post-body">
                @if($page->konten || $page->pdf_path)
                <div class="zi-row">
                    @if($page->konten)
                    <div class="zi-col-text">
                        <div class="zi-content">{!! nl2br(e($page->konten)) !!}</div>
                    </div>
                    @endif

                    @if($page->pdf_path)
                    <div class="zi-col-pdf">
                        <div class="zi-pdf-wrap">
                            <object data="{{ asset('storage/' . $page->pdf_path) }}" type="application/pdf" class="zi-pdf-embed">
                                <iframe src="{{ asset('storage/' . $page->pdf_path) }}" class="zi-pdf-embed"></iframe>
                            </object>
                            <div class="zi-pdf-footer">
                                <a href="{{ asset('storage/' . $page->pdf_path) }}" target="_blank" class="zi-pdf-name">
                                    <i class="fas fa-file-pdf"></i>
                                    {{ basename($page->pdf_path) }}
                                </a>
                                <a href="{{ asset('storage/' . $page->pdf_path) }}" download class="zi-btn-download">
                                    <i class="fas fa-download"></i> {{ __('messages.btn_unduh') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                @if($page->gambar)
                <div class="mt-2 text-center">
                    @if(in_array(strtolower(pathinfo($page->gambar, PATHINFO_EXTENSION)), ['mp4','mov','avi','mkv','webm']))
                        <div class="zi-media-wrap">
                            <video src="{{ asset('storage/' . $page->gambar) }}" controls preload="metadata"></video>
                        </div>
                    @else
                        <a href="{{ asset('storage/' . $page->gambar) }}" target="_blank" class="zi-media-wrap">
                            <img src="{{ asset('storage/' . $page->gambar) }}" alt="{{ $page->judul }}" loading="lazy">
                        </a>
                    @endif
                </div>
                @endif

                {{-- Media tambahan (foto/video/PDF) --}}
                @if($page->media->count())
                <div class="mt-3">
                    <div class="row g-3">
                        @foreach($page->media as $m)
                        <div class="col-md-6 col-lg-4">
                            @if($m->tipe === 'video')
                                <div class="zi-media-wrap d-block">
                                    <video src="{{ asset('storage/' . $m->file_path) }}" controls preload="metadata" style="width:100%;border-radius:10px;"></video>
                                </div>
                            @elseif($m->tipe === 'pdf')
                                <div class="zi-pdf-wrap">
                                    <object data="{{ asset('storage/' . $m->file_path) }}" type="application/pdf" class="zi-pdf-embed" style="min-height:320px;">
                                        <iframe src="{{ asset('storage/' . $m->file_path) }}" class="zi-pdf-embed" style="min-height:320px;"></iframe>
                                    </object>
                                    <div class="zi-pdf-footer">
                                        <a href="{{ asset('storage/' . $m->file_path) }}" target="_blank" class="zi-pdf-name">
                                            <i class="fas fa-file-pdf"></i> {{ basename($m->file_path) }}
                                        </a>
                                        <a href="{{ asset('storage/' . $m->file_path) }}" download class="zi-btn-download">
                                            <i class="fas fa-download"></i> {{ __('messages.btn_unduh') }}
                                        </a>
                                    </div>
                                </div>
                            @else
                                <a href="{{ asset('storage/' . $m->file_path) }}" target="_blank" class="zi-media-wrap d-block">
                                    <img src="{{ asset('storage/' . $m->file_path) }}" alt="Media" loading="lazy" style="width:100%;border-radius:10px;">
                                </a>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </article>
        @empty
        <div class="zi-empty">
            <i class="fas fa-folder-open d-block"></i>
            <p>{{ __('messages.empty_pembangunan') }}</p>
        </div>
        @endforelse
    </div>
</section>
@endsection
