@extends('layouts.admin')

@section('title', 'Whistle Blowing System')
@section('page-title', 'Whistle Blowing System')

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
                    <i class="fas fa-bullhorn text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-0 fw-bold text-dark">Whistle Blowing System</h4>
                    <small class="text-muted">Kelola data whistle blowing.</small>
                </div>
                <a href="{{ route('admin.whistle-blowing.create') }}" class="btn btn-tambah"><i class="fas fa-plus me-1"></i> Tambah Data</a>
            </div>

            <div class="card setting-card">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-list me-2"></i>Daftar Whistle Blowing</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr style="background:linear-gradient(135deg,#001f3f,#003d82);color:white;">
                                    <th style="width:50px;padding:14px 20px;">#</th>
                                    <th style="padding:14px 16px;">{{ __('messages.judul') }}</th>
                                    <th style="padding:14px 16px;">{{ __('messages.gambar') }}</th>
                                    <th style="padding:14px 16px;">Link Tujuan</th>
                                    <th style="padding:14px 16px;">{{ __('messages.aktif') }}</th>
                                    <th style="width:140px;padding:14px 16px;text-align:center;">{{ __('messages.aksi') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($settings as $item)
                                <tr>
                                    <td class="ps-4">{{ $loop->iteration }}</td>
                                    <td class="fw-semibold">{{ $item->judul ?? '-' }}</td>
                                    <td>
                                        @if($item->gambar)
                                            @if(str_starts_with($item->gambar, 'whistle-blowing/'))
                                                <img src="{{ asset('storage/' . $item->gambar) }}" alt="Gambar" style="max-width:60px;max-height:60px;" class="rounded">
                                            @else
                                                <img src="{{ asset($item->gambar) }}" alt="Gambar" style="max-width:60px;max-height:60px;" class="rounded">
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td><a href="{{ $item->link_tujuan }}" target="_blank" style="color:#003d82;">{{ Str::limit($item->link_tujuan, 40) }}</a></td>
                                    <td>
                                        @if($item->is_active)
                                            <span class="badge bg-success">{{ __('messages.aktif') }}</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ route('admin.whistle-blowing.edit', $item) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('admin.whistle-blowing.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x d-block mb-2 opacity-50"></i>Belum ada data Whistle Blowing.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
