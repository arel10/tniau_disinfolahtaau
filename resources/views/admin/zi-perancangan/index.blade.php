@extends('layouts.admin')
@section('title', 'Zona Integritas - Perancangan')
@section('page-title', __('messages.admin_perancangan'))

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
                    <i class="fas fa-bullhorn text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-0 fw-bold text-dark">Perancangan</h4>
                    <small class="text-muted">Kelola data Perancangan Zona Integritas.</small>
                </div>
                <a href="{{ route('admin.zi.perancangan.create') }}" class="btn btn-tambah"><i class="fas fa-plus me-1"></i> Tambah</a>
            </div>

            <div class="card setting-card">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-list me-2"></i>Daftar Perancangan</h6>
                </div>
                <div class="card-body p-0">
                    @if($posts->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr style="background:linear-gradient(135deg,#001f3f,#003d82);color:white;">
                                    <th style="width:50px;padding:14px 20px;">#</th>
                                    <th style="padding:14px 16px;">{{ __('messages.judul') }}</th>
                                    <th style="padding:14px 16px;">PDF</th>
                                    <th style="padding:14px 16px;">{{ __('messages.foto') }}</th>
                                    <th style="padding:14px 16px;">{{ __('messages.tanggal') }}</th>
                                    <th style="width:160px;padding:14px 16px;text-align:center;">{{ __('messages.aksi') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($posts as $post)
                                <tr>
                                    <td class="ps-4">{{ $loop->iteration + ($posts->currentPage() - 1) * $posts->perPage() }}</td>
                                    <td class="fw-semibold">{{ $post->judul }}</td>
                                    <td>
                                        @if($post->pdf_path)
                                            <a href="{{ asset('storage/' . $post->pdf_path) }}" target="_blank" class="badge bg-danger"><i class="fas fa-file-pdf"></i> PDF</a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $post->photos->count() }} foto</td>
                                    <td>{{ $post->created_at->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ route('admin.zi.perancangan.edit', $post->getKey()) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('admin.zi.perancangan.destroy', $post->getKey()) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data ini beserta semua foto?')">
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
                        <div>{{ $posts->links() }}</div>
                    </div>
                    @else
                    <div class="text-center text-muted py-5"><i class="fas fa-inbox fa-2x d-block mb-2 opacity-50"></i>Belum ada data Perancangan.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
