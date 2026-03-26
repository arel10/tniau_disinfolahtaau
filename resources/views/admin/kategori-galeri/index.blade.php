@extends('layouts.admin')

@section('title', 'Manajemen Kategori Galeri')
@section('page-title', 'Manajemen Kategori Galeri')

@push('styles')
<style>
    .setting-card { border:none; box-shadow:0 2px 12px rgba(0,61,130,0.10); border-radius:12px; }
    .setting-card .card-header { background:linear-gradient(135deg,#001f3f 0%,#003d82 100%); color:white; border-radius:12px 12px 0 0 !important; padding:16px 24px; }
    .setting-card .card-header h6 { color:white !important; }
    .btn-tambah { background:linear-gradient(135deg,#001f3f 0%,#0066cc 100%); color:white; font-weight:600; border:none; border-radius:8px; transition:transform 0.15s, box-shadow 0.15s; }
    .btn-tambah:hover { color:#fff; transform:translateY(-2px); box-shadow:0 6px 20px rgba(0,61,130,0.35); }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">

            <div class="d-flex align-items-center mb-4 gap-3">
                <div style="background:linear-gradient(135deg,#001f3f,#0066cc);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-folder text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-0 fw-bold text-dark">Manajemen Kategori Galeri</h4>
                    <small class="text-muted">Kelola kategori galeri foto dan video.</small>
                </div>
                <a href="{{ route('admin.kategori-galeri.create') }}" class="btn btn-tambah">
                    <i class="fas fa-plus me-1"></i> Tambah Kategori
                </a>
                <a href="{{ route('admin.galeri.index') }}" title="Kembali"
                   style="width:36px;height:36px;border-radius:50%;background:#e9ecef;border:none;color:#555;display:flex;align-items:center;justify-content:center;font-size:1.1rem;text-decoration:none;transition:background 0.2s;flex-shrink:0;"
                   onmouseover="this.style.background='#d0d7e2'" onmouseout="this.style.background='#e9ecef'">
                    <i class="fas fa-times"></i>
                </a>
            </div>

            <div class="card setting-card">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-folder-open me-2"></i>Daftar Kategori Galeri</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr style="background:linear-gradient(135deg,#001f3f,#003d82);color:white;">
                                    <th style="width:5%;padding:14px 20px;">{{ __('messages.no') }}</th>
                                    <th style="padding:14px 16px;">{{ __('messages.form_nama_kategori') }}</th>
                                    <th style="padding:14px 16px;">{{ __('messages.slug') }}</th>
                                    <th style="padding:14px 16px;">{{ __('messages.status') }}</th>
                                    <th style="padding:14px 16px;">Jumlah Galeri</th>
                                    <th style="padding:14px 16px;">Tanggal Dibuat</th>
                                    <th style="width:15%;padding:14px 16px;text-align:center;">{{ __('messages.aksi') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kategoriGaleris as $index => $kategori)
                                <tr>
                                    <td class="ps-4">{{ $kategoriGaleris->firstItem() + $index }}</td>
                                    <td class="fw-semibold">{{ $kategori->nama_kategori }}</td>
                                    <td><code>{{ $kategori->slug }}</code></td>
                                    <td>
                                        @if($kategori->status)
                                            <span class="badge bg-success">{{ __('messages.aktif') }}</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td><span class="badge" style="background:linear-gradient(135deg,#001f3f,#003d82);">{{ $kategori->galeris_count }}</span></td>
                                    <td>{{ $kategori->created_at->format('d M Y') }}</td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ route('admin.kategori-galeri.edit', $kategori->id) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('admin.kategori-galeri.destroy', $kategori->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus" {{ $kategori->galeris_count > 0 ? 'disabled' : '' }}><i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="7" class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x d-block mb-2 opacity-50"></i>Belum ada kategori galeri.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($kategoriGaleris->hasPages())
                    <div class="d-flex flex-column align-items-center py-3 gap-2">
                        <div>{{ $kategoriGaleris->links() }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
