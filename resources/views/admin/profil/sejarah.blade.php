@extends('layouts.admin')
@section('title', __('messages.admin_sejarah'))
@section('page-title', 'Edit Sejarah')

@push('styles')
<style>
    /* ===== Exact same timeline styles as public page ===== */
    .sejarah-header {
        background: linear-gradient(135deg, #0d47a1, #1565c0);
        color: white;
        border: none;
        border-radius: 50px;
        padding: 8px 30px;
        font-weight: 800;
        font-size: 1rem;
        display: inline-block;
        letter-spacing: 1px;
        box-shadow: 0 3px 10px rgba(13,71,161,0.3);
    }
    .sejarah-title-box {
        background: linear-gradient(135deg, #ffd600 0%, #ffab00 100%);
        border: none;
        border-radius: 15px;
        padding: 15px 30px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(255,214,0,0.4);
    }
    .sejarah-title-box h1 {
        font-size: 1.8rem;
        font-weight: 900;
        margin: 0;
        letter-spacing: 1px;
        color: #0d47a1;
    }
    .sejarah-title-box p {
        font-size: 0.85rem;
        font-weight: 700;
        margin: 5px 0 0;
        color: #1a237e;
    }
    .timeline-year {
        background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
        color: white;
        border: none;
        border-radius: 50px;
        padding: 10px 28px;
        font-size: 1.2rem;
        font-weight: 800;
        display: inline-block;
        box-shadow: 0 3px 12px rgba(13,71,161,0.3);
    }
    .timeline-year-alt {
        background: linear-gradient(135deg, #ffd600 0%, #ffab00 100%);
        color: #0d47a1;
        box-shadow: 0 3px 12px rgba(255,214,0,0.4);
    }
    .timeline-content-box {
        background: #fff;
        border: 2px solid #e3f2fd;
        border-radius: 12px;
        padding: 15px;
        text-align: justify;
        line-height: 1.6;
        font-size: 0.85rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        border-left: 4px solid #1565c0;
    }
    .timeline-header-badge {
        background: linear-gradient(135deg, #1565c0, #1976d2);
        color: white;
        border: none;
        border-radius: 50px;
        padding: 5px 14px;
        font-weight: 700;
        font-size: 0.75rem;
        display: inline-block;
        margin-bottom: 10px;
    }
    .timeline-header-badge-alt {
        background: linear-gradient(135deg, #ffd600 0%, #ffab00 100%);
        color: #0d47a1;
    }
    .timeline-header-badge-light {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        color: #0d47a1;
    }
    .timeline-diamond {
        width: 16px;
        height: 16px;
        background: linear-gradient(135deg, #ffd600, #ffab00);
        border: 2px solid #0d47a1;
        transform: rotate(45deg);
        margin: 0 auto;
        position: relative;
        z-index: 2;
        flex-shrink: 0;
    }
    .timeline-wrapper {
        position: relative;
    }
    .timeline-wrapper::before {
        content: '';
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        top: 0;
        bottom: 0;
        width: 3px;
        background: linear-gradient(180deg, #1565c0, #1976d2, #ffd600);
        z-index: 0;
    }
    .timeline-wrapper .row {
        position: relative;
        z-index: 1;
    }
    .timeline-center {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .timeline-entry {
        position: relative;
    }
    .timeline-entry::after {
        content: '';
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        height: 3px;
        background: linear-gradient(90deg, #1565c0, #ffd600);
        z-index: 0;
        left: 20%;
        right: 20%;
    }
    .timeline-entry .col-md-5,
    .timeline-entry .col-md-2 {
        position: relative;
        z-index: 1;
    }
    .timeline-year, .timeline-content-box {
        position: relative;
        z-index: 2;
    }
    .logo-center {
        width: 50px;
        height: auto;
    }

    /* ===== Admin inline-edit additions ===== */
    .editing-mode [contenteditable="true"] {
        outline: none;
        background: rgba(13,110,253,0.04);
        border-radius: 4px;
        padding: 2px 4px;
        min-height: 1.2em;
    }
    .editing-mode [contenteditable="true"]:focus {
        background: rgba(13,110,253,0.08);
        box-shadow: 0 0 0 2px rgba(13,110,253,0.25);
    }
    .editing-mode .diagram-title[contenteditable="true"] {
        background: #1565c0;
        color: #fff;
    }
    .editing-mode .diagram-title[contenteditable="true"]:focus {
        background: #0d47a1;
        color: #fff;
        box-shadow: 0 0 0 2px rgba(13,110,253,0.4);
    }
    .editing-mode .diagram-title.timeline-header-badge-alt[contenteditable="true"] {
        background: linear-gradient(135deg, #ffd600 0%, #ffab00 100%);
        color: #0d47a1;
    }
    .editing-mode .diagram-title.timeline-header-badge-light[contenteditable="true"] {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        color: #0d47a1;
    }
    .editing-mode .timeline-year[contenteditable="true"] {
        min-width: 60px;
        background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
        color: #fff;
    }
    .editing-mode .timeline-year[contenteditable="true"]:focus {
        background: #0d47a1;
        color: #fff;
        box-shadow: 0 0 0 2px rgba(13,110,253,0.4);
    }
    .editing-mode .timeline-year.timeline-year-alt[contenteditable="true"] {
        background: linear-gradient(135deg, #ffd600 0%, #ffab00 100%);
        color: #0d47a1;
    }
    .editing-mode .timeline-year.timeline-year-alt[contenteditable="true"]:focus {
        background: linear-gradient(135deg, #ffd600 0%, #ffab00 100%);
        color: #0d47a1;
        box-shadow: 0 0 0 2px rgba(255,214,0,0.5);
    }
    .btn-actions-item {
        display: none;
        margin-top: 10px;
    }
    .editing-mode .btn-actions-item {
        display: block;
    }
    .editing-mode .timeline-content-box {
        outline: 2px dashed rgba(13,110,253,0.3);
        cursor: text;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4 gap-3">
        <div class="flex-grow-1">
            <h4 class="mb-0 fw-bold text-dark">Sejarah</h4>
            <small class="text-muted">Kelola konten sejarah organisasi.</small>
        </div>
    </div>
</div>

<!-- Toolbar -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <button class="btn btn-primary" id="btnToggleEdit" onclick="toggleEditMode()">
            <i class="fas fa-edit me-1"></i> Edit
        </button>
        <button class="btn btn-success d-none" id="btnSaveAll" onclick="saveAll()">
            <i class="fas fa-save me-1"></i> Simpan
        </button>
        <button class="btn btn-secondary d-none" id="btnCancel" onclick="cancelEdit()">
            <i class="fas fa-times me-1"></i> Batal
        </button>
    </div>
    <button class="btn btn-outline-primary d-none" id="btnAdd" onclick="showAddForm()">
        <i class="fas fa-plus me-1"></i> Tambah Item
    </button>
</div>

<!-- Add new form (hidden) -->
<div class="card mb-4 d-none" id="addFormCard">
    <div class="card-header bg-primary text-white">
        <i class="fas fa-plus me-1"></i> Tambah Sejarah
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-2">
                <label class="form-label">Tahun</label>
                <input type="text" class="form-control" id="addYear" placeholder="cth: 1964">
            </div>
            <div class="col-md-3">
                <label class="form-label">{{ __('messages.judul') }}</label>
                <input type="text" class="form-control" id="addTitle" placeholder="Judul peristiwa">
            </div>
            <div class="col-md-7">
                <label class="form-label">{{ __('messages.deskripsi') }}</label>
                <textarea class="form-control" id="addDesc" rows="2" placeholder="Deskripsi singkat"></textarea>
            </div>
        </div>
    </div>
</div>

<!-- Header Section (same as public) -->
<div class="text-center mb-3">
    <img src="{{ asset('assets/image/disinfolahta.png') }}" alt="Logo" class="logo-center">
</div>
<div class="text-center mb-3">
    <span class="sejarah-header">SEJARAH</span>
</div>
<div class="text-center mb-3">
    <div class="sejarah-title-box d-inline-block">
        <h1>DISINFOLAHTAAU</h1>
        <p>DINAS INFORMASI DAN PENGOLAHAN DATA<br>TNI ANGKATAN UDARA</p>
    </div>
</div>

<!-- Timeline (same zigzag layout as public) -->
<div class="timeline-wrapper" id="timelineWrapper">
    <div class="row">
        <div class="col-12" id="timelineContainer">
            @forelse($diagrams as $i => $d)
                @php $isOdd = $i % 2 === 0; @endphp
                <div class="row align-items-center py-3 timeline-entry" data-id="{{ $d->id }}">
                    @if($isOdd)
                        {{-- Content LEFT, Year RIGHT --}}
                        <div class="col-md-5">
                            <div class="timeline-content-box">
                                <span class="timeline-header-badge diagram-title">{{ $d->title }}</span>
                                <p class="diagram-desc mb-0" style="white-space:pre-wrap">{!! nl2br(e($d->description)) !!}</p>
                                <div class="btn-actions-item">
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteItem(this)">
                                        <i class="fas fa-trash me-1"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 timeline-center">
                            <div class="timeline-diamond"></div>
                        </div>
                        <div class="col-md-5 text-center">
                            <span class="timeline-year {{ $i % 4 >= 2 ? 'timeline-year-alt' : '' }} diagram-year">{{ $d->year }}</span>
                        </div>
                    @else
                        {{-- Year LEFT, Content RIGHT --}}
                        <div class="col-md-5 text-center">
                            <span class="timeline-year {{ $i % 4 >= 2 ? 'timeline-year-alt' : '' }} diagram-year">{{ $d->year }}</span>
                        </div>
                        <div class="col-md-2 timeline-center">
                            <div class="timeline-diamond"></div>
                        </div>
                        <div class="col-md-5">
                            <div class="timeline-content-box">
                                <span class="timeline-header-badge diagram-title">{{ $d->title }}</span>
                                <p class="diagram-desc mb-0" style="white-space:pre-wrap">{!! nl2br(e($d->description)) !!}</p>
                                <div class="btn-actions-item">
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteItem(this)">
                                        <i class="fas fa-trash me-1"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-center text-muted py-5" id="emptyMsg">Belum ada data sejarah. Klik "Edit Sejarah" lalu "Tambah Item".</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let isEditing = false;

    function toggleEditMode() {
        isEditing = true;
        document.getElementById('timelineWrapper').classList.add('editing-mode');
        document.getElementById('btnToggleEdit').classList.add('d-none');
        document.getElementById('btnSaveAll').classList.remove('d-none');
        document.getElementById('btnCancel').classList.remove('d-none');
        document.getElementById('btnAdd').classList.remove('d-none');

        document.querySelectorAll('.diagram-title').forEach(el => el.setAttribute('contenteditable', 'true'));
        document.querySelectorAll('.diagram-desc').forEach(el => el.setAttribute('contenteditable', 'true'));
        document.querySelectorAll('.diagram-year').forEach(el => el.setAttribute('contenteditable', 'true'));
    }

    function cancelEdit() {
        location.reload();
    }

    async function saveAll() {
        const entries = document.querySelectorAll('.timeline-entry[data-id]');
        const btn = document.getElementById('btnSaveAll');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';

        let saved = 0, errors = 0;

        for (const entry of entries) {
            const id = entry.dataset.id;
            const title = entry.querySelector('.diagram-title').innerText.trim();
            const desc = entry.querySelector('.diagram-desc').innerText.trim();
            const year = entry.querySelector('.diagram-year').innerText.trim();

            if (!title || !year) { errors++; continue; }

            try {
                const res = await fetch("{{ route('admin.profil.sejarah.store') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ id, title, description: desc, year })
                });
                const json = await res.json();
                if (json.success) { entry.dataset.id = json.diagram.id; saved++; }
                else { errors++; }
            } catch (e) { errors++; }
        }

        // Exit edit mode
        isEditing = false;
        document.getElementById('timelineWrapper').classList.remove('editing-mode');
        document.getElementById('btnToggleEdit').classList.remove('d-none');
        document.getElementById('btnSaveAll').classList.add('d-none');
        document.getElementById('btnCancel').classList.add('d-none');
        document.getElementById('btnAdd').classList.add('d-none');
        document.getElementById('addFormCard').classList.add('d-none');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-1"></i> Simpan Perubahan';

        document.querySelectorAll('[contenteditable]').forEach(el => el.removeAttribute('contenteditable'));

        if (errors > 0) {
            showToast(saved + ' item tersimpan, ' + errors + ' gagal.', 'warning');
        } else {
            showToast(saved + ' item berhasil disimpan!', 'success');
        }
    }

    async function deleteItem(btn) {
        if (!confirm('Hapus item sejarah ini?')) return;
        const entry = btn.closest('.timeline-entry');
        const id = entry.dataset.id;

        try {
            const res = await fetch("{{ url('admin/profil/sejarah') }}/" + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken }
            });
            const json = await res.json();
            if (json.success) {
                entry.remove();
                showToast('Item dihapus.', 'success');
            }
        } catch (e) {
            showToast('Gagal menghapus.', 'danger');
        }
    }

    function showAddForm() {
        var form = document.getElementById('addFormCard');
        var btn  = document.getElementById('btnAdd');
        var isHidden = form.classList.contains('d-none');
        form.classList.toggle('d-none');
        if (isHidden) {
            btn.classList.add('d-none');
        } else {
            btn.classList.remove('d-none');
        }
    }

    async function addDiagram() {
        const year  = document.getElementById('addYear').value.trim();
        const title = document.getElementById('addTitle').value.trim();
        const desc  = document.getElementById('addDesc').value.trim();

        if (!year || !title) { alert('Tahun dan Judul wajib diisi.'); return; }

        try {
            const res = await fetch("{{ route('admin.profil.sejarah.store') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ year, title, description: desc })
            });
            const json = await res.json();

            if (json.success) {
                document.getElementById('addYear').value = '';
                document.getElementById('addTitle').value = '';
                document.getElementById('addDesc').value = '';
                showToast('Item ditambahkan! Halaman dimuat ulang...', 'success');
                setTimeout(() => location.reload(), 800);
            }
        } catch (e) {
            showToast('Gagal menambahkan item.', 'danger');
        }
    }

    function showToast(msg, type) {
        type = type || 'success';
        const el = document.createElement('div');
        el.className = 'position-fixed bottom-0 end-0 p-3';
        el.style.zIndex = 9999;
        el.innerHTML = '<div class="toast show align-items-center text-bg-' + type + ' border-0" role="alert">' +
            '<div class="d-flex"><div class="toast-body">' + msg + '</div>' +
            '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div></div>';
        document.body.appendChild(el);
        setTimeout(() => el.remove(), 3000);
    }

    // Submit form dengan Enter key pada input Tahun dan Judul
    document.getElementById('addYear').addEventListener('keydown', function(e) { if (e.key === 'Enter') { e.preventDefault(); addDiagram(); } });
    document.getElementById('addTitle').addEventListener('keydown', function(e) { if (e.key === 'Enter') { e.preventDefault(); addDiagram(); } });
</script>
@endpush
