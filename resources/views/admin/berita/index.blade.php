@extends('layouts.admin')

@section('title', 'Manajemen Berita')
@section('page-title', 'Manajemen Berita')

@push('styles')
<style>
    .setting-card { border:none; box-shadow:0 2px 12px rgba(0,61,130,0.10); border-radius:12px; }
    .setting-card .card-header { background:linear-gradient(135deg,#001f3f 0%,#003d82 100%); color:white; border-radius:12px 12px 0 0 !important; padding:16px 24px; }
    .setting-card .card-header h6 { color:white !important; }
    .btn-tambah { background:linear-gradient(135deg,#001f3f 0%,#0066cc 100%); color:white; font-weight:600; border:none; border-radius:8px; transition:transform 0.15s, box-shadow 0.15s; }
    .btn-tambah:hover { color:#fff; transform:translateY(-2px); box-shadow:0 6px 20px rgba(0,61,130,0.35); }
    .btn-filter { background:linear-gradient(135deg,#001f3f 0%,#0066cc 100%); color:white; font-weight:600; border:none; border-radius:8px; }
    .btn-filter:hover { color:#fff; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-11 col-lg-12">

            <div class="d-flex align-items-center mb-4 gap-3">
                <div style="background:linear-gradient(135deg,#001f3f,#0066cc);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-newspaper text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-0 fw-bold text-dark">Manajemen Berita</h4>
                    <small class="text-muted">Kelola semua berita dan artikel.</small>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.berita.create') }}" class="btn btn-tambah"><i class="fas fa-plus me-1"></i> Tambah Berita</a>
                    <a href="/admin/kategori" class="btn btn-outline-secondary"><i class="fas fa-list me-1"></i> Kategori</a>
                </div>
            </div>

            <!-- Filter -->
            <div class="card setting-card mb-4">
                <div class="card-header py-2">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-filter me-2"></i>Filter Berita</h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.berita.index') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold" style="color:#003d82;">Cari</label>
                                <input type="text" name="search" class="form-control" placeholder="Cari berita..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-semibold" style="color:#003d82;">{{ __('messages.kategori') }}</label>
                                <select name="kategori_id" class="form-select">
                                    <option value="">Semua Kategori</option>
                                    @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-semibold" style="color:#003d82;">{{ __('messages.status') }}</label>
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>{{ __('messages.draft') }}</option>
                                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-filter w-100"><i class="fas fa-search me-1"></i> Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Berita Table -->
            <div class="card setting-card">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-newspaper me-2"></i>Daftar Berita</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr style="background:linear-gradient(135deg,#001f3f,#003d82);color:white;">
                                    <th style="padding:14px 20px;">{{ __('messages.gambar') }}</th>
                                    <th style="padding:14px 16px;">{{ __('messages.judul') }}</th>
                                    <th style="padding:14px 16px;">{{ __('messages.kategori') }}</th>
                                    <th style="padding:14px 16px;">{{ __('messages.penulis') }}</th>
                                    <th style="padding:14px 16px;">{{ __('messages.status') }}</th>
                                    <th style="padding:14px 16px;">{{ __('messages.tanggal') }}</th>
                                    <th style="padding:14px 16px;text-align:center;">{{ __('messages.aksi') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($beritas as $berita)
                                <tr>
                                    <td class="ps-4">
                                        @if($berita->gambar_utama)
                                            <img src="{{ asset('storage/' . $berita->gambar_utama) }}" alt="{{ $berita->judul }}" style="width:60px;height:60px;object-fit:cover;" class="rounded">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center rounded" style="width:60px;height:60px;background:linear-gradient(135deg,#001f3f,#003d82);">
                                                <i class="fas fa-image text-white"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="fw-semibold">{{ Str::limit($berita->judul, 40) }}</td>
                                    <td><span class="badge" style="background:linear-gradient(135deg,#001f3f,#003d82);">{{ $berita->kategori->nama_kategori }}</span></td>
                                    <td>{{ $berita->user->name }}</td>
                                    <td>
                                        @if($berita->status == 'published')
                                            <span class="badge bg-success">Published</span>
                                        @else
                                            <span class="badge bg-warning">{{ __('messages.draft') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $berita->created_at->format('d M Y') }}</td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ route('admin.berita.show', ['berita' => $berita->id]) }}" class="btn btn-sm btn-outline-info" title="Lihat"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('admin.berita.edit', ['berita' => $berita->id]) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="confirmDelete({{ $berita->id }})"><i class="fas fa-trash"></i></button>
                                        </div>
                                        <form id="delete-form-{{ $berita->id }}" action="{{ route('admin.berita.destroy', ['berita' => $berita->id]) }}" method="POST" style="display:none;">@csrf @method('DELETE')</form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="7" class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x d-block mb-2 opacity-50"></i>Belum ada berita.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($beritas->hasPages())
                    <div class="d-flex flex-column align-items-center py-3 gap-2">
                        <div>{{ $beritas->links() }}</div>
                        <span class="pagination-info">Menampilkan {{ $beritas->firstItem() }} - {{ $beritas->lastItem() }} dari {{ $beritas->total() }} berita</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(id) {
    if (confirm('Yakin ingin menghapus berita ini?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush
@endsection
