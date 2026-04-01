@extends('layouts.admin')
@section('page-title', 'Kelola Menu Kustom')

@push('styles')
<style>
    .setting-card { border:none; box-shadow:0 2px 12px rgba(0,61,130,0.10); border-radius:12px; }
    .setting-card .card-header { background:linear-gradient(135deg,#001f3f 0%,#003d82 100%); color:white; border-radius:12px 12px 0 0 !important; padding:16px 24px; }

    /* Menu tree */
    .menu-tree { list-style:none; padding:0; margin:0; }
    .menu-tree .menu-item { background:#fff; border:1px solid #e9ecef; border-radius:10px; margin-bottom:10px; transition:all 0.2s; }
    .menu-tree .menu-item:hover { box-shadow:0 4px 12px rgba(0,61,130,0.10); }
    .menu-item-header { padding:13px 16px; display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
    .drag-handle { color:#adb5bd; font-size:16px; cursor:grab; flex-shrink:0; }
    .menu-icon { width:36px; height:36px; border-radius:8px; background:linear-gradient(135deg,#003d82,#0066cc); color:#fff; display:flex; align-items:center; justify-content:center; font-size:14px; flex-shrink:0; }
    .menu-info { flex:1; min-width:0; }
    .menu-info h6 { margin:0; font-weight:600; font-size:14px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .menu-info small { color:#6c757d; font-size:12px; }
    .menu-actions .btn { border-radius:7px; padding:4px 10px; font-size:12px; }
    .badge-published { background:#d4edda; color:#155724; font-weight:500; font-size:11px; }
    .badge-draft { background:#f8d7da; color:#721c24; font-weight:500; font-size:11px; }
    .widget-count-badge { background:linear-gradient(135deg,#667eea,#764ba2); color:#fff; font-size:10px; padding:2px 7px; border-radius:10px; }
    .empty-state { text-align:center; padding:48px 20px; }
    .empty-state i { font-size:64px; color:#dee2e6; margin-bottom:16px; }
    .pos-badge { font-size:10px; background:#e9ecef; color:#495057; padding:1px 6px; border-radius:4px; margin-left:4px; font-weight:400; }

    /* Sub-menus */
    .sub-menus { padding:0 16px 12px 56px; }
    .sub-menus .menu-item { border-left:3px solid #0066cc; background:#f8f9fd; }
    .sub-menu-icon { background:linear-gradient(135deg,#5dade2,#3498db) !important; width:30px !important; height:30px !important; font-size:12px !important; border-radius:8px; color:#fff; display:flex; align-items:center; justify-content:center; flex-shrink:0; }

    /* Inline edit form */
    .inline-edit-form { display:none; background:#f0f4ff; border-top:1px solid #dce4f5; padding:14px 16px; border-radius:0 0 10px 10px; }
    .inline-edit-form.show { display:flex; flex-wrap:wrap; gap:10px; align-items:flex-end; }
    .inline-edit-form .form-group { display:flex; flex-direction:column; gap:4px; }
    .inline-edit-form label { font-size:11px; font-weight:600; color:#555; margin:0; }
    .inline-edit-form .form-control, .inline-edit-form .form-select { font-size:13px; padding:6px 10px; border-radius:7px; }
    .icon-preview-wrap { display:flex; align-items:center; gap:6px; }
    .icon-preview-sm { width:28px; height:28px; border-radius:6px; background:linear-gradient(135deg,#003d82,#0066cc); color:#fff; display:flex; align-items:center; justify-content:center; font-size:13px; flex-shrink:0; }
    .btn-save { background:linear-gradient(135deg,#16a34a,#22c55e); color:#fff; border:none; }
    .btn-save:hover { color:#fff; opacity:0.9; }

    /* Drag feedback */
    .sortable-ghost { opacity:0.35; background:#e0e8ff !important; }
    .sortable-chosen { box-shadow:0 4px 16px rgba(0,61,130,0.25) !important; }

    /* Sub-menu toggle */
    .sub-toggle-btn { font-size:11px !important; padding:3px 9px !important; border-radius:20px !important; transition:all 0.2s; }
    .sub-toggle-btn i { transition:transform 0.25s; }
    .sub-toggle-btn.open i { transform:rotate(180deg); }
    .sub-menus-wrap { overflow:hidden; transition:max-height 0.3s ease; }
    .sub-menus-wrap.collapsed { max-height:0 !important; }
    /* Quick add sub-menu btn */
    .quick-add-sub { font-size:11px !important; padding:3px 9px !important; border-radius:20px !important; }

    /* Position selector */
    .pos-options { display:flex; flex-direction:column; gap:4px; max-height:220px; overflow-y:auto; padding:2px; }
    .pos-option { padding:8px 12px; border:1px solid #dee2e6; border-radius:8px; cursor:pointer; display:flex; align-items:center; gap:8px; transition:all 0.15s; font-size:13px; }
    .pos-option:hover, .pos-option.selected { border-color:#0066cc; background:#eef5ff; }
    .pos-option .pos-icon { width:28px; height:28px; border-radius:6px; background:linear-gradient(135deg,#003d82,#0066cc); color:#fff; display:flex; align-items:center; justify-content:center; font-size:12px; flex-shrink:0; }
    .pos-option.top-option { border-style:dashed; color:#0066cc; font-weight:600; }
    .pos-option.top-option .pos-icon { background:linear-gradient(135deg,#22c55e,#16a34a); }
    .pos-divider { border:none; border-top:2px dashed #dee2e6; margin:2px 0; }

    /* Built-in website nav section */
    .builtin-toggle { cursor:pointer; user-select:none; }
    .builtin-toggle:hover { background:rgba(255,255,255,0.06); }
    .builtin-sub-wrap { overflow:hidden; transition:max-height 0.3s ease; }
    .builtin-sub-wrap.collapsed { max-height:0 !important; }
    .builtin-tag { font-size:10px; padding:2px 7px; border-radius:10px; font-weight:600; background:#e8f0fe; color:#1a56db; }
    .builtin-label { font-size:10.5px; font-weight:700; color:#fff; padding:2px 8px; border-radius:6px; }
    .nav-section-divider { font-size:11px; color:#adb5bd; text-transform:uppercase; letter-spacing:.08em; font-weight:700; padding:8px 16px 4px; }

    /* Mobile: compact layout - name full width, icon-only buttons */
    @media (max-width: 575.98px) {
        /* Header: wrap so name + icon stay on row 1, badge+actions go row 2 */
        .menu-item-header { padding: 10px 12px; gap: 8px; }
        .menu-info { min-width: 0; flex: 1 1 0; }
        .menu-info h6 { font-size: 13px; white-space: normal; word-break: break-word; }

        /* Badge and actions always go to their own row under the name */
        .menu-item-header .badge-published,
        .menu-item-header .badge-draft { order: 3; }
        .menu-actions { order: 4; width: 100%; justify-content: flex-end; flex-wrap: nowrap !important; }

        /* Icon-only buttons */
        .menu-actions .btn { font-size: 0 !important; padding: 5px 8px !important; }
        .menu-actions .btn i,
        .menu-actions .btn .fa,
        .menu-actions .btn .fas { font-size: 12px !important; }

        /* Sub-toggle text label hidden, just icon */
        .menu-actions .sub-toggle-btn,
        .menu-actions .quick-add-sub { font-size: 0 !important; padding: 5px 8px !important; border-radius: 8px !important; }
        .menu-actions .sub-toggle-btn i,
        .menu-actions .quick-add-sub i { font-size: 12px !important; }

        /* Built-in menu section: keep title/link readable, move action buttons to next row */
        .builtin-toggle { align-items: flex-start !important; }
        .builtin-toggle .menu-info { flex: 1 1 calc(100% - 46px); min-width: 0; }
        .builtin-toggle .menu-info h6 {
            display: inline;
            white-space: nowrap;
            word-break: normal;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .builtin-toggle .menu-info small {
            display: inline;
            margin-left: 8px;
            white-space: normal;
        }
        .builtin-toggle .menu-info small a {
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 3px;
        }
        .builtin-toggle > .builtin-tag,
        .builtin-toggle > .btn,
        .builtin-toggle > a.btn {
            order: 3;
            margin-top: 6px;
        }
        .builtin-toggle > .builtin-tag { margin-left: auto; }

        /* Built-in child rows: keep name + 'Lihat halaman' compact, move Edit below-right */
        .sub-menus .menu-item-header { align-items: flex-start; }
        .sub-menus .menu-item-header .menu-info { flex: 1 1 calc(100% - 40px); min-width: 0; }
        .sub-menus .menu-item-header .menu-info h6 {
            white-space: nowrap;
            word-break: normal;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sub-menus .menu-item-header .menu-info small a {
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 3px;
        }
        .sub-menus .menu-item-header > a.btn {
            order: 3;
            margin-left: auto;
            margin-top: 6px;
        }

        /* Header adjustments (mobile only): align left icon with title and shrink sizes */
        .page-header-custom-menu { align-items: flex-start; gap: 10px; flex-wrap: wrap !important; }
        .page-header-custom-menu > div:first-child { width:40px !important; height:40px !important; border-radius:8px !important; }
        .page-header-custom-menu .flex-grow-1 { flex: 1 1 calc(100% - 50px); min-width: 0; margin-top: -2px; }
        .page-header-custom-menu h4 { font-size:16px !important; line-height:1.05; margin-bottom:4px; }
        .page-header-custom-menu small { font-size:12px !important; }

        /* Make the Add button compact and single-line on mobile */
        .page-header-custom-menu .btn.btn-primary { font-size:11px !important; padding:6px 9px !important; white-space:nowrap !important; max-width:none; text-align:center; display:inline-flex; align-items:center; line-height:1; margin-left:auto; flex-shrink:0; order:3; }
        .page-header-custom-menu > .btn.btn-primary { margin-top:2px; }
        .page-header-custom-menu .btn.btn-primary i { margin-right:6px; font-size:14px; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">

            {{-- Header --}}
            <div class="d-flex align-items-center mb-4 gap-3 page-header-custom-menu">
                <div style="background:linear-gradient(135deg,#001f3f,#0066cc);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-bars text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-0 fw-bold text-dark">Menu Kustom</h4>
                    <small class="text-muted">Buat dan kelola menu. Geser baris untuk mengubah urutan.</small>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMenuModal">
                    <i class="fas fa-plus me-1"></i> Tambah Menu / Sub-Menu
                </button>
            </div>

            {{-- Alert --}}
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- Menu List --}}
            <div class="card setting-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-bars me-2"></i>Daftar Menu</h6>
                    <span class="badge bg-light text-dark">{{ $menus->count() }} menu utama</span>
                </div>
                <div class="card-body p-3">
                    @if($menus->count())
                    <ul class="menu-tree" id="menuSortable">
                        @foreach($menus as $menu)
                        <li class="menu-item" data-id="{{ $menu->id }}" data-pos="{{ $menu->position }}">
                            {{-- Header row --}}
                            <div class="menu-item-header">
                                <div class="drag-handle"><i class="fas fa-grip-vertical"></i></div>
                                <div class="menu-icon"><i class="{{ $menu->icon }}"></i></div>
                                <div class="menu-info">
                                    <h6>{{ $menu->name }} <span class="pos-badge">#{{ $menu->position + 1 }}</span></h6>
                                    <small>/halaman/{{ $menu->slug }}
                                        @if($menu->children->count())
                                            &middot; {{ $menu->children->count() }} sub-menu
                                        @endif
                                        @if($menu->widgets->count())
                                            <span class="widget-count-badge ms-1">{{ $menu->widgets->count() }} widget</span>
                                        @endif
                                    </small>
                                </div>
                                <span class="badge {{ $menu->is_published ? 'badge-published' : 'badge-draft' }} me-1">
                                    {{ $menu->is_published ? 'Published' : 'Draft' }}
                                </span>
                                <div class="menu-actions d-flex gap-1 flex-wrap">
                                    <a href="{{ route('admin.custom-menu.edit', $menu->id) }}" class="btn btn-outline-primary btn-sm" title="Edit Widget Konten">
                                        <i class="fas fa-cog me-1"></i><span class="d-none d-sm-inline">{{ __('messages.edit') }}</span>
                                    </a>
                                    @if($menu->children->count())
                                    <button type="button" class="btn btn-outline-info btn-sm sub-toggle-btn open"
                                            onclick="toggleSubMenu({{ $menu->id }}, this)"
                                            title="Tampilkan/Sembunyikan Sub-Menu">
                                        <i class="fas fa-chevron-up"></i> {{ $menu->children->count() }} sub
                                    </button>
                                    @endif
                                    <button type="button" class="btn btn-outline-success btn-sm quick-add-sub"
                                            onclick="quickAddSub({{ $menu->id }}, '{{ addslashes($menu->name) }}')"
                                            title="Tambah Sub-Menu di sini">
                                        <i class="fas fa-plus"></i> Sub-Menu
                                    </button>
                                    <form action="{{ route('admin.custom-menu.toggle-publish', $menu->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-{{ $menu->is_published ? 'warning' : 'success' }} btn-sm" title="{{ $menu->is_published ? 'Unpublish' : 'Publish' }}">
                                            <i class="fas fa-{{ $menu->is_published ? 'eye-slash' : 'eye' }}"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleEdit('edit-{{ $menu->id }}')" title="Edit">
                                        <i class="fas fa-pen"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.custom-menu.destroy', $menu->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus menu \'{{ addslashes($menu->name) }}\' beserta semua sub-menu dan kontennya?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>

                            {{-- Inline Edit Form --}}
                            <form action="{{ route('admin.custom-menu.update', $menu->id) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="inline-edit-form" id="edit-{{ $menu->id }}">
                                    <div class="form-group" style="min-width:160px;flex:1;">
                                        <label><i class="fas fa-tag me-1"></i> Nama Menu</label>
                                        <input type="text" name="name" class="form-control" value="{{ $menu->name }}" required>
                                    </div>
                                    <div class="form-group" style="min-width:180px;flex:1;">
                                        <label><i class="fas fa-level-up-alt me-1"></i> Parent</label>
                                        <select name="parent_id" class="form-select">
                                            <option value="">— Menu Utama —</option>
                                            @foreach($menus as $pm)
                                                @if($pm->id !== $menu->id)
                                                <option value="{{ $pm->id }}" {{ $menu->parent_id == $pm->id ? 'selected' : '' }}>{{ $pm->name }}</option>
                                                    @foreach($pm->children as $pc)
                                                        @if($pc->id !== $menu->id)
                                                        <option value="{{ $pc->id }}" {{ $menu->parent_id == $pc->id ? 'selected' : '' }}>&nbsp;&nbsp;&nbsp;↳ {{ $pc->name }}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group" style="min-width:180px;flex:1;">
                                        <label><i class="fas fa-icons me-1"></i> Icon</label>
                                        <div class="icon-preview-wrap">
                                            <span class="icon-preview-sm edit-icon-preview-{{ $menu->id }}"><i class="{{ $menu->icon }}"></i></span>
                                            <input type="text" name="icon" class="form-control edit-icon-input" value="{{ $menu->icon }}"
                                                   data-preview="edit-icon-preview-{{ $menu->id }}" placeholder="fas fa-file-alt">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div class="d-flex gap-1">
                                            <button type="submit" class="btn btn-save btn-sm"><i class="fas fa-save me-1"></i> Simpan</button>
                                            <button type="button" class="btn btn-light btn-sm" onclick="toggleEdit('edit-{{ $menu->id }}')">{{ __('messages.batal') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            {{-- Sub-menu rows --}}
                            @if($menu->children->count())
                            <div class="sub-menus-wrap" id="sub-wrap-{{ $menu->id }}" style="max-height:2000px;">
                            <div class="sub-menus" id="sub-sortable-{{ $menu->id }}" data-parent="{{ $menu->id }}">
                                @foreach($menu->children->sortBy('position') as $child)
                                <div class="menu-item" data-id="{{ $child->id }}" data-pos="{{ $child->position }}">
                                    <div class="menu-item-header">
                                        <div class="drag-handle"><i class="fas fa-grip-vertical"></i></div>
                                        <div class="sub-menu-icon"><i class="{{ $child->icon }}"></i></div>
                                        <div class="menu-info">
                                            <h6>{{ $child->name }} <span class="pos-badge">#{{ $child->position + 1 }}</span></h6>
                                            <small>/halaman/{{ $menu->slug }}/{{ $child->slug }}
                                                @if($child->widgets->count())
                                                    <span class="widget-count-badge ms-1">{{ $child->widgets->count() }} widget</span>
                                                @endif
                                            </small>
                                        </div>
                                        <span class="badge {{ $child->is_published ? 'badge-published' : 'badge-draft' }} me-1">
                                            {{ $child->is_published ? 'Published' : 'Draft' }}
                                        </span>
                                        <div class="menu-actions d-flex gap-1 flex-wrap">
                                            <a href="{{ route('admin.custom-menu.edit', $child->id) }}" class="btn btn-outline-primary btn-sm" title="Edit Widget">
                                                <i class="fas fa-cog me-1"></i><span class="d-none d-sm-inline">{{ __('messages.edit') }}</span>
                                            </a>
                                            <form action="{{ route('admin.custom-menu.toggle-publish', $child->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-{{ $child->is_published ? 'warning' : 'success' }} btn-sm">
                                                    <i class="fas fa-{{ $child->is_published ? 'eye-slash' : 'eye' }}"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleEdit('edit-{{ $child->id }}')">
                                                <i class="fas fa-pen"></i> Edit
                                            </button>
                                            <form action="{{ route('admin.custom-menu.destroy', $child->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus sub-menu \'{{ addslashes($child->name) }}\'?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button>
                                            </form>
                                        </div>
                                    </div>

                                    {{-- Inline Edit Form (sub-menu) --}}
                                    <form action="{{ route('admin.custom-menu.update', $child->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="inline-edit-form" id="edit-{{ $child->id }}">
                                            <div class="form-group" style="min-width:160px;flex:1;">
                                                <label><i class="fas fa-tag me-1"></i> Nama Sub-Menu</label>
                                                <input type="text" name="name" class="form-control" value="{{ $child->name }}" required>
                                            </div>
                                            <div class="form-group" style="min-width:180px;flex:1;">
                                                <label><i class="fas fa-level-up-alt me-1"></i> Parent</label>
                                                <select name="parent_id" class="form-select">
                                                    <option value="">— Menu Utama —</option>
                                                    @foreach($menus as $pm)
                                                        @if($pm->id !== $child->id)
                                                        <option value="{{ $pm->id }}" {{ $child->parent_id == $pm->id ? 'selected' : '' }}>{{ $pm->name }}</option>
                                                            @foreach($pm->children as $pc)
                                                                @if($pc->id !== $child->id)
                                                                <option value="{{ $pc->id }}" {{ $child->parent_id == $pc->id ? 'selected' : '' }}>&nbsp;&nbsp;&nbsp;↳ {{ $pc->name }}</option>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group" style="min-width:180px;flex:1;">
                                                <label><i class="fas fa-icons me-1"></i> Icon</label>
                                                <div class="icon-preview-wrap">
                                                    <span class="icon-preview-sm edit-icon-preview-{{ $child->id }}"><i class="{{ $child->icon }}"></i></span>
                                                    <input type="text" name="icon" class="form-control edit-icon-input" value="{{ $child->icon }}"
                                                           data-preview="edit-icon-preview-{{ $child->id }}" placeholder="fas fa-file-alt">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                <div class="d-flex gap-1">
                                                    <button type="submit" class="btn btn-save btn-sm"><i class="fas fa-save me-1"></i> Simpan</button>
                                                    <button type="button" class="btn btn-light btn-sm" onclick="toggleEdit('edit-{{ $child->id }}')">{{ __('messages.batal') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                @endforeach
                            </div>{{-- /.sub-menus --}}
                            </div>{{-- /.sub-menus-wrap --}}
                            @endif
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <div class="empty-state">
                        <i class="fas fa-sitemap d-block"></i>
                        <h5 class="text-muted fw-bold">Belum Ada Menu Kustom</h5>
                        <p class="text-muted">Klik <strong>Tambah Menu / Sub-Menu</strong> di atas untuk menambahkan menu baru.</p>
                    </div>
                    @endif
                </div>
            </div>

            <p class="text-muted small text-center mt-2"><i class="fas fa-grip-vertical me-1"></i> Geser baris menu untuk mengubah urutan. Urutan tersimpan otomatis.</p>

            {{-- ================================================================== --}}
            {{-- MENU BAR WEBSITE — merged into same card below                     --}}
            {{-- ================================================================== --}}
            @php
                $navKategorisBuiltin = \App\Models\Kategori::orderBy('nama_kategori')->get();
                $navEventsBuiltin    = \App\Models\Event::where('is_published', true)->orderBy('position')->get();
                $galeriCategories    = \App\Models\Galeri::$kategoriGaleriOptions;

                /**
                 * Each entry: [icon, label, color (hex border), adminUrl, children[]]
                 * children: [icon, label, url]
                 */
                $builtinMenus = [
                    [
                        'icon'   => 'fas fa-home',
                        'label'  => 'Beranda',
                        'color'  => '#0066cc',
                        'url'    => route('home'),
                        'admin'  => route('admin.dashboard'),
                        'kids'   => [],
                    ],
                    [
                        'icon'   => 'fas fa-user-circle',
                        'label'  => 'Profil',
                        'color'  => '#28a745',
                        'url'    => route('profil.index'),
                        'admin'  => route('admin.profil.kata-pengantar'),
                        'kids'   => [
                            ['icon'=>'fas fa-comment-dots','label'=>'Kata Pengantar','admin'=>route('admin.profil.kata-pengantar'),'url'=>route('profil.kata-pengantar')],
                            ['icon'=>'fas fa-clock','label'=>'Sejarah','admin'=>route('admin.profil.sejarah'),'url'=>route('profil.sejarah')],
                            ['icon'=>'fas fa-sitemap','label'=>'Struktur Organisasi','admin'=>route('admin.struktur.index'),'url'=>route('profil.struktur')],
                            ['icon'=>'fas fa-info-circle','label'=>'Tentang Kami','admin'=>route('admin.profil.tentang'),'url'=>route('profil.index')],
                        ],
                    ],
                    [
                        'icon'   => 'fas fa-newspaper',
                        'label'  => 'Berita',
                        'color'  => '#17a2b8',
                        'url'    => route('berita.index'),
                        'admin'  => route('admin.berita.index'),
                        'kids'   => $navKategorisBuiltin->map(fn($k)=>[
                            'icon'  => 'fas fa-tag',
                            'label' => $k->nama_kategori,
                            'admin' => route('admin.berita.index'),
                            'url'   => route('berita.kategori', $k->slug),
                        ])->toArray(),
                    ],
                    [
                        'icon'   => 'fas fa-shield-alt',
                        'label'  => 'Zona Integritas',
                        'color'  => '#6f42c1',
                        'url'    => route('zona.index'),
                        'admin'  => route('admin.zi.pages.index', ['type'=>'zona_integritas']),
                        'kids'   => [
                            ['icon'=>'fas fa-file-alt','label'=>'Zona Integritas','admin'=>route('admin.zi.pages.index',['type'=>'zona_integritas']),'url'=>route('zona.index')],
                            ['icon'=>'fas fa-bullhorn','label'=>'Perancangan','admin'=>route('admin.zi.perancangan.index'),'url'=>route('zona.perancangan')],
                            ['icon'=>'fas fa-clipboard-check','label'=>'Penetapan','admin'=>route('admin.zi.penetapan.index'),'url'=>route('zona.penetapan')],
                            ['icon'=>'fas fa-hard-hat','label'=>'Pembangunan','admin'=>route('admin.zi.pembangunan.index'),'url'=>route('zona.pembangunan')],
                            ['icon'=>'fas fa-search','label'=>'Pemantauan','admin'=>route('admin.zi.pemantauan.index'),'url'=>route('zona.pemantauan')],
                        ],
                    ],
                    [
                        'icon'   => 'fas fa-users',
                        'label'  => 'PIA',
                        'color'  => '#fd7e14',
                        'url'    => route('pia'),
                        'admin'  => route('admin.pia.index'),
                        'kids'   => [],
                    ],
                    [
                        'icon'   => 'fas fa-book',
                        'label'  => 'e-Library',
                        'color'  => '#20c997',
                        'url'    => route('e-library.index'),
                        'admin'  => route('admin.e-library.index'),
                        'kids'   => [],
                    ],
                    [
                        'icon'   => 'fas fa-images',
                        'label'  => 'Galeri',
                        'color'  => '#e83e8c',
                        'url'    => route('galeri.index'),
                        'admin'  => route('admin.galeri.index'),
                        'kids'   => collect($galeriCategories)->map(fn($label,$key)=>[
                            'icon'  => $key==='video' ? 'fas fa-play-circle' : 'fas fa-camera',
                            'label' => $label,
                            'admin' => route('admin.galeri.index'),
                            'url'   => route('galeri.kategori', $key),
                        ])->values()->toArray(),
                    ],
                    [
                        'icon'   => 'fas fa-hand-holding-heart',
                        'label'  => 'Pelayanan Publik',
                        'color'  => '#dc3545',
                        'url'    => route('pelayanan.berita'),
                        'admin'  => route('admin.pelayanan-publik.berita.index'),
                        'kids'   => [
                            ['icon'=>'fas fa-newspaper','label'=>'Berita Pelayanan Publik','admin'=>route('admin.pelayanan-publik.berita.index'),'url'=>route('pelayanan.berita')],
                            ['icon'=>'fas fa-list-alt','label'=>'Standar Pelayanan Publik','admin'=>route('admin.pelayanan-publik.standar.index'),'url'=>route('pelayanan.standar')],
                            ['icon'=>'fas fa-comments','label'=>'Layanan Pengaduan','admin'=>route('admin.pelayanan-publik.pengaduan.index'),'url'=>route('pelayanan.pengaduan')],
                            ['icon'=>'fas fa-gift','label'=>'Kompensasi Pelayanan','admin'=>route('admin.pelayanan-publik.kompensasi.index'),'url'=>route('pelayanan.kompensasi')],
                            ['icon'=>'fas fa-poll','label'=>'Survei Kepuasan Publik','admin'=>route('admin.pelayanan-publik.survei.index'),'url'=>route('pelayanan.survei')],
                            ['icon'=>'fas fa-chart-bar','label'=>'Hasil Survei Kepuasan','admin'=>route('admin.pelayanan-publik.hasil-survei.index'),'url'=>route('pelayanan.hasil-survei')],
                            ['icon'=>'fas fa-chalkboard-teacher','label'=>'Tutorial','admin'=>route('admin.tutorial.index'),'url'=>route('tutorial')],
                        ],
                    ],
                    [
                        'icon'   => 'fas fa-bullhorn',
                        'label'  => 'SP4N-Lapor!',
                        'color'  => '#ffc107',
                        'url'    => route('sp4n-lapor'),
                        'admin'  => route('admin.sp4n-lapor.index'),
                        'kids'   => [],
                    ],
                    [
                        'icon'   => 'fas fa-comment-exclamation',
                        'label'  => 'Whistle Blowing',
                        'color'  => '#6c757d',
                        'url'    => route('whistle-blowing'),
                        'admin'  => route('admin.whistle-blowing.index'),
                        'kids'   => [],
                    ],
                    [
                        'icon'   => 'fas fa-envelope',
                        'label'  => 'Kontak',
                        'color'  => '#0dcaf0',
                        'url'    => route('kontak.index'),
                        'admin'  => route('admin.kontak.index'),
                        'kids'   => [],
                    ],
                    [
                        'icon'   => 'fas fa-calendar-alt',
                        'label'  => 'Events',
                        'color'  => '#198754',
                        'url'    => route('events.index'),
                        'admin'  => \Route::has('admin.events.show') && $navEventsBuiltin->count() ? route('admin.events.show', $navEventsBuiltin->first()) : route('admin.dashboard'),
                        'kids'   => $navEventsBuiltin->map(fn($e)=>[
                            'icon'  => 'fas fa-calendar-day',
                            'label' => \Illuminate\Support\Str::limit($e->nama_kegiatan, 40),
                            'admin' => route('admin.events.show', $e),
                            'url'   => route('events.show', $e),
                        ])->toArray(),
                    ],
                ];
            @endphp

            <div class="card setting-card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-globe me-2"></i>Menu Bar Website</h6>
                    <span class="builtin-label" style="background:#1a56db;">{{ count($builtinMenus) }} menu bawaan</span>
                </div>
                <div class="card-body p-3">
                    <p class="text-muted small mb-3"><i class="fas fa-info-circle me-1"></i>Semua menu yang tampil di navigasi website beserta sub-menunya. Klik <strong>{{ __('messages.edit') }}</strong> untuk mengedit konten, atau <strong>+ Sub-Menu</strong> untuk menambah sub-menu kustom di bawah menu ini.</p>
                    <ul class="menu-tree" style="list-style:none;padding:0;margin:0;">
                        @php
                        $builtinSubs = \App\Models\CustomMenu::whereNotNull('builtin_parent')
                            ->ordered()->get()->groupBy('builtin_parent');
                        @endphp
                        @foreach($builtinMenus as $bi)
                        @php
                            $biId      = 'bi-'.Str::slug($bi['label']);
                            $biKey     = Str::slug($bi['label']);
                            $biCustoms = $builtinSubs->get($biKey, collect());
                            $totalSubs = count($bi['kids']) + $biCustoms->count();
                        @endphp
                        <li class="menu-item" style="border-left:3px solid {{ $bi['color'] }};">
                            {{-- Header row --}}
                            <div class="menu-item-header builtin-toggle"
                                 @if($totalSubs) onclick="toggleBuiltin('{{ $biId }}')" @endif>
                                <div class="menu-icon" style="background:linear-gradient(135deg,{{ $bi['color'] }},{{ $bi['color'] }}cc);"><i class="{{ $bi['icon'] }}"></i></div>
                                <div class="menu-info">
                                    <h6>{{ $bi['label'] }}</h6>
                                    <small>
                                        @if($totalSubs)
                                            <a href="{{ $bi['url'] }}" target="_blank" class="text-muted text-decoration-none me-2" onclick="event.stopPropagation()">
                                                <i class="fas fa-external-link-alt fa-xs"></i> Lihat
                                            </a>
                                            {{ $totalSubs }} sub-menu
                                        @else
                                            <a href="{{ $bi['url'] }}" target="_blank" class="text-muted text-decoration-none">
                                                <i class="fas fa-external-link-alt fa-xs"></i> Lihat halaman
                                            </a>
                                        @endif
                                    </small>
                                </div>
                                <span class="builtin-tag">Bawaan</span>
                                @if($totalSubs)
                                <span class="btn btn-outline-secondary btn-sm" style="font-size:11px;padding:3px 9px;border-radius:20px;" id="toggle-btn-{{ $biId }}">
                                    <i class="fas fa-chevron-up"></i> {{ $totalSubs }} sub
                                </span>
                                @endif
                                <button type="button" class="btn btn-outline-success btn-sm quick-add-sub" style="font-size:11px;padding:3px 9px;border-radius:20px;"
                                        onclick="event.stopPropagation(); quickAddBuiltin('{{ addslashes($bi['label']) }}')"
                                        title="Tambah Sub-Menu Kustom di bawah {{ $bi['label'] }}">
                                    <i class="fas fa-plus"></i> Sub-Menu
                                </button>
                                <a href="{{ $bi['admin'] }}" class="btn btn-outline-primary btn-sm" style="font-size:11px;" onclick="event.stopPropagation()">
                                    <i class="fas fa-cog me-1"></i>Edit
                                </a>
                            </div>
                            {{-- Sub-menus --}}
                            @if($totalSubs)
                            <div class="builtin-sub-wrap" id="{{ $biId }}" style="max-height:2000px;">
                                <div class="sub-menus pe-2">
                                    {{-- Built-in kids --}}
                                    @foreach($bi['kids'] as $kid)
                                    <div class="menu-item mb-1" style="border-left:3px solid {{ $bi['color'] }}88;background:#fafbff;">
                                        <div class="menu-item-header py-2">
                                            <div class="sub-menu-icon" style="background:linear-gradient(135deg,{{ $bi['color'] }}cc,{{ $bi['color'] }}) !important;"><i class="{{ $kid['icon'] }}"></i></div>
                                            <div class="menu-info">
                                                <h6 style="font-size:13px;">{{ $kid['label'] }}</h6>
                                                <small><a href="{{ $kid['url'] }}" target="_blank" class="text-muted text-decoration-none"><i class="fas fa-external-link-alt fa-xs"></i> Lihat</a></small>
                                            </div>
                                            <a href="{{ $kid['admin'] }}" class="btn btn-outline-secondary btn-sm" style="font-size:11px;">
                                                <i class="fas fa-cog me-1"></i>Edit
                                            </a>
                                        </div>
                                    </div>
                                    @endforeach
                                    {{-- Custom sub-menus attached to this built-in --}}
                                    @foreach($biCustoms as $csub)
                                    <div class="menu-item mb-1" style="border-left:3px solid #667eea;background:#f5f7ff;">
                                        <div class="menu-item-header py-2">
                                            <div class="sub-menu-icon" style="background:linear-gradient(135deg,#667eea,#764ba2) !important;"><i class="{{ $csub->icon }}"></i></div>
                                            <div class="menu-info">
                                                <h6 style="font-size:13px;">{{ $csub->name }}</h6>
                                                <small>
                                                    <a href="{{ url('/halaman/'.$csub->slug) }}" target="_blank" class="text-muted text-decoration-none me-2"><i class="fas fa-external-link-alt fa-xs"></i> Lihat</a>
                                                    <span class="badge bg-primary" style="font-size:9px;">Kustom</span>
                                                </small>
                                            </div>
                                            <a href="{{ route('admin.custom-menu.edit', $csub->id) }}" class="btn btn-outline-primary btn-sm" style="font-size:11px;">
                                                <i class="fas fa-edit me-1"></i>Edit
                                            </a>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- =============================================================== --}}
{{-- Modal Tambah Menu                                               --}}
{{-- =============================================================== --}}
<div class="modal fade" id="addMenuModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form action="{{ route('admin.custom-menu.store') }}" method="POST" id="addMenuForm">
            @csrf
            <input type="hidden" name="position" id="addPositionInput" value="">
            <input type="hidden" name="builtin_parent" id="addBuiltinParentInput" value="">
            <div class="modal-content" style="border-radius:12px;overflow:hidden;">
                <div class="modal-header" style="background:linear-gradient(135deg,#001f3f,#003d82);color:#fff;">
                    <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Tambah Menu / Sub-Menu Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">

                        {{-- Kolom Kiri: Nama & Icon --}}
                        <div class="col-md-5">
                            <div class="mb-3">
                                <label class="form-label fw-semibold"><i class="fas fa-tag me-1 text-primary"></i> Nama Menu</label>
                                <input type="text" name="name" class="form-control" required placeholder="Contoh: Layanan Informasi">
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-semibold"><i class="fas fa-icons me-1 text-success"></i> Icon <small class="text-muted fw-normal">(FontAwesome class)</small></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-file-alt" id="addIconPreviewI"></i></span>
                                    <input type="text" name="icon" class="form-control" value="fas fa-file-alt" placeholder="fas fa-file-alt" id="addIconInput">
                                </div>
                                <small class="text-muted">fas fa-globe, fas fa-book, fas fa-cog</small>
                            </div>
                        </div>

                        {{-- Kolom Kanan: Parent + Posisi --}}
                        <div class="col-md-7">
                            <div class="mb-2">
                                <label class="form-label fw-semibold"><i class="fas fa-level-up-alt me-1 text-info"></i> Tambahkan Sebagai</label>
                                <select name="parent_id" class="form-select" id="addParentSelect">
                                    <option value="">🏠 Menu Utama (Top Level)</option>
                                    @foreach($menus as $m)
                                    <optgroup label="Sub-menu dari: {{ $m->name }}">
                                        <option value="{{ $m->id }}">↳ Sub-menu langsung dari &quot;{{ $m->name }}&quot;</option>
                                        @foreach($m->children as $c)
                                        <option value="{{ $c->id }}">&nbsp;&nbsp;↳ Sub-menu dari &quot;{{ $c->name }}&quot;</option>
                                        @endforeach
                                    </optgroup>
                                    @endforeach                                    <optgroup label="── Sub-menu menu bawaan website ──">
                                        @foreach($builtinMenus as $bi)
                                        @php $biKey = Str::slug($bi['label']); @endphp
                                        <option value="builtin:{{ $biKey }}">&#128205; Sub-menu dari: {{ $bi['label'] }}</option>
                                        @endforeach
                                    </optgroup>                                </select>
                            </div>
                            <div>
                                <label class="form-label fw-semibold"><i class="fas fa-sort-amount-down me-1 text-warning"></i> Posisi</label>
                                <div id="positionSelector" class="pos-options border rounded p-2" style="background:#fafbfc; min-height:50px;">
                                    <div class="text-muted small p-2 text-center" id="posLoading">
                                        <i class="fas fa-spinner fa-spin me-1"></i> Memuat pilihan posisi...
                                    </div>
                                </div>
                                <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Pilih di mana menu ini akan muncul dalam urutan.</small>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i> Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
// ── Inline Edit Toggle ───────────────────────────────────────────────────────
function toggleEdit(id) {
    const el = document.getElementById(id);
    if (!el) return;
    const willOpen = !el.classList.contains('show');
    document.querySelectorAll('.inline-edit-form.show').forEach(function(f) {
        if (f.id !== id) f.classList.remove('show');
    });
    el.classList.toggle('show', willOpen);
    if (willOpen) el.querySelector('input[name="name"]')?.focus();
}

// Inline icon live preview
document.addEventListener('input', function(e) {
    if (!e.target.classList.contains('edit-icon-input')) return;
    const spanClass = e.target.dataset.preview;
    if (!spanClass) return;
    const span = document.querySelector('.' + spanClass);
    if (span) span.querySelector('i').className = e.target.value.trim() || 'fas fa-file-alt';
});

// Modal icon live preview
document.getElementById('addIconInput')?.addEventListener('input', function() {
    document.getElementById('addIconPreviewI').className = this.value.trim() || 'fas fa-file-alt';
});

// ── Position Selector ────────────────────────────────────────────────────────

// Build menu data from Blade
@php
$__menuDataJs = $menus->map(function($m) {
    return [
        'id'       => $m->id,
        'name'     => $m->name,
        'icon'     => $m->icon,
        'position' => $m->position,
        'children' => $m->children->sortBy('position')->values()->map(function($c) {
            return ['id' => $c->id, 'name' => $c->name, 'icon' => $c->icon, 'position' => $c->position];
        })->values()->toArray(),
    ];
})->values()->toArray();

// Built-in menus in their fixed nav order (read-only reference)
$__builtinJs = array_map(fn($bi) => ['name' => $bi['label'], 'icon' => $bi['icon'], 'key' => \Illuminate\Support\Str::slug($bi['label'])], $builtinMenus);

// Custom menus attached to built-in sections
$__builtinSubMap = \App\Models\CustomMenu::whereNotNull('builtin_parent')
    ->ordered()->get()
    ->groupBy('builtin_parent')
    ->map(fn($g) => $g->map(fn($m) => ['id'=>$m->id,'name'=>$m->name,'icon'=>$m->icon])->values()->toArray())
    ->toArray();
@endphp
const MENU_DATA         = {!! json_encode($__menuDataJs) !!};
const BUILTIN_DATA      = {!! json_encode($__builtinJs) !!};
const BUILTIN_SUB_DATA  = {!! json_encode($__builtinSubMap) !!};

function buildPositionSelector(parentId, builtinKey) {
    const container = document.getElementById('positionSelector');
    container.innerHTML = '';

    // ── BUILTIN PARENT mode ───────────────────────────────────────────────────
    if (builtinKey) {
        const siblings = BUILTIN_SUB_DATA[builtinKey] || [];
        const builtinName = (BUILTIN_DATA.find(b => b.key === builtinKey) || {}).name || builtinKey;
        const hdr = document.createElement('div');
        hdr.style.cssText = 'font-size:11px;color:#6c757d;padding:4px 2px 6px;';
        hdr.innerHTML = '<i class="fas fa-info-circle me-1 text-primary"></i>Menu Anda akan muncul sebagai sub-menu di bawah <strong>' + builtinName + '</strong>';
        container.appendChild(hdr);
        if (!siblings.length) {
            const item = makePosOption('Tambahkan sebagai sub-menu pertama', 'fas fa-list', 0, true);
            container.appendChild(item);
            pickPosition(item, 0);
            return;
        }
        const topItem = makePosOption('Di atas \u201c' + siblings[0].name + '\u201d \u2014 paling atas', 'fas fa-angle-double-up', 0, true);
        container.appendChild(topItem);
        siblings.forEach(function(sib, idx) {
            const hr = document.createElement('hr'); hr.className = 'pos-divider'; container.appendChild(hr);
            const pos = idx + 1;
            const label = idx < siblings.length - 1
                ? 'Di antara \u201c' + sib.name + '\u201d dan \u201c' + siblings[idx+1].name + '\u201d'
                : 'Di bawah \u201c' + sib.name + '\u201d \u2014 paling bawah';
            container.appendChild(makePosOption(label, sib.icon, pos, false));
        });
        const last = container.lastElementChild;
        if (last && last.classList.contains('pos-option')) pickPosition(last, siblings.length);
        return;
    }

    if (!parentId) {
        // TOP LEVEL: show full merged nav (built-ins as gray markers + custom as clickable slots)
        // Built-ins always precede custom menus in the nav
        // Insert positions are: before pos 0, between pos n and pos n+1, after last custom
        const customs = MENU_DATA; // sorted by position

        // -- header: built-in reference block --
        const builtinWrap = document.createElement('div');
        builtinWrap.className = 'mb-2 p-2 rounded';
        builtinWrap.style.cssText = 'background:#f5f3ff;border:1px solid #c7d2fe;';
        const builtinTitle = document.createElement('div');
        builtinTitle.innerHTML = '<i class="fas fa-globe fa-xs me-1" style="color:#667eea"></i><span style="font-size:11px;font-weight:700;color:#667eea;text-transform:uppercase;letter-spacing:.05em;">Menu Bawaan Website</span>';
        builtinWrap.appendChild(builtinTitle);
        const builtinPills = document.createElement('div');
        builtinPills.style.cssText = 'display:flex;flex-wrap:wrap;gap:4px;margin-top:6px;';
        BUILTIN_DATA.forEach(function(bi) {
            const pill = document.createElement('span');
            pill.style.cssText = 'background:#eef2ff;color:#3730a3;font-size:11px;padding:2px 8px;border-radius:20px;display:flex;align-items:center;gap:4px;border:1px solid #c7d2fe;';
            pill.innerHTML = '<i class="' + bi.icon + '" style="font-size:10px;"></i>' + bi.name;
            builtinPills.appendChild(pill);
        });
        builtinWrap.appendChild(builtinPills);
        container.appendChild(builtinWrap);

        if (!customs.length) {
            const info = document.createElement('div');
            info.style.cssText = 'font-size:12px;color:#64748b;padding:8px 4px;';
            info.innerHTML = '<i class="fas fa-arrow-down me-1"></i>Menu kustom Anda akan muncul <strong>setelah semua menu bawaan</strong> di atas.';
            container.appendChild(info);
            const item = makePosOption('Tambahkan sebagai menu kustom pertama', 'fas fa-list', 0, true);
            container.appendChild(item);
            pickPosition(item, 0);
            return;
        }

        // -- custom positions --
        const sep = document.createElement('div');
        sep.style.cssText = 'font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;padding:6px 2px 2px;';
        sep.innerHTML = '<i class="fas fa-grip-vertical me-1"></i>Posisi di antara menu kustom Anda';
        container.appendChild(sep);

        const topItem = makePosOption('Di atas \u201c' + customs[0].name + '\u201d \u2014 paling atas', 'fas fa-angle-double-up', 0, true);
        container.appendChild(topItem);
        customs.forEach(function(sib, idx) {
            const hr = document.createElement('hr');
            hr.className = 'pos-divider';
            container.appendChild(hr);
            const pos = idx + 1;
            let label = idx < customs.length - 1
                ? 'Di antara \u201c' + sib.name + '\u201d dan \u201c' + customs[idx + 1].name + '\u201d'
                : 'Di bawah \u201c' + sib.name + '\u201d \u2014 paling bawah';
            container.appendChild(makePosOption(label, sib.icon, pos, false));
        });
        const last = container.lastElementChild;
        if (last && last.classList.contains('pos-option')) pickPosition(last, customs.length);
        return;
    }

    // SUB-LEVEL: same as before
    let siblings = [];
    const pid = parseInt(parentId);
    for (let i = 0; i < MENU_DATA.length; i++) {
        const m = MENU_DATA[i];
        if (m.id === pid) {
            siblings = m.children.map(function(c) { return { id: c.id, name: c.name, icon: c.icon }; });
            break;
        }
    }

    if (!siblings.length) {
        const item = makePosOption('Tambahkan sebagai yang pertama (belum ada yang lain)', 'fas fa-list', 0, true);
        container.appendChild(item);
        pickPosition(item, 0);
        return;
    }
    const topItem = makePosOption('Di atas \u201c' + siblings[0].name + '\u201d \u2014 paling atas', 'fas fa-angle-double-up', 0, true);
    container.appendChild(topItem);
    siblings.forEach(function(sib, idx) {
        const hr = document.createElement('hr');
        hr.className = 'pos-divider';
        container.appendChild(hr);
        const pos = idx + 1;
        let label = idx < siblings.length - 1
            ? 'Di antara \u201c' + sib.name + '\u201d dan \u201c' + siblings[idx + 1].name + '\u201d'
            : 'Di bawah \u201c' + sib.name + '\u201d \u2014 paling bawah';
        container.appendChild(makePosOption(label, sib.icon, pos, false));
    });
    const last = container.lastElementChild;
    if (last && last.classList.contains('pos-option')) pickPosition(last, siblings.length);
}

function makePosOption(label, icon, pos, isTop) {
    const div = document.createElement('div');
    div.className = 'pos-option' + (isTop ? ' top-option' : '');
    div.dataset.pos = pos;
    div.innerHTML = '<div class="pos-icon"><i class="' + (icon || 'fas fa-file-alt') + '"></i></div><span>' + label + '</span>';
    div.addEventListener('click', function() { pickPosition(div, pos); });
    return div;
}

function pickPosition(el, pos) {
    document.querySelectorAll('#positionSelector .pos-option').forEach(function(o) { o.classList.remove('selected'); });
    el.classList.add('selected');
    document.getElementById('addPositionInput').value = pos;
}

document.getElementById('addParentSelect')?.addEventListener('change', function() {
    const val = this.value;
    if (val && val.startsWith('builtin:')) {
        const bKey = val.replace('builtin:', '');
        document.getElementById('addBuiltinParentInput').value = bKey;
        buildPositionSelector(null, bKey);
    } else {
        document.getElementById('addBuiltinParentInput').value = '';
        buildPositionSelector(val || null);
    }
});

document.getElementById('addMenuModal')?.addEventListener('show.bs.modal', function() {
    const sel = document.getElementById('addParentSelect');
    const val = sel ? (sel.value || '') : '';
    if (val.startsWith('builtin:')) {
        buildPositionSelector(null, val.replace('builtin:', ''));
    } else {
        buildPositionSelector(val || null);
    }
    // Reset name input
    document.querySelector('#addMenuForm input[name="name"]').value = '';
});

// ── Fix: clear parent_id before submit when a builtin option is selected ─────
document.getElementById('addMenuForm')?.addEventListener('submit', function() {
    const sel = document.getElementById('addParentSelect');
    if (sel && sel.value && sel.value.startsWith('builtin:')) {
        sel.value = ''; // prevent invalid parent_id reaching server; builtin_parent hidden input carries the key
    }
});

// ── Drag & Drop Reorder ──────────────────────────────────────────────────────
function pushReorder(type, parentId, order) {
    fetch('{{ route("admin.custom-menu.reorder") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ type: type, parent_id: parentId, order: order }),
    }).catch(function(err) { console.warn('Reorder error:', err); });
}

function refreshPosBadges(container, selector) {
    container.querySelectorAll(selector).forEach(function(el, i) {
        const badge = el.querySelector(':scope > .menu-item-header .pos-badge');
        if (badge) badge.textContent = '#' + (i + 1);
    });
}

// Top-level sort
const topSortable = document.getElementById('menuSortable');
if (topSortable && typeof Sortable !== 'undefined') {
    new Sortable(topSortable, {
        handle: '.drag-handle',
        animation: 200,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        onEnd: function() {
            refreshPosBadges(topSortable, ':scope > .menu-item');
            const order = Array.from(topSortable.querySelectorAll(':scope > .menu-item')).map(function(el) { return el.dataset.id; });
            pushReorder('top', null, order);
        }
    });
}

// Sub-level sort
document.querySelectorAll('[id^="sub-sortable-"]').forEach(function(container) {
    if (typeof Sortable === 'undefined') return;
    const parentId = container.dataset.parent;
    new Sortable(container, {
        handle: '.drag-handle',
        animation: 200,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        onEnd: function() {
            refreshPosBadges(container, ':scope > .menu-item');
            const order = Array.from(container.querySelectorAll(':scope > .menu-item')).map(function(el) { return el.dataset.id; });
            pushReorder('sub', parentId, order);
        }
    });
});

// ── Sub-menu toggle (collapse/expand) ────────────────────────────────────────
function toggleSubMenu(id, btn) {
    const wrap = document.getElementById('sub-wrap-' + id);
    if (!wrap) return;
    const isOpen = !wrap.classList.contains('collapsed');
    if (isOpen) {
        wrap.classList.add('collapsed');
        btn.classList.remove('open');
        btn.innerHTML = '<i class="fas fa-chevron-down"></i> ' + btn.textContent.trim().replace(/\s+/g, ' ').replace(/chevron.*?\s/, '').trim();
    } else {
        wrap.classList.remove('collapsed');
        wrap.style.maxHeight = '2000px';
        btn.classList.add('open');
        btn.innerHTML = '<i class="fas fa-chevron-up"></i> ' + btn.textContent.trim().replace(/\s+/g, ' ').replace(/chevron.*?\s/, '').trim();
    }
}

// ── Quick add sub-menu (pre-fill parent in modal) ────────────────────────────
function quickAddSub(parentId, parentName) {
    const sel = document.getElementById('addParentSelect');
    if (sel) {
        // Find option with value = parentId
        for (let i = 0; i < sel.options.length; i++) {
            if (sel.options[i].value == parentId) {
                sel.value = parentId;
                sel.dispatchEvent(new Event('change'));
                break;
            }
        }
    }
    const modal = new bootstrap.Modal(document.getElementById('addMenuModal'));
    modal.show();
}

// ── Built-in menu row toggle ─────────────────────────────────────────────────
function toggleBuiltin(id) {
    const wrap = document.getElementById(id);
    const btnEl = document.getElementById('toggle-btn-' + id);
    if (!wrap) return;
    const isOpen = !wrap.classList.contains('collapsed');
    if (isOpen) {
        wrap.classList.add('collapsed');
        if (btnEl) btnEl.querySelector('i').className = 'fas fa-chevron-down';
    } else {
        wrap.classList.remove('collapsed');
        wrap.style.maxHeight = '2000px';
        if (btnEl) btnEl.querySelector('i').className = 'fas fa-chevron-up';
    }
}

// ── Quick add sub-menu for built-in menu (opens modal at top level) ──────────
function quickAddBuiltin(builtinName) {
    // Derive slug key the same way PHP Str::slug does (lowercase, non-alnum → '-')
    const biKey = builtinName.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
    const sel = document.getElementById('addParentSelect');
    if (sel) {
        const targetVal = 'builtin:' + biKey;
        for (let i = 0; i < sel.options.length; i++) {
            if (sel.options[i].value === targetVal) {
                sel.value = targetVal;
                break;
            }
        }
        document.getElementById('addBuiltinParentInput').value = biKey;
        buildPositionSelector(null, biKey);
    }
    const nameInput = document.querySelector('#addMenuForm input[name="name"]');
    if (nameInput) { nameInput.value = ''; nameInput.focus(); }
    const modal = new bootstrap.Modal(document.getElementById('addMenuModal'));
    modal.show();
}
</script>
@endpush
