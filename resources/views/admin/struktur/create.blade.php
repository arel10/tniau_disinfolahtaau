@extends('layouts.admin')

@section('title', 'Tambah Jabatan Struktur')
@section('page-title', 'Tambah Jabatan Struktur')

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.struktur.index') }}">Struktur Organisasi</a></li>
        <li class="breadcrumb-item active">{{ __('messages.tambah') }}</li>
    </ol>
</nav>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.struktur.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-8">
                    <!-- Kode & Nama Jabatan -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Kode <span class="text-danger">*</span></label>
                            <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror" 
                                   value="{{ old('kode') }}" placeholder="cth: kadisinfolahtaau" required>
                            <small class="text-muted">Huruf kecil tanpa spasi</small>
                            @error('kode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Nama Jabatan (Singkatan) <span class="text-danger">*</span></label>
                            <input type="text" name="nama_jabatan" class="form-control @error('nama_jabatan') is-invalid @enderror" 
                                   value="{{ old('nama_jabatan') }}" placeholder="cth: KADISINFOLAHTAAU" required>
                            @error('nama_jabatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap Jabatan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_lengkap_jabatan" class="form-control @error('nama_lengkap_jabatan') is-invalid @enderror" 
                               value="{{ old('nama_lengkap_jabatan') }}" placeholder="cth: Kepala Dinas Informasi dan Pengolahan Data TNI AU" required>
                        @error('nama_lengkap_jabatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Unit</label>
                        <input type="text" name="unit" class="form-control @error('unit') is-invalid @enderror" 
                               value="{{ old('unit') }}" placeholder="cth: Dinas Informasi dan Pengolahan Data TNI AU">
                        @error('unit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>
                    <h6 class="text-primary fw-bold mb-3"><i class="fas fa-user me-1"></i> Data Pejabat</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Pejabat</label>
                            <input type="text" name="nama_pejabat" class="form-control @error('nama_pejabat') is-invalid @enderror" 
                                   value="{{ old('nama_pejabat') }}" placeholder="Nama lengkap pejabat">
                            @error('nama_pejabat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('messages.form_pangkat') }}</label>
                            <input type="text" name="pangkat" class="form-control @error('pangkat') is-invalid @enderror" 
                                   value="{{ old('pangkat') }}" placeholder="cth: Marsekal Pertama TNI">
                            @error('pangkat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('messages.form_nrp') }}</label>
                            <input type="text" name="nrp" class="form-control @error('nrp') is-invalid @enderror" 
                                   value="{{ old('nrp') }}" placeholder="Nomor Registrasi Pokok">
                            @error('nrp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                   value="{{ old('tanggal_lahir') }}">
                            @error('tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>
                    <h6 class="text-primary fw-bold mb-3"><i class="fas fa-cog me-1"></i> Pengaturan</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Induk (Parent)</label>
                            <select name="parent_kode" class="form-select @error('parent_kode') is-invalid @enderror">
                                <option value="">-- Tidak ada (Level teratas) --</option>
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->kode }}" {{ old('parent_kode') == $parent->kode ? 'selected' : '' }}>
                                        {{ $parent->nama_jabatan }} ({{ $parent->kode }})
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_kode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">{{ __('messages.urutan') }}</label>
                            <input type="number" name="urutan" class="form-control @error('urutan') is-invalid @enderror" 
                                   value="{{ old('urutan', 0) }}" min="0">
                            @error('urutan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">{{ __('messages.status') }}</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="isActive">{{ __('messages.aktif') }}</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Foto Column -->
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <label class="form-label fw-bold">Foto Pejabat (3x4)</label>
                            <div class="mb-3">
                                <div id="fotoPreview" class="mx-auto border rounded bg-white d-flex align-items-center justify-content-center"
                                     style="width: 150px; height: 200px; overflow: hidden;">
                                    <i class="fas fa-user fa-3x text-muted"></i>
                                </div>
                            </div>
                            <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" 
                                   id="fotoInput" accept="image/jpeg,image/png,image/jpg">
                            <small class="text-muted d-block mt-1">Format: JPG, PNG. Max: 2MB</small>
                            <small class="text-muted">Rasio disarankan: 3x4</small>
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <div class="d-flex gap-2 align-items-center">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="{{ route('admin.struktur.index') }}" title="Kembali"
                   style="width:36px;height:36px;border-radius:50%;background:#e9ecef;border:none;color:#555;display:flex;align-items:center;justify-content:center;font-size:1.1rem;text-decoration:none;transition:background 0.2s;flex-shrink:0;"
                   onmouseover="this.style.background='#d0d7e2'" onmouseout="this.style.background='#e9ecef'">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('fotoInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('fotoPreview').innerHTML = 
                    '<img src="' + event.target.result + '" style="width: 100%; height: 100%; object-fit: cover;">';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
