@extends('layouts.public')

@section('title', __('messages.whistle_blowing') . __('messages.site_title_suffix'))

@section('content')
<!-- Page Hero -->
<section class="page-hero">
    <div class="container">
        <h2>{{ __('messages.hero_wbs') }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('messages.hero_wbs') }}</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Content Section -->
<section class="py-5">
    <div class="container">
        @if($setting && $setting->is_active)
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    @if($setting->judul)
                        <h2 class="text-center mb-4 fw-bold text-primary">{{ $setting->judul }}</h2>
                    @endif

                    @if($setting->deskripsi)
                        <!-- Hapus kalimat di atas gambar -->
                    @endif

                    <div class="text-center">
                        <a href="{{ $setting->link_tujuan }}" target="_blank" rel="noopener noreferrer" class="whistle-link d-inline-block">
                            @if(str_starts_with($setting->gambar, 'whistle-blowing/'))
                                <img src="{{ asset('storage/' . $setting->gambar) }}" 
                                     alt="Whistle Blowing System" 
                                     class="img-fluid whistle-image">
                            @else
                                <img src="{{ asset($setting->gambar) }}" 
                                     alt="Whistle Blowing System" 
                                     class="img-fluid whistle-image">
                            @endif
                        </a>
                        <p class="mt-3 text-muted fw-semibold">{{ __('messages.misc_klik_icon') }}</p>
                    </div>

                    <!-- Hapus tombol dan penjelasan -->
                </div>
            </div>
        @else
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-5">
                            <i class="fas fa-tools text-muted mb-3" style="font-size: 4rem;"></i>
                            <h3 class="fw-bold mb-3">{{ __('messages.heading_coming_soon') }}</h3>
                            <p class="text-muted mb-0">{{ __('messages.misc_wbs_developing') }}</p>
                            <p class="mt-3 text-muted fw-semibold">{{ __('messages.misc_klik_icon') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<style>
    .whistle-link {
        display: inline-block;
        border-radius: 50%;
        overflow: hidden;
        box-shadow: 0 0 0 6px #e9ecef, 0 10px 30px rgba(0, 61, 130, 0.10);
        background: #fff;
        padding: 0;
        transition: box-shadow 0.3s, transform 0.3s;
    }
    [data-theme="dark"] .whistle-link { background: #1e293b; box-shadow: 0 0 0 6px #2a2a4a, 0 10px 30px rgba(0,0,0,0.3); }
    .whistle-link:hover {
        transform: scale(1.04);
        box-shadow: 0 0 0 8px #cce0ff, 0 20px 50px rgba(0, 61, 130, 0.18);
    }
    [data-theme="dark"] .whistle-link:hover { box-shadow: 0 0 0 8px #2a2a4a, 0 20px 50px rgba(0,0,0,0.4); }
    .whistle-image {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        object-fit: cover;
        background: none;
        display: block;
        margin: 0 auto;
        box-shadow: none;
    }
    @media (max-width: 768px) {
        .whistle-image {
            width: 120px;
            height: 120px;
        }
    }

    .whistle-image {
        width: 166px;
        height: 166px;
        border-radius: 50%;
        display: block;
        margin: 0 auto;
        object-fit: cover;
        background: none;
        box-shadow: none;
    }
    @media (max-width: 768px) {
        .whistle-image {
            width: 110px;
            height: 110px;
        }
    }

    .btn-primary {
        background: linear-gradient(135deg, #003d82 0%, #0066cc 100%);
        border: none;
        padding: 12px 40px;
        font-weight: 600;
        box-shadow: 0 5px 15px rgba(0, 61, 130, 0.3);
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 61, 130, 0.4);
    }

    @media (max-width: 768px) {
        .whistle-image {
            max-height: 400px;
        }
    }
</style>
@endsection
