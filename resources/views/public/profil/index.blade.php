@extends('layouts.public')

@section('title', __('messages.profile') . __('messages.site_title_suffix'))

@section('content')
<div class="page-hero">
    <div class="container">
        <h2><i class="fas fa-info-circle me-2"></i>{{ __('messages.hero_about_us') }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item active">{{ __('messages.profile') }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container my-5">

    <!-- Header Section -->
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary"><i class="fas fa-building me-2"></i>{{ __('messages.heading_tentang_kami') }}</h2>
        <div class="border-bottom border-primary border-3 w-25 mx-auto mt-3"></div>
    </div>

    <!-- Content Section -->
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <!-- Tentang -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-landmark me-2"></i>{{ __('messages.heading_perkembangan') }}</h5>
                </div>
                <div class="card-body p-4">
                    <div class="profil-content" style="text-align: justify; line-height: 1.8;" translate="yes">
                        {!! nl2br(e($sejarah)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profil-content {
    font-size: 1rem;
    color: #333;
}
[data-theme="dark"] .profil-content { color: #ccc; }
</style>
@endsection
