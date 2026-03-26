@extends('layouts.admin')

@section('title', __('messages.admin_logo_footer'))
@section('page-title', __('messages.admin_logo_footer'))

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
    .logo-preview-box {
        border: 2px dashed #0066cc;
        border-radius: 8px;
        padding: 10px 16px;
        background: #f0f6ff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 100px;
    }
    .logo-preview-box img { max-height: 70px; max-width: 140px; object-fit: contain; border-radius: 4px; }
    .hint-text { font-size: 0.82rem; color: #6c757d; margin-top: 4px; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">

            {{-- Page header --}}
            <div class="d-flex align-items-center mb-4 gap-3">
                <div style="background:linear-gradient(135deg,#001f3f,#0066cc);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-shield-alt text-white fa-lg"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold text-dark">Logo Footer</h4>
                    <small class="text-muted">Kelola logo dan teks identitas yang tampil di bagian footer website.</small>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="ms-auto" title="Kembali ke Dashboard"
                   style="width:36px;height:36px;border-radius:50%;background:#e9ecef;border:none;color:#555;display:flex;align-items:center;justify-content:center;font-size:1.1rem;text-decoration:none;transition:background 0.2s;"
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

            <div class="card setting-card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fas fa-edit"></i>
                    <span class="fw-bold">Edit Logo & Teks Footer</span>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.setting.logo-footer.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        {{-- Logo --}}
                        <div class="mb-4">
                            <label class="form-label">Logo TNI AU</label>
                            <div class="d-flex align-items-center gap-3 mb-2">
                                @php $currentLogo = setting('footer_logo'); @endphp
                                <div class="logo-preview-box">
                                    <img id="logoPreview"
                                         src="{{ $currentLogo ? asset('storage/'.$currentLogo) : asset('assets/image/disinfolahta.png') }}"
                                         alt="Logo">
                                </div>
                                <div class="flex-fill">
                                    <input type="file" name="footer_logo" id="logoInput"
                                           class="form-control @error('footer_logo') is-invalid @enderror"
                                           accept="image/*" onchange="previewLogo(this)">
                                    <div class="hint-text">Format: JPG, PNG, SVG. Maks 2MB. Kosongkan jika tidak ingin mengubah logo.</div>
                                    @error('footer_logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-4" style="border-color:#e0e8f5;">

                        <div class="row g-4">
                            {{-- Site Name --}}
                            <div class="col-md-6">
                                <label class="form-label">Nama Instansi / Judul <span class="text-danger">*</span></label>
                                <input type="text" name="footer_site_name" class="form-control @error('footer_site_name') is-invalid @enderror"
                                       value="{{ old('footer_site_name', setting('footer_site_name', 'TNI AU - Disinfolahtaau')) }}"
                                       placeholder="TNI AU - Disinfolahtaau" maxlength="100" required>
                                <div class="hint-text">Tampil sebagai judul tebal di footer.</div>
                                @error('footer_site_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Subtitle --}}
                            <div class="col-md-6">
                                <label class="form-label">Sub Judul <span class="text-danger">*</span></label>
                                <input type="text" name="footer_site_subtitle" class="form-control @error('footer_site_subtitle') is-invalid @enderror"
                                       value="{{ old('footer_site_subtitle', setting('footer_site_subtitle', 'Angkatan Udara Indonesia')) }}"
                                       placeholder="Angkatan Udara Indonesia" maxlength="100" required>
                                <div class="hint-text">Tampil sebagai teks kecil di bawah judul.</div>
                                @error('footer_site_subtitle')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Description --}}
                            <div class="col-12">
                                <label class="form-label">Deskripsi Footer <span class="text-danger">*</span></label>
                                <textarea name="footer_description" rows="3"
                                          class="form-control @error('footer_description') is-invalid @enderror"
                                          placeholder="Dinas Informasi & Pengolahan Data TNI Angkatan Udara."
                                          maxlength="500" required>{{ old('footer_description', setting('footer_description', 'Dinas Informasi & Pengolahan Data TNI Angkatan Udara.')) }}</textarea>
                                <div class="hint-text">Tampil sebagai paragraf deskripsi di bawah logo di footer.</div>
                                @error('footer_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mt-4 pt-2 d-flex justify-content-end">
                            <button type="submit" class="btn btn-simpan">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function previewLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('logoPreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
