@extends('layouts.admin')
@section('title', $event->nama_kegiatan)
@section('page-title', $event->nama_kegiatan)

@push('styles')
<style>
    .setting-card { border:none; box-shadow:0 2px 12px rgba(0,61,130,0.10); border-radius:12px; }
    .setting-card .card-header { background:linear-gradient(135deg,#001f3f 0%,#003d82 100%); color:white; border-radius:12px 12px 0 0 !important; padding:16px 24px; }
    .setting-card .card-header h6 { color:white !important; }
    .setting-card .card-header.hero-header { background:linear-gradient(135deg,#0d4a1a 0%,#1a8a3e 100%); }
    .setting-card .card-header.video-header { background:linear-gradient(135deg,#4a0e0e 0%,#8b1a1a 100%); }
    .btn-tambah { background:linear-gradient(135deg,#001f3f 0%,#0066cc 100%); color:white; font-weight:600; border:none; border-radius:8px; transition:transform 0.15s, box-shadow 0.15s; }
    .btn-tambah:hover { color:#fff; transform:translateY(-2px); box-shadow:0 6px 20px rgba(0,61,130,0.35); }
    .info-form .form-control { border-radius:10px; border:2px solid #e0e0e0; padding:10px 16px; transition:border-color 0.2s; }
    .info-form .form-control:focus { border-color:#003d82; box-shadow:0 0 0 3px rgba(0,61,130,0.1); }
    .upload-zone { border:2px dashed #c0c8d4; border-radius:16px; padding:24px 20px; text-align:center; cursor:pointer; transition:all 0.3s ease; background:#fafbfd; position:relative; }
    .upload-zone:hover { border-color:#003d82; background:#f0f4fa; }
    .upload-zone.dragover { border-color:#0066cc; background:#e8f0fe; }
    .upload-zone input[type="file"] { position:absolute; inset:0; opacity:0; cursor:pointer; }
    .upload-icon { width:48px; height:48px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; margin-bottom:8px; }
    .upload-icon.hero { background:linear-gradient(135deg,#e8f5e9,#c8e6c9); color:#1a8a3e; }
    .upload-icon.foto { background:linear-gradient(135deg,#e8f0fe,#d4e4fc); color:#003d82; }
    .upload-icon.video { background:linear-gradient(135deg,#fce4ec,#f8bbd0); color:#c62828; }
    .upload-list { list-style:none; padding:0; margin:8px 0 0; }
    .upload-list li { display:flex; align-items:center; gap:8px; padding:5px 10px; background:#f8f9fa; border-radius:8px; margin-bottom:3px; font-size:0.82rem; }
    .thumb-grid { display:flex; flex-wrap:wrap; gap:10px; margin-top:12px; }
    .thumb-item { position:relative; width:100px; height:75px; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.1); }
    .thumb-item img, .thumb-item video { width:100%; height:100%; object-fit:cover; }
    .thumb-item .section-badge { position:absolute; top:2px; left:2px; font-size:0.6rem; }
    .thumb-item .delete-btn { position:absolute; top:2px; right:2px; width:20px; height:20px; border-radius:50%; background:rgba(220,53,69,0.9); color:#fff; border:none; display:flex; align-items:center; justify-content:center; font-size:0.55rem; cursor:pointer; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">

            {{-- Header --}}
            <div class="d-flex align-items-center mb-4 gap-3">
                <div style="background:linear-gradient(135deg,#001f3f,#0066cc);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-calendar-alt text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-0 fw-bold text-dark">{{ $event->nama_kegiatan }}</h4>
                    <small class="text-muted">Kelola informasi & media event</small>
                </div>
                <form action="{{ route('admin.events.toggle-publish', $event) }}" method="POST" class="d-inline">@csrf
                    <button type="submit" class="btn btn-sm {{ $event->is_published ? 'btn-success' : 'btn-outline-secondary' }}" style="border-radius:8px;">
                        <i class="fas {{ $event->is_published ? 'fa-eye' : 'fa-eye-slash' }} me-1"></i>{{ $event->is_published ? 'Publik' : 'Draft' }}
                    </button>
                </form>
                <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus event ini beserta semua media?')">@csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:8px;"><i class="fas fa-trash me-1"></i> Hapus</button>
                </form>
                <a href="{{ route('admin.events.index') }}" style="width:36px;height:36px;border-radius:50%;background:#e9ecef;color:#555;display:flex;align-items:center;justify-content:center;font-size:1.1rem;text-decoration:none;"
                   onmouseover="this.style.background='#d0d7e2'" onmouseout="this.style.background='#e9ecef'"><i class="fas fa-times"></i></a>
            </div>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:10px; border-left:4px solid #198754;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:10px; border-left:4px solid #dc3545;">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- ═══ SATU FORM ═══ --}}
            <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                {{-- 1. Informasi Event --}}
                <div class="card setting-card mb-4">
                    <div class="card-header"><h6 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2"></i>Informasi Event</h6></div>
                    <div class="card-body p-4 info-form">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Judul Event <span class="text-muted fw-normal">(opsional)</span></label>
                                <input type="text" name="nama_kegiatan" class="form-control form-control-lg" value="{{ old('nama_kegiatan') }}" style="font-size:1.05rem; padding:12px 16px;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal Kegiatan <span class="text-muted fw-normal">(opsional)</span></label>
                                <input type="date" name="tanggal_kegiatan" class="form-control" value="{{ old('tanggal_kegiatan', $event->tanggal_kegiatan?->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-6"></div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Deskripsi <span class="text-muted fw-normal">(opsional)</span></label>
                                <textarea name="deskripsi" rows="3" class="form-control" placeholder="Deskripsi tentang kegiatan...">{{ old('deskripsi', $event->deskripsi) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. Hero / Banner --}}
                <div class="card setting-card mb-4">
                    <div class="card-header hero-header"><h6 class="mb-0 fw-bold"><i class="fas fa-panorama me-2"></i>Hero / Banner (Background Full Layar)</h6></div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-3">Gambar/video tampil penuh sebagai slider di bagian atas halaman publik.</p>
                        <div class="upload-zone" id="heroZone">
                            <input type="file" name="heroes[]" id="heroInput" accept="image/*,video/mp4,video/webm" multiple>
                            <div class="upload-icon hero"><i class="fas fa-cloud-upload-alt fa-lg"></i></div>
                            <h6 class="fw-bold text-dark mb-1">Drag & drop gambar/video hero</h6>
                            <p class="text-muted small mb-0">Rekomendasi: landscape, resolusi tinggi</p>
                        </div>
                        <ul class="upload-list" id="heroList"></ul>
                        @if($event->heroes->count())
                        <div class="thumb-grid">
                            @foreach($event->heroes as $h)
                            <div class="thumb-item">
                                @if($h->type === 'video')
                                    <video muted><source src="{{ asset('storage/'.$h->file_path) }}"></video>
                                @else
                                    <img src="{{ asset('storage/'.$h->file_path) }}">
                                @endif
                                <span class="section-badge badge bg-success">Hero</span>
                                <form action="{{ route('admin.events.media.destroy', [$event, $h]) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')
                                    <button type="submit" class="delete-btn"><i class="fas fa-times"></i></button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                {{-- 3. Galeri Foto --}}
                <div class="card setting-card mb-4">
                    <div class="card-header"><h6 class="mb-0 fw-bold"><i class="fas fa-images me-2"></i>Galeri Foto</h6></div>
                    <div class="card-body p-4">
                        <div class="upload-zone" id="fotoZone">
                            <input type="file" name="fotos[]" id="fotoInput" accept="image/*" multiple>
                            <div class="upload-icon foto"><i class="fas fa-cloud-upload-alt fa-lg"></i></div>
                            <h6 class="fw-bold text-dark mb-1">Drag & drop foto</h6>
                            <p class="text-muted small mb-0">JPG, PNG, GIF, WebP &bull; Tanpa batas ukuran</p>
                        </div>
                        <ul class="upload-list" id="fotoList"></ul>
                        @if($event->galeriFotos->count())
                        <div class="thumb-grid">
                            @foreach($event->galeriFotos as $f)
                            <div class="thumb-item">
                                <img src="{{ asset('storage/'.$f->file_path) }}">
                                <form action="{{ route('admin.events.media.destroy', [$event, $f]) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')
                                    <button type="submit" class="delete-btn"><i class="fas fa-times"></i></button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                {{-- 4. Galeri Video --}}
                <div class="card setting-card mb-4">
                    <div class="card-header video-header"><h6 class="mb-0 fw-bold"><i class="fas fa-video me-2"></i>Galeri Video</h6></div>
                    <div class="card-body p-4">
                        <div class="upload-zone" id="videoZone">
                            <input type="file" name="videos[]" id="videoInput" accept="video/mp4,video/webm,video/quicktime,video/x-msvideo" multiple>
                            <div class="upload-icon video"><i class="fas fa-film fa-lg"></i></div>
                            <h6 class="fw-bold text-dark mb-1">Drag & drop video</h6>
                            <p class="text-muted small mb-0">MP4, WebM, MOV, AVI &bull; Tanpa batas ukuran</p>
                        </div>
                        <ul class="upload-list" id="videoList"></ul>
                        <div class="mt-3">
                            <label class="form-label fw-semibold small">Atau URL Video <span class="text-muted fw-normal">(YouTube, dll)</span></label>
                            <input type="url" name="video_url" class="form-control form-control-sm" placeholder="https://www.youtube.com/watch?v=..." style="border-radius:8px; border:2px solid #e0e0e0;">
                        </div>
                        @if($event->galeriVideos->count())
                        <div class="thumb-grid">
                            @foreach($event->galeriVideos as $v)
                            <div class="thumb-item">
                                @if($v->file_path)
                                    <video muted><source src="{{ asset('storage/'.$v->file_path) }}"></video>
                                @elseif($v->video_url)
                                    <div style="width:100%;height:100%;background:#111;display:flex;align-items:center;justify-content:center;"><i class="fab fa-youtube text-danger fa-lg"></i></div>
                                @endif
                                <form action="{{ route('admin.events.media.destroy', [$event, $v]) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')
                                    <button type="submit" class="delete-btn"><i class="fas fa-times"></i></button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Simpan --}}
                <div class="d-flex justify-content-end mb-4">
                    <button type="submit" class="btn btn-tambah px-5 py-2" style="font-size:1rem;">{{ __('messages.simpan') }}</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function setupZone(inputId, listId, zoneId) {
    const input = document.getElementById(inputId);
    const list = document.getElementById(listId);
    const zone = document.getElementById(zoneId);
    zone.addEventListener('dragover', (e) => { e.preventDefault(); zone.classList.add('dragover'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('dragover'));
    zone.addEventListener('drop', () => zone.classList.remove('dragover'));
    input.addEventListener('change', function() {
        list.innerHTML = '';
        Array.from(this.files).forEach(file => {
            const li = document.createElement('li');
            const size = file.size > 1048576 ? (file.size/1048576).toFixed(1)+' MB' : (file.size/1024).toFixed(0)+' KB';
            li.innerHTML = '<i class="fas fa-file text-primary"></i> <span class="flex-grow-1">'+file.name+'</span> <span class="text-muted">'+size+'</span>';
            list.appendChild(li);
        });
    });
}
setupZone('heroInput','heroList','heroZone');
setupZone('fotoInput','fotoList','fotoZone');
setupZone('videoInput','videoList','videoZone');
</script>
@endpush
