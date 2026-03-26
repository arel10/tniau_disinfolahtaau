@extends('layouts.admin')

@section('title', __('messages.admin_menu_utama'))
@section('page-title', __('messages.admin_menu_utama'))

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
    .btn-tambah {
        background: linear-gradient(135deg, #001f3f 0%, #0066cc 100%);
        color: white;
        font-weight: 700;
        padding: 10px 28px;
        border-radius: 8px;
        font-size: 0.95rem;
        border: none;
        transition: transform 0.15s, box-shadow 0.15s;
        white-space: nowrap;
    }
    .btn-tambah:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,61,130,0.35);
    }
    .menu-row { border-bottom: 1px solid #e9ecef; padding: 12px 0; }
    .menu-row:last-child { border-bottom: none; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-9 col-lg-11">

            {{-- Page header --}}
            <div class="d-flex align-items-center mb-4 gap-3">
                <div style="background:linear-gradient(135deg,#001f3f,#0066cc);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-bars text-white fa-lg"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold text-dark">Menu Utama</h4>
                    <small class="text-muted">Kelola menu yang tampil di footer halaman publik.</small>
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

            {{-- Form Tambah --}}
            <div class="card setting-card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fas fa-plus-circle"></i>
                    <span class="fw-bold">Tambah Menu Utama</span>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.setting.menu-utama.store') }}" method="POST">
                        @csrf
                        <div class="row g-3 align-items-end">
                            <div class="col-md-9">
                                <label class="form-label">Pilih Menu</label>
                                <select name="route_name" id="routeSelect" class="form-select" required onchange="setNama(this)">
                                    <option value="">-- Pilih menu --</option>
                                    @foreach($options as $label => $routeName)
                                        <option value="{{ $routeName }}" data-label="{{ $label }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('route_name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <input type="hidden" name="nama" id="namaInput" value="{{ old('nama') }}">
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-tambah w-100">
                                    <i class="fas fa-plus me-1"></i>Tambah
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Daftar Menu --}}
            <div class="card setting-card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fas fa-list"></i>
                    <span class="fw-bold">Daftar Menu Utama</span>
                    <span class="ms-auto badge bg-white text-primary fw-bold">{{ $items->count() }} menu</span>
                </div>
                <div class="card-body p-0">
                    @if($items->isEmpty())
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>
                            Belum ada menu. Tambahkan di atas.
                        </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead>
                                <tr style="background:linear-gradient(135deg,#001f3f,#003d82);color:white;">
                                    <th style="width:50px;padding:14px 20px;">#</th>
                                    <th style="padding:14px 16px;">Nama / Label</th>
                                    <th style="padding:14px 16px;">Route</th>
                                    <th style="padding:14px 16px;">URL Preview</th>
                                    <th style="width:90px;padding:14px 16px;">{{ __('messages.aksi') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $i => $item)
                                <tr>
                                    <td class="text-muted ps-4">{{ $i + 1 }}</td>
                                    <td class="fw-semibold">{{ $item->nama }}</td>
                                    <td><code class="text-primary">{{ $item->route_name }}</code></td>
                                    <td>
                                        @php
                                            try { $previewUrl = route($item->route_name); } catch (\Exception $e) { $previewUrl = null; }
                                        @endphp
                                        @if($previewUrl)
                                            <a href="{{ $previewUrl }}" target="_blank" class="text-decoration-none small text-muted">{{ $previewUrl }}</a>
                                        @else
                                            <span class="text-danger small"><i class="fas fa-exclamation-triangle me-1"></i>Route tidak ditemukan</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.setting.menu-utama.destroy', $item) }}" method="POST"
                                              onsubmit="return confirm('Hapus menu ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function setNama(sel) {
    const opt = sel.options[sel.selectedIndex];
    const namaInput = document.getElementById('namaInput');
    namaInput.value = (opt && opt.dataset.label) ? opt.dataset.label : '';
}
</script>
@endsection
