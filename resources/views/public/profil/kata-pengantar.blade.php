@extends('layouts.public')

@section('title', __('messages.foreword') . __('messages.site_title_suffix'))

@push('styles')
<style>
    .kata-pengantar-section {
        padding: 30px 0 60px;
        position: relative;
        z-index: 1;
        background: #f8f9fa;
    }
    [data-theme="dark"] .kata-pengantar-section { background: var(--bg-color); }
    .pengantar-card {
        background: white;
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    [data-theme="dark"] .pengantar-card { background: var(--card-bg); }
    .pengantar-header {
        background: linear-gradient(135deg, #0d47a1 0%, #1976d2 100%);
        color: white;
        padding: 20px 30px;
        text-align: center;
    }
    .pengantar-header h3 {
        margin: 0;
        font-weight: 700;
        font-size: 1.4rem;
        letter-spacing: 1px;
    }
    .pengantar-body {
        padding: 30px;
    }
    .pengantar-foto {
        width: 180px;
        height: 220px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        flex-shrink: 0;
    }
    .pengantar-text {
        font-size: 1.05rem;
        line-height: 1.9;
        color: #37474f;
        text-align: justify;
    }
    [data-theme="dark"] .pengantar-text { color: #ccc; }
    .pengantar-text p {
        margin-bottom: 1rem;
    }
    @media (max-width: 767.98px) {
        .pengantar-foto {
            width: 140px;
            height: 180px;
            margin: 0 auto 20px;
        }
        .pengantar-body .d-md-flex {
            flex-direction: column;
            text-align: center;
        }
    }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="container">
        <h2>{{ __('messages.foreword') }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profil.index') }}">{{ __('messages.profile') }}</a></li>
                <li class="breadcrumb-item active">{{ __('messages.foreword') }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container kata-pengantar-section">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="pengantar-card">
                <div class="pengantar-header">
                    <h3 translate="yes"><i class="fas fa-quote-left me-2"></i> {{ $title ?: __('messages.foreword') }}</h3>
                </div>
                <div class="pengantar-body">
                    <div class="d-md-flex align-items-start gap-4">
                        @if($image)
                        <div class="text-center flex-shrink-0 mb-3 mb-md-0">
                            <img src="{{ asset('storage/' . $image) }}" alt="{{ __('messages.foreword') }}" class="pengantar-foto">
                        </div>
                        @endif
                        <div class="pengantar-text flex-grow-1" translate="yes">
                            {!! nl2br(e($content)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
@endsection
