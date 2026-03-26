@extends('layouts.public')

@section('title', localized_text('Pencarian') . ': ' . e($q) . ' — ' . setting('site_name', 'Disinfolahtaau'))

@push('styles')
<style>
    .search-results-section {
        padding: 30px 0 60px;
        background: #f8f9fa;
        min-height: 60vh;
    }
    [data-theme="dark"] .search-results-section { background: var(--bg-color); }

    .search-bar-top form { display:flex; gap:0; max-width:680px; width:100%; }
    .search-bar-top .form-control {
        border-radius: 30px 0 0 30px;
        border-right: none;
        font-size: 1rem;
        padding: 10px 20px;
        background: #fff;
        border: 2px solid #003d82;
        color: #222;
    }
    [data-theme="dark"] .search-bar-top .form-control { background:#1e2635; color:#e4e8f0; border-color:#2d3a52; }
    .search-bar-top .btn-search {
        border-radius: 0 30px 30px 0;
        background: #003d82;
        color: #fff;
        border: 2px solid #003d82;
        padding: 10px 26px;
        font-size: 1rem;
        font-weight: 600;
        transition: background 0.2s;
    }
    .search-bar-top .btn-search:hover { background: #0055b3; border-color: #0055b3; color:#fff; }

    .search-summary { color: #666; font-size: 0.92rem; margin-bottom: 18px; }
    [data-theme="dark"] .search-summary { color: #a0aec0; }

    .search-card {
        display: flex;
        gap: 16px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.07);
        padding: 18px 20px;
        margin-bottom: 14px;
        transition: box-shadow 0.2s, transform 0.2s;
        text-decoration: none;
        color: inherit;
    }
    [data-theme="dark"] .search-card { background: var(--card-bg, #1e2635); box-shadow: 0 2px 10px rgba(0,0,0,0.25); }
    .search-card:hover { box-shadow: 0 6px 20px rgba(0,61,130,0.13); transform: translateY(-2px); text-decoration: none; color: inherit; }

    .search-card-thumb {
        width: 80px;
        min-width: 80px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
        background: #e9ecef;
    }
    [data-theme="dark"] .search-card-thumb { background: #2d3a52; }
    .search-card-thumb-icon {
        width: 80px; min-width: 80px; height: 60px;
        border-radius: 8px;
        background: linear-gradient(135deg, #003d82 0%, #0066cc 100%);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 1.6rem;
        flex-shrink: 0;
    }

    .search-card-body { flex: 1; min-width: 0; }
    .search-card-type {
        display: inline-block;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        background: #e8f0fe;
        color: #003d82;
        border-radius: 30px;
        padding: 2px 10px;
        margin-bottom: 5px;
    }
    [data-theme="dark"] .search-card-type { background: #1a2a4a; color: #7eb4ff; }
    .search-card-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1a2340;
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    [data-theme="dark"] .search-card-title { color: #e4e8f0; }
    .search-card-excerpt {
        font-size: 0.85rem;
        color: #555;
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
    }
    [data-theme="dark"] .search-card-excerpt { color: #a0aec0; }
    .search-card-date {
        font-size: 0.78rem;
        color: #999;
        margin-top: 4px;
    }

    .no-results-box {
        text-align: center;
        padding: 60px 20px;
        color: #888;
    }
    .no-results-box .no-results-icon { font-size: 4rem; color: #ccc; margin-bottom: 18px; }
    [data-theme="dark"] .no-results-box { color: #a0aec0; }
    [data-theme="dark"] .no-results-box .no-results-icon { color: #3a4a6a; }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="container">
        <h2><i class="fas fa-search me-2"></i>{{ localized_text('Pencarian') }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item active">{{ localized_text('Pencarian') }}</li>
            </ol>
        </nav>
    </div>
</div>

<section class="search-results-section">
    <div class="container">

        {{-- Search Bar --}}
        <div class="search-bar-top mb-4">
            <form action="{{ route('search') }}" method="GET">
                <input type="text" name="q" class="form-control" value="{{ e($q) }}"
                      placeholder="{{ localized_text('Cari berita, menu, dokumen, galeri, event...') }}" autofocus>
                <button type="submit" class="btn btn-search"><i class="fas fa-search"></i></button>
            </form>
        </div>

        {{-- Summary --}}
        @if(mb_strlen($q) >= 2)
            <p class="search-summary">
                @if($total > 0)
                    {{ localized_text('Ditemukan') }} <strong>{{ $total }}</strong> {{ localized_text('hasil untuk kata kunci') }}
                    "<strong>{{ e($q) }}</strong>"
                @else
                    {{ localized_text('Tidak ada hasil untuk kata kunci') }} "<strong>{{ e($q) }}</strong>"
                @endif
            </p>
        @endif

        {{-- Results --}}
        @if($total > 0)
            @foreach($results as $item)
            <a href="{{ $item['url'] }}" class="search-card d-block">
                {{-- Thumbnail --}}
                @if($item['image'])
                    <img src="{{ $item['image'] }}" class="search-card-thumb" alt="" loading="lazy" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                    <div class="search-card-thumb-icon" style="display:none;"><i class="{{ $item['icon'] }}"></i></div>
                @else
                    <div class="search-card-thumb-icon"><i class="{{ $item['icon'] }}"></i></div>
                @endif

                {{-- Body --}}
                <div class="search-card-body">
                    <span class="search-card-type">{{ $item['type'] }}</span>
                    <div class="search-card-title" title="{{ $item['title'] }}">{!! $item['highlighted_title'] !!}</div>
                    @if($item['excerpt'])
                        <div class="search-card-excerpt">{!! $item['highlighted_excerpt'] !!}</div>
                    @endif
                    @if($item['date'])
                        <div class="search-card-date"><i class="fas fa-calendar-alt me-1"></i>{{ $item['date'] }}</div>
                    @endif
                </div>
            </a>
            @endforeach
        @elseif(mb_strlen($q) >= 2)
            <div class="no-results-box">
                <div class="no-results-icon"><i class="fas fa-search-minus"></i></div>
                <h5>{{ localized_text('Tidak ada hasil ditemukan') }}</h5>
                <p class="mb-0">{{ localized_text('Coba kata kunci lain atau periksa ejaan pencarian Anda.') }}</p>
            </div>
        @else
            <div class="no-results-box">
                <div class="no-results-icon"><i class="fas fa-search"></i></div>
                <h5>{{ localized_text('Mulai Pencarian') }}</h5>
                <p class="mb-0">{{ localized_text('Masukkan kata kunci di atas untuk mencari berita, menu, dokumen, galeri, dan sebagainya.') }}</p>
            </div>
        @endif

    </div>
</section>
@endsection
