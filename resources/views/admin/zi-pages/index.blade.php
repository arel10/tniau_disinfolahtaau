@extends('layouts.admin')
@section('title', 'Zona Integritas - Halaman')
@section('page-title', 'Edit Zona Integritas')

@push('styles')
<style>
    .setting-card { border:none; box-shadow:0 2px 12px rgba(0,61,130,0.10); border-radius:12px; }
    .setting-card .card-header { background:linear-gradient(135deg,#001f3f 0%,#003d82 100%); color:white; border-radius:12px 12px 0 0 !important; padding:16px 24px; }
    .setting-card .card-header h6 { color:white !important; }
    .btn-tambah { background:linear-gradient(135deg,#001f3f 0%,#0066cc 100%); color:white; font-weight:600; border:none; border-radius:8px; }
    .btn-tambah:hover { color:#fff; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">

            <div class="d-flex align-items-center mb-4 gap-3">
                <div style="background:linear-gradient(135deg,#001f3f,#0066cc);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-shield-halved text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-0 fw-bold text-dark">Zona Integritas — Halaman</h4>
                    <small class="text-muted">Kelola halaman Zona Integritas.</small>
                </div>
                <a href="{{ route('admin.zi.pages.create', ['type' => $type]) }}" class="btn btn-tambah"><i class="fas fa-plus me-1"></i> Tambah</a>
            </div>

            <div class="card setting-card">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-file-alt me-2"></i>Daftar Halaman</h6>
                </div>
                <div class="card-body p-0">
                    @if($pages->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr style="background:linear-gradient(135deg,#001f3f,#003d82);color:white;">
                                    <th style="width:50px;padding:14px 20px;">#</th>
                                    <th style="padding:14px 16px;">{{ __('messages.gambar') }}</th>
                                    <th style="padding:14px 16px;">{{ __('messages.judul') }}</th>
                                    <th style="width:160px;padding:14px 16px;text-align:center;">{{ __('messages.aksi') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pages as $page)
                                <tr>
                                    <td class="ps-4">{{ $loop->iteration + ($pages->currentPage() - 1) * $pages->perPage() }}</td>
                                    <td>
                                        @if($page->gambar)
                                            <img src="{{ asset('storage/' . $page->gambar) }}" alt="" style="height:50px;border-radius:6px;">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center rounded" style="width:40px;height:50px;background:linear-gradient(135deg,#001f3f,#003d82);">
                                                <i class="fas fa-image text-white"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="fw-semibold">{{ $page->judul }}</td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ route('admin.zi.pages.edit', ['type' => $type, 'ziPage' => $page->id]) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('admin.zi.pages.destroy', ['type' => $type, 'ziPage' => $page->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex flex-column align-items-center py-3 gap-2">
                        <div>{{ $pages->appends(['type' => $type])->links() }}</div>
                    </div>
                    @else
                    <div class="text-center text-muted py-5"><i class="fas fa-inbox fa-2x d-block mb-2 opacity-50"></i>{{ __('messages.belum_ada_data') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
