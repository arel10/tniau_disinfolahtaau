@extends('layouts.admin')
@section('title', 'Pembangunan ZI')
@section('page-title', __('messages.admin_pembangunan'))

@push('styles')
<style>
    .setting-card { border:none; box-shadow:0 2px 12px rgba(0,61,130,0.10); border-radius:12px; }
    .setting-card .card-header { background:linear-gradient(135deg,#001f3f 0%,#003d82 100%); color:white; border-radius:12px 12px 0 0 !important; padding:16px 24px; }
    .setting-card .card-header h6 { color:white !important; }
    .btn-tambah { background:linear-gradient(135deg,#001f3f 0%,#0066cc 100%); color:white; font-weight:600; border:none; border-radius:8px; transition:transform 0.15s, box-shadow 0.15s; }
    .btn-tambah:hover { color:#fff; transform:translateY(-2px); box-shadow:0 6px 20px rgba(0,61,130,0.35); }
    .item-img-placeholder { width:60px; height:60px; background:linear-gradient(135deg,#001f3f,#003d82); border-radius:8px; display:flex; align-items:center; justify-content:center; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">

            <div class="d-flex align-items-center mb-4 gap-3">
                <div style="background:linear-gradient(135deg,#001f3f,#0066cc);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-hard-hat text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-0 fw-bold text-dark">Pembangunan ZI</h4>
                    <small class="text-muted">Kelola item pembangunan Zona Integritas.</small>
                </div>
                <a href="{{ route('admin.zi.pembangunan.create') }}" class="btn btn-tambah">
                    <i class="fas fa-plus me-1"></i> Tambah
                </a>
            </div>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:10px; border-left:4px solid #198754;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="card setting-card">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-list me-2"></i>Daftar Item Pembangunan</h6>
                </div>
                <div class="card-body p-0">
                    @if($items->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr style="background:linear-gradient(135deg,#001f3f,#003d82);color:white;">
                                    <th style="width:50px;padding:14px 20px;">#</th>
                                    <th style="width:80px;padding:14px 16px;">{{ __('messages.gambar') }}</th>
                                    <th style="padding:14px 16px;">{{ __('messages.judul') }}</th>
                                    <th style="padding:14px 16px;">{{ __('messages.admin_keterangan') }}</th>
                                    <th style="width:160px;padding:14px 16px;text-align:center;">{{ __('messages.aksi') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                <tr>
                                    <td class="ps-4">{{ $loop->iteration + ($items->currentPage() - 1) * $items->perPage() }}</td>
                                    <td>
                                        @if($item->gambar)
                                            <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}" style="width:60px;height:60px;object-fit:cover;border-radius:8px;">
                                        @else
                                            <div class="item-img-placeholder">
                                                <i class="fas fa-image text-white"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="fw-semibold">{{ $item->judul }}</td>
                                    <td>
                                        <div style="max-width:350px;" class="text-muted">
                                            {{ Str::limit($item->konten, 100) }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ route('admin.zi.pembangunan.edit', $item->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.zi.pembangunan.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus item ini?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($items->hasPages())
                    <div class="d-flex flex-column align-items-center py-3 gap-2">
                        <div>{{ $items->links() }}</div>
                    </div>
                    @endif
                    @else
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-inbox fa-2x d-block mb-2 opacity-50"></i>
                        Belum ada item pembangunan.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
