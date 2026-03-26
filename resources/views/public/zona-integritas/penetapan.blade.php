@extends('layouts.public')

@section('title', __('messages.penetapan') . ' - ' . __('messages.zona_integritas'))

@section('hero')
<div class="page-hero">
    <div class="container-fluid px-3">
        <h2>{{ __('messages.hero_penetapan') }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('zona.index') }}">{{ __('messages.zona_integritas') }}</a></li>
                <li class="breadcrumb-item active">{{ __('messages.penetapan') }}</li>
            </ol>
        </nav>
    </div>
</div>
@endsection

@section('content')
@push('styles')
<style>
    .penetapan-page { padding: 50px 0 60px; background: #f5f7fa; }

    /* Judul utama halaman */
    .penetapan-main-title {
        text-align: center;
        font-size: 1.6rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #1a2942;
        margin-bottom: 50px;
        position: relative;
        padding-bottom: 16px;
    }
    .penetapan-main-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #003d82, #0077cc);
        border-radius: 2px;
    }

    /* Kategori section */
    .kategori-section { margin-bottom: 50px; }

    .kategori-header {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 28px;
        padding: 14px 24px;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    }
    .kategori-header .kategori-badge {
        font-size: 1.1rem;
        font-weight: 800;
        color: #fff;
        padding: 8px 20px;
        border-radius: 30px;
        white-space: nowrap;
    }
    .kategori-header h3 {
        font-weight: 700;
        font-size: 1.25rem;
        margin: 0;
        color: #1a2942;
    }

    /* Warna dinamis per kategori (selang-seling) */
    .kategori-section:nth-child(odd) .kategori-badge { background: linear-gradient(135deg, #003d82, #0066cc); }
    .kategori-section:nth-child(even) .kategori-badge { background: linear-gradient(135deg, #1e8449, #27ae60); }
    .kategori-section:nth-child(odd) .item-persen { color: #003d82; }
    .kategori-section:nth-child(even) .item-persen { color: #1e8449; }
    .kategori-section:nth-child(odd) .item-title { color: #003d82; }
    .kategori-section:nth-child(even) .item-title { color: #1e8449; }

    /* Grid item - semua sejajar dalam 1 baris */
    .items-grid {
        display: flex;
        gap: 16px;
    }
    .items-grid .penetapan-card {
        flex: 1 1 0;
        min-width: 0;
    }
    /* Jika hanya 2 item, batasi lebar */
    .items-grid.items-few {
        max-width: 600px;
        margin: 0 auto;
    }

    .penetapan-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        padding: 16px 12px 14px;
        text-align: center;
        transition: transform 0.3s, box-shadow 0.3s;
        position: relative;
        overflow: hidden;
    }
    .penetapan-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }
    .kategori-section:nth-child(odd) .penetapan-card::before {
        background: linear-gradient(90deg, #003d82, #0066cc);
    }
    .kategori-section:nth-child(even) .penetapan-card::before {
        background: linear-gradient(90deg, #1e8449, #27ae60);
    }
    .penetapan-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }

    .item-persen {
        font-size: 1.2rem;
        font-weight: 800;
        margin-bottom: 6px;
    }
    .item-icon-wrap {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        margin: 0 auto 8px;
        overflow: hidden;
        border: 3px solid #e8ecf1;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
    }
    .item-icon-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .item-title {
        font-weight: 700;
        font-size: 0.85rem;
        margin-bottom: 6px;
        line-height: 1.3;
    }
    .item-desc {
        font-size: 0.75rem;
        color: #555;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Empty state */
    .empty-kategori {
        text-align: center;
        padding: 40px;
        color: #999;
        font-style: italic;
    }

    /* Uncategorized items (logo / banner) */
    .penetapan-header-images {
        text-align: center;
        margin-bottom: 30px;
    }
    .penetapan-header-images img {
        max-width: 280px;
        max-height: 240px;
        object-fit: contain;
        margin: 0 auto;
    }
    .penetapan-header-images .header-caption {
        font-size: 0.95rem;
        color: #555;
        margin-top: 10px;
        font-weight: 600;
    }

    @media (max-width: 767px) {
        .penetapan-main-title { font-size: 1.2rem; }
        .items-grid { flex-wrap: wrap; }
        .items-grid .penetapan-card { flex: 1 1 calc(50% - 8px); min-width: calc(50% - 8px); }
        .items-grid.items-few { max-width: 100%; }
        .penetapan-card { padding: 12px 8px 10px; }
        .item-persen { font-size: 1rem; }
        .item-icon-wrap { width: 48px; height: 48px; }
        .item-title { font-size: 0.78rem; }
        .item-desc { font-size: 0.7rem; -webkit-line-clamp: 3; }
        .kategori-header { flex-direction: column; text-align: center; gap: 8px; padding: 12px 16px; }
    }
</style>
@endpush

<section class="penetapan-page">
    <div class="container">

        {{-- Foto / Logo header tanpa kategori --}}
        @if($uncategorized->count())
        <div class="penetapan-header-images">
            @foreach($uncategorized as $item)
                @if($item->foto)
                    <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->judul }}" class="d-block mx-auto mb-2">
                @endif
            @endforeach
        </div>
        @endif

        <h2 class="penetapan-main-title">
            {{ __('messages.heading_kondisi_zi') }}
        </h2>

        @forelse($kategoris as $kat)
        @if($kat->items->count())
        <div class="kategori-section">
            {{-- Header per kategori --}}
            <div class="kategori-header">
                <span class="kategori-badge">{{ $kat->total_persen }}%</span>
                <h3>{{ __('messages.label_komponen') }} {{ $kat->nama }}: {{ $kat->total_persen }}%</h3>
            </div>

            <div class="items-grid {{ $kat->items->count() <= 2 ? 'items-few' : '' }}">
                @foreach($kat->items as $item)
                <div class="penetapan-card">
                    <div class="item-persen">{{ $item->persen }}%</div>

                    <div class="item-icon-wrap">
                        @if($item->foto)
                            <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->judul }}">
                        @else
                            <i class="fas fa-shield-alt fa-2x text-secondary"></i>
                        @endif
                    </div>

                    <div class="item-title">{{ $item->judul }}</div>

                    @if($item->konten)
                    <div class="item-desc">{!! nl2br(e($item->konten)) !!}</div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @empty
        <div class="text-center text-muted py-5">
            <i class="fas fa-folder-open fa-3x mb-3 d-block"></i>
            <p>{{ __('messages.empty_penetapan') }}</p>
        </div>
        @endforelse

    </div>
</section>
@endsection
