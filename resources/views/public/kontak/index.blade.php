@extends('layouts.public')

@section('title', __('messages.contact') . __('messages.site_title_suffix'))

@push('styles')
<style>
    .kontak-section {
        padding: 30px 0 60px;
        position: relative;
        z-index: 1;
        background: #f8f9fa;
    }
    [data-theme="dark"] .kontak-section { background: var(--bg-color); }
    .kontak-section .card {
        height: auto !important;
    }
    .kontak-section .card:hover {
        transform: none !important;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
    }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="container">
        <h2><i class="fas fa-envelope me-2"></i>{{ __('messages.hero_kontak') }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item active">{{ __('messages.contact') }}</li>
            </ol>
        </nav>
    </div>
</div>

<section class="kontak-section">
<div class="container">
    <div class="row">
        <div class="col-lg-8">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="card">
                <div class="card-body p-4">
                    <h4 class="mb-4">{{ __('messages.heading_kirim_pesan') }}</h4>
                    <form method="POST" action="{{ route('kontak.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.label_nama_lengkap') }} <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.label_email') }} <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.label_subjek') }} <span class="text-danger">*</span></label>
                            <input type="text" name="subjek" class="form-control @error('subjek') is-invalid @enderror" value="{{ old('subjek') }}" required>
                            @error('subjek')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.label_pesan') }} <span class="text-danger">*</span></label>
                            <textarea name="pesan" rows="6" class="form-control @error('pesan') is-invalid @enderror" required>{{ old('pesan') }}</textarea>
                            @error('pesan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> {{ __('messages.btn_kirim_pesan') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-map-marker-alt"></i> {{ __('messages.heading_alamat') }}</h5>
                    <p class="card-text">
                        TNI Angkatan Udara<br>
                        Jakarta, Indonesia
                    </p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-phone"></i> {{ __('messages.heading_telepon') }}</h5>
                    <p class="card-text">+62-21-XXXXXXXX</p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-envelope"></i> {{ __('messages.heading_email') }}</h5>
                    <p class="card-text">infonet@tni.au.mil.id</p>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-clock"></i> {{ __('messages.heading_jam_operasional') }}</h5>
                    <p class="card-text">
                        {{ __('messages.label_senin_jumat') }}<br>
                        {{ __('messages.label_jam_kerja') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
@endsection
