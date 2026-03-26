@extends('layouts.admin')
@section('title', __('messages.admin_events'))
@section('page-title', __('messages.admin_events'))

@push('styles')
<style>
    .setting-card { border:none; box-shadow:0 2px 12px rgba(0,61,130,0.10); border-radius:12px; }
    .setting-card .card-header { background:linear-gradient(135deg,#001f3f 0%,#003d82 100%); color:white; border-radius:12px 12px 0 0 !important; padding:16px 24px; }
    .setting-card .card-header h6 { color:white !important; }
    .btn-tambah { background:linear-gradient(135deg,#001f3f 0%,#0066cc 100%); color:white; font-weight:600; border:none; border-radius:8px; transition:transform 0.15s, box-shadow 0.15s; }
    .btn-tambah:hover { color:#fff; transform:translateY(-2px); box-shadow:0 6px 20px rgba(0,61,130,0.35); }
    .event-card { border:none; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,0.08); transition:transform 0.2s, box-shadow 0.2s; overflow:hidden; }
    .event-card:hover { transform:translateY(-4px); box-shadow:0 8px 30px rgba(0,61,130,0.18); }
    .event-cover { height:180px; object-fit:cover; width:100%; }
    .event-cover-placeholder { height:180px; background:linear-gradient(135deg,#001f3f,#003d82); display:flex; align-items:center; justify-content:center; }
    .badge-published { background:linear-gradient(135deg,#198754,#20c997); }
    .badge-draft { background:linear-gradient(135deg,#6c757d,#adb5bd); }
    .media-count { display:inline-flex; align-items:center; gap:4px; font-size:0.82rem; color:#6c757d; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">

            <div class="d-flex align-items-center mb-4 gap-3">
                <div style="background:linear-gradient(135deg,#001f3f,#0066cc);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-calendar-alt text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-0 fw-bold text-dark">Events</h4>
                    <small class="text-muted">Kelola kegiatan dan dokumentasi event.</small>
                </div>
                <a href="{{ route('admin.events.create') }}" class="btn btn-tambah">
                    <i class="fas fa-plus me-1"></i> Tambah Event
                </a>
            </div>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:10px; border-left:4px solid #198754;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if($events->count())
            <div class="row g-4">
                @foreach($events as $event)
                <div class="col-md-6 col-lg-4">
                    <div class="card event-card h-100">
                        @if($event->cover_image)
                            <img src="{{ asset('storage/' . $event->cover_image) }}" class="event-cover" alt="{{ $event->nama_kegiatan }}">
                        @else
                            <div class="event-cover-placeholder">
                                <i class="fas fa-calendar-alt text-white" style="font-size:3rem;opacity:0.4;"></i>
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-start justify-content-between mb-2">
                                <h6 class="fw-bold mb-0" style="line-height:1.4;">{{ $event->nama_kegiatan }}</h6>
                                <span class="badge {{ $event->is_published ? 'badge-published' : 'badge-draft' }} ms-2" style="white-space:nowrap;">
                                    {{ $event->is_published ? 'Publik' : 'Draft' }}
                                </span>
                            </div>

                            @if($event->tanggal_kegiatan)
                            <div class="mb-2" style="font-size:0.82rem;color:#6c757d;">
                                <i class="fas fa-calendar fa-sm me-1"></i>{{ $event->tanggal_kegiatan->format('d M Y') }}
                            </div>
                            @endif

                            @if($event->deskripsi)
                            <p class="text-muted small mb-2" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">{{ $event->deskripsi }}</p>
                            @endif

                            <div class="mt-auto d-flex align-items-center justify-content-between pt-2" style="border-top:1px solid #f0f0f0;">
                                <div class="d-flex gap-3">
                                    <span class="media-count"><i class="fas fa-image"></i> {{ $event->fotos_count ?? $event->media->where('type','foto')->count() }}</span>
                                    <span class="media-count"><i class="fas fa-video"></i> {{ $event->videos_count ?? $event->media->where('type','video')->count() }}</span>
                                </div>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.events.show', $event) }}" class="btn btn-sm btn-outline-primary" title="Lihat"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST" onsubmit="return confirm('Hapus event ini beserta semua media?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="card setting-card">
                <div class="card-body py-5 text-center">
                    <i class="fas fa-calendar-plus fa-3x mb-3" style="color:#003d82;opacity:0.3;"></i>
                    <p class="text-muted mb-3">Belum ada event. Mulai tambahkan kegiatan pertama.</p>
                    <a href="{{ route('admin.events.create') }}" class="btn btn-tambah">
                        <i class="fas fa-plus me-1"></i> Tambah Event Pertama
                    </a>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
