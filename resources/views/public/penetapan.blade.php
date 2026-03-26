@extends('layouts.public')

@section('title', __('messages.penetapan') . ' - ' . __('messages.zona_integritas'))

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold mb-3">{{ __('messages.penetapan') }}</h2>
            <p class="text-muted">{{ __('messages.misc_info_penetapan') }}</p>
        </div>
    </div>

    {{-- Section Pengungkit --}}
    <h4 class="fw-bold mb-3">{{ __('messages.heading_pengungkit') }}</h4>
    @if($pengungkit->count())
    <div class="row mb-5">
        @foreach($pengungkit as $item)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm">
                @if($item->foto)
                <img src="{{ asset('storage/' . $item->foto) }}" class="card-img-top" alt="{{ $item->judul }}" style="height: 200px; object-fit: cover;">
                @endif
                <div class="card-body">
                    @if($item->judul)
                        <h5 class="card-title">{{ $item->judul }}</h5>
                    @endif
                    @if(!is_null($item->persen))
                    <div class="mb-2">
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $item->persen }}%;" aria-valuenow="{{ $item->persen }}" aria-valuemin="0" aria-valuemax="100">{{ $item->persen }}%</div>
                        </div>
                    </div>
                    @endif
                    @if($item->konten)
                        <p class="card-text">{{ Str::limit(strip_tags($item->konten), 150) }}</p>
                    @endif
                    <small class="text-muted">{{ $item->created_at->format('d M Y') }}</small>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="alert alert-info text-center mb-5">
        <i class="fas fa-info-circle"></i> {{ __('messages.empty_data_pengungkit') }}
    </div>
    @endif

    {{-- Section Hasil --}}
    <h4 class="fw-bold mb-3">{{ __('messages.heading_hasil') }}</h4>
    @if($hasil->count())
    <div class="row">
        @foreach($hasil as $item)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm">
                @if($item->foto)
                <img src="{{ asset('storage/' . $item->foto) }}" class="card-img-top" alt="{{ $item->judul }}" style="height: 200px; object-fit: cover;">
                @endif
                <div class="card-body">
                    @if($item->judul)
                        <h5 class="card-title">{{ $item->judul }}</h5>
                    @endif
                    @if(!is_null($item->persen))
                    <div class="mb-2">
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $item->persen }}%;" aria-valuenow="{{ $item->persen }}" aria-valuemin="0" aria-valuemax="100">{{ $item->persen }}%</div>
                        </div>
                    </div>
                    @endif
                    @if($item->konten)
                        <p class="card-text">{{ Str::limit(strip_tags($item->konten), 150) }}</p>
                    @endif
                    <small class="text-muted">{{ $item->created_at->format('d M Y') }}</small>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="alert alert-info text-center">
        <i class="fas fa-info-circle"></i> {{ __('messages.empty_data_hasil') }}
    </div>
    @endif
</div>
@endsection
