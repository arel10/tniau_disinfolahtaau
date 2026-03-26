@extends('layouts.public')

@section('title', __('messages.public_service_compensation') . __('messages.site_title_suffix'))

@section('content')
<div class="page-hero">
    <div class="container">
        <h2><i class="fas fa-hand-holding-usd me-2"></i>{{ __('messages.public_service_compensation') }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item">{{ __('messages.public_service') }}</li>
                <li class="breadcrumb-item active">{{ __('messages.public_service_compensation') }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container my-5">
    @if($items->count())
        @foreach($items as $item)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                @if($item->judul)
                <h3 class="fw-bold mb-4 text-center">{{ $item->judul }}</h3>
                @endif

                @if($item->logo_path)
                <div class="mb-3 text-center">
                    @if($item->logo_link)<a href="{{ $item->logo_link }}" target="_blank" rel="noopener">@endif
                        <img src="{{ asset('storage/'.$item->logo_path) }}" alt="Logo" style="max-height:220px; max-width:100%;">
                    @if($item->logo_link)</a>@endif
                </div>
                @endif

                @if($item->youtube_embed_url)
                <div class="ratio ratio-16x9 mb-3" style="max-width:100%; margin:0 auto;">
                    <iframe src="{{ $item->youtube_embed_url }}" allowfullscreen style="border-radius:10px;"></iframe>
                </div>
                @endif

                @if($item->media->whereIn('type', ['image','video'])->count())
                @php $mediaItems = $item->media->whereIn('type', ['image','video']); @endphp
                <div class="row g-3 mb-3">
                    @foreach($mediaItems as $m)
                    <div class="{{ $mediaItems->count() > 1 ? 'col-md-6' : 'col-12' }}">
                        @if($m->type === 'image')
                        <img src="{{ asset('storage/'.$m->file_path) }}" alt="{{ $m->original_name }}" class="img-fluid rounded shadow-sm w-100" style="cursor:pointer;" onclick="window.open(this.src,'_blank')">
                        @else
                        <video src="{{ asset('storage/'.$m->file_path) }}" controls class="rounded shadow-sm w-100"></video>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif

                @if($item->deskripsi)
                <div class="mb-3">{!! nl2br(e($item->deskripsi)) !!}</div>
                @endif

                @if($item->media->where('type','pdf')->count())
                @php $pdfItems = $item->media->where('type','pdf'); @endphp
                <div class="row g-3 mb-3">
                    @foreach($pdfItems as $pdf)
                    <div class="{{ $pdfItems->count() > 1 ? 'col-md-6' : 'col-12' }}">
                        <div class="border rounded overflow-hidden shadow-sm bg-white">
                            <iframe src="{{ asset('storage/'.$pdf->file_path) }}" style="width:100%; height:500px; border:none;"></iframe>
                            <div class="bg-light p-2 text-center border-top d-flex align-items-center justify-content-center gap-2 flex-wrap">
                                <small class="text-muted text-truncate" style="max-width:200px;">{{ $pdf->original_name ?: __('messages.label_dokumen_pdf') }}</small>
                                <a href="{{ asset('storage/'.$pdf->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-external-link-alt me-1"></i>{{ __('messages.btn_buka') }}
                                </a>
                                <a href="{{ asset('storage/'.$pdf->file_path) }}" download class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-download me-1"></i>{{ __('messages.btn_unduh') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        @endforeach
    @else
    <div class="card shadow-sm border-0">
        <div class="card-body text-center py-5">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <p class="text-muted">{{ __('messages.empty_kompensasi') }}</p>
            <a href="{{ route('home') }}" class="btn btn-primary mt-2"><i class="fas fa-home me-1"></i> {{ __('messages.btn_kembali_beranda') }}</a>
        </div>
    </div>
    @endif
</div>
@endsection
