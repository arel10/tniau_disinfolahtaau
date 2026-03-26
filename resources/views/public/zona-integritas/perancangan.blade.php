@extends('layouts.public')

@section('title', __('messages.perancangan') . ' - ' . __('messages.zona_integritas'))

@section('hero')
<div class="page-hero">
    <div class="container-fluid px-3">
        <h2><i class="fas fa-bullhorn me-2"></i>{{ __('messages.hero_perancangan') }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('zona.index') }}">{{ __('messages.zona_integritas') }}</a></li>
                <li class="breadcrumb-item active">{{ __('messages.perancangan') }}</li>
            </ol>
        </nav>
    </div>
</div>
@endsection

@section('content')
@push('styles')
<style>
/* ================================================================
   Perancangan - Modern Layout
================================================================ */
.perancangan-section {
    padding: 50px 0 70px;
    background: #f4f6f9;
}
[data-theme="dark"] .perancangan-section { background: #111827; }

/* --- Post Card --- */
.pc-post {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 20px rgba(0,31,63,0.07);
    margin-bottom: 48px;
    overflow: hidden;
    border: 1px solid rgba(0,61,130,0.06);
    transition: box-shadow 0.3s;
}
.pc-post:hover { box-shadow: 0 8px 32px rgba(0,31,63,0.12); }
[data-theme="dark"] .pc-post { background: #1e293b; border-color: rgba(255,255,255,0.06); }

/* Post header accent bar */
.pc-post-header {
    padding: 28px 32px 0;
    position: relative;
}
.pc-post-header::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, #001f3f, #0066cc, #00a8ff);
    border-radius: 16px 16px 0 0;
}
.pc-post-header h3 {
    font-size: 1.5rem;
    font-weight: 800;
    color: #1a1a2e;
    margin: 0;
    line-height: 1.4;
}
[data-theme="dark"] .pc-post-header h3 { color: #f1f5f9; }

.pc-post-date {
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
[data-theme="dark"] .pc-post-date { background: #334155; color: #94a3b8; }

.pc-post-body { padding: 24px 32px 32px; }

/* --- Two-column: Text + PDF side by side --- */
.pc-row {
    display: flex;
    gap: 28px;
    margin-bottom: 24px;
}
.pc-col-text { flex: 1; min-width: 0; }
.pc-col-pdf { flex: 1; min-width: 0; }

@media (max-width: 991.98px) {
    .pc-row { flex-direction: column; gap: 20px; }
    .pc-post-header { padding: 24px 20px 0; }
    .pc-post-body { padding: 20px 20px 24px; }
}

/* Content text */
.pc-content {
    font-size: 0.98rem;
    line-height: 1.85;
    color: #334155;
}
[data-theme="dark"] .pc-content { color: #cbd5e1; }

/* --- PDF Viewer --- */
.pc-pdf-wrap {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
}
[data-theme="dark"] .pc-pdf-wrap { background: #0f172a; border-color: #334155; }

.pc-pdf-embed {
    width: 100%;
    min-height: 480px;
    border: none;
    display: block;
}

.pc-pdf-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    padding: 12px 16px;
    background: #f1f5f9;
    border-top: 1px solid #e2e8f0;
}
[data-theme="dark"] .pc-pdf-footer { background: #1e293b; border-color: #334155; }

.pc-pdf-name {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #dc2626;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    max-width: 60%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.pc-pdf-name:hover { color: #b91c1c; }
.pc-pdf-name i { font-size: 1.1rem; }

.pc-btn-download {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg, #c0392b, #e74c3c);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 8px 20px;
    font-size: 0.85rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s;
    box-shadow: 0 2px 8px rgba(192,57,43,0.25);
}
.pc-btn-download:hover {
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(192,57,43,0.4);
}

/* --- Photo / Video Grid --- */
.pc-media-grid {
    display: grid;
    gap: 16px;
    margin-top: 8px;
}
.pc-media-grid.g-1 { grid-template-columns: 1fr; max-width: 600px; }
.pc-media-grid.g-2 { grid-template-columns: repeat(2, 1fr); }
.pc-media-grid.g-3 { grid-template-columns: repeat(3, 1fr); }
.pc-media-grid.g-more { grid-template-columns: repeat(3, 1fr); }

@media (max-width: 991.98px) {
    .pc-media-grid.g-3, .pc-media-grid.g-more { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 575.98px) {
    .pc-media-grid.g-2, .pc-media-grid.g-3, .pc-media-grid.g-more { grid-template-columns: 1fr; }
}

.pc-media-item { text-align: center; }

.pc-media-frame {
    display: block;
    border-radius: 10px;
    overflow: hidden;
    position: relative;
    background: #e2e8f0;
    transition: transform 0.3s, box-shadow 0.3s;
}
.pc-media-frame:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
}

.pc-media-frame img,
.pc-media-frame video {
    width: 100%;
    height: auto;
    display: block;
    max-height: 500px;
    object-fit: contain;
    background: #f8fafc;
}

.pc-media-caption {
    font-size: 0.82rem;
    color: #64748b;
    margin-top: 8px;
    font-weight: 500;
}
[data-theme="dark"] .pc-media-caption { color: #94a3b8; }

/* Empty state */
.pc-empty {
    text-align: center;
    padding: 80px 20px;
}
.pc-empty i { font-size: 3rem; color: #94a3b8; margin-bottom: 16px; }
.pc-empty p { color: #64748b; font-size: 1.05rem; }
</style>
@endpush

<section class=Perancangan-section">
    <div class="container">
        @forelse($posts as $post)
        <article class="pc-post">
            {{-- Header --}}
            <div class="pc-post-header">
                @if($post->judul)
                    <h3>{{ $post->judul }}</h3>
                @endif
                <div class="pc-post-date">
                    <i class="far fa-calendar-alt"></i>
                    {{ $post->created_at->format('d F Y') }}
                </div>
            </div>

            {{-- Body --}}
            <div class="pc-post-body">
                {{-- Text + PDF side by side --}}
                @if($post->konten || $post->pdf_path)
                <div class="pc-row">
                    @if($post->konten)
                    <div class="pc-col-text">
                        <div class="pc-content">{!! $post->konten !!}</div>
                    </div>
                    @endif

                    @if($post->pdf_path)
                    <div class="pc-col-pdf">
                        <div class="pc-pdf-wrap">
                            <object data="{{ asset('storage/' . $post->pdf_path) }}" type="application/pdf" class="pc-pdf-embed">
                                <iframe src="{{ asset('storage/' . $post->pdf_path) }}" class="pc-pdf-embed"></iframe>
                            </object>
                            <div class="pc-pdf-footer">
                                <a href="{{ asset('storage/' . $post->pdf_path) }}" target="_blank" class="pc-pdf-name">
                                    <i class="fas fa-file-pdf"></i>
                                    {{ basename($post->pdf_path) }}
                                </a>
                                <a href="{{ asset('storage/' . $post->pdf_path) }}" download class="pc-btn-download">
                                    <i class="fas fa-download"></i> {{ __('messages.btn_unduh') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                {{-- Photos / Videos --}}
                @php $mediaCount = $post->photos->count(); @endphp
                @if($mediaCount)
                    @php
                        $gridClass = $mediaCount === 1 ? 'g-1' : ($mediaCount === 2 ? 'g-2' : ($mediaCount === 3 ? 'g-3' : 'g-more'));
                    @endphp
                    <div class="pc-media-grid {{ $gridClass }}">
                        @foreach($post->photos as $photo)
                        <div class="pc-media-item">
                            @if(in_array(strtolower(pathinfo($photo->path, PATHINFO_EXTENSION)), ['mp4','mov','avi','mkv','webm']))
                                <div class="pc-media-frame">
                                    <video src="{{ asset('storage/' . $photo->path) }}" controls preload="metadata"></video>
                                </div>
                            @else
                                <a href="{{ asset('storage/' . $photo->path) }}" target="_blank" class="pc-media-frame">
                                    <img src="{{ asset('storage/' . $photo->path) }}" alt="{{ $photo->caption }}" loading="lazy">
                                </a>
                            @endif
                            @if($photo->caption)
                                <div class="pc-media-caption">{{ $photo->caption }}</div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </article>
        @empty
        <div class="pc-empty">
            <i class="fas fa-folder-open d-block"></i>
            <p>{{ __('messages.empty_perancangan') }}</p>
        </div>
        @endforelse
    </div>
</section>
@endsection
