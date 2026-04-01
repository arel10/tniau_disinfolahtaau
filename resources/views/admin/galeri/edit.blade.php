@extends('layouts.admin')

@section('title', 'Edit Galeri')
@section('page-title', 'Edit Galeri')

@push('styles')
<style>
    .setting-card { border:none; box-shadow:0 2px 12px rgba(0,61,130,0.10); border-radius:12px; }
    .setting-card .card-header { background:linear-gradient(135deg,#001f3f 0%,#003d82 100%); color:white; border-radius:12px 12px 0 0 !important; padding:16px 24px; }
    .setting-card .card-header h6 { color:white !important; }
    .form-label { font-weight:600; color:#003d82; }
    .btn-simpan { background:linear-gradient(135deg,#001f3f 0%,#0066cc 100%); color:white; font-weight:700; padding:10px 36px; border-radius:8px; font-size:1rem; border:none; transition:transform 0.15s, box-shadow 0.15s; }
    .btn-simpan:hover { color:#fff; transform:translateY(-2px); box-shadow:0 6px 20px rgba(0,61,130,0.35); }
    .btn-pilih-file { border-radius:8px; }
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
                    <h4 class="mb-0 fw-bold text-dark">Edit Galeri</h4>
                    <small class="text-muted">Ubah data galeri.</small>
                </div>
                <a href="{{ route('admin.galeri.index') }}" class="ms-auto" style="width:36px;height:36px;border-radius:50%;background:#e9ecef;border:none;color:#555;display:flex;align-items:center;justify-content:center;font-size:1.1rem;text-decoration:none;transition:background 0.2s;flex-shrink:0;" onmouseover="this.style.background='#d0d7e2'" onmouseout="this.style.background='#e9ecef'"><i class="fas fa-times"></i></a>
            </div>
            <div class="card setting-card">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-images me-2"></i>Data Galeri</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.galeri.update', $galeri->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Judul <small class="text-muted fw-normal">(opsional)</small></label>
                            <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul', $galeri->judul) }}" placeholder="Kosongkan jika tidak perlu">
                            @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Kegiatan</label>
                                <input type="date" name="tanggal_kegiatan" class="form-control @error('tanggal_kegiatan') is-invalid @enderror" value="{{ old('tanggal_kegiatan', $galeri->tanggal_kegiatan ? $galeri->tanggal_kegiatan->format('Y-m-d') : '') }}">
                                @error('tanggal_kegiatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kategori Galeri <span class="text-danger">*</span></label>
                                <select name="kategori_galeri" class="form-select @error('kategori_galeri') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($kategoriGaleris as $kat)
                                        <option value="{{ $kat->slug }}" {{ old('kategori_galeri', $galeri->kategori_galeri) == $kat->slug ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                                @error('kategori_galeri')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Foto / Video</label>
                            @if($galeri->gambar)
                                <div class="mb-2">
                                    @if(in_array(pathinfo($galeri->gambar, PATHINFO_EXTENSION), ['mp4','mov','avi','mkv','webm']))
                                        <video src="{{ asset('storage/' . $galeri->gambar) }}" controls class="img-thumbnail rounded" style="max-height:200px;"></video>
                                    @else
                                        <img src="{{ asset('storage/' . $galeri->gambar) }}" alt="{{ $galeri->judul }}" class="img-thumbnail rounded" style="max-height:200px;">
                                    @endif
                                </div>
                            @endif
                            <input type="file" id="gambarInput" name="gambar" class="d-none @error('gambar') is-invalid @enderror" accept="image/*,video/*">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <button type="button" class="btn btn-outline-primary btn-sm btn-pilih-file" onclick="document.getElementById('gambarInput').click()">
                                    <i class="fas fa-image me-1"></i> Pilih Foto / Video
                                </button>
                                <small id="gambarInfo" class="text-muted">Belum ada file dipilih</small>
                            </div>
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah file. Format: JPG, PNG, GIF, WEBP, MP4, MOV, AVI, MKV (Ukuran bebas)</small>
                            @error('gambar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Video dari URL <small class="text-muted fw-normal">(YouTube, dll)</small></label>
                            <input type="url" name="video_url" class="form-control @error('video_url') is-invalid @enderror" value="{{ old('video_url', $galeri->video_url) }}" placeholder="https://www.youtube.com/watch?v=...">
                            <small class="text-muted">Masukkan link YouTube/video. Bisa diisi tanpa upload file.</small>
                            @error('video_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-file-pdf text-danger"></i> Upload PDF</label>
                            @if($galeri->pdf_path)
                                <div class="mb-2">
                                    <a href="{{ asset('storage/' . $galeri->pdf_path) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-file-pdf"></i> Lihat PDF saat ini
                                    </a>
                                    <div class="form-check mt-1">
                                        <input class="form-check-input" type="checkbox" name="hapus_pdf" id="hapus_pdf" value="1">
                                        <label class="form-check-label text-danger" for="hapus_pdf">Hapus PDF</label>
                                    </div>
                                </div>
                            @endif
                            <input type="file" name="pdf" class="form-control @error('pdf') is-invalid @enderror" accept="application/pdf">
                            <small class="text-muted">Upload file PDF baru (opsional). Biarkan kosong jika tidak ingin mengubah.</small>
                            @error('pdf')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.deskripsi') }}</label>
                            <textarea name="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $galeri->deskripsi) }}</textarea>
                            @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
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

@push('scripts')
<script>
    (function () {
        var input = document.getElementById('gambarInput');
        var info = document.getElementById('gambarInfo');
        if (!input || !info) return;

        input.addEventListener('change', function () {
            var file = input.files && input.files.length ? input.files[0] : null;
            info.textContent = file ? file.name : 'Belum ada file dipilih';
        });
    })();
</script>
@endpush


