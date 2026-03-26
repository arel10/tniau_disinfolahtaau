@extends('layouts.public')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 fw-bold text-center">{{ __('messages.tutorial') }}</h2>
    <div class="row justify-content-center">
        @forelse($tutorials as $tutorial)
            <div class="col-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm border-0 tutorial-card">
                    @if($tutorial->gambar)
                        <a href="{{ $tutorial->link ?? '#' }}" target="_blank">
                            <img src="{{ asset('storage/' . $tutorial->gambar) }}" class="card-img-top img-fluid" alt="{{ $tutorial->judul }}" style="object-fit:contain; height:120px; max-height:120px; width:100%;">
                        </a>
                    @endif
                    <div class="card-body text-center p-2">
                        <h6 class="card-title mb-2" style="font-size:1rem;">{{ $tutorial->judul }}</h6>
                        {{-- Tombol lihat tutorial dihilangkan sesuai permintaan --}}
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted">{{ __('messages.empty_tutorial') }}</div>
        @endforelse
    </div>
</div>
@endsection

@push('styles')
<style>
.tutorial-card img {
    border-radius: 10px 10px 0 0;
    transition: transform 0.2s;
    object-fit: contain !important;
    height: 120px !important;
    max-height: 120px !important;
    width: 100% !important;
    background: #f8f9fa;
}
.tutorial-card img:hover {
    transform: scale(1.04);
}
.tutorial-card .card-body { padding: 0.75rem 0.5rem; }
</style>
@endpush
