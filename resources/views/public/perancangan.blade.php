@extends('layouts.public')

@section('title', __('messages.perancangan') . ' - ' . __('messages.zona_integritas'))

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold mb-3">{{ __('messages.perancangan') }}</h2>
            <p class="text-muted">{{ __('messages.misc_info_perancangan') }}</p>
        </div>
    </div>

    @if($posts->count())
        @foreach($posts as $post)
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                @if($post->judul)
                    <h4 class="card-title fw-bold mb-3">{{ $post->judul }}</h4>
                @endif

                @if($post->konten)
                    <div class="mb-3">{!! $post->konten !!}</div>
                @endif

                @if($post->pdf_path)
                <div class="mb-3">
                    <a href="{{ asset('storage/' . $post->pdf_path) }}" target="_blank" class="btn btn-outline-danger">
                        <i class="fas fa-file-pdf me-1"></i> {{ $post->pdf_label ?: __('messages.btn_lihat_pdf') }}
                    </a>
                </div>
                @endif

                @if($post->photos->count())
                <div class="row g-2 mt-2">
                    @foreach($post->photos as $photo)
                    <div class="col-6 col-md-4 col-lg-3">
                        <a href="{{ asset('storage/' . $photo->path) }}" target="_blank">
                            <img src="{{ asset('storage/' . $photo->path) }}" class="img-fluid rounded" alt="{{ $photo->caption }}" style="height: 180px; width: 100%; object-fit: cover;">
                        </a>
                        @if($photo->caption)
                            <small class="text-muted d-block text-center mt-1">{{ $photo->caption }}</small>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif

                <small class="text-muted d-block mt-3">{{ $post->created_at->format('d M Y') }}</small>
            </div>
        </div>
        @endforeach
    @else
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> {{ __('messages.empty_perancangan') }}
        </div>
    @endif
</div>
@endsection
