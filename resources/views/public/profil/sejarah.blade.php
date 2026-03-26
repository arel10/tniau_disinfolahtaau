@extends('layouts.public')

@section('title', __('messages.history') . __('messages.site_title_suffix'))

@push('styles')
<style>
    .sejarah-header {
        background: linear-gradient(135deg, #0d47a1, #1565c0);
        color: white;
        border: none;
        border-radius: 50px;
        padding: 8px 30px;
        font-weight: 800;
        font-size: 1rem;
        display: inline-block;
        letter-spacing: 1px;
        box-shadow: 0 3px 10px rgba(13,71,161,0.3);
    }
    .sejarah-title-box {
        background: linear-gradient(135deg, #ffd600 0%, #ffab00 100%);
        border: none;
        border-radius: 15px;
        padding: 15px 30px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(255,214,0,0.4);
    }
    .sejarah-title-box h1 {
        font-size: 1.8rem;
        font-weight: 900;
        margin: 0;
        letter-spacing: 1px;
        color: #0d47a1;
    }
    .sejarah-title-box p {
        font-size: 0.85rem;
        font-weight: 700;
        margin: 5px 0 0;
        color: #1a237e;
    }
    .timeline-year {
        background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
        color: white;
        border: none;
        border-radius: 50px;
        padding: 10px 28px;
        font-size: 1.2rem;
        font-weight: 800;
        display: inline-block;
        box-shadow: 0 3px 12px rgba(13,71,161,0.3);
    }
    .timeline-year-alt {
        background: linear-gradient(135deg, #ffd600 0%, #ffab00 100%);
        color: #0d47a1;
        box-shadow: 0 3px 12px rgba(255,214,0,0.4);
    }
    .timeline-content-box {
        background: #fff;
        border: 2px solid #e3f2fd;
        border-radius: 12px;
        padding: 15px;
        text-align: justify;
        line-height: 1.6;
        font-size: 0.85rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        border-left: 4px solid #1565c0;
    }
    [data-theme="dark"] .timeline-content-box { background: #1e293b; border-color: #2a2a4a; color: #ccc; border-left-color: #4da3ff; }
    .timeline-header {
        background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
        color: #ffd600;
        border: none;
        border-radius: 50px;
        padding: 5px 14px;
        font-weight: 700;
        font-size: 0.95rem;
        display: inline-block;
        margin-bottom: 10px;
        letter-spacing: 1px;
    }
    .timeline-header-alt {
        background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
        color: #ffd600;
    }
    .timeline-header-light {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        color: #0d47a1;
    }
    [data-theme="dark"] .timeline-header-light { background: linear-gradient(135deg, #1e293b 0%, #2a3a5a 100%); color: #7db8ff; }
    .timeline-line {
        width: 3px;
        background: linear-gradient(180deg, #1565c0, #ffd600);
        margin: 0 auto;
    }
    .timeline-diamond {
        width: 16px;
        height: 16px;
        background: linear-gradient(135deg, #ffd600, #ffab00);
        border: 2px solid #0d47a1;
        transform: rotate(45deg);
        margin: 0 auto;
        position: relative;
        z-index: 2;
        flex-shrink: 0;
    }
    .logo-center {
        width: 50px;
        height: auto;
    }
    .timeline-wrapper {
        position: relative;
    }
    .timeline-wrapper::before {
        content: '';
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        top: 0;
        bottom: 0;
        width: 3px;
        background: linear-gradient(180deg, #1565c0, #1976d2, #ffd600);
        z-index: 0;
    }
    .timeline-wrapper .row {
        position: relative;
        z-index: 1;
    }
    .timeline-center {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .timeline-entry {
        position: relative;
    }
    .timeline-entry::after {
        content: '';
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        height: 3px;
        background: linear-gradient(90deg, #1565c0, #ffd600);
        z-index: 0;
        left: 20%;
        right: 20%;
    }
    .timeline-entry .col-md-5,
    .timeline-entry .col-md-2 {
        position: relative;
        z-index: 1;
    }
    .timeline-year, .timeline-content-box {
        position: relative;
        z-index: 2;
    }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="container">
        <h2><i class="fas fa-history me-2"></i>{{ __('messages.hero_sejarah') }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profil.index') }}">{{ __('messages.profile') }}</a></li>
                <li class="breadcrumb-item active">{{ __('messages.history') }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container my-3">

    <!-- Header Section -->
    <div class="text-center mb-3">
        <span class="sejarah-header">{{ __('messages.heading_sejarah') }}</span>
    </div>
    <div class="text-center mb-3">
        <div class="sejarah-title-box d-inline-block">
            <h1>{{ __('messages.heading_disinfolahtaau') }}</h1>
            <p>{!! __('messages.label_disinfolahtaau_full') !!}</p>
        </div>
    </div>

    <!-- Logo -->
    <div class="text-center mb-3">
        <img src="{{ asset('assets/image/disinfolahta.png') }}" alt="Logo Disinfolahtaau" class="logo-center">
    </div>

    <!-- Timeline -->
    <div class="timeline-wrapper" translate="yes">
        <div class="row">
            <div class="col-12">
                @forelse($diagrams as $i => $d)
                @php
                    $isOdd = $i % 2 === 0;
                    $yearClass = ($i % 4 >= 2) ? 'timeline-year-alt' : '';
                @endphp

                <div class="row align-items-center py-3 timeline-entry">
                    @if($isOdd)
                        {{-- Content LEFT, Year RIGHT --}}
                        <div class="col-md-5">
                            <div class="timeline-content-box">
                                <span class="timeline-header">{{ $d->title }}</span>
                                <p>{!! nl2br(e($d->description)) !!}</p>
                            </div>
                        </div>
                        <div class="col-md-2 timeline-center">
                            <div class="timeline-diamond"></div>
                        </div>
                        <div class="col-md-5 text-center">
                            <span class="timeline-year {{ $yearClass }}">{{ $d->year }}</span>
                        </div>
                    @else
                        {{-- Year LEFT, Content RIGHT --}}
                        <div class="col-md-5 text-center">
                            <span class="timeline-year {{ $yearClass }}">{{ $d->year }}</span>
                        </div>
                        <div class="col-md-2 timeline-center">
                            <div class="timeline-diamond"></div>
                        </div>
                        <div class="col-md-5">
                            <div class="timeline-content-box">
                                <span class="timeline-header">{{ $d->title }}</span>
                                <p>{!! nl2br(e($d->description)) !!}</p>
                            </div>
                        </div>
                    @endif
                </div>
                @empty
                <p class="text-center text-muted py-5">{{ __('messages.empty_sejarah') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
