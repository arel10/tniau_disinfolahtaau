@extends('layouts.public')

@section('title', __('messages.org_structure') . __('messages.site_title_suffix'))

@push('styles')
<style>
    .org-chart {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        position: relative;
        overflow-x: auto;
    }
    /* ===== ORG BOX STYLES ===== */
    .org-node {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
    }
    .org-box {
        background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 100%);
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-align: center;
        box-shadow: 0 3px 12px rgba(0,0,0,0.2);
        min-width: 120px;
        display: inline-block;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        position: relative;
        z-index: 2;
        letter-spacing: 0.3px;
    }
    .org-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.35);
    }
    .org-box-primary {
        background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
        padding: 14px 30px;
        font-size: 14px;
        min-width: 180px;
        letter-spacing: 0.5px;
    }
    .org-box-secondary {
        background: linear-gradient(135deg, #37474f 0%, #546e7a 100%);
        padding: 10px 24px;
        font-size: 12px;
    }
    .org-box-tertiary {
        background: linear-gradient(135deg, #455a64 0%, #607d8b 100%);
        font-size: 11px;
        padding: 8px 16px;
    }
    .org-box-small {
        background: linear-gradient(135deg, #546e7a 0%, #78909c 100%);
        font-size: 10px;
        padding: 6px 12px;
        min-width: 90px;
    }
    .org-box-subdis {
        background: linear-gradient(135deg, #1565c0 0%, #1976d2 100%);
        padding: 12px 20px;
        font-size: 11px;
        min-width: 140px;
    }
    .org-box-item {
        background: #fff;
        color: #37474f;
        border: 1px solid #cfd8dc;
        padding: 6px 12px;
        font-size: 9px;
        font-weight: 600;
        min-width: 80px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        cursor: pointer;
    }
    .org-box-item:hover {
        background: #e3f2fd;
        border-color: #1976d2;
    }
    [data-theme="dark"] .org-box-item { background: #1e293b; color: #ccc; border-color: #3a3a5a; }
    [data-theme="dark"] .org-box-item:hover { background: #2a3a5a; border-color: #4da3ff; }

    /* ===== CONNECTING LINES ===== */
    .line-down {
        width: 2px;
        background: #90a4ae;
        margin: 0 auto;
    }
    .line-down-30 { height: 30px; }
    .line-down-25 { height: 25px; }
    .line-down-20 { height: 20px; }
    .line-down-15 { height: 15px; }

    /* ===== TREE CONNECTOR SYSTEM ===== */
    /* Row containing children connected by a horizontal bar */
    .tree-row {
        display: flex;
        position: relative;
    }
    /* Horizontal bar across children (from first to last center) */
    .tree-row::before {
        content: '';
        position: absolute;
        top: 0;
        height: 2px;
        background: #90a4ae;
        z-index: 1;
    }
    /* For 3 equal-width children */
    .tree-row.cols-3::before {
        left: calc(100% / 6);
        right: calc(100% / 6);
    }
    /* For 4 equal-width children */
    .tree-row.cols-4::before {
        left: calc(100% / 8);
        right: calc(100% / 8);
    }
    /* Each child column */
    .tree-col {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        padding-top: 25px;
    }
    /* Nested tree-row boxes: no min-width constraint */
    .tree-row .tree-row .org-box {
        min-width: auto;
    }
    /* Vertical drop from horizontal bar to child content */
    .tree-col::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 2px;
        height: 25px;
        background: #90a4ae;
        z-index: 1;
    }
    /* Smaller vertical connector for nested items */
    .tree-col.tree-col-sm {
        padding-top: 18px;
    }
    .tree-col.tree-col-sm::before {
        height: 18px;
    }

    /* Section Labels */
    .section-label {
        background: #e3f2fd;
        color: #1565c0;
        padding: 5px 14px;
        border-radius: 5px;
        font-size: 10px;
        font-weight: 700;
        border: 1px dashed #1565c0;
        letter-spacing: 0.3px;
    }
    [data-theme="dark"] .section-label { background: #1e293b; color: #7db8ff; border-color: #4da3ff; }

    /* Card */
    .card-struktur {
        border: none;
        box-shadow: 0 5px 30px rgba(0,0,0,0.1);
        border-radius: 15px;
        overflow: hidden;
    }
    .chart-title {
        background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
        color: white;
        padding: 20px;
        text-align: center;
    }
    .chart-title h4 {
        margin: 0;
        font-weight: 700;
        letter-spacing: 1px;
    }

    /* Subdis containers */
    .subdis-wrapper {
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    .subdis-col {
        flex: 1;
        min-width: 200px;
        max-width: 280px;
    }
    .subdis-container {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        border: 1px solid #dee2e6;
        height: 100%;
    }
    [data-theme="dark"] .subdis-container { background: #16213e; border-color: #2a2a4a; }
    .subdis-items {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    /* ===== PROFILE MODAL ===== */
    .profile-modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 9999;
        justify-content: center;
        align-items: center;
    }
    .profile-modal-overlay.active {
        display: flex;
    }
    .profile-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        max-width: 400px;
        width: 90%;
        position: relative;
        overflow: hidden;
        animation: profileSlideIn 0.3s ease;
    }
    [data-theme="dark"] .profile-card { background: #1e293b; }
    @keyframes profileSlideIn {
        from { transform: scale(0.8) translateY(30px); opacity: 0; }
        to { transform: scale(1) translateY(0); opacity: 1; }
    }
    .profile-card-header {
        background: linear-gradient(135deg, #0d47a1 0%, #1976d2 100%);
        color: white;
        padding: 20px;
        text-align: center;
        position: relative;
    }
    .profile-card-header h5 {
        font-weight: 700;
        margin: 0;
        font-size: 1.1rem;
    }
    .profile-card-header small {
        opacity: 0.85;
    }
    .profile-close {
        position: absolute;
        top: 10px;
        right: 15px;
        background: rgba(255,255,255,0.2);
        border: none;
        color: white;
        font-size: 1.2rem;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }
    .profile-close:hover {
        background: rgba(255,255,255,0.4);
    }
    .profile-card-body {
        padding: 25px;
        text-align: center;
    }
    .profile-photo {
        width: 120px;
        height: 160px;
        border-radius: 6px;
        object-fit: cover;
        border: 3px solid #e3f2fd;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        margin-bottom: 15px;
        background: #e3f2fd;
    }
    .profile-info-list {
        list-style: none;
        padding: 0;
        margin: 15px 0 0;
        text-align: left;
    }
    .profile-info-list li {
        padding: 10px 15px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 0.9rem;
    }
    [data-theme="dark"] .profile-info-list li { border-bottom-color: #2a2a4a; }
    .profile-info-list li:last-child {
        border-bottom: none;
    }
    .profile-info-list i {
        color: #1565c0;
        width: 20px;
        text-align: center;
    }
    .profile-info-list .info-label {
        color: #999;
        font-size: 0.8rem;
    }
    .profile-info-list .info-value {
        font-weight: 600;
        color: #333;
    }
    [data-theme="dark"] .profile-info-list .info-value { color: #e0e0e0; }

    /* Hint text */
    .click-hint {
        font-size: 0.75rem;
        color: #999;
        margin-top: 15px;
    }

    /* ===== MOBILE RESPONSIVE ===== */
    @media (max-width: 767.98px) {
        /* Keep same horizontal layout as PC, just scale everything down */
        .org-chart {
            padding: 8px 2px !important;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .card-body.p-4.org-chart {
            padding: 10px 5px !important;
        }
        .chart-title {
            padding: 12px 8px;
        }
        .chart-title h4 {
            font-size: 0.75rem;
            letter-spacing: 0.3px;
        }

        /* Scale down all org boxes */
        .org-box {
            font-size: 6px;
            padding: 4px 6px;
            min-width: 50px;
            border-radius: 4px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.15);
            letter-spacing: 0;
            white-space: nowrap;
        }
        .org-box-primary {
            padding: 6px 10px;
            font-size: 7px;
            min-width: 70px;
        }
        .org-box-secondary {
            padding: 5px 8px;
            font-size: 6.5px;
        }
        .org-box-tertiary {
            font-size: 6px;
            padding: 4px 6px;
        }
        .org-box-small {
            font-size: 5px;
            padding: 3px 4px;
            min-width: 30px;
        }
        .org-box-subdis {
            padding: 5px 6px;
            font-size: 5.5px;
            min-width: 60px;
        }
        .org-box-item {
            font-size: 5px;
            padding: 3px 5px;
            min-width: 40px;
        }

        /* Thin connecting lines */
        .line-down {
            width: 1px;
        }
        .line-down-30 { height: 15px; }
        .line-down-25 { height: 12px; }
        .line-down-20 { height: 10px; }
        .line-down-15 { height: 8px; }
        .line-down[style*="height: 60px"] {
            height: 25px !important;
        }

        /* Tree connectors thinner */
        .tree-row::before {
            height: 1px;
        }
        .tree-col::before {
            width: 1px;
            height: 15px;
        }
        .tree-col {
            padding-top: 15px;
        }
        .tree-col.tree-col-sm {
            padding-top: 10px;
        }
        .tree-col.tree-col-sm::before {
            height: 10px;
        }

        /* Subdis containers smaller */
        .subdis-container {
            padding: 5px;
            border-radius: 5px;
        }
        .subdis-items {
            gap: 2px;
        }

        /* Section labels */
        .section-label {
            font-size: 5px;
            padding: 2px 5px;
            border-radius: 3px;
        }

        /* Spacing */
        .container.my-5 {
            margin-top: 1rem !important;
            margin-bottom: 1rem !important;
            padding-left: 5px;
            padding-right: 5px;
        }

        /* Subdis columns tighter */
        .tree-row.cols-4 > .tree-col {
            padding-left: 3px !important;
            padding-right: 3px !important;
        }

        /* Legend */
        .card.mt-4 .card-body {
            padding: 10px;
            font-size: 0.7rem;
        }
    }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="container">
        <h2><i class="fas fa-sitemap me-2"></i>{{ __('messages.hero_struktur') }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profil.index') }}">{{ __('messages.profile') }}</a></li>
                <li class="breadcrumb-item active">{{ __('messages.org_structure') }}</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Profile Modal -->
<div class="profile-modal-overlay" id="profileModal">
    <div class="profile-card">
        <div class="profile-card-header">
            <button class="profile-close" onclick="closeProfile()">&times;</button>
            <h5 id="profileJabatan">-</h5>
            <small id="profileUnit">-</small>
        </div>
        <div class="profile-card-body">
            <img src="" alt="{{ __('messages.alt_foto_pejabat') }}" class="profile-photo" id="profilePhoto">
            <h5 class="fw-bold mb-1" id="profileNama">-</h5>
            <span class="badge bg-primary mb-3" id="profilePangkat">-</span>
            <ul class="profile-info-list">
                <li>
                    <i class="fas fa-briefcase"></i>
                    <div>
                        <div class="info-label">{{ __('messages.label_jabatan') }}</div>
                        <div class="info-value" id="profileJabatanFull">-</div>
                    </div>
                </li>
                <li>
                    <i class="fas fa-calendar-alt"></i>
                    <div>
                        <div class="info-label">{{ __('messages.label_tanggal_lahir') }}</div>
                        <div class="info-value" id="profileTTL">-</div>
                    </div>
                </li>
                <li>
                    <i class="fas fa-id-card"></i>
                    <div>
                        <div class="info-label">{{ __('messages.label_nrp') }}</div>
                        <div class="info-value" id="profileNRP">-</div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="container my-5">

    <div class="card card-struktur">
        <div class="chart-title">
            <h4>{{ __('messages.heading_struktur') }}</h4>
        </div>
        <div class="card-body p-4 org-chart">
            
            <!-- ====== LEVEL 1: KADISINFOLAHTAAU ====== -->
            <div class="text-center position-relative">
                <div class="org-box org-box-primary" onclick="showProfile('kadisinfolahtaau')">KADISINFOLAHTAAU</div>
                <!-- Section Labels (absolute so they don't break the line) -->
                <div style="position: absolute; right: 0; top: 0;">
                    <div class="d-flex flex-column align-items-end gap-1">
                        <span class="section-label">{{ __('messages.label_unsur_pembantu') }}</span>
                    </div>
                </div>
            </div>
            <div class="line-down" style="height: 60px;"></div>
            
            <!-- ====== LEVEL 2: SETDIS ====== -->
            <div class="text-center">
                <div class="org-box org-box-secondary" onclick="showProfile('setdis')">SETDIS</div>
            </div>
            <div class="line-down line-down-25"></div>

            <!-- ====== LEVEL 3: BAGUM / BAGPROGAR / BAGBINPROF ====== -->
            <div class="tree-row cols-3">
                <!-- BAGUM Column -->
                <div class="tree-col">
                    <div class="org-box org-box-tertiary" onclick="showProfile('bagum')">BAGUM</div>
                    <div class="line-down line-down-15"></div>
                    <div class="org-box org-box-small" onclick="showProfile('subbagmin')">SUBBAGMIN</div>
                    <div class="line-down line-down-15"></div>
                    <!-- UR items (4 children) -->
                    <div class="tree-row cols-4" style="width: 100%;">
                        <div class="tree-col tree-col-sm" style="padding-left: 2px; padding-right: 2px;">
                            <div class="org-box org-box-small" style="font-size: 8px; padding: 5px 8px;" onclick="showProfile('urtu')">URTU</div>
                        </div>
                        <div class="tree-col tree-col-sm" style="padding-left: 2px; padding-right: 2px;">
                            <div class="org-box org-box-small" style="font-size: 8px; padding: 5px 8px;" onclick="showProfile('urdal')">URDAL</div>
                        </div>
                        <div class="tree-col tree-col-sm" style="padding-left: 2px; padding-right: 2px;">
                            <div class="org-box org-box-small" style="font-size: 8px; padding: 5px 8px;" onclick="showProfile('urpers')">URPERS</div>
                        </div>
                        <div class="tree-col tree-col-sm" style="padding-left: 2px; padding-right: 2px;">
                            <div class="org-box org-box-small" style="font-size: 8px; padding: 5px 8px;" onclick="showProfile('urbmn')">UR BMN</div>
                        </div>
                    </div>
                </div>
                
                <!-- BAGPROGAR Column -->
                <div class="tree-col">
                    <div class="org-box org-box-tertiary" onclick="showProfile('bagprogar')">BAGPROGAR</div>
                </div>
                
                <!-- BAGBINPROF Column -->
                <div class="tree-col">
                    <div class="org-box org-box-tertiary" onclick="showProfile('bagbinprof')">BAGBINPROF</div>
                    <div class="line-down line-down-15"></div>
                    <div class="org-box org-box-small" onclick="showProfile('subbagpers')">SUBBAGPERS</div>
                </div>
            </div>
            
            <!-- Separator label -->
            <div class="text-end position-relative" style="margin-top: 15px; margin-bottom: 10px;">
                <span class="section-label">{{ __('messages.label_unsur_pelaksana') }}</span>
            </div>
            
            <!-- ====== LEVEL 4: SUBDIS ROW ====== -->
            <div class="tree-row cols-4 w-100">
                <div class="tree-col" style="padding-left: 10px; padding-right: 10px;">
                    <div class="org-box org-box-subdis" onclick="showProfile('subdissidukops')">SUBDISSIDUKOPS</div>
                    <div class="line-down line-down-15"></div>
                    <div class="subdis-container mt-2">
                        <div class="subdis-items">
                            <div class="org-box org-box-item" onclick="showProfile('siapldatabase_ops')">SIAPLDATABASE</div>
                            <div class="org-box org-box-item" onclick="showProfile('subsiapl_ops')">SUBSIAPL</div>
                            <div class="org-box org-box-item" onclick="showProfile('urrenharapl_ops')">URRENHARAPL</div>
                            <div class="org-box org-box-item" onclick="showProfile('subsidatabase_ops')">SUBSIDATABASE</div>
                            <div class="org-box org-box-item" onclick="showProfile('urrenhardb_ops')">URRENHAR DATA BASE</div>
                            <div class="org-box org-box-item" onclick="showProfile('sikompjar_ops')">SIKOMPJAR</div>
                            <div class="org-box org-box-item" onclick="showProfile('subsikomp_ops')">SUBSIKOMP</div>
                            <div class="org-box org-box-item" onclick="showProfile('urrenharkomp_ops')">URRENHARKOMP</div>
                            <div class="org-box org-box-item" onclick="showProfile('subsjar_ops')">SUBSJAR</div>
                            <div class="org-box org-box-item" onclick="showProfile('urrenharjar_ops')">URRENHARJAR</div>
                        </div>
                    </div>
                </div>
                
                <div class="tree-col" style="padding-left: 10px; padding-right: 10px;">
                    <div class="org-box org-box-subdis" onclick="showProfile('subdissidukpers')">SUBDISSIDUKPERS</div>
                    <div class="line-down line-down-15"></div>
                    <div class="subdis-container mt-2">
                        <div class="subdis-items">
                            <div class="org-box org-box-item" onclick="showProfile('siapldatabase_pers')">SIAPLDATABASE</div>
                            <div class="org-box org-box-item" onclick="showProfile('subsiapl_pers')">SUBSIAPL</div>
                            <div class="org-box org-box-item" onclick="showProfile('urrenharapl_pers')">URRENHARAPL</div>
                            <div class="org-box org-box-item" onclick="showProfile('subsidatabase_pers')">SUBSIDATABASE</div>
                            <div class="org-box org-box-item" onclick="showProfile('urrenhardb_pers')">URRENHAR DATA BASE</div>
                            <div class="org-box org-box-item" onclick="showProfile('sikompjar_pers')">SIKOMPJAR</div>
                            <div class="org-box org-box-item" onclick="showProfile('subsikomp_pers')">SUBSIKOMP</div>
                            <div class="org-box org-box-item" onclick="showProfile('urrenharkomp_pers')">URRENHARKOMP</div>
                            <div class="org-box org-box-item" onclick="showProfile('subsjar_pers')">SUBSJAR</div>
                            <div class="org-box org-box-item" onclick="showProfile('urrenharjar_pers')">URRENHARJAR</div>
                            <div class="org-box org-box-item" onclick="showProfile('sigarku_pers')">SIGARKU</div>
                            <div class="org-box org-box-item" onclick="showProfile('subsiapl2_pers')">SUBSIAPL</div>
                            <div class="org-box org-box-item" onclick="showProfile('urrenharapl2_pers')">URRENHARAPL</div>
                            <div class="org-box org-box-item" onclick="showProfile('subsidatabase2_pers')">SUBSIDATABASE</div>
                            <div class="org-box org-box-item" onclick="showProfile('urrenhardb2_pers')">URRENHAR DATABASE</div>
                        </div>
                    </div>
                </div>
                
                <div class="tree-col" style="padding-left: 10px; padding-right: 10px;">
                    <div class="org-box org-box-subdis" onclick="showProfile('subdissiduklog')">SUBDISSIDUKLOG</div>
                    <div class="line-down line-down-15"></div>
                    <div class="subdis-container mt-2">
                        <div class="subdis-items">
                            <div class="org-box org-box-item" onclick="showProfile('siapldatabase_log')">SIAPLDATABASE</div>
                            <div class="org-box org-box-item" onclick="showProfile('subsiapl_log')">SUBSIAPL</div>
                            <div class="org-box org-box-item" onclick="showProfile('urrenharapl_log')">URRENHARAPL</div>
                            <div class="org-box org-box-item" onclick="showProfile('subsidatabase_log')">SUBSIDATABASE</div>
                            <div class="org-box org-box-item" onclick="showProfile('urrenhardb_log')">URRENHAR DATA BASE</div>
                            <div class="org-box org-box-item" onclick="showProfile('sikompjar_log')">SIKOMPJAR</div>
                            <div class="org-box org-box-item" onclick="showProfile('subsikomp_log')">SUBSIKOMP</div>
                            <div class="org-box org-box-item" onclick="showProfile('urrenharkomp_log')">URRENHARKOMP</div>
                            <div class="org-box org-box-item" onclick="showProfile('subsjar_log')">SUBSJAR</div>
                            <div class="org-box org-box-item" onclick="showProfile('urrenharjar_log')">URRENHARJAR</div>
                        </div>
                    </div>
                </div>
                
                <div class="tree-col" style="padding-left: 10px; padding-right: 10px;">
                    <div class="org-box org-box-subdis" onclick="showProfile('subdissiduksissmin')">SUBDISSIDUKSISSMIN</div>
                    <div class="line-down line-down-15"></div>
                    <div class="subdis-container mt-2">
                        <div class="subdis-items">
                            <div class="org-box org-box-item" onclick="showProfile('siapldatabase_sis')">SIAPLDATABASE</div>
                            <div class="org-box org-box-item" onclick="showProfile('subsiapl_sis')">SUBSIAPL</div>
                            <div class="org-box org-box-item" onclick="showProfile('urrenharapl_sis')">URRENHARAPL</div>
                            <div class="org-box org-box-item" onclick="showProfile('subsidatabase_sis')">SUBSIDATABASE</div>
                            <div class="org-box org-box-item" onclick="showProfile('urrenhardb_sis')">URRENHAR DATA BASE</div>
                            <div class="org-box org-box-item" onclick="showProfile('sikompjar_sis')">SIKOMPJAR</div>
                            <div class="org-box org-box-item" onclick="showProfile('subsikomp_sis')">SUBSIKOMP</div>
                            <div class="org-box org-box-item" onclick="showProfile('urrenharkomp_sis')">URRENHARKOMP</div>
                            <div class="org-box org-box-item" onclick="showProfile('subsjar_sis')">SUBSJAR</div>
                            <div class="org-box org-box-item" onclick="showProfile('urrenharjar_sis')">URRENHARJAR</div>
                            <div class="org-box org-box-item" onclick="showProfile('pustasinfo')">PUSTASINFO</div>
                            <div class="org-box org-box-item" onclick="showProfile('subsihar')">SUBSIHAR</div>
                            <div class="org-box org-box-item" onclick="showProfile('urharapljar')">URHARAPLJAR</div>
                            <div class="org-box org-box-item" onclick="showProfile('subsiops')">SUBSIOPS</div>
                            <div class="org-box org-box-item" onclick="showProfile('uropsapljar')">UROPSAPLJAR</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Legend -->
    <div class="card mt-4">
        <div class="card-body" translate="yes">
            <h6 class="mb-3"><i class="fas fa-info-circle text-primary"></i> {{ __('messages.heading_keterangan') }}</h6>
            <div class="row">
                <div class="col-md-6">
                    <ul class="list-unstyled small">
                        <li class="mb-1"><strong>KADISINFOLAHTAAU</strong> - Kepala Dinas Informasi dan Pengolahan Data TNI AU</li>
                        <li class="mb-1"><strong>SETDIS</strong> - Sekretariat Dinas</li>
                        <li class="mb-1"><strong>BAGUM</strong> - Bagian Umum</li>
                        <li class="mb-1"><strong>BAGPROGAR</strong> - Bagian Program dan Anggaran</li>
                        <li class="mb-1"><strong>BAGBINPROF</strong> - Bagian Pembinaan Profesi</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-unstyled small">
                        <li class="mb-1"><strong>SUBDISSIDUKOPS</strong> - Sub Dinas Sistem Dukungan Operasi</li>
                        <li class="mb-1"><strong>SUBDISSIDUKPERS</strong> - Sub Dinas Sistem Dukungan Personel</li>
                        <li class="mb-1"><strong>SUBDISSIDUKLOG</strong> - Sub Dinas Sistem Dukungan Logistik</li>
                        <li class="mb-1"><strong>SUBDISSIDUKSISSMIN</strong> - Sub Dinas Sistem Dukungan Sistem Informasi Manajemen</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Data profil pejabat dari database
    const profileData = {
        @foreach($strukturs as $kode => $s)
        '{{ $kode }}': {
            jabatan: '{{ addslashes($s->nama_jabatan) }}',
            unit: '{{ addslashes($s->unit ?? $s->nama_lengkap_jabatan) }}',
            nama: '{{ addslashes($s->nama_pejabat ?? "-") }}',
            pangkat: '{{ addslashes($s->pangkat ?? "-") }}',
            jabatanFull: '{{ addslashes($s->nama_lengkap_jabatan) }}',
            ttl: '{{ $s->tanggal_lahir ? $s->tanggal_lahir->translatedFormat("d F Y") : "-" }}',
            nrp: '{{ addslashes($s->nrp ?? "-") }}',
            foto: '{{ $s->foto ? asset("storage/" . $s->foto) : asset("assets/image/default-profile.svg") }}'
        },
        @endforeach
    };

    // Default profile for items not in the database
    const defaultProfile = {
        jabatan: '-',
        unit: '-',
        nama: '-',
        pangkat: '-',
        jabatanFull: '-',
        ttl: '-',
        nrp: '-',
        foto: '{{ asset("assets/image/default-profile.svg") }}'
    };

    function showProfile(key) {
        const data = profileData[key] || {...defaultProfile, jabatan: key.toUpperCase(), jabatanFull: key.toUpperCase()};
        
        document.getElementById('profileJabatan').textContent = data.jabatan;
        document.getElementById('profileUnit').textContent = data.unit;
        document.getElementById('profileNama').textContent = data.nama;
        const pangkatEl = document.getElementById('profilePangkat');
        pangkatEl.textContent = data.pangkat;
        pangkatEl.style.display = (data.pangkat && data.pangkat !== '-') ? '' : 'none';
        document.getElementById('profileJabatanFull').textContent = data.jabatanFull;
        document.getElementById('profileTTL').textContent = data.ttl;
        document.getElementById('profileNRP').textContent = data.nrp;
        document.getElementById('profilePhoto').src = data.foto;
        
        document.getElementById('profileModal').classList.add('active');
    }

    function closeProfile() {
        document.getElementById('profileModal').classList.remove('active');
    }

    // Close on overlay click
    document.getElementById('profileModal').addEventListener('click', function(e) {
        if (e.target === this) closeProfile();
    });

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeProfile();
    });
</script>
@endpush
