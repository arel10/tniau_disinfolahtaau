@extends('layouts.admin')
@section('title', 'Kategori Penetapan')
@section('page-title', 'Daftar Kategori Penetapan')

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
        <div class="col-xl-9 col-lg-11">

            <div class="d-flex align-items-center mb-4 gap-3">
                <div style="background:linear-gradient(135deg,#001f3f,#0066cc);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-folder-open text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-0 fw-bold text-dark">Kategori Penetapan</h4>
                    <small class="text-muted">Kelola kategori untuk item penetapan.</small>
                </div>
                <a href="{{ route('admin.zi.penetapan.kategori.create') }}" class="btn btn-tambah"><i class="fas fa-plus me-1"></i> Tambah</a>
                <a href="{{ route('admin.zi.penetapan.index') }}" title="Kembali"
                   style="width:36px;height:36px;border-radius:50%;background:#e9ecef;border:none;color:#555;display:flex;align-items:center;justify-content:center;font-size:1.1rem;text-decoration:none;transition:background 0.2s;flex-shrink:0;"
                   onmouseover="this.style.background='#d0d7e2'" onmouseout="this.style.background='#e9ecef'">
                    <i class="fas fa-times"></i>
                </a>
            </div>

            <div class="card setting-card">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-tags me-2"></i>Daftar Kategori</h6>
                </div>
                <div class="card-body p-0">
                    @if($kategoris->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr style="background:linear-gradient(135deg,#001f3f,#003d82);color:white;">
                                    <th style="width:50px;padding:14px 20px;">#</th>
                                    <th style="padding:14px 16px;">{{ __('messages.form_nama_kategori') }}</th>
                                    <th style="padding:14px 16px;">{{ __('messages.slug') }}</th>
                                    <th style="width:160px;padding:14px 16px;text-align:center;">{{ __('messages.aksi') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kategoris as $kategori)
                                <tr>
                                    <td class="ps-4">{{ $loop->iteration + ($kategoris->currentPage() - 1) * $kategoris->perPage() }}</td>
                                    <td class="fw-semibold">{{ $kategori->nama }}</td>
                                    <td><code>{{ $kategori->slug }}</code></td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ route('admin.zi.penetapan.kategori.edit', $kategori) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('admin.zi.penetapan.kategori.destroy', $kategori) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
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
                    <div class="d-flex justify-content-center py-3">{{ $kategoris->links() }}</div>
                    @else
                    <div class="text-center text-muted py-5"><i class="fas fa-inbox fa-2x d-block mb-2 opacity-50"></i>Belum ada kategori penetapan.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
