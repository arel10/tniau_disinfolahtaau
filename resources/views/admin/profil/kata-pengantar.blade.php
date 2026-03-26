@extends('layouts.admin')
@section('title', __('messages.admin_kata_pengantar'))
@section('page-title', 'Edit Kata Pengantar')

@push('styles')
<style>
    .setting-card { border:none; box-shadow:0 2px 12px rgba(0,61,130,0.10); border-radius:12px; }
    .setting-card .card-header { background:linear-gradient(135deg,#001f3f 0%,#003d82 100%); color:white; border-radius:12px 12px 0 0 !important; padding:16px 24px; }
    .setting-card .card-header h6 { color:white !important; }
    .form-label { font-weight:600; color:#003d82; }
    .btn-simpan { background:linear-gradient(135deg,#001f3f 0%,#0066cc 100%); color:white; font-weight:700; padding:10px 36px; border-radius:8px; font-size:1rem; border:none; transition:transform 0.15s, box-shadow 0.15s; }
    .btn-simpan:hover { color:#fff; transform:translateY(-2px); box-shadow:0 6px 20px rgba(0,61,130,0.35); }
    .foto-preview { max-width:200px; max-height:200px; object-fit:cover; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.15); }
    .foto-slot { width:200px; height:200px; border:2px dashed #ccc; border-radius:10px; display:flex; align-items:center; justify-content:center; color:#aaa; font-size:3rem; background:#f8f9fa; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="d-flex align-items-center mb-4 gap-3">
                <div style="background:linear-gradient(135deg,#001f3f,#0066cc);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-comment-dots text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-0 fw-bold text-dark">Kata Pengantar</h4>
                    <small class="text-muted">Kelola kata pengantar organisasi.</small>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="ms-auto" style="width:36px;height:36px;border-radius:50%;background:#e9ecef;border:none;color:#555;display:flex;align-items:center;justify-content:center;font-size:1.1rem;text-decoration:none;transition:background 0.2s;flex-shrink:0;" onmouseover="this.style.background='#d0d7e2'" onmouseout="this.style.background='#e9ecef'">
                    <i class="fas fa-times"></i>
                </a>
            </div>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="card setting-card">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-edit me-2"></i>Konten Kata Pengantar</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profil.kata-pengantar.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Judul (opsional) --}}
                        <div class="mb-4">
                            <label for="title" class="form-label">Judul (opsional)</label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $title) }}">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Foto (opsional) --}}
                        <div class="mb-4">
                            <label class="form-label">Foto (opsional)</label>
                            <div class="d-flex align-items-start gap-3">
                                @if($image)
                                    <div class="position-relative">
                                        <img src="{{ asset('storage/' . $image) }}" alt="Foto Kata Pengantar" class="foto-preview" id="fotoPreview">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="hapus_foto" id="hapusFoto" value="1">
                                            <label class="form-check-label text-danger small" for="hapusFoto">Hapus foto</label>
                                        </div>
                                    </div>
                                @else
                                    <div class="foto-slot" id="fotoSlot">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <img src="" alt="" class="foto-preview d-none" id="fotoPreview">
                                @endif
                                <div class="flex-grow-1">
                                    <input type="file" name="foto" id="fotoInput" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                                    <small class="text-muted d-block mt-1">Format: JPG, PNG, WebP. Maks: 2MB</small>
                                    @error('foto')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Teks Kata Pengantar --}}
                        <div class="mb-4">
                            <label for="content" class="form-label">Teks Kata Pengantar</label>
                            <textarea name="content" id="content" rows="10" class="form-control @error('content') is-invalid @enderror" placeholder="Tuliskan kata pengantar di sini...">{{ old('content', $content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-simpan"><i class="fas fa-save me-2"></i>{{ __('messages.simpan') }}</button>
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
    document.getElementById('fotoInput').addEventListener('change', function(e) {
        var file = e.target.files[0];
        if (!file) return;
        var reader = new FileReader();
        reader.onload = function(ev) {
            var preview = document.getElementById('fotoPreview');
            var slot = document.getElementById('fotoSlot');
            preview.src = ev.target.result;
            preview.classList.remove('d-none');
            if (slot) slot.classList.add('d-none');
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush
