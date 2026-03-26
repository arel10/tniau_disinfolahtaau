@extends('layouts.public')

@section('title', __('messages.vision_mission') . __('messages.site_title_suffix'))

@push('styles')
<style>
    .visi-misi-section {
        padding: 30px 0 60px;
        position: relative;
        z-index: 1;
        background: #f8f9fa;
    }
    [data-theme="dark"] .visi-misi-section { background: var(--bg-color); }
    .visi-misi-section .card {
        height: auto !important;
        transform: none !important;
    }
    .visi-misi-section .card:hover {
        transform: none !important;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1) !important;
    }
    .visi-card {
        background: linear-gradient(135deg, #0d47a1 0%, #1976d2 100%);
        border: none;
        border-radius: 15px;
        color: white;
        box-shadow: 0 5px 20px rgba(13, 71, 161, 0.3);
        position: relative;
        z-index: 1;
        height: auto !important;
    }
    .visi-icon {
        width: 60px;
        height: 60px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
    }
    .visi-icon i {
        font-size: 28px;
        color: white;
    }
    .visi-title {
        font-size: 1.3rem;
        font-weight: 700;
        letter-spacing: 2px;
        margin-bottom: 15px;
    }
    .visi-text {
        font-size: 1rem;
        line-height: 1.7;
    }
    
    .misi-card {
        background: white;
        border: none;
        border-radius: 15px;
        height: auto !important;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        position: relative;
        z-index: 1;
        margin-bottom: 30px;
    }
    [data-theme="dark"] .misi-card { background: var(--card-bg); }
    .misi-header {
        background: linear-gradient(135deg, #1565c0 0%, #1976d2 100%);
        color: white;
        padding: 15px 20px;
        border-radius: 15px 15px 0 0;
    }
    .misi-header h3 {
        margin: 0;
        font-weight: 700;
        font-size: 1.3rem;
        letter-spacing: 2px;
    }
    .misi-item {
        display: flex;
        align-items: center;
        padding: 15px;
        border-bottom: 1px solid #eee;
        background: white;
    }
    [data-theme="dark"] .misi-item { background: var(--card-bg); border-bottom-color: #2a2a4a; }
    .misi-item:last-child {
        border-bottom: none;
        border-radius: 0 0 15px 15px;
    }
    .misi-item:hover {
        background: #f8f9fa;
    }
    [data-theme="dark"] .misi-item:hover { background: #1e293b; }
    .misi-number {
        width: 35px;
        height: 35px;
        min-width: 35px;
        background: linear-gradient(135deg, #0d47a1 0%, #1976d2 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        margin-right: 15px;
    }
    .misi-text-item {
        font-size: 0.95rem;
        color: #37474f;
        line-height: 1.5;
    }
    [data-theme="dark"] .misi-text-item { color: #ccc; }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="container">
        <h2><i class="fas fa-eye me-2"></i>{{ __('messages.hero_visi_misi') }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profil.index') }}">{{ __('messages.profile') }}</a></li>
                <li class="breadcrumb-item active">{{ __('messages.vision_mission') }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container visi-misi-section">

    <div class="row justify-content-center g-4">
        <div class="col-lg-8">
            <!-- VISI -->
            <div class="card visi-card mb-4">
                <div class="card-body p-4 text-center">
                    <div class="visi-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3 class="visi-title">{{ __('messages.heading_visi') }}</h3>
                    <p class="visi-text mb-0">{{ $visi }}</p>
                </div>
            </div>

            <!-- MISI -->
            <div class="card misi-card">
                <div class="misi-header text-center">
                    <h3><i class="fas fa-bullseye me-2"></i> {{ __('messages.heading_misi') }}</h3>
                </div>
                <div class="card-body p-0">
                    @foreach($misi as $index => $item)
                    <div class="misi-item">
                        <div class="misi-number">{{ $index + 1 }}</div>
                        <div class="misi-text-item">{{ $item }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
@endsection
