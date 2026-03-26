@extends('layouts.admin')
@section('title', 'Tambah Perancangan')
@section('page-title', 'Tambah Perancangan')

@push('styles')
<style>
    .setting-card { border:none; box-shadow:0 2px 12px rgba(0,61,130,0.10); border-radius:12px; }
    .setting-card .card-header { background:linear-gradient(135deg,#001f3f 0%,#003d82 100%); color:white; border-radius:12px 12px 0 0 !important; padding:16px 24px; }
    .setting-card .card-header h6 { color:white !important; }
    .form-label { font-weight:600; color:#003d82; }
    .btn-simpan { background:linear-gradient(135deg,#001f3f 0%,#0066cc 100%); color:white; font-weight:700; padding:10px 36px; border-radius:8px; font-size:1rem; border:none; transition:transform 0.15s, box-shadow 0.15s; }
    .btn-simpan:hover { color:#fff; transform:translateY(-2px); box-shadow:0 6px 20px rgba(0,61,130,0.35); }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-7 col-lg-9">
            <div class="d-flex align-items-center mb-4 gap-3">
                <div style="background:linear-gradient(135deg,#001f3f,#0066cc);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-plus text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-0 fw-bold text-dark">Tambah Perancangan</h4>
                    <small class="text-muted">Tambah data Perancangan baru.</small>
                </div>
                <a href="{{ route('admin.zi.perancangan.index') }}" class="ms-auto" style="width:36px;height:36px;border-radius:50%;background:#e9ecef;border:none;color:#555;display:flex;align-items:center;justify-content:center;font-size:1.1rem;text-decoration:none;transition:background 0.2s;flex-shrink:0;" onmouseover="this.style.background='#d0d7e2'" onmouseout="this.style.background='#e9ecef'"><i class="fas fa-times"></i></a>
            </div>
            <div class="card setting-card">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-bullhorn me-2"></i>Data Perancangan</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.zi.perancangan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Judul <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}">
                            @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.konten') }}</label>
                            <textarea name="konten" class="form-control @error('konten') is-invalid @enderror" rows="6">{{ old('konten') }}</textarea>
                            @error('konten') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.form_file_pdf') }}</label>
                            <input type="file" name="pdf" class="form-control @error('pdf') is-invalid @enderror" accept="application/pdf">
                            <small class="text-muted">Format: PDF (Ukuran bebas)</small>
                            @error('pdf') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Foto / Video (bisa pilih banyak)</label>
                            <input type="file" name="photos[]" class="form-control @error('photos.*') is-invalid @enderror" accept="image/*,video/*" multiple onchange="showPhotoCaptions(this)">
                            <small class="text-muted">Format: JPG, PNG, WEBP, MP4, MOV, AVI, MKV (Ukuran bebas)</small>
                            @error('photos.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div id="photo-captions"></div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-simpan">{{ __('messages.simpan') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showPhotoCaptions(input) {
    const container = document.getElementById('photo-captions');
    container.innerHTML = '';
    if (input.files && input.files.length > 0) {
        for (let i = 0; i < input.files.length; i++) {
            const div = document.createElement('div');
            div.className = 'mb-2 mt-2';
            div.innerHTML = `<label class="form-label">Nama File ${i+1}:</label> <input type='text' name='captions[${i}]' class='form-control' placeholder='Nama/caption file'>`;
            container.appendChild(div);
        }
    }
}
</script>
@endsection
