@extends('layouts.admin')

@section('title', 'Manajemen Pesan Kontak')
@section('page-title', 'Manajemen Pesan Kontak')

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
    .btn-filter {
        background: linear-gradient(135deg, #001f3f 0%, #0066cc 100%);
        color: white;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        transition: transform 0.15s, box-shadow 0.15s;
    }
    .btn-filter:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,61,130,0.35);
    }
    .stat-badge {
        display: flex;
        align-items: center;
        gap: 14px;
        background: rgba(0,61,130,0.06);
        border-radius: 10px;
        padding: 14px 18px;
    }
    .stat-icon-box {
        width: 46px; height: 46px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-11 col-lg-12">

            {{-- Page Header --}}
            <div class="d-flex align-items-center mb-4 gap-3">
                <div style="background:linear-gradient(135deg,#001f3f,#0066cc);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-envelope text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-0 fw-bold text-dark">Manajemen Pesan Kontak</h4>
                    <small class="text-muted">Pesan masuk dari pengunjung website.</small>
                </div>
            </div>

            {{-- Statistik --}}
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="stat-badge">
                        <div class="stat-icon-box" style="background:linear-gradient(135deg,#001f3f,#003d82);">
                            <i class="fas fa-envelope text-white"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">{{ $statusCounts['baru'] }}</h5>
                            <small class="text-muted">Pesan Baru</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-badge">
                        <div class="stat-icon-box" style="background:linear-gradient(135deg,#0d6efd,#0dcaf0);">
                            <i class="fas fa-envelope-open text-white"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">{{ $statusCounts['dibaca'] }}</h5>
                            <small class="text-muted">Dibaca</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-badge">
                        <div class="stat-icon-box" style="background:linear-gradient(135deg,#198754,#20c997);">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">{{ $statusCounts['selesai'] }}</h5>
                            <small class="text-muted">Selesai</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="card setting-card mb-4">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-filter me-2"></i>Filter Pesan</h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.kontak.index') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label fw-semibold" style="color:#003d82;">Kata Kunci</label>
                                <input type="text" name="search" class="form-control" placeholder="Cari nama, email, subjek..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold" style="color:#003d82;">{{ __('messages.status') }}</label>
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="baru" {{ request('status') == 'baru' ? 'selected' : '' }}>Baru</option>
                                    <option value="dibaca" {{ request('status') == 'dibaca' ? 'selected' : '' }}>Dibaca</option>
                                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-filter w-100">
                                    <i class="fas fa-search me-1"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tabel Pesan --}}
            <div class="card setting-card">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-inbox me-2"></i>Daftar Pesan Masuk</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr style="background:linear-gradient(135deg,#001f3f,#003d82);color:white;">
                                    <th style="padding:14px 20px;">{{ __('messages.nama') }}</th>
                                    <th style="padding:14px 16px;">{{ __('messages.email') }}</th>
                                    <th style="padding:14px 16px;">{{ __('messages.subjek') }}</th>
                                    <th style="padding:14px 16px;">{{ __('messages.status') }}</th>
                                    <th style="padding:14px 16px;">{{ __('messages.tanggal') }}</th>
                                    <th style="padding:14px 16px;text-align:center;">{{ __('messages.aksi') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kontaks as $kontak)
                                <tr class="{{ $kontak->status == 'baru' ? 'fw-semibold' : '' }}">
                                    <td class="ps-4">{{ $kontak->nama }}</td>
                                    <td>{{ $kontak->email }}</td>
                                    <td>{{ Str::limit($kontak->subjek, 40) }}</td>
                                    <td>
                                        @if($kontak->status == 'baru')
                                            <span class="badge" style="background:linear-gradient(135deg,#001f3f,#003d82);">Baru</span>
                                        @elseif($kontak->status == 'dibaca')
                                            <span class="badge bg-info">Dibaca</span>
                                        @elseif($kontak->status == 'diproses')
                                            <span class="badge bg-warning text-dark">Diproses</span>
                                        @else
                                            <span class="badge bg-success">Selesai</span>
                                        @endif
                                    </td>
                                    <td>{{ $kontak->created_at->format('d M Y') }}</td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ route('admin.kontak.show', $kontak->id) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.kontak.destroy', $kontak->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pesan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block opacity-50"></i>
                                        Belum ada pesan masuk.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($kontaks->hasPages())
                    <div class="d-flex flex-column align-items-center py-3 gap-2">
                        <div>{{ $kontaks->links() }}</div>
                        <span class="pagination-info">Menampilkan {{ $kontaks->firstItem() }} - {{ $kontaks->lastItem() }} dari {{ $kontaks->total() }} pesan</span>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
