@extends('layouts.admin')
@section('title', 'Edit Dokumen e-Library')
@section('page-title', 'Edit Dokumen')

@push('styles')
<style>
    .setting-card { border:none; box-shadow:0 2px 12px rgba(0,61,130,0.10); border-radius:12px; }
    .setting-card .card-header { background:linear-gradient(135deg,#001f3f 0%,#003d82 100%); color:white; border-radius:12px 12px 0 0 !important; padding:16px 24px; }
    .setting-card .card-header h6 { color:white !important; }
    .btn-simpan { background:linear-gradient(135deg,#001f3f 0%,#0066cc 100%); color:white; font-weight:600; border:none; border-radius:8px; padding:10px 32px; transition:transform 0.15s, box-shadow 0.15s; }
    .btn-simpan:hover { color:#fff; transform:translateY(-2px); box-shadow:0 6px 20px rgba(0,61,130,0.35); }
    .form-control:focus, .form-select:focus { border-color:#003d82; box-shadow:0 0 0 0.2rem rgba(0,61,130,0.15); }
    .current-cover { border-radius:10px; border:2px solid #003d82; max-width:220px; }
    .current-file-info { background:#f8f9fa; border-radius:8px; padding:12px 16px; border:1px solid #e9ecef; }
    #preview-cover { max-width:220px; }
    #preview-cover img { border-radius:10px; border:2px solid #003d82; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">

            <div class="d-flex align-items-center mb-4 gap-3">
                <div style="background:linear-gradient(135deg,#001f3f,#0066cc);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-edit text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-0 fw-bold text-dark">Edit Dokumen</h4>
                    <small class="text-muted">Ubah data dokumen e-Library.</small>
                </div>
                <a href="{{ route('admin.e-library.index') }}" class="ms-auto" style="width:36px;height:36px;border-radius:50%;background:#e9ecef;border:none;color:#555;display:flex;align-items:center;justify-content:center;font-size:1.1rem;text-decoration:none;transition:background 0.2s;flex-shrink:0;" onmouseover="this.style.background='#d0d7e2'" onmouseout="this.style.background='#e9ecef'"><i class="fas fa-times"></i></a>
            </div>

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:10px; border-left:4px solid #dc3545;">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="card setting-card">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-file-alt me-2"></i>Form Edit Dokumen</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.e-library.update', $document->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        {{-- Current file info --}}
                        @if($document->pdf_path)
                        <div class="mb-3">
                            <label class="form-label fw-semibold">File Saat Ini</label>
                            <div class="current-file-info">
                                @php $ext = strtolower(pathinfo($document->pdf_path, PATHINFO_EXTENSION)); @endphp
                                @if($ext === 'pdf')
                                    <i class="fas fa-file-pdf text-danger me-2"></i>
                                @elseif(in_array($ext, ['doc','docx']))
                                    <i class="fas fa-file-word text-primary me-2"></i>
                                @elseif(in_array($ext, ['xls','xlsx']))
                                    <i class="fas fa-file-excel text-success me-2"></i>
                                @elseif(in_array($ext, ['ppt','pptx']))
                                    <i class="fas fa-file-powerpoint text-warning me-2"></i>
                                @elseif(in_array($ext, ['jpg','jpeg','png','gif','webp']))
                                    <i class="fas fa-file-image text-info me-2"></i>
                                @else
                                    <i class="fas fa-file text-muted me-2"></i>
                                @endif
                                <span class="fw-semibold">{{ basename($document->pdf_path) }}</span>
                                <span class="badge bg-secondary text-uppercase ms-2">{{ strtoupper($ext) }}</span>
                            </div>
                        </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ganti File</label>
                            <input type="file" name="file" class="form-control">
                            <small class="text-muted">Kosongkan jika tidak ingin mengganti file. Semua tipe file didukung. Maks 50MB.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('messages.status') }}</label>
                            <select name="status" class="form-select">
                                <option value="published" {{ $document->status === 'published' ? 'selected' : '' }}>Published</option>
                                <option value="private" {{ $document->status === 'private' ? 'selected' : '' }}>Private (Admin & User only)</option>
                                <option value="draft" {{ $document->status === 'draft' ? 'selected' : '' }}>{{ __('messages.draft') }}</option>
                            </select>
                            <small class="text-muted">Private: hanya tampil untuk admin/user yang login. Draft: tidak tampil di website.</small>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-simpan"><i class="fas fa-save me-1"></i> Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
