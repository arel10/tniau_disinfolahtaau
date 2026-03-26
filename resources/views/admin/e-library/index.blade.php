@extends('layouts.admin')
@section('title', 'e-Library Admin')
@section('page-title', 'e-Library Admin')

@push('styles')
<style>
    .setting-card { border:none; box-shadow:0 2px 12px rgba(0,61,130,0.10); border-radius:12px; }
    .setting-card .card-header { background:linear-gradient(135deg,#001f3f 0%,#003d82 100%); color:white; border-radius:12px 12px 0 0 !important; padding:16px 24px; }
    .setting-card .card-header h6 { color:white !important; }
    .btn-tambah { background:linear-gradient(135deg,#001f3f 0%,#0066cc 100%); color:white; font-weight:600; border:none; border-radius:8px; transition:transform 0.15s, box-shadow 0.15s; }
    .btn-tambah:hover { color:#fff; transform:translateY(-2px); box-shadow:0 6px 20px rgba(0,61,130,0.35); }
    .file-icon-box { width:45px; height:60px; background:linear-gradient(135deg,#001f3f,#003d82); border-radius:8px; display:flex; align-items:center; justify-content:center; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">

            <div class="d-flex align-items-center mb-4 gap-3">
                <div style="background:linear-gradient(135deg,#001f3f,#0066cc);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-book text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-0 fw-bold text-dark">e-Library Admin</h4>
                    <small class="text-muted">Kelola dokumen perpustakaan digital.</small>
                </div>
                <a href="{{ route('admin.e-library.create') }}" class="btn btn-tambah">
                    <i class="fas fa-plus me-1"></i> Tambah
                </a>
            </div>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:10px; border-left:4px solid #198754;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="card setting-card">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-file-alt me-2"></i>Daftar Dokumen</h6>
                </div>
                <div class="card-body p-0">
                    @if($documents->count())
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead>
                                <tr style="background:linear-gradient(135deg,#001f3f,#003d82);color:white;">
                                    <th style="width:50px;padding:14px 20px;">#</th>
                                    <th style="width:80px;padding:14px 16px;">Preview</th>
                                    <th style="padding:14px 16px;">{{ __('messages.file') }}</th>
                                    <th style="padding:14px 16px;">{{ __('messages.status') }}</th>
                                    <th style="width:180px;padding:14px 16px;text-align:center;">{{ __('messages.aksi') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $doc)
                                <tr>
                                    <td class="text-muted ps-4">{{ $loop->iteration }}</td>
                                    <td>
                                        @if($doc->cover_path)
                                            <img src="{{ asset('storage/'.$doc->cover_path) }}" alt="cover" style="width:45px;height:60px;object-fit:cover;border-radius:8px;">
                                        @else
                                            <div class="file-icon-box">
                                                @php $ext = strtolower(pathinfo($doc->pdf_path, PATHINFO_EXTENSION)); @endphp
                                                @if($ext === 'pdf')
                                                    <i class="fas fa-file-pdf text-white"></i>
                                                @elseif(in_array($ext, ['doc','docx']))
                                                    <i class="fas fa-file-word text-white"></i>
                                                @elseif(in_array($ext, ['xls','xlsx']))
                                                    <i class="fas fa-file-excel text-white"></i>
                                                @elseif(in_array($ext, ['ppt','pptx']))
                                                    <i class="fas fa-file-powerpoint text-white"></i>
                                                @elseif(in_array($ext, ['jpg','jpeg','png','gif','webp']))
                                                    <i class="fas fa-file-image text-white"></i>
                                                @elseif(in_array($ext, ['zip','rar','7z']))
                                                    <i class="fas fa-file-archive text-white"></i>
                                                @else
                                                    <i class="fas fa-file text-white"></i>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $doc->title }}</div>
                                        <span class="badge bg-secondary text-uppercase">{{ strtoupper(pathinfo($doc->pdf_path, PATHINFO_EXTENSION)) }}</span>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.e-library.toggle-publish', $doc->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @if($doc->status === 'published')
                                                <button class="btn btn-sm btn-success">Published</button>
                                            @elseif($doc->status === 'private')
                                                <button class="btn btn-sm btn-warning text-dark">Private</button>
                                            @else
                                                <button class="btn btn-sm btn-secondary">{{ __('messages.draft') }}</button>
                                            @endif
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('admin.e-library.edit', $doc->id) }}" class="btn btn-sm btn-outline-primary" style="white-space:nowrap;" title="Edit">
                                                <i class="fas fa-edit me-1"></i>Edit
                                            </a>
                                            <form action="{{ route('admin.e-library.destroy', $doc->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus dokumen ini?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-inbox fa-2x d-block mb-2 opacity-50"></i>
                        Belum ada dokumen.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
