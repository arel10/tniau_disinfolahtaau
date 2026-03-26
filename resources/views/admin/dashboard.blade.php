@extends('layouts.admin')

@section('title', __('messages.admin_dashboard'))
@section('page-title', __('messages.admin_dashboard'))

@section('content')
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="text-muted"><i class="fas fa-newspaper"></i> Total Berita</h6>
                <h3>{{ $total_berita ?? 0 }}</h3>
                <small class="text-success">{{ $berita_published ?? 0 }} Published</small> |
                <small class="text-warning">{{ $berita_draft ?? 0 }} Draft</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="text-muted"><i class="fas fa-images"></i> Total Galeri</h6>
                <h3>{{ $total_galeri ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="text-muted"><i class="fas fa-envelope"></i> Pesan Kontak</h6>
                <h3>{{ $total_pesan ?? 0 }}</h3>
                <small class="text-danger">{{ $pesan_baru ?? 0 }} Baru</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="text-muted"><i class="fas fa-users"></i> Total Admin</h6>
                <h3>{{ $total_admin ?? 0 }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-7 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-newspaper"></i> Berita Terbaru</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('messages.judul') }}</th>
                                <th>{{ __('messages.kategori') }}</th>
                                <th>{{ __('messages.status') }}</th>
                                <th>{{ __('messages.tanggal') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($berita_terbaru ?? [] as $berita)
                            <tr>
                                <td>{{ Str::limit($berita->judul, 40) }}</td>
                                <td>{{ $berita->kategori->nama ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $berita->status === 'published' ? 'success' : 'warning' }}">
                                        {{ ucfirst($berita->status) }}
                                    </span>
                                </td>
                                <td>{{ $berita->created_at->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted">Belum ada berita</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-envelope"></i> Pesan Terbaru</h5>
            </div>
            <div class="card-body">
                @forelse($pesan_terbaru ?? [] as $pesan)
                <div class="d-flex justify-content-between align-items-start border-bottom pb-2 mb-2">
                    <div>
                        <strong>{{ $pesan->nama }}</strong>
                        <p class="mb-0 text-muted small">{{ Str::limit($pesan->pesan, 60) }}</p>
                    </div>
                    <small class="text-muted">{{ $pesan->created_at->diffForHumans() }}</small>
                </div>
                @empty
                <p class="text-center text-muted">Belum ada pesan baru</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
