@extends('layouts.admin')
@section('title', 'Tambah Dokumen e-Library')
@section('page-title', 'Tambah Dokumen')

@push('styles')
<style>
    .setting-card { border:none; box-shadow:0 2px 12px rgba(0,61,130,0.10); border-radius:12px; }
    .setting-card .card-header { background:linear-gradient(135deg,#001f3f 0%,#003d82 100%); color:white; border-radius:12px 12px 0 0 !important; padding:16px 24px; }
    .setting-card .card-header h6 { color:white !important; }
    .btn-simpan { background:linear-gradient(135deg,#001f3f 0%,#0066cc 100%); color:white; font-weight:600; border:none; border-radius:8px; padding:10px 32px; transition:transform 0.15s, box-shadow 0.15s; }
    .btn-simpan:hover { color:#fff; transform:translateY(-2px); box-shadow:0 6px 20px rgba(0,61,130,0.35); }
    .form-control:focus, .form-select:focus { border-color:#003d82; box-shadow:0 0 0 0.2rem rgba(0,61,130,0.15); }
    #preview-cover { max-width:220px; }
    #preview-cover img { border-radius:10px; border:2px solid #003d82; }
    .file-info { background:#f8f9fa; border-radius:8px; padding:12px 16px; border:1px solid #e9ecef; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">

            <div class="d-flex align-items-center mb-4 gap-3">
                <div style="background:linear-gradient(135deg,#001f3f,#0066cc);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-plus text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-0 fw-bold text-dark">Tambah Dokumen</h4>
                    <small class="text-muted">Upload file baru ke perpustakaan digital. Semua tipe file didukung.</small>
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
                    <h6 class="mb-0 fw-bold"><i class="fas fa-upload me-2"></i>Form Upload Dokumen</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.e-library.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">File <span class="text-danger">*</span></label>
                            <input type="file" name="file" id="input-file" class="form-control" required>
                            <small class="text-muted">Semua tipe file didukung (PDF, Word, Excel, gambar, dll). Maks 50MB.</small>
                            <div id="file-info" class="file-info mt-2" style="display:none;">
                                <i class="fas fa-file me-2" id="file-icon"></i>
                                <span id="file-name" class="fw-semibold"></span>
                                <span class="text-muted ms-2" id="file-size"></span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('messages.status') }}</label>
                            <select name="status" class="form-select">
                                <option value="published" selected>Published</option>
                                <option value="private">Private (Admin & User only)</option>
                                <option value="draft">{{ __('messages.draft') }}</option>
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

@push('scripts')
<script>
// File info display
document.getElementById('input-file').addEventListener('change', function(e){
    const file = e.target.files[0];
    const info = document.getElementById('file-info');
    if(file){
        document.getElementById('file-name').textContent = file.name;
        const size = file.size < 1024*1024
            ? (file.size/1024).toFixed(1) + ' KB'
            : (file.size/(1024*1024)).toFixed(1) + ' MB';
        document.getElementById('file-size').textContent = '(' + size + ')';

        const ext = file.name.split('.').pop().toLowerCase();
        const icon = document.getElementById('file-icon');
        icon.className = 'fas me-2 ';
        if(ext === 'pdf') icon.className += 'fa-file-pdf text-danger';
        else if(['doc','docx'].includes(ext)) icon.className += 'fa-file-word text-primary';
        else if(['xls','xlsx'].includes(ext)) icon.className += 'fa-file-excel text-success';
        else if(['ppt','pptx'].includes(ext)) icon.className += 'fa-file-powerpoint text-warning';
        else if(['jpg','jpeg','png','gif','webp'].includes(ext)) icon.className += 'fa-file-image text-info';
        else if(['zip','rar','7z'].includes(ext)) icon.className += 'fa-file-archive text-secondary';
        else icon.className += 'fa-file text-muted';

        info.style.display = 'block';
    } else {
        info.style.display = 'none';
    }
});
</script>
@endpush
