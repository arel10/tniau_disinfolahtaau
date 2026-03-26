@extends('layouts.admin')

@section('title', __('messages.admin_hubungi_kami'))
@section('page-title', __('messages.admin_hubungi_kami'))

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
    .hk-row { border-bottom: 1px solid #e9ecef; padding: 14px 0; }
    .hk-row:last-child { border-bottom: none; }
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
                    <i class="fas fa-phone text-white fa-lg"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold text-dark">Hubungi Kami</h4>
                    <small class="text-muted">Kelola informasi kontak yang tampil di footer website.</small>
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

            {{-- Tambah Form --}}
            <div class="card setting-card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fas fa-plus-circle"></i>
                    <span class="fw-bold">Tambah Item Baru</span>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.setting.hubungi-kami.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3 align-items-start">
                            <div class="col-md-3">
                                <label class="form-label">Upload Ikon <span class="text-muted fw-normal">(dari perangkat)</span></label>
                                <input type="file" name="icon_image" class="form-control @error('icon_image') is-invalid @enderror"
                                       accept="image/*">
                                @error('icon_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <small class="text-muted">Atau isi kolom FontAwesome di bawah.</small>
                                <input type="text" name="icon" class="form-control mt-2 @error('icon') is-invalid @enderror"
                                       placeholder="fas fa-phone" value="{{ old('icon') }}">
                                <small class="text-muted">Ikon FontAwesome (jika tanpa upload)</small>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Teks / Keterangan <span class="text-danger">*</span></label>
                                <input type="text" name="teks" class="form-control @error('teks') is-invalid @enderror"
                                       value="{{ old('teks') }}">
                                @error('teks') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Link <span class="text-muted fw-normal">(opsional)</span></label>
                                <input type="text" name="link" class="form-control @error('link') is-invalid @enderror"
                                       value="{{ old('link') }}">
                                @error('link') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <small class="text-muted">Isi jika teks bisa diklik (alamat maps, email, dll).</small>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-simpan">{{ __('messages.simpan') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Daftar --}}
            <div class="card setting-card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fas fa-list"></i>
                    <span class="fw-bold">Daftar Hubungi Kami ({{ $items->count() }})</span>
                </div>
                <div class="card-body p-0">
                    @forelse($items as $item)
                    <div class="hk-row px-4">
                        {{-- Item preview row --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width:40px;height:40px;border-radius:50%;background:#003d82;display:flex;align-items:center;justify-content:center;color:#ffc107;font-size:1.1rem;flex-shrink:0;overflow:hidden;">
                                    @if($item->icon_image)
                                        <img src="{{ asset('storage/'.$item->icon_image) }}" alt="" style="width:26px;height:26px;object-fit:contain;filter:brightness(0) invert(1) sepia(1) saturate(5) hue-rotate(5deg);">
                                    @else
                                        <i class="{{ $item->icon }}"></i>
                                    @endif
                                </div>
                                <div>
                                    @if($item->link)
                                        <a href="{{ $item->link }}" target="_blank" class="text-dark text-decoration-none fw-semibold">{{ $item->teks }}</a>
                                    @else
                                        <span class="fw-semibold text-dark">{{ $item->teks }}</span>
                                    @endif
                                    <small class="d-block text-muted">{{ $item->icon_image ? 'Ikon gambar' : $item->icon }}</small>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary"
                                        type="button"
                                        style="white-space:nowrap;"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#editHk{{ $item->id }}">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </button>
                                <form action="{{ route('admin.setting.hubungi-kami.destroy', $item) }}" method="POST"
                                      onsubmit="return confirm('Hapus item ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Edit collapse --}}
                        <div class="collapse edit-collapse mt-3" id="editHk{{ $item->id }}">
                            <div class="card">
                                <div class="card-body p-3">
                                    <form action="{{ route('admin.setting.hubungi-kami.update', $item) }}" method="POST" enctype="multipart/form-data">
                                        @csrf @method('PUT')
                                        <div class="row g-3 align-items-start">
                                            <div class="col-md-3">
                                                <label class="form-label">Upload Ikon <span class="text-muted fw-normal">(dari perangkat)</span></label>
                                                <input type="file" name="icon_image" class="form-control" accept="image/*">
                                                @if($item->icon_image)
                                                    <div class="mt-1 d-flex align-items-center gap-2">
                                                        <img src="{{ asset('storage/'.$item->icon_image) }}" alt="" style="height:28px;width:28px;object-fit:contain;background:#003d82;border-radius:50%;padding:3px;">
                                                        <small class="text-muted">Ikon saat ini</small>
                                                    </div>
                                                @endif
                                                <small class="text-muted">Atau isi kolom FontAwesome di bawah.</small>
                                                <input type="text" name="icon" class="form-control mt-2"
                                                       value="{{ $item->icon }}"
                                                       placeholder="fas fa-phone">
                                                <small class="text-muted">Ikon FontAwesome (jika tanpa upload)</small>
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-label">Teks / Keterangan</label>
                                                <input type="text" name="teks" class="form-control"
                                                       value="{{ $item->teks }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Link <span class="text-muted fw-normal">(opsional)</span></label>
                                                <input type="text" name="link" class="form-control"
                                                       value="{{ $item->link }}">
                                                <small class="text-muted">Isi jika teks bisa diklik (alamat maps, email, dll).</small>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-simpan">{{ __('messages.simpan') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-phone fa-2x mb-3 d-block" style="opacity:0.3;"></i>
                        {{ __('messages.belum_ada_data') }} Tambahkan item di atas.
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
