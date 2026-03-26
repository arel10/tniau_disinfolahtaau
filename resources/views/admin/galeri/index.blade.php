@extends('layouts.admin')

@section('title', 'Manajemen Galeri')
@section('page-title', 'Manajemen Galeri')

@push('styles')
<style>
    .setting-card { border:none; box-shadow:0 2px 12px rgba(0,61,130,0.10); border-radius:12px; }
    .setting-card .card-header { background:linear-gradient(135deg,#001f3f 0%,#003d82 100%); color:white; border-radius:12px 12px 0 0 !important; padding:16px 24px; }
    .setting-card .card-header h6 { color:white !important; }
    .btn-tambah { background:linear-gradient(135deg,#001f3f 0%,#0066cc 100%); color:white; font-weight:600; border:none; border-radius:8px; transition:transform 0.15s, box-shadow 0.15s; }
    .btn-tambah:hover { color:#fff; transform:translateY(-2px); box-shadow:0 6px 20px rgba(0,61,130,0.35); }
    .btn-filter { background:linear-gradient(135deg,#001f3f 0%,#0066cc 100%); color:white; font-weight:600; border:none; border-radius:8px; }
    .btn-filter:hover { color:#fff; }
    .galeri-card { border:none; box-shadow:0 2px 12px rgba(0,61,130,0.08); border-radius:12px; overflow:hidden; transition:transform 0.2s, box-shadow 0.2s; }
    .galeri-card:hover { transform:translateY(-4px); box-shadow:0 8px 24px rgba(0,61,130,0.18); }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-11 col-lg-12">

            <div class="d-flex align-items-center mb-4 gap-3">
                <div style="background:linear-gradient(135deg,#001f3f,#0066cc);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-images text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-0 fw-bold text-dark">Manajemen Galeri</h4>
                    <small class="text-muted">Kelola galeri foto dan video.</small>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.galeri.create') }}" class="btn btn-tambah"><i class="fas fa-plus me-1"></i> Tambah Galeri</a>
                    <a href="{{ route('admin.kategori-galeri.index') }}" class="btn btn-outline-secondary"><i class="fas fa-list me-1"></i> Kategori</a>
                </div>
            </div>

            <!-- Filter -->
            <div class="card setting-card mb-4">
                <div class="card-header py-2">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-filter me-2"></i>Filter Galeri</h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.galeri.index') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold" style="color:#003d82;">Cari</label>
                                <input type="text" name="search" class="form-control" placeholder="Cari galeri..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-semibold" style="color:#003d82;">{{ __('messages.tipe') }}</label>
                                <select name="tipe" class="form-select">
                                    <option value="">Semua Tipe</option>
                                    <option value="foto" {{ request('tipe') == 'foto' ? 'selected' : '' }}>{{ __('messages.foto') }}</option>
                                    <option value="video" {{ request('tipe') == 'video' ? 'selected' : '' }}>{{ __('messages.video') }}</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-semibold" style="color:#003d82;">{{ __('messages.kategori') }}</label>
                                <select name="kategori_galeri" class="form-select">
                                    <option value="">Semua Kategori</option>
                                    @foreach($kategoriGaleris as $kat)
                                        <option value="{{ $kat->slug }}" {{ request('kategori_galeri') == $kat->slug ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-filter w-100"><i class="fas fa-search me-1"></i> Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Galeri Grid (grouped by upload batch) -->
            <div class="row g-4">
                @php
                    // Group paginated items by their group_id so one upload appears as one album
                    $groups = $galeris->getCollection()->groupBy('group_id');
                @endphp

                @if($groups->count())
                    @foreach($groups as $groupId => $items)
                        @php $galeri = $items->first(); $count = $items->count(); @endphp
                        <div class="col-md-3">
                            <div class="card galeri-card h-100 position-relative">
                                @if($galeri->thumbnail_url)
                                    <img src="{{ $galeri->thumbnail_url }}" class="card-img-top" alt="{{ $galeri->judul }}" style="height:200px;object-fit:cover;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center" style="height:200px;background:linear-gradient(135deg,#001f3f,#003d82);">
                                        <i class="fas fa-image text-white fa-3x"></i>
                                    </div>
                                @endif
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge" style="background:{{ $galeri->tipe == 'foto' ? 'linear-gradient(135deg,#0066cc,#0099ff)' : 'linear-gradient(135deg,#cc0000,#ff3333)' }};">
                                        <i class="fas fa-{{ $galeri->tipe == 'foto' ? 'image' : 'video' }}"></i> {{ ucfirst($galeri->tipe) }}
                                    </span>
                                </div>
                                @if($count > 1)
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-dark">{{ $count }} item</span>
                                    </div>
                                @endif
                                <div class="card-body">
                                    @if($galeri->kategori_galeri)
                                        <span class="badge mb-2" style="background:linear-gradient(135deg,#001f3f,#003d82);">{{ $galeri->kategoriGaleriRelasi->nama_kategori ?? ucfirst($galeri->kategori_galeri) }}</span>
                                    @endif
                                    @if($galeri->judul && !Str::startsWith($galeri->judul, 'WhatsApp'))
                                    <h6 class="card-title fw-semibold">{{ Str::limit($galeri->judul, 40) }}</h6>
                                    @endif
                                    <p class="text-muted small mb-3">
                                        <i class="fas fa-calendar"></i> {{ $galeri->tanggal_kegiatan ? $galeri->tanggal_kegiatan->format('d M Y') : '-' }}
                                    </p>
                                    <div class="d-flex gap-1 w-100">
                                        <a href="{{ route('admin.galeri.edit', $galeri->id) }}" class="btn btn-outline-warning btn-sm flex-fill"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admin.galeri.destroy', $galeri->id) }}" method="POST" class="flex-fill" onsubmit="return confirm('Yakin ingin menghapus galeri ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm w-100"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="card setting-card"><div class="card-body text-center text-muted py-5"><i class="fas fa-inbox fa-3x d-block mb-3 opacity-50"></i>Belum ada galeri.</div></div>
                    </div>
                @endif
            </div>

            @if($galeris->hasPages())
            <div class="d-flex flex-column align-items-center mt-4 gap-2">
                <div>{{ $galeris->links() }}</div>
                <span class="pagination-info">Menampilkan {{ $galeris->firstItem() }} - {{ $galeris->lastItem() }} dari {{ $galeris->total() }} item</span>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
