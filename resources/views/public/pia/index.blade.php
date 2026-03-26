@extends('layouts.public')

@section('title', __('messages.pia') . __('messages.site_title_suffix'))

@section('hero')
<div class="page-hero">
    <div class="container-fluid px-3">
        <h2>{{ $page->page_title }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item active">{{ $page->page_title }}</li>
            </ol>
        </nav>
    </div>
</div>
@endsection

@section('content')
@push('styles')
<style>
    .pia-section { padding: 60px 0; }
    .pia-history {
        max-width: 800px;
        margin: 0 auto 50px;
        text-align: center;
    }
    .pia-history h3 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #003d82;
        margin-bottom: 16px;
    }
    [data-theme="dark"] .pia-history h3 { color: #7db8ff; }
    .pia-history .history-content {
        font-size: 1rem;
        color: #555;
        line-height: 1.8;
        text-align: justify;
    }
    [data-theme="dark"] .pia-history .history-content { color: #ccc; }
    .logo-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 40px;
        max-width: 700px;
        margin: 0 auto;
    }
    .logo-card {
        text-align: center;
        text-decoration: none;
        display: block;
        transition: transform 0.3s;
    }
    .logo-card:hover {
        transform: translateY(-6px);
    }
    .logo-card img {
        max-width: 180px;
        max-height: 180px;
        object-fit: contain;
        margin: 0 auto 16px;
        display: block;
    }
    .logo-card .logo-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #003d82;
    }
    [data-theme="dark"] .logo-card .logo-title { color: #7db8ff; }
    @media (max-width: 576px) {
        .logo-grid {
            grid-template-columns: 1fr;
            gap: 30px;
        }
    }
</style>
@endpush

<section class="pia-section">
    <div class="container">

        {{-- Sejarah --}}
        @if($page->history_title || $page->history_content)
        <div class="pia-history">
            @if($page->history_title)
                <h3>{{ $page->history_title }}</h3>
            @endif
            @if($page->history_content)
                <div class="history-content">{!! nl2br(e($page->history_content)) !!}</div>
            @endif
        </div>
        @endif

        {{-- Logo Items --}}
        @if($page->logoItems->count())
        <div class="logo-grid">
            @foreach($page->logoItems as $item)
            <a href="{{ $item->link_url }}" class="logo-card" target="_blank" rel="noopener noreferrer">
                <img src="{{ asset('storage/' . $item->logo_path) }}" alt="{{ $item->title }}">
                <div class="logo-title">{{ $item->title }}</div>
            </a>
            @endforeach
        </div>
        @else
        <p class="text-muted text-center">{{ __('messages.empty_logo') }}</p>
        @endif

    </div>
</section>
@endsection
