@extends('layouts.admin')
@section('title', 'Edit Pemantauan')
@section('page-title', 'Edit Pemantauan')

@push('styles')
<style>
    .setting-card { border:none; box-shadow:0 2px 12px rgba(0,61,130,0.10); border-radius:12px; }
    .setting-card .card-header { background:linear-gradient(135deg,#001f3f 0%,#003d82 100%); color:white; border-radius:12px 12px 0 0 !important; padding:16px 24px; }
    .setting-card .card-header h6 { color:white !important; }
    .btn-simpan { background:linear-gradient(135deg,#001f3f 0%,#0066cc 100%); color:white; font-weight:600; border:none; border-radius:8px; padding:10px 32px; transition:transform 0.15s, box-shadow 0.15s; }
    .btn-simpan:hover { color:#fff; transform:translateY(-2px); box-shadow:0 6px 20px rgba(0,61,130,0.35); }
    .form-control:focus, .form-select:focus { border-color:#003d82; box-shadow:0 0 0 0.2rem rgba(0,61,130,0.15); }
    #preview-container { max-width:220px; }
    #preview-container img { border-radius:10px; border:2px solid #003d82; }
    .current-img { border-radius:10px; border:2px solid #003d82; max-width:220px; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">

            <div class="d-flex align-items-center mb-4 gap-3">
                <div style="background:linear-gradient(135deg,#001f3f,#0066cc);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-edit text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-0 fw-bold text-dark">Edit Pemantauan</h4>
                    <small class="text-muted">Ubah data item pemantauan Zona Integritas.</small>
                </div>
                <a href="{{ route('admin.zi.pemantauan.index') }}" title="Kembali"
                   style="width:36px;height:36px;border-radius:50%;background:#e9ecef;border:none;color:#555;display:flex;align-items:center;justify-content:center;font-size:1.1rem;text-decoration:none;transition:background 0.2s;flex-shrink:0;"
                   onmouseover="this.style.background='#d0d7e2'" onmouseout="this.style.background='#e9ecef'">
                    <i class="fas fa-times"></i>
                </a>
            </div>

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:10px; border-left:4px solid #dc3545;">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="card setting-card">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-edit me-2"></i>Form Edit Pemantauan</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.zi.pemantauan.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Gambar / Video Utama</label>
                            @if($item->gambar)
                                <div class="mb-2">
                                    @if(in_array(strtolower(pathinfo($item->gambar, PATHINFO_EXTENSION)), ['mp4','mov','avi','mkv','webm']))
                                        <video src="{{ asset('storage/' . $item->gambar) }}" controls style="max-height:180px;border-radius:6px;" class="d-block mb-1"></video>
                                    @else
                                        <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}" style="max-height:180px;border-radius:6px;" class="d-block mb-1">
                                    @endif
                                    <label class="form-check-label mt-1">
                                        <input type="checkbox" name="hapus_gambar" value="1" class="form-check-input"> Hapus file ini
                                    </label>
                                </div>
                            @endif
                            <input type="file" name="gambar" class="form-control @error('gambar') is-invalid @enderror" accept="image/*,video/*">
                            <small class="text-muted">Format: JPG, PNG, GIF, WEBP, MP4, MOV, AVI (Ukuran bebas)</small>
                            @error('gambar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('messages.judul') }}</label>
                            <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul', $item->judul) }}" placeholder="Masukkan judul">
                            @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('messages.admin_keterangan') }}</label>
                            <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="5" placeholder="Masukkan keterangan (opsional)">{{ old('keterangan', $item->konten) }}</textarea>
                            @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('messages.form_file_pdf') }}</label>
                            @if($item->pdf_path)
                                <div class="mb-2">
                                    <a href="{{ asset('storage/' . $item->pdf_path) }}" target="_blank" class="badge bg-danger"><i class="fas fa-file-pdf"></i> Lihat PDF</a>
                                    <label class="form-check-label ms-2">
                                        <input type="checkbox" name="hapus_pdf" value="1" class="form-check-input"> Hapus PDF ini
                                    </label>
                                </div>
                            @endif
                            <input type="file" name="pdf" class="form-control @error('pdf') is-invalid @enderror" accept="application/pdf">
                            <small class="text-muted">Format: PDF (Ukuran bebas)</small>
                            @error('pdf') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Foto / Video / PDF Tambahan</label>
                            @if($item->media->count())
                                <div class="row g-2 mb-2">
                                    @foreach($item->media as $media)
                                        <div class="col-auto">
                                            <div class="position-relative" style="display:inline-block;">
                                                @if($media->tipe === 'video')
                                                    <video src="{{ asset('storage/' . $media->file_path) }}" style="height:80px;border-radius:6px;" muted></video>
                                                    <span class="badge bg-dark position-absolute top-0 start-0 m-1" style="font-size:0.6rem;"><i class="fas fa-video"></i></span>
                                                @elseif($media->tipe === 'pdf')
                                                    <a href="{{ asset('storage/' . $media->file_path) }}" target="_blank" class="d-flex align-items-center justify-content-center bg-light border" style="height:80px;width:70px;border-radius:6px;text-decoration:none;">
                                                        <i class="fas fa-file-pdf text-danger fa-2x"></i>
                                                    </a>
                                                @else
                                                    <img src="{{ asset('storage/' . $media->file_path) }}" style="height:80px;border-radius:6px;">
                                                @endif
                                                <label class="position-absolute top-0 end-0 m-1" style="cursor:pointer;">
                                                    <input type="checkbox" name="hapus_media[]" value="{{ $media->id }}" class="form-check-input" style="width:16px;height:16px;">
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <small class="text-muted d-block mb-1"><i class="fas fa-info-circle"></i> Centang untuk menghapus file.</small>
                            @endif
                            <input type="file" name="media_files[]" class="form-control @error('media_files.*') is-invalid @enderror" accept="image/*,video/*,application/pdf" multiple>
                            <small class="text-muted">Bisa upload banyak file sekaligus — foto, video, PDF (Ukuran bebas)</small>
                            @error('media_files.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-simpan"><i class="fas fa-save me-1"></i> Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
