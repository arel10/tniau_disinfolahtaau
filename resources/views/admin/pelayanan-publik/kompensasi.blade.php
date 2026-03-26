@extends('layouts.admin')
@section('page-title', __('messages.admin_kompensasi_pelayanan'))

@push('styles')
<style>
    .setting-card { border:none; box-shadow:0 2px 12px rgba(0,61,130,0.10); border-radius:12px; }
    .setting-card .card-header { background:linear-gradient(135deg,#001f3f 0%,#003d82 100%); color:white; border-radius:12px 12px 0 0 !important; padding:16px 24px; }
    .setting-card .card-header h6 { color:white !important; }
    .media-preview { position:relative; display:inline-block; margin:4px; }
    .media-preview img, .media-preview video { width:80px; height:80px; object-fit:cover; border-radius:6px; border:2px solid #dee2e6; }
    .media-preview .remove-btn { position:absolute; top:-6px; right:-6px; background:#dc3545; color:#fff; border:none; border-radius:50%; width:20px; height:20px; font-size:10px; display:flex; align-items:center; justify-content:center; cursor:pointer; }
    .media-preview .pdf-icon { width:80px; height:80px; border-radius:6px; border:2px solid #dee2e6; background:#f8f9fa; display:flex; flex-direction:column; align-items:center; justify-content:center; font-size:10px; color:#6c757d; }
    .media-preview .pdf-icon i { font-size:24px; color:#dc3545; margin-bottom:4px; }
    .logo-thumb { width:40px; height:40px; object-fit:contain; border-radius:6px; background:#f8f9fa; padding:2px; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">

            {{-- Header --}}
            <div class="d-flex align-items-center mb-4 gap-3">
                <div style="background:linear-gradient(135deg,#001f3f,#0066cc);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-hand-holding-usd text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-0 fw-bold text-dark">Kompensasi Pelayanan Publik</h4>
                    <small class="text-muted">Kelola kompensasi pelayanan publik — semua field bersifat opsional, minimal satu harus diisi.</small>
                </div>
            </div>

            {{-- Alert --}}
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                @foreach($errors->all() as $error) {{ $error }}<br> @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- Form Card --}}
            <div class="card setting-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-{{ isset($item) ? 'edit' : 'plus-circle' }} me-2"></i>
                        {{ isset($item) ? 'Edit Data' : 'Tambah Data Baru' }}
                    </h6>
                    @if(isset($item))
                    <a href="{{ route('admin.pelayanan-publik.kompensasi.index') }}" class="btn btn-sm btn-outline-light">
                        <i class="fas fa-times me-1"></i>Batal Edit
                    </a>
                    @endif
                </div>
                <div class="card-body p-4">
                    <form action="{{ isset($item) ? route('admin.pelayanan-publik.kompensasi.update', $item->id) : route('admin.pelayanan-publik.kompensasi.store') }}"
                          method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($item)) @method('PUT') @endif

                        <div class="row g-3">
                            {{-- Judul --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold"><i class="fas fa-heading me-1 text-primary"></i> Judul</label>
                                <input type="text" name="judul" class="form-control"
                                       value="{{ old('judul', $item->judul ?? '') }}"
                                       placeholder="Judul kompensasi (opsional)">
                            </div>

                            {{-- Media Files --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold"><i class="fas fa-photo-video me-1 text-success"></i> Gambar / Video</label>
                                <input type="file" name="media_files[]" class="form-control" multiple accept="image/*,video/*" id="mediaFilesInput">
                                <small class="text-muted">Upload gambar atau video (boleh banyak, tanpa batas ukuran).</small>
                                <div id="mediaPreview" class="mt-2"></div>
                                @if(isset($item) && $item->media->whereIn('type', ['image','video'])->count())
                                <div class="mt-2">
                                    <small class="text-muted fw-semibold">Media saat ini:</small>
                                    <div class="d-flex flex-wrap mt-1">
                                        @foreach($item->media->whereIn('type', ['image','video']) as $m)
                                        <div class="media-preview">
                                            @if($m->type === 'image')
                                            <img src="{{ asset('storage/'.$m->file_path) }}" alt="{{ $m->original_name }}">
                                            @else
                                            <video src="{{ asset('storage/'.$m->file_path) }}" muted></video>
                                            @endif
                                            <label class="remove-btn" title="Hapus">
                                                <input type="checkbox" name="delete_media[]" value="{{ $m->id }}" class="d-none"
                                                       onchange="this.closest('.media-preview').style.opacity=this.checked?'0.3':'1'">
                                                <i class="fas fa-times"></i>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>

                            {{-- PDF Files --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold"><i class="fas fa-file-pdf me-1 text-danger"></i> File PDF</label>
                                <input type="file" name="pdf_files[]" class="form-control" multiple accept=".pdf" id="pdfFilesInput">
                                <small class="text-muted">Upload file PDF (boleh banyak, tanpa batas ukuran).</small>
                                <div id="pdfPreview" class="mt-2"></div>
                                @if(isset($item) && $item->pdfs->count())
                                <div class="mt-2">
                                    <small class="text-muted fw-semibold">PDF saat ini:</small>
                                    <div class="d-flex flex-wrap mt-1">
                                        @foreach($item->pdfs as $p)
                                        <div class="media-preview">
                                            <div class="pdf-icon">
                                                <i class="fas fa-file-pdf"></i>
                                                <span class="text-truncate" style="max-width:70px;">{{ Str::limit($p->original_name, 10) }}</span>
                                            </div>
                                            <label class="remove-btn" title="Hapus">
                                                <input type="checkbox" name="delete_media[]" value="{{ $p->id }}" class="d-none"
                                                       onchange="this.closest('.media-preview').style.opacity=this.checked?'0.3':'1'">
                                                <i class="fas fa-times"></i>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>

                            {{-- Logo --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold"><i class="fas fa-image me-1 text-warning"></i> Logo</label>
                                <input type="file" name="logo" class="form-control" accept="image/*">
                                @if(isset($item) && $item->logo_path)
                                <div class="mt-2 d-flex align-items-center gap-2">
                                    <img src="{{ asset('storage/'.$item->logo_path) }}" class="logo-thumb">
                                    <label class="form-check-label small">
                                        <input type="checkbox" name="remove_logo" value="1" class="form-check-input"> Hapus logo
                                    </label>
                                </div>
                                @endif
                            </div>

                            {{-- Logo Link --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold"><i class="fas fa-link me-1 text-info"></i> Logo Link (URL)</label>
                                <input type="url" name="logo_link" class="form-control"
                                       value="{{ old('logo_link', $item->logo_link ?? '') }}"
                                       placeholder="https://contoh.com">
                                <small class="text-muted">Link tujuan saat logo diklik oleh pengunjung.</small>
                            </div>

                            {{-- Video URL --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold"><i class="fab fa-youtube me-1 text-danger"></i> Video URL (YouTube)</label>
                                <input type="url" name="video_url" class="form-control"
                                       value="{{ old('video_url', $item->video_url ?? '') }}"
                                       placeholder="https://www.youtube.com/watch?v=...">
                            </div>

                            {{-- Deskripsi --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold"><i class="fas fa-align-left me-1 text-secondary"></i> Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="4"
                                          placeholder="Deskripsi (opsional)">{{ old('deskripsi', $item->deskripsi ?? '') }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn text-white px-4" style="background:linear-gradient(135deg,#001f3f,#003d82);">
                                <i class="fas fa-save me-2"></i>{{ isset($item) ? 'Perbarui' : 'Simpan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Data List --}}
            <div class="card setting-card">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-list me-2"></i>Daftar Kompensasi Pelayanan Publik ({{ $items->count() }})</h6>
                </div>
                <div class="card-body p-0">
                    @if($items->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:50px">#</th>
                                    <th>{{ __('messages.judul') }}</th>
                                    <th style="width:100px">Media</th>
                                    <th style="width:80px">{{ __('messages.form_logo') }}</th>
                                    <th style="width:80px">{{ __('messages.status') }}</th>
                                    <th style="width:160px" class="text-center">{{ __('messages.aksi') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $i => $row)
                                <tr>
                                    <td class="text-muted">{{ $i + 1 }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $row->judul ?: '(Tanpa Judul)' }}</div>
                                        @if($row->video_url)<small class="text-danger"><i class="fab fa-youtube me-1"></i>YouTube</small>@endif
                                        @if($row->deskripsi)<small class="text-muted d-block">{{ Str::limit($row->deskripsi, 60) }}</small>@endif
                                    </td>
                                    <td>
                                        @php
                                            $imgC = $row->media->where('type','image')->count();
                                            $vidC = $row->media->where('type','video')->count();
                                            $pdfC = $row->media->where('type','pdf')->count();
                                        @endphp
                                        @if($imgC)<span class="badge bg-success me-1"><i class="fas fa-image me-1"></i>{{ $imgC }}</span>@endif
                                        @if($vidC)<span class="badge bg-primary me-1"><i class="fas fa-video me-1"></i>{{ $vidC }}</span>@endif
                                        @if($pdfC)<span class="badge bg-danger me-1"><i class="fas fa-file-pdf me-1"></i>{{ $pdfC }}</span>@endif
                                        @if(!$imgC && !$vidC && !$pdfC)<span class="text-muted small">—</span>@endif
                                    </td>
                                    <td>
                                        @if($row->logo_path)
                                        <img src="{{ asset('storage/'.$row->logo_path) }}" class="logo-thumb">
                                        @else <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.pelayanan-publik.kompensasi.toggle-publish', $row->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $row->is_published ? 'btn-success' : 'btn-secondary' }}">
                                                <i class="fas fa-{{ $row->is_published ? 'eye' : 'eye-slash' }}"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.pelayanan-publik.kompensasi.edit', $row->id) }}" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admin.pelayanan-publik.kompensasi.destroy', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="py-5 text-center">
                        <i class="fas fa-inbox fa-3x mb-3" style="color:#003d82;opacity:0.3;"></i>
                        <p class="text-muted mb-0">Belum ada data Kompensasi Pelayanan Publik.</p>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('mediaFilesInput')?.addEventListener('change', function() {
    const preview = document.getElementById('mediaPreview');
    preview.innerHTML = '';
    Array.from(this.files).forEach(file => {
        const div = document.createElement('div');
        div.className = 'media-preview';
        if (file.type.startsWith('image/')) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            div.appendChild(img);
        } else if (file.type.startsWith('video/')) {
            const vid = document.createElement('video');
            vid.src = URL.createObjectURL(file);
            vid.muted = true;
            div.appendChild(vid);
        }
        preview.appendChild(div);
    });
});
document.getElementById('pdfFilesInput')?.addEventListener('change', function() {
    const preview = document.getElementById('pdfPreview');
    preview.innerHTML = '';
    Array.from(this.files).forEach(file => {
        const div = document.createElement('div');
        div.className = 'media-preview';
        div.innerHTML = '<div class="pdf-icon"><i class="fas fa-file-pdf"></i><span>' + file.name.substring(0, 10) + '</span></div>';
        preview.appendChild(div);
    });
});
</script>
@endpush
