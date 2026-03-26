@extends('layouts.public')

@section('title', __('messages.sp4n_lapor') . __('messages.site_title_suffix'))

@section('content')
<div class="page-hero">
    <div class="container">
        <h2><i class="fas fa-bullhorn me-2"></i>{{ __('messages.sp4n_lapor') }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item active">{{ __('messages.sp4n_lapor') }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container my-5">
    @if($items->count())
        @foreach($items as $item)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                {{-- Title --}}
                @if($item->judul)
                <h4 class="fw-bold mb-3">{{ $item->judul }}</h4>
                @endif

                {{-- Logo with link --}}
                @if($item->logo_path)
                <div class="mb-3 text-center">
                    @if($item->logo_link)
                    <a href="{{ $item->logo_link }}" target="_blank" rel="noopener">
                    @endif
                        <img src="{{ asset('storage/'.$item->logo_path) }}" alt="Logo"
                             style="max-height:120px; max-width:100%;">
                    @if($item->logo_link)
                    </a>
                    @endif
                </div>
                @endif

                {{-- YouTube embed --}}
                @if($item->embed_url)
                <div class="ratio ratio-16x9 mb-3" style="max-width:720px; margin:0 auto;">
                    <iframe src="{{ $item->embed_url }}" allowfullscreen
                            style="border-radius:10px;"></iframe>
                </div>
                @endif

                {{-- Media gallery (images/videos) --}}
                @if($item->media->whereIn('type', ['image','video'])->count())
                <div class="row g-3 mb-3">
                    @foreach($item->media->whereIn('type', ['image','video']) as $m)
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        @if($m->type === 'image')
                        <img src="{{ asset('storage/'.$m->file_path) }}" alt="{{ $m->original_name }}"
                             class="img-fluid rounded shadow-sm" style="width:100%; height:200px; object-fit:cover;">
                        @else
                        <video src="{{ asset('storage/'.$m->file_path) }}" controls
                               class="rounded shadow-sm" style="width:100%; height:200px; object-fit:cover;"></video>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Description --}}
                @if($item->deskripsi)
                <div class="mb-3">{!! nl2br(e($item->deskripsi)) !!}</div>
                @endif

                {{-- PDF files --}}
                @if($item->media->where('type','pdf')->count())
                <div class="mb-2">
                    <strong><i class="fas fa-file-pdf text-danger me-1"></i> {{ __('messages.label_dokumen_pdf') }}:</strong>
                    <ul class="list-unstyled mt-1 ms-3">
                        @foreach($item->media->where('type','pdf') as $pdf)
                        <li>
                            <a href="{{ asset('storage/'.$pdf->file_path) }}" target="_blank" class="text-decoration-none">
                                <i class="fas fa-download me-1"></i>{{ $pdf->original_name ?: __('messages.btn_unduh') . ' PDF' }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    @else
    <div class="card shadow-sm border-0">
        <div class="card-body text-center py-5">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <p class="text-muted">{{ __('messages.empty_sp4n') }}</p>
            <a href="{{ route('home') }}" class="btn btn-primary mt-2">
                <i class="fas fa-home me-1"></i> {{ __('messages.btn_kembali_beranda') }}
            </a>
        </div>
    </div>
    @endif
</div>
@endsection