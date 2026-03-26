@extends('layouts.admin')

@section('title', __('messages.admin_media_sosial'))
@section('page-title', __('messages.admin_media_sosial'))

@push('styles')
<style>
    .setting-card {
        border: none;
        box-shadow: 0 2px 12px rgba(0,61,130,0.10);
        border-radius: 12px;
    }
    .setting-card .card-header {
        background: linear-gradient(135deg, #001f3f 0%, #003d82 100%);
        color: white;
        border-radius: 12px 12px 0 0 !important;
        padding: 16px 24px;
    }
    .form-label { font-weight: 600; color: #003d82; }
    .btn-simpan {
        background: linear-gradient(135deg, #001f3f 0%, #0066cc 100%);
        color: white;
        font-weight: 700;
        padding: 10px 36px;
        border-radius: 8px;
        font-size: 1rem;
        border: none;
        transition: transform 0.15s, box-shadow 0.15s;
    }
    .btn-simpan:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,61,130,0.35);
    }
    .sosmed-preview-box {
        border: 2px dashed #0066cc;
        border-radius: 8px;
        padding: 8px;
        background: #f0f6ff;
        min-height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .sosmed-preview-box img { max-height: 80px; max-width: 100%; border-radius: 4px; object-fit: contain; }
    .sosmed-logo-thumb {
        height: 50px;
        max-width: 90px;
        object-fit: contain;
        border-radius: 6px;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 4px;
    }
    .sosmed-row { border-bottom: 1px solid #e9ecef; padding: 14px 0; }
    .sosmed-row:last-child { border-bottom: none; }
    .edit-collapse .card { border: none; box-shadow: 0 1px 6px rgba(0,0,0,0.07); border-radius: 8px; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-9 col-lg-11">

            {{-- Page header --}}
            <div class="d-flex align-items-center mb-4 gap-3" style="position:relative;">
                <div style="background:linear-gradient(135deg,#001f3f,#0066cc);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;">
                    <i class="fab fa-facebook text-white fa-lg"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold text-dark">Media Sosial</h4>
                    <small class="text-muted">Kelola logo dan link media sosial yang tampil di footer website.</small>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="ms-auto" title="Kembali ke Dashboard"
                   style="width:36px;height:36px;border-radius:50%;background:#e9ecef;border:none;color:#555;display:flex;align-items:center;justify-content:center;font-size:1.1rem;text-decoration:none;transition:background 0.2s;flex-shrink:0;"
                   onmouseover="this.style.background='#d0d7e2'" onmouseout="this.style.background='#e9ecef'">
                    <i class="fas fa-times"></i>
                </a>
            </div>
 
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- ─── FORM TAMBAH ─── --}}
            <div class="card setting-card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fas fa-plus-circle"></i>
                    <span class="fw-bold">Tambah Media Sosial Baru</span>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.setting.media-sosial.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3 align-items-start">
                            <div class="col-md-3">
                                <label class="form-label">Nama Platform <span class="text-muted fw-normal">(opsional)</span></label>
                                <input type="text" name="nama" class="form-control" placeholder="Contoh: Facebook"
                                       value="{{ old('nama') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Ikon FontAwesome</label>
                                <input type="text" name="icon" class="form-control" placeholder="fab fa-facebook-f"
                                       value="{{ old('icon') }}">
                                <small class="text-muted">Isi ikon ATAU upload logo</small>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Logo / Gambar</label>
                                <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror"
                                       accept="image/*" onchange="previewNewLogo(this)">
                                @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Preview</label>
                                <div class="sosmed-preview-box" id="newLogoPreview">
                                    <span class="text-muted small">{{ __('messages.form_logo') }}</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Link URL</label>
                                <input type="url" name="link" class="form-control @error('link') is-invalid @enderror"
                                       placeholder="https://..."
                                       value="{{ old('link') }}">
                                @error('link') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-simpan" style="padding:10px 32px;">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ─── DAFTAR MEDIA SOSIAL ─── --}}
            <div class="card setting-card mb-5">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fas fa-list"></i>
                    <span class="fw-bold">Daftar Media Sosial ({{ $mediaSosial->count() }})</span>
                </div>
                <div class="card-body p-3">
                    @if($mediaSosial->isEmpty())
                        <p class="text-muted text-center py-4 mb-0">Belum ada media sosial. Tambahkan di atas.</p>
                    @else
                    @foreach($mediaSosial as $item)
                    <div class="sosmed-row">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            {{-- Logo → link --}}
                            @if($item->link)
                                <a href="{{ $item->link }}" target="_blank" title="{{ $item->nama ?? $item->link }}">
                                    @if($item->logo)
                                        <img src="{{ asset($item->logo) }}" alt="{{ $item->nama }}" class="sosmed-logo-thumb">
                                    @elseif($item->icon)
                                        <span style="font-size:2rem;color:#003d82;"><i class="{{ $item->icon }}"></i></span>
                                    @endif
                                </a>
                            @else
                                @if($item->logo)
                                    <img src="{{ asset($item->logo) }}" alt="{{ $item->nama }}" class="sosmed-logo-thumb">
                                @elseif($item->icon)
                                    <span style="font-size:2rem;color:#003d82;"><i class="{{ $item->icon }}"></i></span>
                                @endif
                            @endif

                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $item->nama ?: '—' }}</div>
                                <small class="text-muted">{{ $item->link ?: 'Belum ada link' }}</small>
                            </div>

                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary"
                                        type="button"
                                        style="white-space:nowrap;"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#editSosmed{{ $item->id }}"
                                        title="Edit">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </button>
                                <form action="{{ route('admin.setting.media-sosial.destroy', $item->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus media sosial ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Edit collapse --}}
                        <div class="collapse edit-collapse mt-3" id="editSosmed{{ $item->id }}">
                            <div class="card">
                                <div class="card-body p-3">
                                    <form action="{{ route('admin.setting.media-sosial.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf @method('PUT')
                                        <div class="row g-3 align-items-start">
                                            <div class="col-md-3">
                                                <label class="form-label">Nama Platform</label>
                                                <input type="text" name="nama" class="form-control"
                                                       value="{{ $item->nama }}"
                                                       placeholder="Nama platform">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Ikon FontAwesome</label>
                                                <input type="text" name="icon" class="form-control"
                                                       value="{{ $item->icon }}"
                                                       placeholder="fab fa-facebook-f">
                                                <small class="text-muted">Isi ikon ATAU upload logo</small>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Ganti Logo</label>
                                                <input type="file" name="logo" class="form-control"
                                                       accept="image/*"
                                                       onchange="previewEditLogo(this, {{ $item->id }})">
                                                <small class="text-muted">Kosongkan jika tidak ingin mengganti.</small>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Saat Ini</label>
                                                <div class="sosmed-preview-box" id="editLogoPreview{{ $item->id }}">
                                                    @if($item->logo)
                                                        <img src="{{ asset($item->logo) }}" alt="{{ $item->nama }}" style="max-height:72px;max-width:100%;object-fit:contain;">
                                                    @elseif($item->icon)
                                                        <i class="{{ $item->icon }}" style="font-size:2rem;color:#003d82;"></i>
                                                    @else
                                                        <span class="text-muted small">Belum ada</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Link URL</label>
                                                <input type="url" name="link" class="form-control"
                                                       value="{{ $item->link }}"
                                                       placeholder="https://...">
                                                @if($item->link)
                                                <small class="text-muted d-block mt-1">
                                                    <a href="{{ $item->link }}" target="_blank" class="text-primary text-truncate d-inline-block" style="max-width:180px;vertical-align:bottom;" title="{{ $item->link }}">{{ $item->link }}</a>
                                                </small>
                                                @else
                                                <small class="text-muted">Belum ada link tersimpan.</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-simpan" style="padding:10px 32px;">
                                                    Simpan
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewNewLogo(input) {
        const box = document.getElementById('newLogoPreview');
        if (input.files && input.files[0]) {
            const url = URL.createObjectURL(input.files[0]);
            box.innerHTML = '<img src="' + url + '" style="max-height:72px;max-width:100%;object-fit:contain;">';
        }
    }
    function previewEditLogo(input, id) {
        const box = document.getElementById('editLogoPreview' + id);
        if (input.files && input.files[0]) {
            const url = URL.createObjectURL(input.files[0]);
            box.innerHTML = '<img src="' + url + '" style="max-height:72px;max-width:100%;object-fit:contain;">';
        }
    }
</script>
@endpush
