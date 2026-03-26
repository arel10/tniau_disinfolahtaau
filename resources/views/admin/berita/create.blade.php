@extends('layouts.admin')

@section('title', 'Tambah Berita')
@section('page-title', 'Tambah Berita')

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
        <div class="col-xl-8 col-lg-10">
            <div class="d-flex align-items-center mb-4 gap-3">
                <div style="background:linear-gradient(135deg,#001f3f,#0066cc);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-plus text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-0 fw-bold text-dark">Tambah Berita</h4>
                    <small class="text-muted">Buat berita atau artikel baru.</small>
                </div>
                <a href="{{ route('admin.berita.index') }}" class="ms-auto" style="width:36px;height:36px;border-radius:50%;background:#e9ecef;border:none;color:#555;display:flex;align-items:center;justify-content:center;font-size:1.1rem;text-decoration:none;transition:background 0.2s;flex-shrink:0;" onmouseover="this.style.background='#d0d7e2'" onmouseout="this.style.background='#e9ecef'"><i class="fas fa-times"></i></a>
            </div>
            <div class="card setting-card">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-newspaper me-2"></i>Data Berita</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.berita.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Judul Berita <span class="text-danger">*</span></label>
                            {{-- Tabs for multi-language title --}}
                            <ul class="nav nav-tabs nav-tabs-sm mb-2" role="tablist">
                                <li class="nav-item"><a class="nav-link active py-1 px-3" data-bs-toggle="tab" href="#judul-id"><img src="https://flagcdn.com/16x12/id.png" alt="ID" class="me-1"> ID</a></li>
                                <li class="nav-item"><a class="nav-link py-1 px-3" data-bs-toggle="tab" href="#judul-en"><img src="https://flagcdn.com/16x12/gb.png" alt="EN" class="me-1"> EN</a></li>
                                <li class="nav-item"><a class="nav-link py-1 px-3" data-bs-toggle="tab" href="#judul-ja"><img src="https://flagcdn.com/16x12/jp.png" alt="JA" class="me-1"> JA</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="judul-id">
                                    <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}" required placeholder="Judul dalam Bahasa Indonesia (wajib)">
                                    @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="tab-pane fade" id="judul-en">
                                    <input type="text" name="judul_en" class="form-control" value="{{ old('judul_en') }}" placeholder="Title in English (optional)">
                                </div>
                                <div class="tab-pane fade" id="judul-ja">
                                    <input type="text" name="judul_ja" class="form-control" value="{{ old('judul_ja') }}" placeholder="日本語タイトル (任意)">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" required>
                                    <option value="">{{ __('messages.form_pilih_kategori') }}</option>
                                    @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
                                    @endforeach
                                </select>
                                @error('kategori_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>{{ __('messages.draft') }}</option>
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">{{ __('messages.tanggal') }}</label>
                                <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', date('Y-m-d')) }}">
                                @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.form_gambar_utama') }}</label>
                            <input type="file" name="gambar_utama" class="form-control @error('gambar_utama') is-invalid @enderror" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG, GIF, WEBP (Ukuran bebas)</small>
                            @error('gambar_utama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Foto / Video Tambahan</label>
                            <input type="file" name="gambar_tambahan[]" class="form-control" accept="image/*,video/*" multiple>
                            <small class="text-muted">Bisa pilih banyak foto/video sekaligus. Format: JPG, PNG, GIF, WEBP, MP4, MOV, AVI, MKV (Ukuran bebas)</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Konten Berita <span class="text-danger">*</span></label>
                            {{-- Tabs for multi-language content --}}
                            <ul class="nav nav-tabs nav-tabs-sm mb-2" role="tablist">
                                <li class="nav-item"><a class="nav-link active py-1 px-3" data-bs-toggle="tab" href="#konten-id"><img src="https://flagcdn.com/16x12/id.png" alt="ID" class="me-1"> ID</a></li>
                                <li class="nav-item"><a class="nav-link py-1 px-3" data-bs-toggle="tab" href="#konten-en"><img src="https://flagcdn.com/16x12/gb.png" alt="EN" class="me-1"> EN</a></li>
                                <li class="nav-item"><a class="nav-link py-1 px-3" data-bs-toggle="tab" href="#konten-ja"><img src="https://flagcdn.com/16x12/jp.png" alt="JA" class="me-1"> JA</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="konten-id">
                                    <textarea name="konten" rows="10" class="form-control @error('konten') is-invalid @enderror" required placeholder="Konten dalam Bahasa Indonesia (wajib)">{{ old('konten') }}</textarea>
                                    @error('konten')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="tab-pane fade" id="konten-en">
                                    <textarea name="konten_en" rows="10" class="form-control" placeholder="Content in English (optional)">{{ old('konten_en') }}</textarea>
                                </div>
                                <div class="tab-pane fade" id="konten-ja">
                                    <textarea name="konten_ja" rows="10" class="form-control" placeholder="日本語コンテンツ (任意)">{{ old('konten_ja') }}</textarea>
                                </div>
                            </div>
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
@endsection
