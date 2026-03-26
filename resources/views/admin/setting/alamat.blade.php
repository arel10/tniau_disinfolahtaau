@extends('layouts.admin')

@section('title', 'Pengaturan Alamat')
@section('page-title', 'Pengaturan Alamat')

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
    .btn-back-x {
        position: absolute;
        top: 18px;
        right: 24px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(255,255,255,0.15);
        border: none;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        cursor: pointer;
        transition: background 0.2s;
        text-decoration: none;
    }
    .btn-back-x:hover { background: rgba(255,255,255,0.35); color: white; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-7 col-lg-9">

            <div class="d-flex align-items-center mb-4 gap-3">
                <div style="background:linear-gradient(135deg,#001f3f,#0066cc);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-map-marker-alt text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-0 fw-bold text-dark">Pengaturan Alamat</h4>
                    <small class="text-muted">Kelola alamat dan link Google Maps yang tampil di website.</small>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="ms-auto" title="Kembali ke Dashboard"
                   style="width:36px;height:36px;border-radius:50%;background:#e9ecef;border:none;color:#555;display:flex;align-items:center;justify-content:center;font-size:1.1rem;text-decoration:none;transition:background 0.2s;flex-shrink:0;"
                   onmouseover="this.style.background='#d0d7e2'" onmouseout="this.style.background='#e9ecef'">
                    <i class="fas fa-times"></i>
                </a>
            </div>

            <form action="{{ route('admin.setting.alamat.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card setting-card mb-4" style="position:relative;">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="fas fa-map-marker-alt"></i>
                        <span class="fw-bold">Informasi Alamat</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label for="alamat_text" class="form-label">Teks Alamat</label>
                            <textarea class="form-control @error('alamat_text') is-invalid @enderror"
                                      id="alamat_text" name="alamat_text" rows="3"
                                      placeholder="Contoh: Disinfolahtaau Gedung B 3 Lantai 1, Jl. Raya Hamkam Cilangkap, Jakarta Timur.">{{ old('alamat_text', setting('alamat_text', 'Disinfolahtaau Gedung B 3 Lantai 1, Jl. Raya Hamkam Cilangkap, Jakarta Timur.')) }}</textarea>
                            @error('alamat_text') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="alamat_link" class="form-label">Link Google Maps</label>
                            <input type="url" class="form-control @error('alamat_link') is-invalid @enderror"
                                   id="alamat_link" name="alamat_link"
                                   value="{{ old('alamat_link', setting('alamat_link', 'https://www.google.com/maps/place/Dinas+Informasi+dan+Pengolahan+Data+TNI+Angkatan+Udara/@-6.261453,106.918343,17z')) }}"
                                   placeholder="https://www.google.com/maps/...">
                            @error('alamat_link') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="text-muted mt-1 d-block">URL ini akan digunakan saat pengunjung mengklik alamat di navbar dan footer.</small>
                        </div>

                        @if(setting('alamat_link'))
                        <div class="mb-2">
                            <a href="{{ setting('alamat_link') }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-external-link-alt me-1"></i> Buka di Google Maps
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mb-5">
                    <button type="submit" class="btn btn-simpan">
                        Simpan
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
