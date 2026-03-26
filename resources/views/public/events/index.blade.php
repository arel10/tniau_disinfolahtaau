@extends('layouts.public')
@section('title', __('messages.events'))
@section('content')
<div class="page-hero">
    <div class="container">
        <h2><i class="fas fa-calendar-alt me-2"></i>{{ __('messages.hero_events') }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item active">{{ __('messages.events') }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        @forelse($events as $event)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0 rounded-4">
                @if($event->cover_image)
                    <img src="{{ asset('storage/' . $event->cover_image) }}" class="card-img-top rounded-top-4" style="height:180px;object-fit:cover;" alt="{{ $event->localized_nama_kegiatan }}">
                @else
                    <div class="d-flex align-items-center justify-content-center bg-gradient rounded-top-4" style="height:180px;background:linear-gradient(135deg,#001f3f,#003d82);">
                        <i class="fas fa-calendar-alt text-white" style="font-size:2.5rem;opacity:0.3;"></i>
                    </div>
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="fw-bold mb-1">{{ $event->localized_nama_kegiatan }}</h5>
                    @if($event->tanggal_kegiatan)
                        <div class="mb-2 text-muted small"><i class="fas fa-calendar fa-sm me-1"></i>{{ $event->tanggal_kegiatan->format('d M Y') }}</div>
                    @endif
                    <p class="text-muted small mb-2" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">{{ $event->localized_deskripsi }}</p>
                    <a href="{{ route('events.show', $event) }}" class="btn btn-outline-primary mt-auto w-100 rounded-pill">{{ __('messages.btn_lihat_detail') }}</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-calendar-times fa-3x mb-3 text-muted"></i>
            <p class="text-muted">{{ __('messages.empty_event') }}</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
