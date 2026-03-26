@extends('layouts.admin')

@section('title', 'Detail Pesan')
@section('page-title', 'Detail Pesan')

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
    .setting-card .card-header h6 { color: white !important; }
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
    .btn-hapus {
        background: linear-gradient(135deg, #c0392b, #e74c3c);
        color: white;
        font-weight: 700;
        padding: 8px 20px;
        border-radius: 8px;
        border: none;
        transition: transform 0.15s, box-shadow 0.15s;
    }
    .btn-hapus:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(192,57,43,0.35);
    }
    .detail-row {
        padding: 10px 0;
        border-bottom: 1px solid rgba(0,61,130,0.08);
    }
    .detail-row:last-child { border-bottom: none; }
    .detail-label { font-weight: 600; color: #003d82; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.04em; }
    .detail-value { color: #212529; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">

            {{-- Page Header --}}
            <div class="d-flex align-items-center mb-4 gap-3">
                <div style="background:linear-gradient(135deg,#001f3f,#0066cc);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-envelope-open-text text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-0 fw-bold text-dark">Detail Pesan Kontak</h4>
                    <small class="text-muted">Informasi lengkap pesan yang diterima dari pengunjung.</small>
                </div>
                <a href="{{ route('admin.kontak.index') }}" class="ms-auto" title="Kembali"
                   style="width:36px;height:36px;border-radius:50%;background:#e9ecef;border:none;color:#555;display:flex;align-items:center;justify-content:center;font-size:1.1rem;text-decoration:none;transition:background 0.2s;flex-shrink:0;"
                   onmouseover="this.style.background='#d0d7e2'" onmouseout="this.style.background='#e9ecef'">
                    <i class="fas fa-times"></i>
                </a>
            </div>

            <div class="row g-4">

                {{-- Isi Pesan --}}
                <div class="col-lg-8">
                    <div class="card setting-card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold"><i class="fas fa-comment-alt me-2"></i>Isi Pesan</h6>
                            <span class="badge bg-white" style="color:#001f3f;font-size:0.78rem;">
                                {{ $kontak->created_at->format('d F Y, H:i') }} WIB
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="detail-row">
                                <div class="detail-label mb-1">{{ __('messages.subjek') }}</div>
                                <div class="detail-value fw-semibold fs-5">{{ $kontak->subjek }}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label mb-1">Dari</div>
                                <div class="detail-value">
                                    <span class="fw-semibold">{{ $kontak->nama }}</span>
                                    &nbsp;&mdash;&nbsp;
                                    <a href="mailto:{{ $kontak->email }}" class="text-decoration-none" style="color:#0066cc;">{{ $kontak->email }}</a>
                                </div>
                            </div>
                            <div class="detail-row mt-2">
                                <div class="detail-label mb-2">{{ __('messages.pesan') }}</div>
                                <div class="detail-value" style="white-space:pre-wrap;line-height:1.7;">{{ $kontak->pesan }}</div>
                            </div>

                            <div class="mt-4 pt-2 d-flex justify-content-between align-items-center">
                                <a href="mailto:{{ $kontak->email }}" class="btn btn-outline-primary">
                                    <i class="fas fa-reply me-1"></i> Balas via Email
                                </a>
                                <form action="{{ route('admin.kontak.destroy', $kontak->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pesan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-hapus">
                                        <i class="fas fa-trash me-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Update Status --}}
                <div class="col-lg-4">
                    <div class="card setting-card">
                        <div class="card-header">
                            <h6 class="mb-0 fw-bold"><i class="fas fa-tag me-2"></i>Update Status</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Status Saat Ini</label>
                                <div>
                                    @if($kontak->status == 'baru')
                                        <span class="badge" style="background:linear-gradient(135deg,#001f3f,#003d82);font-size:0.9rem;padding:6px 14px;">Baru</span>
                                    @elseif($kontak->status == 'dibaca')
                                        <span class="badge bg-info" style="font-size:0.9rem;padding:6px 14px;">Dibaca</span>
                                    @elseif($kontak->status == 'diproses')
                                        <span class="badge bg-warning text-dark" style="font-size:0.9rem;padding:6px 14px;">Diproses</span>
                                    @else
                                        <span class="badge bg-success" style="font-size:0.9rem;padding:6px 14px;">Selesai</span>
                                    @endif
                                </div>
                            </div>

                            <form method="POST" action="{{ route('admin.kontak.updateStatus', $kontak->id) }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Ubah Status</label>
                                    <select name="status" class="form-select" required>
                                        <option value="baru" {{ $kontak->status == 'baru' ? 'selected' : '' }}>Baru</option>
                                        <option value="dibaca" {{ $kontak->status == 'dibaca' ? 'selected' : '' }}>Dibaca</option>
                                        <option value="diproses" {{ $kontak->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                        <option value="selesai" {{ $kontak->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                </div>
                                <div class="d-flex justify-content-end">
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
    </div>
</div>
@endsection
