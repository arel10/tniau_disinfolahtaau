@extends('layouts.admin')
@section('title', 'Edit Event')
@section('page-title', 'Edit Event')

@push('styles')
<style>
    .setting-card { border:none; box-shadow:0 2px 12px rgba(0,61,130,0.10); border-radius:12px; }
    .setting-card .card-header { background:linear-gradient(135deg,#001f3f 0%,#003d82 100%); color:white; border-radius:12px 12px 0 0 !important; padding:16px 24px; }
    .setting-card .card-header h6 { color:white !important; }
    .btn-tambah { background:linear-gradient(135deg,#001f3f 0%,#0066cc 100%); color:white; font-weight:600; border:none; border-radius:8px; transition:transform 0.15s, box-shadow 0.15s; }
    .btn-tambah:hover { color:#fff; transform:translateY(-2px); box-shadow:0 6px 20px rgba(0,61,130,0.35); }
    .cover-preview { max-height:200px; border-radius:10px; object-fit:cover; }
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
                    <h4 class="mb-0 fw-bold text-dark">Edit Event</h4>
                    <small class="text-muted">Ubah informasi event &ldquo;{{ $event->nama_kegiatan }}&rdquo;</small>
                </div>
                <a href="{{ route('admin.events.show', $event) }}" title="Kembali"
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

            <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card setting-card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2"></i>Informasi Event</h6>
                    </div>
                    <div class="card-body p-4">
                        {{-- Nama Kegiatan --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Nama Kegiatan <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kegiatan" class="form-control form-control-lg @error('nama_kegiatan') is-invalid @enderror"
                                   value="{{ old('nama_kegiatan', $event->nama_kegiatan) }}" required
                                   style="border-radius:10px; border:2px solid #e0e0e0; padding:12px 16px;">
                            @error('nama_kegiatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tanggal Kegiatan --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Tanggal Kegiatan <span class="text-muted fw-normal">(opsional)</span></label>
                            <input type="date" name="tanggal_kegiatan" class="form-control @error('tanggal_kegiatan') is-invalid @enderror"
                                   value="{{ old('tanggal_kegiatan', $event->tanggal_kegiatan?->format('Y-m-d')) }}"
                                   style="border-radius:10px; border:2px solid #e0e0e0; padding:10px 16px; max-width:300px;">
                            @error('tanggal_kegiatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Deskripsi Singkat <span class="text-muted fw-normal">(opsional)</span></label>
                            <textarea name="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror"
                                      style="border-radius:10px; border:2px solid #e0e0e0; padding:12px 16px;">{{ old('deskripsi', $event->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Cover Image --}}
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Cover Image <span class="text-muted fw-normal">(opsional, maks 2MB)</span></label>
                            @if($event->cover_image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $event->cover_image) }}" class="cover-preview w-100" alt="Cover saat ini">
                            </div>
                            <small class="text-muted">Upload gambar baru untuk mengganti cover.</small>
                            @endif
                            <input type="file" name="cover_image" id="coverInput" class="form-control mt-2 @error('cover_image') is-invalid @enderror"
                                   accept="image/*" style="border-radius:10px; border:2px solid #e0e0e0;">
                            @error('cover_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <img id="coverPreview" class="cover-preview mt-3 w-100" alt="Preview" style="display:none;">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-tambah px-4 py-2">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('coverInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('coverPreview');
    if (file) {
        const reader = new FileReader();
        reader.onload = (ev) => { preview.src = ev.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
});
</script>
@endpush
