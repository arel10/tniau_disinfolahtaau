@extends('layouts.admin')
@section('title', __('messages.admin_kelola') . ' ' . __('messages.admin_pia')))
@section('page-title', 'Kelola Halaman PIA')

@push('styles')
<style>
    .setting-card { border:none; box-shadow:0 2px 12px rgba(0,61,130,0.10); border-radius:12px; }
    .setting-card .card-header { background:linear-gradient(135deg,#001f3f 0%,#003d82 100%); color:white; border-radius:12px 12px 0 0 !important; padding:16px 24px; }
    .setting-card .card-header h6 { color:white !important; }
    .form-label { font-weight:600; color:#003d82; }
    .btn-simpan { background:linear-gradient(135deg,#001f3f 0%,#0066cc 100%); color:white; font-weight:700; padding:10px 36px; border-radius:8px; font-size:1rem; border:none; transition:transform 0.15s, box-shadow 0.15s; }
    .btn-simpan:hover { color:#fff; transform:translateY(-2px); box-shadow:0 6px 20px rgba(0,61,130,0.35); }
    .btn-tambah-sm { background:linear-gradient(135deg,#001f3f 0%,#0066cc 100%); color:white; font-weight:600; border:none; border-radius:8px; }
    .btn-tambah-sm:hover { color:#fff; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">

            <div class="d-flex align-items-center mb-4 gap-3">
                <div style="background:linear-gradient(135deg,#001f3f,#0066cc);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-star text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-0 fw-bold text-dark">Kelola Halaman PIA</h4>
                    <small class="text-muted">Kelola sejarah dan logo items PIA.</small>
                </div>
            </div>

{{-- ==================== SECTION 1: SEJARAH ==================== --}}
<div class="card setting-card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><i class="fas fa-history me-2"></i>Sejarah PIA</h6>
        <a href="{{ route('admin.pia.history.revisions') }}" class="btn btn-sm" style="background:rgba(255,255,255,0.15);color:white;border:1px solid rgba(255,255,255,0.3);border-radius:6px;">
            <i class="fas fa-clock-rotate-left me-1"></i> Riwayat Revisi
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.pia.history.update') }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Judul Sejarah</label>
                <input type="text" name="history_title" class="form-control @error('history_title') is-invalid @enderror"
                       value="{{ old('history_title', $page->history_title) }}" placeholder="Contoh: PIA Ardhya Garini">
                @error('history_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Konten Sejarah</label>
                <textarea name="history_content" class="form-control @error('history_content') is-invalid @enderror"
                          rows="6" placeholder="Tulis konten sejarah PIA...">{{ old('history_content', $page->history_content) }}</textarea>
                @error('history_content') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-simpan">Simpan Sejarah</button>
            </div>
        </form>

        @if($page->history_title || $page->history_content)
        <hr>
        <form action="{{ route('admin.pia.history.destroy') }}" method="POST" onsubmit="return confirm('Hapus sejarah? Data lama tetap tersimpan di riwayat revisi.')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm">
                <i class="fas fa-trash me-1"></i> Hapus Sejarah
            </button>
        </form>
        @endif
    </div>
</div>

{{-- ==================== SECTION 2: LOGO ITEMS ==================== --}}
<div class="card setting-card mb-4">
    <div class="card-header">
        <h6 class="mb-0 fw-bold"><i class="fas fa-images me-2"></i>Logo Items</h6>
    </div>
    <div class="card-body">

        {{-- Form Tambah Logo Item --}}
        <div class="border rounded p-3 mb-4" style="background:rgba(0,61,130,0.03);">
            <h6 class="fw-bold mb-3" style="color:#003d82;">Tambah Logo Item Baru</h6>
            <form action="{{ route('admin.pia.logo-items.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ __('messages.judul') }}</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}" placeholder="Contoh: PIA Ardhya Garini" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Link URL</label>
                        <input type="url" name="link_url" class="form-control @error('link_url') is-invalid @enderror"
                               value="{{ old('link_url') }}" placeholder="https://...">
                        @error('link_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Logo (png/jpg/webp)</label>
                        <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror"
                               accept="image/png,image/jpeg,image/webp" required>
                        @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-tambah-sm btn-sm">
                    <i class="fas fa-plus me-1"></i> Tambah Item
                </button>
            </form>
        </div>

        {{-- Tabel List Logo Items --}}
        @if($page->logoItems->count())
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr style="background:linear-gradient(135deg,#001f3f,#003d82);color:white;">
                        <th style="width:60px;padding:14px 20px;">Pos</th>
                        <th style="width:80px;padding:14px 16px;">{{ __('messages.form_logo') }}</th>
                        <th style="padding:14px 16px;">{{ __('messages.judul') }}</th>
                        <th style="padding:14px 16px;">{{ __('messages.link') }}</th>
                        <th style="width:200px;padding:14px 16px;text-align:center;">{{ __('messages.aksi') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($page->logoItems as $item)
                    <tr>
                        <td class="ps-4">{{ $item->position }}</td>
                        <td>
                            <img src="{{ asset('storage/' . $item->logo_path) }}" alt="{{ $item->title }}"
                                 style="height:50px;width:50px;object-fit:contain;border-radius:6px;border:1px solid #eee;">
                        </td>
                        <td class="fw-semibold">{{ $item->title }}</td>
                        <td>
                            <a href="{{ $item->link_url }}" target="_blank" class="text-truncate d-inline-block" style="max-width:200px;color:#003d82;">{{ $item->link_url }}</a>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}"><i class="fas fa-edit"></i></button>
                                <form action="{{ route('admin.pia.logo-items.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus logo item ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- Edit Modal --}}
                    <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('admin.pia.logo-items.update', $item) }}" method="POST" enctype="multipart/form-data">
                                    @csrf @method('PUT')
                                    <div class="modal-header" style="background:linear-gradient(135deg,#001f3f,#003d82);color:white;">
                                        <h5 class="modal-title">Edit Logo Item</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('messages.judul') }}</label>
                                            <input type="text" name="title" class="form-control" value="{{ $item->title }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Link URL</label>
                                            <input type="url" name="link_url" class="form-control" value="{{ $item->link_url }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Logo Baru (kosongkan jika tidak ganti)</label>
                                            <input type="file" name="logo" class="form-control" accept="image/png,image/jpeg,image/webp">
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $item->logo_path) }}" alt="" style="height:60px;object-fit:contain;">
                                                <small class="text-muted d-block">Logo saat ini</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.batal') }}</button>
                                        <button type="submit" class="btn btn-simpan">{{ __('messages.simpan') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        @endif
    </div>
</div>

        </div>
    </div>
</div>
@endsection
