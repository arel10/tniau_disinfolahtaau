@extends('layouts.admin')

@section('title', 'Manajemen Struktur Organisasi')
@section('page-title', 'Edit Struktur Organisasi')

@push('styles')
<style>
    /* ===== ORG CHART (same as public) ===== */
    .org-chart {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        position: relative;
        overflow-x: auto;
    }
    .org-box {
        background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 100%);
        color: white; padding: 10px 20px; border-radius: 8px;
        font-size: 12px; font-weight: 700; text-align: center;
        box-shadow: 0 3px 12px rgba(0,0,0,0.2); min-width: 120px;
        display: inline-block; cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        position: relative; z-index: 2; letter-spacing: 0.3px;
    }
    .org-box:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.35); }
    .org-box-primary { background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%); padding: 14px 30px; font-size: 14px; min-width: 180px; }
    .org-box-secondary { background: linear-gradient(135deg, #37474f 0%, #546e7a 100%); padding: 10px 24px; font-size: 12px; }
    .org-box-tertiary { background: linear-gradient(135deg, #455a64 0%, #607d8b 100%); font-size: 11px; padding: 8px 16px; }
    .org-box-small { background: linear-gradient(135deg, #546e7a 0%, #78909c 100%); font-size: 10px; padding: 6px 12px; min-width: 90px; }
    .org-box-subdis { background: linear-gradient(135deg, #1565c0 0%, #1976d2 100%); padding: 12px 20px; font-size: 11px; min-width: 140px; }
    .org-box-item {
        background: #fff; color: #37474f; border: 1px solid #cfd8dc;
        padding: 6px 12px; font-size: 9px; font-weight: 600;
        min-width: 80px; box-shadow: 0 1px 4px rgba(0,0,0,0.08); cursor: pointer;
    }
    .org-box-item:hover { background: #e3f2fd; border-color: #1976d2; }
    .org-box .pejabat-name { display: block; font-size: 8px; font-weight: 400; opacity: 0.85; margin-top: 3px; }

    /* Lines */
    .line-down { width: 2px; background: #90a4ae; margin: 0 auto; }
    .line-down-30 { height: 30px; } .line-down-25 { height: 25px; }
    .line-down-20 { height: 20px; } .line-down-15 { height: 15px; }

    /* Tree connectors */
    .tree-row { display: flex; position: relative; }
    .tree-row::before { content: ''; position: absolute; top: 0; height: 2px; background: #90a4ae; z-index: 1; }
    .tree-row.cols-3::before { left: calc(100% / 6); right: calc(100% / 6); }
    .tree-row.cols-4::before { left: calc(100% / 8); right: calc(100% / 8); }
    .tree-col { flex: 1; min-width: 0; display: flex; flex-direction: column; align-items: center; position: relative; padding-top: 25px; }
    .tree-col::before { content: ''; position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 2px; height: 25px; background: #90a4ae; z-index: 1; }
    .tree-col.tree-col-sm { padding-top: 18px; }
    .tree-col.tree-col-sm::before { height: 18px; }
    .tree-row .tree-row .org-box { min-width: auto; }

    .section-label { background: #e3f2fd; color: #1565c0; padding: 5px 14px; border-radius: 5px; font-size: 10px; font-weight: 700; border: 1px dashed #1565c0; }
    .card-struktur { border: none; box-shadow: 0 5px 30px rgba(0,0,0,0.1); border-radius: 15px; overflow: hidden; }
    .chart-title { background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%); color: white; padding: 20px; text-align: center; }
    .chart-title h4 { margin: 0; font-weight: 700; letter-spacing: 1px; }
    .subdis-container { background: #f8f9fa; border-radius: 10px; padding: 15px; border: 1px solid #dee2e6; height: 100%; }
    .subdis-items { display: flex; flex-direction: column; gap: 4px; }
    .click-hint { font-size: 0.75rem; color: #999; margin-top: 15px; }

    /* ===== PROFILE EDIT MODAL (matches public profile popup) ===== */
    .profile-modal-overlay {
        display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.5); z-index: 9999;
        justify-content: center; align-items: center;
    }
    .profile-modal-overlay.active { display: flex; }
    .profile-card {
        background: white; border-radius: 15px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        max-width: 420px; width: 92%; position: relative;
        overflow: hidden; animation: profileSlideIn 0.3s ease;
        max-height: 90vh; overflow-y: auto;
    }
    @keyframes profileSlideIn {
        from { transform: scale(0.8) translateY(30px); opacity: 0; }
        to { transform: scale(1) translateY(0); opacity: 1; }
    }
    .profile-card-header {
        background: linear-gradient(135deg, #0d47a1 0%, #1976d2 100%);
        color: white; padding: 20px; text-align: center; position: relative;
    }
    .profile-card-header h5 { font-weight: 700; margin: 0; font-size: 1.1rem; }
    .profile-card-header small { opacity: 0.85; }
    .profile-close {
        position: absolute; top: 10px; right: 15px;
        background: rgba(255,255,255,0.2); border: none; color: white;
        font-size: 1.2rem; width: 32px; height: 32px; border-radius: 50%;
        cursor: pointer; display: flex; align-items: center; justify-content: center;
    }
    .profile-close:hover { background: rgba(255,255,255,0.4); }
    .profile-card-body { padding: 25px; }

    /* Photo section with Ganti button beside it */
    .photo-section {
        display: flex; align-items: flex-start; gap: 15px;
        margin-bottom: 20px;
    }
    .profile-photo {
        width: 120px; height: 160px; border-radius: 6px;
        object-fit: cover; border: 3px solid #e3f2fd;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15); background: #e3f2fd;
        flex-shrink: 0;
    }
    .photo-actions {
        display: flex; flex-direction: column; gap: 8px; padding-top: 5px;
    }
    .btn-ganti-foto {
        background: linear-gradient(135deg, #1565c0, #1976d2);
        color: white; border: none; padding: 8px 16px;
        border-radius: 8px; font-size: 0.8rem; font-weight: 600;
        cursor: pointer; display: flex; align-items: center; gap: 6px;
        transition: all 0.2s;
    }
    .btn-ganti-foto:hover { background: linear-gradient(135deg, #0d47a1, #1565c0); transform: translateY(-1px); }
    .photo-hint { font-size: 0.7rem; color: #999; line-height: 1.3; }

    /* Editable info list (same structure as public profile list) */
    .edit-info-list { list-style: none; padding: 0; margin: 0; }
    .edit-info-list li {
        padding: 8px 0; border-bottom: 1px solid #f0f0f0;
        display: flex; align-items: center; gap: 12px;
    }
    .edit-info-list li:last-child { border-bottom: none; }
    .edit-info-list i { color: #1565c0; width: 20px; text-align: center; font-size: 0.9rem; flex-shrink: 0; }
    .edit-info-list .info-group { flex: 1; }
    .edit-info-list .info-label { color: #999; font-size: 0.75rem; margin-bottom: 2px; }
    .edit-info-list .form-control {
        border: 1px solid #e0e0e0; border-radius: 6px; font-size: 0.85rem;
        padding: 6px 10px; transition: border-color 0.2s;
    }
    .edit-info-list .form-control:focus { border-color: #1976d2; box-shadow: 0 0 0 2px rgba(25,118,210,0.15); }

    /* Save button */
    .btn-simpan {
        background: linear-gradient(135deg, #43a047, #66bb6a);
        color: white; border: none; padding: 10px 28px;
        border-radius: 10px; font-size: 0.9rem; font-weight: 700;
        cursor: pointer; display: flex; align-items: center; gap: 8px;
        transition: all 0.2s; box-shadow: 0 4px 15px rgba(67,160,71,0.3);
    }
    .btn-simpan:hover { background: linear-gradient(135deg, #388e3c, #43a047); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(67,160,71,0.4); }
    .btn-simpan:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

    /* Toast notification */
    .save-toast {
        position: fixed; top: 20px; right: 20px; z-index: 99999;
        padding: 14px 24px; border-radius: 10px; color: white;
        font-weight: 600; box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        display: none; animation: toastIn 0.3s ease;
        font-size: 0.9rem;
    }
    .save-toast.success { background: #43a047; }
    .save-toast.error { background: #e53935; }
    @keyframes toastIn {
        from { transform: translateX(30px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    /* ===== MOBILE RESPONSIVE: scroll horizontally, keep chart intact ===== */
    @media (max-width: 991.98px) {
        .org-chart-scroll {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 8px;
        }
        .org-chart-scroll > .org-chart,
        .org-chart-scroll > div {
            min-width: 700px;
        }
        .chart-title h4 { font-size: 0.95rem; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4 gap-3">
        <div style="background:linear-gradient(135deg,#001f3f,#0066cc);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fas fa-sitemap text-white fa-lg"></i>
        </div>
        <div class="flex-grow-1">
            <h4 class="mb-0 fw-bold text-dark">Struktur Organisasi</h4>
            <small class="text-muted">Kelola struktur organisasi.</small>
        </div>
    </div>
</div>

<!-- Toast -->
<div class="save-toast" id="saveToast"></div>

<!-- Edit Modal (same style as public profile popup, but editable) -->
<div class="profile-modal-overlay" id="editModal">
    <div class="profile-card">
        <div class="profile-card-header">
            <button class="profile-close" onclick="closeEdit()">&times;</button>
            <h5 id="editTitle">-</h5>
            <small id="editUnit">-</small>
        </div>
        <div class="profile-card-body">
            <form id="editForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Photo + Ganti Button -->
                <div class="photo-section">
                    <img src="" alt="Foto Pejabat" class="profile-photo" id="editFotoPreview">
                    <div class="photo-actions">
                        <button type="button" class="btn-ganti-foto" onclick="document.getElementById('editFotoInput').click()">
                            <i class="fas fa-camera"></i> Ganti Foto
                        </button>
                        <span class="photo-hint">Format: JPG, PNG<br>Ukuran: Maks 2MB<br>Rasio: 3x4</span>
                    </div>
                    <input type="file" name="foto" id="editFotoInput" accept="image/jpeg,image/png,image/jpg" class="d-none">
                </div>

                <!-- Editable Info List -->
                <ul class="edit-info-list">
                    <li>
                        <i class="fas fa-user"></i>
                        <div class="info-group">
                            <div class="info-label">Nama Pejabat</div>
                            <input type="text" name="nama_pejabat" id="editNamaPejabat" class="form-control" placeholder="Nama lengkap pejabat">
                        </div>
                    </li>
                    <li>
                        <i class="fas fa-id-card"></i>
                        <div class="info-group">
                            <div class="info-label">{{ __('messages.form_nrp') }}</div>
                            <input type="text" name="nrp" id="editNrp" class="form-control" placeholder="NRP">
                        </div>
                    </li>
                    <li>
                        <i class="fas fa-calendar-alt"></i>
                        <div class="info-group">
                            <div class="info-label">Tanggal Lahir</div>
                            <input type="date" name="tanggal_lahir" id="editTtl" class="form-control">
                        </div>
                    </li>
                </ul>

                <!-- Save Button (right aligned) -->
                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn-simpan" id="editSaveBtn">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card card-struktur"><div class="org-chart-scroll">
    <div class="chart-title">
        <h4><i class="fas fa-sitemap me-2"></i>STRUKTUR ORGANISASI DISINFOLAHTAAU</h4>
    </div>
    <div class="card-body p-4 org-chart">
        
        <!-- ====== LEVEL 1: KADISINFOLAHTAAU ====== -->
        <div class="text-center position-relative">
            <div class="org-box org-box-primary" onclick="openEdit('kadisinfolahtaau')">
                KADISINFOLAHTAAU
                <span class="pejabat-name">{{ $strukturs['kadisinfolahtaau']->nama_pejabat ?? '-' }}</span>
            </div>
            <div style="position: absolute; right: 0; top: 0;">
                <div class="d-flex flex-column align-items-end gap-1">
                    <span class="section-label">UNSUR PIMPINAN</span>
                    <span class="section-label">UNSUR PEMBANTU PIMPINAN/STAF</span>
                </div>
            </div>
        </div>
        <div class="line-down" style="height: 60px;"></div>
        
        <!-- ====== LEVEL 2: SETDIS ====== -->
        <div class="text-center">
            <div class="org-box org-box-secondary" onclick="openEdit('setdis')">
                SETDIS
                <span class="pejabat-name">{{ $strukturs['setdis']->nama_pejabat ?? '-' }}</span>
            </div>
        </div>
        <div class="line-down line-down-25"></div>

        <!-- ====== LEVEL 3: BAGUM / BAGPROGAR / BAGBINPROF ====== -->
        <div class="tree-row cols-3">
            <div class="tree-col">
                <div class="org-box org-box-tertiary" onclick="openEdit('bagum')">BAGUM<span class="pejabat-name">{{ $strukturs['bagum']->nama_pejabat ?? '-' }}</span></div>
                <div class="line-down line-down-15"></div>
                <div class="org-box org-box-small" onclick="openEdit('subbagmin')">SUBBAGMIN<span class="pejabat-name">{{ $strukturs['subbagmin']->nama_pejabat ?? '-' }}</span></div>
                <div class="line-down line-down-15"></div>
                <div class="tree-row cols-4" style="width: 100%;">
                    <div class="tree-col tree-col-sm" style="padding: 18px 2px 0;">
                        <div class="org-box org-box-small" style="font-size: 8px; padding: 5px 8px;" onclick="openEdit('urtu')">URTU</div>
                    </div>
                    <div class="tree-col tree-col-sm" style="padding: 18px 2px 0;">
                        <div class="org-box org-box-small" style="font-size: 8px; padding: 5px 8px;" onclick="openEdit('urdal')">URDAL</div>
                    </div>
                    <div class="tree-col tree-col-sm" style="padding: 18px 2px 0;">
                        <div class="org-box org-box-small" style="font-size: 8px; padding: 5px 8px;" onclick="openEdit('urpers')">URPERS</div>
                    </div>
                    <div class="tree-col tree-col-sm" style="padding: 18px 2px 0;">
                        <div class="org-box org-box-small" style="font-size: 8px; padding: 5px 8px;" onclick="openEdit('urbmn')">UR BMN</div>
                    </div>
                </div>
            </div>
            <div class="tree-col">
                <div class="org-box org-box-tertiary" onclick="openEdit('bagprogar')">BAGPROGAR<span class="pejabat-name">{{ $strukturs['bagprogar']->nama_pejabat ?? '-' }}</span></div>
            </div>
            <div class="tree-col">
                <div class="org-box org-box-tertiary" onclick="openEdit('bagbinprof')">BAGBINPROF<span class="pejabat-name">{{ $strukturs['bagbinprof']->nama_pejabat ?? '-' }}</span></div>
                <div class="line-down line-down-15"></div>
                <div class="org-box org-box-small" onclick="openEdit('subbagpers')">SUBBAGPERS<span class="pejabat-name">{{ $strukturs['subbagpers']->nama_pejabat ?? '-' }}</span></div>
            </div>
        </div>
        
        <!-- Separator label -->
        <div class="text-end position-relative" style="margin-top: 15px; margin-bottom: 10px;">
            <span class="section-label">UNSUR PELAKSANA</span>
        </div>
        
        <!-- ====== LEVEL 4: SUBDIS ROW ====== -->
        <div class="tree-row cols-4 w-100">
            @php
                $subdisData = [
                    ['kode' => 'subdissidukops', 'label' => 'SUBDISSIDUKOPS', 'children' => ['siapldatabase_ops','subsiapl_ops','urrenharapl_ops','subsidatabase_ops','urrenhardb_ops','sikompjar_ops','subsikomp_ops','urrenharkomp_ops','subsjar_ops','urrenharjar_ops']],
                    ['kode' => 'subdissidukpers', 'label' => 'SUBDISSIDUKPERS', 'children' => ['siapldatabase_pers','subsiapl_pers','urrenharapl_pers','subsidatabase_pers','urrenhardb_pers','sikompjar_pers','subsikomp_pers','urrenharkomp_pers','subsjar_pers','urrenharjar_pers','sigarku_pers','subsiapl2_pers','urrenharapl2_pers','subsidatabase2_pers','urrenhardb2_pers']],
                    ['kode' => 'subdissiduklog', 'label' => 'SUBDISSIDUKLOG', 'children' => ['siapldatabase_log','subsiapl_log','urrenharapl_log','subsidatabase_log','urrenhardb_log','sikompjar_log','subsikomp_log','urrenharkomp_log','subsjar_log','urrenharjar_log']],
                    ['kode' => 'subdissiduksissmin', 'label' => 'SUBDISSIDUKSISSMIN', 'children' => ['siapldatabase_sis','subsiapl_sis','urrenharapl_sis','subsidatabase_sis','urrenhardb_sis','sikompjar_sis','subsikomp_sis','urrenharkomp_sis','subsjar_sis','urrenharjar_sis','pustasinfo','subsihar','urharapljar','subsiops','uropsapljar']],
                ];
            @endphp

            @foreach($subdisData as $subdis)
            <div class="tree-col" style="padding-left: 10px; padding-right: 10px;">
                <div class="org-box org-box-subdis" onclick="openEdit('{{ $subdis['kode'] }}')">
                    {{ $subdis['label'] }}
                    <span class="pejabat-name">{{ $strukturs[$subdis['kode']]->nama_pejabat ?? '-' }}</span>
                </div>
                <div class="line-down line-down-15"></div>
                <div class="subdis-container mt-2">
                    <div class="subdis-items">
                        @foreach($subdis['children'] as $childKode)
                        <div class="org-box org-box-item" onclick="openEdit('{{ $childKode }}')">
                            {{ $strukturs[$childKode]->nama_jabatan ?? strtoupper(str_replace('_', ' ', explode('_', $childKode)[0])) }}
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <p class="click-hint text-center mt-4"><i class="fas fa-hand-pointer me-1"></i> Klik pada kotak jabatan untuk mengedit data pejabat</p>
    </div>
</div></div>{{-- /org-chart-scroll /card-struktur --}}
@endsection

@push('scripts')
<script>
    // Data from controller (pre-built to avoid Blade parse issues)
    const strukturData = {!! json_encode($strukturJson) !!};
    const updateUrls = {!! json_encode($updateUrls) !!};

    function openEdit(kode) {
        const data = strukturData[kode];
        if (!data) return;

        document.getElementById('editTitle').textContent = data.nama_jabatan;
        document.getElementById('editUnit').textContent = data.unit || data.nama_lengkap_jabatan;
        document.getElementById('editNamaPejabat').value = data.nama_pejabat || '';
        document.getElementById('editNrp').value = data.nrp || '';
        document.getElementById('editTtl').value = data.tanggal_lahir || '';
        document.getElementById('editFotoPreview').src = data.foto;
        document.getElementById('editFotoInput').value = '';

        document.getElementById('editForm').dataset.url = updateUrls[kode];
        document.getElementById('editModal').classList.add('active');
    }

    function closeEdit() {
        document.getElementById('editModal').classList.remove('active');
    }

    function showToast(message, type) {
        const toast = document.getElementById('saveToast');
        toast.textContent = message;
        toast.className = 'save-toast ' + type;
        toast.style.display = 'block';
        setTimeout(() => { toast.style.display = 'none'; }, 3000);
    }

    // Photo preview on file select
    document.getElementById('editFotoInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                showToast('Ukuran file terlalu besar (max 2MB)', 'error');
                this.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(ev) {
                document.getElementById('editFotoPreview').src = ev.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Form submit via AJAX
    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const url = form.dataset.url;
        const formData = new FormData(form);

        const btn = document.getElementById('editSaveBtn');
        const origText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw data;
                });
            }
            return response.json();
        })
        .then(data => {
            showToast(data.message || 'Data berhasil disimpan!', 'success');
            setTimeout(() => window.location.reload(), 800);
        })
        .catch(err => {
            if (err && err.errors) {
                const firstError = Object.values(err.errors)[0][0];
                showToast(firstError, 'error');
            } else if (err && err.message) {
                showToast(err.message, 'error');
            } else {
                showToast('Gagal menyimpan data', 'error');
            }
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = origText;
        });
    });

    // Close on overlay click or ESC
    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) closeEdit();
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeEdit();
    });
</script>
@endpush
