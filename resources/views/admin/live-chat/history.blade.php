@extends('layouts.admin')
@section('page-title', 'Histori Live Chat')

@push('styles')
<style>
    .history-card { border:none; box-shadow:0 2px 12px rgba(0,61,130,0.10); border-radius:12px; }
    .history-card .card-header { background:linear-gradient(135deg,#001f3f 0%,#003d82 100%); color:white; border-radius:12px 12px 0 0 !important; padding:16px 24px; }
    .history-row { border-bottom:1px solid #f0f0f0; padding:14px 20px; }
    .history-row:last-child { border-bottom:none; }
    .history-time { font-size:12px; color:#999; }
    .closed-by-badge { font-size:11px; padding:2px 8px; border-radius:10px; }
    .cb-admin { background:#dbeafe; color:#1e40af; }
    .cb-visitor { background:#d1fae5; color:#065f46; }
    .msg-preview { max-height:260px; overflow-y:auto; font-size:13px; }
    .msg-item { padding:6px 10px; border-radius:8px; margin-bottom:5px; }
    .msg-item.visitor { background:#e8f0fe; }
    .msg-item.admin { background:#f3f4f6; }
    .msg-item.system { background:#fef3c7; font-style:italic; color:#92400e; font-size:12px; }
    .msg-sender { font-weight:600; font-size:11px; margin-bottom:2px; }
    .msg-ts { font-size:10px; color:#999; float:right; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">

            {{-- Header --}}
            <div class="d-flex align-items-center mb-4 gap-3">
                <div style="background:linear-gradient(135deg,#6366f1,#4f46e5);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-history text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-0 fw-bold text-dark">Histori Live Chat</h4>
                    <small class="text-muted">Rekaman percakapan yang telah diakhiri. Histori lebih dari 1 bulan dihapus otomatis.</small>
                </div>
                <a href="{{ route('admin.live-chat.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Chat
                </a>
            </div>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="card history-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-archive me-2"></i>Arsip Percakapan</h6>
                    <span class="badge bg-light text-dark">{{ $histories->total() }} rekaman</span>
                </div>
                <div class="card-body p-0">
                    @forelse($histories as $h)
                    <div class="history-row">
                        <div class="d-flex align-items-start justify-content-between gap-3">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="fw-bold">{{ $h->display_name }}</span>
                                    @if($h->visitor_email)
                                        <span class="text-muted small"><i class="fas fa-envelope me-1"></i>{{ $h->visitor_email }}</span>
                                    @endif
                                    <span class="closed-by-badge {{ $h->closed_by === 'admin' ? 'cb-admin' : 'cb-visitor' }}">
                                        <i class="fas fa-{{ $h->closed_by === 'admin' ? 'user-cog' : 'user' }} me-1"></i>
                                        Diakhiri oleh {{ $h->closed_by === 'admin' ? 'Admin' : 'Pengunjung' }}
                                    </span>
                                </div>
                                <div class="history-time mb-2">
                                    <i class="fas fa-clock me-1"></i>{{ $h->closed_at ? $h->closed_at->translatedFormat('d M Y, H:i') : $h->created_at->translatedFormat('d M Y, H:i') }}
                                    &nbsp;&middot;&nbsp; {{ $h->message_count }} pesan
                                    @if($h->visitor_ip)
                                    &nbsp;&middot;&nbsp; <i class="fas fa-globe me-1"></i>{{ $h->visitor_ip }}
                                    @endif
                                </div>

                                {{-- Message preview (collapsed) --}}
                                <div class="collapse" id="history-{{ $h->id }}">
                                    <div class="msg-preview border rounded p-2 bg-light">
                                        @foreach($h->messages as $msg)
                                        <div class="msg-item {{ $msg['sender_type'] }}">
                                            <div class="d-flex justify-content-between">
                                                <span class="msg-sender">{{ $msg['sender_name'] }}</span>
                                                <span class="msg-ts">{{ $msg['time'] }}</span>
                                            </div>
                                            <div>{{ $msg['message'] }}</div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-2 flex-shrink-0 align-items-start mt-1">
                                <button class="btn btn-sm btn-outline-primary" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#history-{{ $h->id }}"
                                    aria-expanded="false">
                                    <i class="fas fa-eye me-1"></i> Lihat
                                </button>
                                <form action="{{ route('admin.live-chat.destroy-history', $h->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus rekaman chat ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3 d-block" style="color:#dee2e6;"></i>
                        <h5>Belum ada histori chat</h5>
                        <p class="mb-0">Rekaman chat akan muncul di sini setelah percakapan diakhiri.</p>
                    </div>
                    @endforelse

                    @if($histories->hasPages())
                    <div class="p-3">{{ $histories->links() }}</div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
