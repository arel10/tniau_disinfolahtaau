@extends('layouts.admin')

@section('title', 'Edit Tutorial')
@section('page-title', 'Edit Tutorial')

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
                    <i class="fas fa-edit text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-0 fw-bold text-dark">Edit Tutorial</h4>
                    <small class="text-muted">Ubah data tutorial.</small>
                </div>
                <a href="{{ route('admin.tutorial.index') }}" class="ms-auto" style="width:36px;height:36px;border-radius:50%;background:#e9ecef;border:none;color:#555;display:flex;align-items:center;justify-content:center;font-size:1.1rem;text-decoration:none;transition:background 0.2s;flex-shrink:0;" onmouseover="this.style.background='#d0d7e2'" onmouseout="this.style.background='#e9ecef'"><i class="fas fa-times"></i></a>
            </div>
            <div class="card setting-card">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-book-open me-2"></i>Data Tutorial</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.tutorial.update', $tutorial) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul <span class="text-danger">*</span></label>
                            <input type="text" name="judul" id="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul', $tutorial->judul) }}" required>
                            @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="gambar" class="form-label">{{ __('messages.gambar') }}</label>
                            @if($tutorial->gambar)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $tutorial->gambar) }}" alt="Gambar" style="max-width:120px;max-height:120px;" class="rounded">
                                </div>
                            @endif
                            <input type="file" name="gambar" id="gambar" class="form-control @error('gambar') is-invalid @enderror">
                            @error('gambar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <input type="hidden" name="gambar_pustaka" id="gambar_pustaka">
                            @if(!empty($pustaka_gambar))
                            <div class="mt-2">
                                <label class="form-label">Atau pilih dari pustaka:</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($pustaka_gambar as $img)
                                        <img src="{{ asset('storage/' . $img) }}" alt="Pustaka" class="img-thumbnail pilih-gambar-pustaka" style="width:80px;height:80px;object-fit:cover;cursor:pointer;">
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="link" class="form-label">Link Tujuan</label>
                            <input type="url" name="link" id="link" class="form-control @error('link') is-invalid @enderror" value="{{ old('link', $tutorial->link) }}" placeholder="https://contoh.com">
                            @error('link')<div class="invalid-feedback">{{ $message }}</div>@enderror
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.pilih-gambar-pustaka').forEach(function(img) {
        img.addEventListener('click', function() {
            document.getElementById('gambar_pustaka').value = this.src.replace(window.location.origin + '/storage/', '');
            document.getElementById('gambar').value = '';
            document.querySelectorAll('.pilih-gambar-pustaka').forEach(i => i.classList.remove('border-primary'));
            this.classList.add('border-primary');
        });
    });
});
</script>
@endpush
@endsection
