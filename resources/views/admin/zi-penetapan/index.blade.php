@extends('layouts.admin')
@section('title', 'Zona Integritas - Penetapan')
@section('page-title', __('messages.admin_penetapan'))

@push('styles')
<style>
    .setting-card { border:none; box-shadow:0 2px 12px rgba(0,61,130,0.10); border-radius:12px; }
    .setting-card .card-header { background:linear-gradient(135deg,#001f3f 0%,#003d82 100%); color:white; border-radius:12px 12px 0 0 !important; padding:16px 24px; }
    .setting-card .card-header h6 { color:white !important; }
    .setting-card .card-header .nav-link { color:rgba(255,255,255,0.7); border:none; padding:6px 14px; border-radius:6px; font-weight:500; }
    .setting-card .card-header .nav-link.active { color:white; background:rgba(255,255,255,0.18); font-weight:700; }
    .setting-card .card-header .nav-link:hover { color:white; }
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
                    <i class="fas fa-check-circle text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-0 fw-bold text-dark">Penetapan ZI</h4>
                    <small class="text-muted">Kelola item penetapan Zona Integritas.</small>
                </div>
                <a href="{{ route('admin.zi.penetapan.create') }}" class="btn btn-tambah"><i class="fas fa-plus me-1"></i> Tambah</a>
            </div>

            <div class="card setting-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <ul class="nav nav-pills mb-0 gap-1">
                        <li class="nav-item"><a class="nav-link active" href="#">Penetapan</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.zi.penetapan.kategori.index') }}">{{ __('messages.kategori') }}</a></li>
                    </ul>
                </div>
                <div class="card-body p-0">
                    @if($items->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr style="background:linear-gradient(135deg,#001f3f,#003d82);color:white;">
                                    <th style="width:50px;padding:14px 20px;">#</th>
                                    <th style="padding:14px 16px;">{{ __('messages.foto') }}</th>
                                    <th style="padding:14px 16px;">{{ __('messages.judul') }}</th>
                                    <th style="padding:14px 16px;">{{ __('messages.kategori') }}</th>
                                    <th style="padding:14px 16px;">Persen</th>
                                    <th style="width:160px;padding:14px 16px;text-align:center;">{{ __('messages.aksi') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                <tr>
                                    <td class="ps-4">{{ $loop->iteration + ($items->currentPage() - 1) * $items->perPage() }}</td>
                                    <td>
                                        @if($item->foto)
                                            <img src="{{ asset('storage/' . $item->foto) }}" alt="" style="height:50px;border-radius:6px;">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center rounded" style="width:40px;height:50px;background:linear-gradient(135deg,#001f3f,#003d82);">
                                                <i class="fas fa-image text-white"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="fw-semibold">{{ $item->judul }}</td>
                                    <td>{{ $item->kategori ? $item->kategori->nama : '-' }}</td>
                                    <td>{{ $item->persen !== null ? $item->persen . '%' : '-' }}</td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ route('admin.zi.penetapan.edit', $item->id) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('admin.zi.penetapan.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus item ini?')">
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
                        <div>{{ $items->appends(['group' => $group])->links() }}</div>
                    </div>
                    @else
                    <div class="text-center text-muted py-5"><i class="fas fa-inbox fa-2x d-block mb-2 opacity-50"></i>Belum ada item penetapan.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
