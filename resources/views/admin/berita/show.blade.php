@extends('layouts.admin')

@section('title', 'Detail Berita')
@section('page-title', 'Detail Berita')

@push('styles')
<style>
    .setting-card { border:none; box-shadow:0 2px 12px rgba(0,61,130,0.10); border-radius:12px; }
    .setting-card .card-header { background:linear-gradient(135deg,#001f3f 0%,#003d82 100%); color:white; border-radius:12px 12px 0 0 !important; padding:16px 24px; }
    .setting-card .card-header h6 { color:white !important; }
    .detail-label { font-weight:600; color:#003d82; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-9 col-lg-11">
            <div class="d-flex align-items-center mb-4 gap-3">
                <div style="background:linear-gradient(135deg,#001f3f,#0066cc);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-eye text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-0 fw-bold text-dark">Detail Berita</h4>
                    <small class="text-muted">Lihat detail lengkap berita.</small>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.berita.edit', $berita->id) }}" class="btn btn-outline-warning btn-sm"><i class="fas fa-edit me-1"></i>{{ __('messages.edit') }}</a>
                    <a href="{{ route('berita.show', $berita->slug) }}" class="btn btn-outline-info btn-sm" target="_blank"><i class="fas fa-external-link-alt me-1"></i>Website</a>
                    <form action="{{ route('admin.berita.destroy', $berita->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm"><i class="fas fa-trash me-1"></i>{{ __('messages.hapus') }}</button>
                    </form>
                </div>
                <a href="{{ route('admin.berita.index') }}" class="ms-2" style="width:36px;height:36px;border-radius:50%;background:#e9ecef;border:none;color:#555;display:flex;align-items:center;justify-content:center;font-size:1.1rem;text-decoration:none;transition:background 0.2s;flex-shrink:0;" onmouseover="this.style.background='#d0d7e2'" onmouseout="this.style.background='#e9ecef'"><i class="fas fa-times"></i></a>
            </div>

            <div class="card setting-card">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-newspaper me-2"></i>Informasi Berita</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-2 detail-label">Status:</div>
                        <div class="col-md-10">
                            @if($berita->status == 'published')
                                <span class="badge bg-success">Published</span>
                            @else
                                <span class="badge bg-warning">{{ __('messages.draft') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-2 detail-label">Kategori:</div>
                        <div class="col-md-10"><span class="badge" style="background:linear-gradient(135deg,#001f3f,#003d82);">{{ $berita->kategori->nama_kategori }}</span></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-2 detail-label">Penulis:</div>
                        <div class="col-md-10">{{ $berita->user->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-2 detail-label">Tanggal Dibuat:</div>
                        <div class="col-md-10">{{ $berita->created_at->format('d F Y, H:i') }} WIB</div>
                    </div>
                    @if($berita->published_at)
                    <div class="row mb-3">
                        <div class="col-md-2 detail-label">Tanggal Publish:</div>
                        <div class="col-md-10">{{ $berita->published_at->format('d F Y, H:i') }} WIB</div>
                    </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-md-2 detail-label">Views:</div>
                        <div class="col-md-10">{{ $berita->views }}</div>
                    </div>

                    <hr>

                    <h3 class="mb-3 fw-bold" style="color:#001f3f;">{{ $berita->judul }}</h3>

                    @if($berita->gambar_utama)
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $berita->gambar_utama) }}" alt="{{ $berita->judul }}" class="img-fluid rounded">
                    </div>
                    @endif

                    @if($berita->ringkasan)
                    <div class="alert border-0 mb-4" style="background:rgba(0,61,130,0.07);color:#003d82;">
                        <strong><i class="fas fa-align-left me-1"></i> Ringkasan:</strong><br>
                        {{ $berita->ringkasan }}
                    </div>
                    @endif

                    <div class="content">
                        {!! nl2br(e($berita->konten)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
