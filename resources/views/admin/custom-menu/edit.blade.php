@extends('layouts.admin')
@section('page-title', 'Edit Menu: ' . $menu->name)

@push('styles')
<style>
    .setting-card { border:none; box-shadow:0 2px 12px rgba(0,61,130,0.10); border-radius:12px; }
    .setting-card .card-header { background:linear-gradient(135deg,#001f3f 0%,#003d82 100%); color:white; border-radius:12px 12px 0 0 !important; padding:16px 24px; }

    /* Widget cards */
    .widget-card { border:1px solid #e0e6ed; border-radius:10px; margin-bottom:12px; background:#fff; transition:all 0.25s; position:relative; overflow:hidden; }
    .widget-card.widget-locked { border-color: #e0e6ed; background: #fff; }
    .widget-card.widget-locked .drag-handle { color:#ced4da !important; cursor:not-allowed !important; pointer-events:none; }
    .widget-card.widget-locked .drag-handle i::after { content:' \f023'; font-size:9px; }
    .widget-card:hover { box-shadow:0 4px 16px rgba(0,61,130,0.10); }
    .widget-card.inactive { opacity:0.5; }
    .widget-card .widget-header { padding:12px 16px; display:flex; align-items:center; gap:10px; background:linear-gradient(135deg,#f8f9ff 0%,#eef2ff 100%); border-bottom:1px solid #e8ecf3; cursor:grab; }
    .widget-card .widget-header .drag-handle { color:#adb5bd; font-size:16px; }
    .widget-card .widget-header .widget-icon { width:32px; height:32px; border-radius:8px; background:linear-gradient(135deg,#667eea,#764ba2); color:#fff; display:flex; align-items:center; justify-content:center; font-size:13px; flex-shrink:0; }
    .widget-card .widget-header .widget-title { flex:1; font-weight:600; font-size:14px; }
    .widget-card .widget-body { padding:16px; }
    .widget-actions .btn { padding:3px 8px; font-size:11px; border-radius:6px; }

    /* Toggle switch */
    .form-switch-custom { display:flex; align-items:center; gap:8px; }
    .form-switch-custom .form-check-input { width:36px; height:18px; cursor:pointer; }

    /* Media preview */
    .media-grid { display:flex; flex-wrap:wrap; gap:8px; margin-top:8px; }
    .media-thumb { position:relative; width:80px; height:80px; border-radius:8px; overflow:hidden; border:2px solid #dee2e6; }
    .media-thumb img, .media-thumb video { width:100%; height:100%; object-fit:cover; }
    .media-thumb .pdf-preview { width:100%; height:100%; display:flex; flex-direction:column; align-items:center; justify-content:center; background:#f8f9fa; font-size:10px; color:#6c757d; }
    .media-thumb .pdf-preview i { font-size:22px; color:#dc3545; margin-bottom:2px; }
    .media-thumb .remove-media { position:absolute; top:-2px; right:-2px; background:#dc3545; color:#fff; border:none; border-radius:50%; width:18px; height:18px; font-size:9px; display:flex; align-items:center; justify-content:center; cursor:pointer; }

    /* Widget palette strip (horizontal scroll) */

    /* ===== Template Picker ===== */
    .tpl-modal .modal-dialog { max-width: 920px; }
    .tpl-sidebar { border-right:1px solid #e9ecef; padding:16px 0; background:#f8f9ff; min-width:150px; border-radius:12px 0 0 0; }
    .tpl-sidebar .tpl-cat { padding:7px 18px; font-size:12px; font-weight:600; color:#495057; cursor:pointer; border-radius:0; transition:all 0.15s; border-left:3px solid transparent; }
    .tpl-sidebar .tpl-cat:hover { background:#eef2ff; color:#667eea; }
    .tpl-sidebar .tpl-cat.active { background:#eef2ff; color:#667eea; border-left-color:#667eea; }
    .tpl-grid { display:grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap:14px; padding:16px; max-height:480px; overflow-y:auto; }
    .tpl-card { border:2px solid #e9ecef; border-radius:12px; padding:16px; cursor:pointer; transition:all 0.2s; background:#fff; text-align:center; position:relative; }
    .tpl-card:hover { border-color:#667eea; background:#f0f2ff; transform:translateY(-2px); box-shadow:0 6px 20px rgba(102,126,234,0.15); }
    .tpl-card.selected { border-color:#667eea; background:#eef2ff; }
    .tpl-card .tpl-icon { width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; margin:0 auto 10px; font-size:20px; color:#fff; }
    .tpl-card .tpl-name { font-weight:700; font-size:13px; color:#212529; margin-bottom:4px; }
    .tpl-card .tpl-desc { font-size:11px; color:#6c757d; line-height:1.4; }
    .tpl-card .tpl-badge { position:absolute; top:8px; right:8px; font-size:9px; background:#e9ecef; color:#495057; padding:2px 6px; border-radius:8px; font-weight:600; }
    .tpl-card[data-hidden="1"] { display:none; }
    .tpl-pw { background:#f8f9fa; border:1.5px solid #e2e8f0; border-radius:8px; overflow:hidden; height:115px; padding:7px 8px; display:flex; flex-direction:column; gap:3px; margin:0 0 10px 0; }
    .tpl-pw .b { border-radius:2px; flex-shrink:0; background:#dee2e6; }
    .tpl-pw .row { display:flex; gap:3px; align-items:center; flex-shrink:0; }
    .tpl-card .tpl-icon { display:none; }
    .palette-strip-wrap { background:#fff; border:1px solid #e9ecef; border-radius:12px; margin-bottom:20px; box-shadow:0 2px 8px rgba(0,61,130,0.07); overflow:hidden; }
    .palette-strip-header { padding:10px 16px; background:linear-gradient(135deg,#001f3f,#003d82); color:#fff; display:flex; align-items:center; gap:8px; font-size:13px; font-weight:600; }
    .palette-strip-scroll { display:flex; gap:8px; overflow-x:auto; padding:12px 16px; scroll-behavior:smooth; scrollbar-width:thin; }
    .palette-strip-scroll::-webkit-scrollbar { height:5px; }
    .palette-strip-scroll::-webkit-scrollbar-thumb { background:#c0cfe0; border-radius:3px; }
    .palette-strip-scroll .palette-item { flex-shrink:0; width:100px; border:2px dashed #d0d5e0; border-radius:10px; padding:10px 6px; text-align:center; cursor:pointer; transition:all 0.2s; background:#fdfdff; }
    .palette-strip-scroll .palette-item:hover { border-color:#667eea; background:#f0f2ff; transform:translateY(-2px); }
    .palette-strip-scroll .palette-item i { font-size:18px; color:#667eea; display:block; margin-bottom:5px; }
    .palette-strip-scroll .palette-item span { font-size:11px; font-weight:600; color:#495057; line-height:1.2; display:block; }
    .palette-category { flex-shrink:0; display:flex; align-items:center; padding:0 4px; }
    .palette-category span { font-size:10px; font-weight:700; text-transform:uppercase; color:#adb5bd; letter-spacing:0.5px; writing-mode:vertical-lr; transform:rotate(180deg); }

    /* Breadcrumb custom */
    .edit-breadcrumb { display:flex; align-items:center; gap:8px; font-size:14px; margin-bottom:20px; }
    .edit-breadcrumb a { color:#0066cc; text-decoration:none; }
    .edit-breadcrumb a:hover { text-decoration:underline; }

    /* Layout controls panel */
    .layout-panel { background:linear-gradient(135deg,#f8f9ff,#eef2ff); border-top:1px dashed #d0d5e0; padding:12px 16px; }
    .layout-panel .layout-title { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; color:#667eea; margin-bottom:8px; }
    .layout-panel .layout-row { display:flex; flex-wrap:wrap; gap:10px; align-items:end; }
    .layout-panel .layout-field { flex:1; min-width:100px; }
    .layout-panel .layout-field label { font-size:11px; font-weight:600; color:#495057; margin-bottom:2px; display:block; }
    .layout-panel .layout-field .form-select,
    .layout-panel .layout-field .form-control { font-size:12px; padding:4px 8px; height:auto; }
    .layout-toggle { cursor:pointer; font-size:11px; color:#667eea; font-weight:600; display:inline-flex; align-items:center; gap:4px; user-select:none; }
    .layout-toggle:hover { color:#4a5bd4; }
    .layout-toggle i { transition:transform 0.2s; font-size:10px; }
    .layout-toggle.open i { transform:rotate(180deg); }

    /* Width preview bar */
    .width-preview { height:6px; border-radius:3px; background:linear-gradient(90deg,#667eea,#764ba2); transition:width 0.3s; margin-top:4px; }
    /* Alignment visual selector */
    .align-selector { display:flex; gap:2px; }
    .align-selector .align-btn { width:28px; height:28px; border:1px solid #dee2e6; border-radius:6px; display:flex; align-items:center; justify-content:center; font-size:11px; cursor:pointer; transition:all 0.15s; color:#6c757d; background:#fff; }
    .align-selector .align-btn:hover { border-color:#667eea; color:#667eea; }
    .align-selector .align-btn.active { background:#667eea; color:#fff; border-color:#667eea; }
    .align-selector input[type="radio"] { display:none; }

    /* ===== Widget Resize Handle ===== */
    .widget-card { position:relative; overflow:visible !important; }
    .widget-resize-handle { position:absolute; right:0; bottom:0; width:16px; height:16px; cursor:se-resize; z-index:10; display:flex; align-items:flex-end; justify-content:flex-end; padding:2px; }
    .widget-resize-handle svg { opacity:0.35; transition:opacity 0.15s; }
    .widget-card:hover .widget-resize-handle svg { opacity:0.75; }
    .widget-card.resizing { box-shadow:0 0 0 2px #667eea, 0 4px 20px rgba(102,126,234,0.3) !important; user-select:none; }
    .widget-size-badge { font-size:10px; background:rgba(102,126,234,0.15); color:#4a5bd4; padding:1px 6px; border-radius:4px; margin-left:6px; font-weight:500; font-family:monospace; }

    /* ===== Collapsible Widget Accordion ===== */
    .widget-card .widget-body { transition: none; }
    .widget-card.wc-collapsed .widget-body { display: none !important; }
    .widget-card.wc-collapsed .layout-panel { display: none !important; }
    .widget-toggle-btn { cursor:pointer; width:26px; height:26px; border:1px solid #dee2e6; border-radius:6px; display:flex; align-items:center; justify-content:center; background:#fff; color:#6c757d; transition:all 0.15s; flex-shrink:0; }
    .widget-toggle-btn:hover { background:#eef2ff; border-color:#667eea; color:#667eea; }
    .widget-toggle-btn i { transition: transform 0.2s; font-size:11px; }
    .widget-card.wc-collapsed .widget-toggle-btn i { transform: rotate(-90deg); }
    .widget-preview-text { font-size:11px; color:#888; font-style:italic; margin-left:6px; max-width:240px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .widget-card:not(.wc-collapsed) .widget-preview-text { display:none; }
    /* Toolbar above widget list */
    .widget-list-toolbar { display:flex; align-items:center; gap:8px; margin-bottom:10px; flex-wrap:wrap; }
    .widget-list-toolbar .btn { font-size:11px; padding:3px 10px; }
    /* Group divider — judul widget as GROUP LEADER */
    .widget-card[data-type="judul"] > .widget-header { background:linear-gradient(135deg,#e8ecff 0%,#dce4ff 100%); border-bottom-color:#c7d0f5; }
    .widget-card[data-type="judul"] > .widget-header .widget-icon { background:linear-gradient(135deg,#003d82,#0066cc); }

    /* GROUP collapse styles */
    #widgetList > .widget-card[data-type="judul"] { border-left:4px solid #003d82 !important; margin-top:14px; }
    #widgetList > .widget-card[data-type="judul"]:first-child { margin-top:0; }
    .widget-card.wg-member { border-left:3px solid #c7d0f5; }
    /* Group-collapsed: rotate chevron */
    .widget-card[data-type="judul"].wg-group-collapsed > .widget-header .wg-chevron { transform:rotate(-90deg); }
    .wg-chevron { transition:transform 0.2s; font-size:11px; }
    /* Count badge on judul header */
    .wg-count-badge { font-size:10px; background:rgba(0,61,130,0.10); color:#003d82; padding:1px 7px; border-radius:8px; font-weight:600; margin-left:4px; flex-shrink:0; white-space:nowrap; }
    /* Edit (pencil) button on judul cards */
    .widget-edit-judul-btn { padding:3px 7px !important; font-size:10px !important; border-radius:5px !important; }

    /* ===== Live Preview Panel ===== */
    .preview-section { margin-top:32px; background:#fff; border-radius:12px; box-shadow:0 2px 12px rgba(0,61,130,0.10); overflow:hidden; }
    .preview-section .preview-header { padding:12px 18px; background:linear-gradient(135deg,#001f3f,#003d82); color:#fff; display:flex; align-items:center; gap:10px; }
    .preview-iframe-wrap { position:relative; width:100%; overflow:hidden; background:#f5f5f5; }
    .preview-iframe-wrap iframe { border:none; transform-origin:top left; background:#fff; display:block; }
    .preview-toolbar { padding:8px 18px; background:#f8f9ff; border-bottom:1px solid #dee2e6; display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
    .preview-toolbar .btn { font-size:12px; padding:4px 12px; }
    .zoom-select { font-size:12px; padding:3px 8px; border-radius:6px; border:1px solid #dee2e6; }
    .device-btn { width:32px; height:28px; border:1px solid #dee2e6; border-radius:6px; display:flex; align-items:center; justify-content:center; font-size:13px; cursor:pointer; background:#fff; color:#6c757d; transition:all 0.15s; }
    .device-btn.active, .device-btn:hover { background:#003d82; color:#fff; border-color:#003d82; }
    /* Vertical resize handle for preview height */
    .preview-resize-bar { height:8px; background:linear-gradient(90deg,#e9ecef 0%,#dee2e6 100%); cursor:ns-resize; display:flex; align-items:center; justify-content:center; user-select:none; }
    .preview-resize-bar:hover { background:linear-gradient(90deg,#c8d0e0,#b8c4d8); }
    .preview-resize-bar::after { content:''; display:block; width:40px; height:3px; border-radius:2px; background:#adb5bd; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">

            {{-- Breadcrumb --}}
            <div class="edit-breadcrumb">
                <a href="{{ route('admin.custom-menu.index') }}"><i class="fas fa-bars me-1"></i> Menu Kustom</a>
                <i class="fas fa-chevron-right text-muted" style="font-size:10px;"></i>
                @if($menu->parent)
                    <span class="text-muted">{{ $menu->parent->name }}</span>
                    <i class="fas fa-chevron-right text-muted" style="font-size:10px;"></i>
                @endif
                <span class="fw-bold">{{ $menu->name }}</span>
            </div>

            {{-- Header --}}
            <div class="d-flex align-items-center mb-4 gap-3">
                <div style="background:linear-gradient(135deg,#667eea,#764ba2);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="{{ $menu->icon }} text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-0 fw-bold text-dark">{{ $menu->name }}</h4>
                    <small class="text-muted">Atur widget konten halaman ini. Semua widget bersifat opsional.</small>
                </div>
                <span class="badge {{ $menu->is_published ? 'bg-success' : 'bg-secondary' }} me-2">
                    {{ $menu->is_published ? 'Published' : 'Draft' }}
                </span>
                <button type="button" class="btn btn-outline-purple btn-sm" data-bs-toggle="modal" data-bs-target="#tplPickerModal"
                        style="border-color:#764ba2;color:#764ba2;font-weight:600;">
                    <i class="fas fa-magic me-1"></i> Pilih Template
                </button>
            </div>

            {{-- Alert --}}
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                @foreach($errors->all() as $error) {{ $error }}<br> @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- ===== Widget Palette Strip (horizontal scroll) ===== --}}
            {{-- ===== Template Picker Modal ===== --}}
            <div class="modal fade tpl-modal" id="tplPickerModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content" style="border-radius:14px;overflow:hidden;">
                        <div class="modal-header" style="background:linear-gradient(135deg,#001f3f,#764ba2);color:#fff;">
                            <h5 class="modal-title"><i class="fas fa-magic me-2"></i>Pilih Template Halaman</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="d-flex" style="min-height:520px;">
                            {{-- Left category sidebar --}}
                            <div class="tpl-sidebar d-none d-md-block">
                                <div class="px-4 pb-2 pt-2" style="font-size:11px;font-weight:700;text-transform:uppercase;color:#adb5bd;">{{ __('messages.kategori') }}</div>
                                <div class="tpl-cat active" data-cat="all">Semua Template</div>
                                @php $tplCats = collect($pageTemplates)->pluck('category')->unique()->sort()->values(); @endphp
                                @foreach($tplCats as $cat)
                                <div class="tpl-cat" data-cat="{{ $cat }}">{{ $cat }}</div>
                                @endforeach
                            </div>
                            {{-- Right grid --}}
                            <div class="flex-grow-1">
                                <div class="px-4 pt-3 pb-1">
                                    <input type="text" id="tplSearch" class="form-control form-control-sm" placeholder="&#128269; Cari template..." style="border-radius:20px;">
                                </div>
                                <div class="tpl-grid" id="tplGrid">
                                    @php
                                    $tplPreviews = [
                                        'profil' => '<div class="tpl-pw"><div class="b" style="height:9px;width:60%;background:#667eea;"></div><div class="row" style="justify-content:center;padding:2px 0;"><div class="b" style="width:34px;height:26px;background:#e8eaf6;border:1.5px dashed #9fa8da;"></div></div><div class="b" style="height:1px;background:#ced4da;"></div><div class="b" style="height:7px;width:90%;"></div><div class="b" style="height:7px;width:70%;"></div><div class="b" style="height:7px;width:50%;"></div></div>',
                                        'tab_navigasi' => '<div class="tpl-pw"><div class="b" style="height:9px;width:55%;background:#764ba2;"></div><div class="row" style="gap:2px;margin-top:2px;"><div class="b" style="height:14px;width:27%;background:#ede7f6;border:1px solid #b39ddb;border-radius:3px 3px 0 0;"></div><div class="b" style="height:12px;width:22%;background:#f3e5f5;border:1px solid #ce93d8;border-radius:3px 3px 0 0;"></div><div class="b" style="height:12px;width:22%;background:#f3e5f5;border:1px solid #ce93d8;border-radius:3px 3px 0 0;"></div><div class="b" style="height:12px;width:22%;background:#f3e5f5;border:1px solid #ce93d8;border-radius:3px 3px 0 0;"></div></div><div class="b" style="flex:1;background:#ede7f6;border-radius:0 4px 4px 4px;border:1px solid #b39ddb;"></div></div>',
                                        'tab_foto' => '<div class="tpl-pw"><div class="b" style="height:9px;width:50%;background:#5c35b4;"></div><div class="b" style="height:32px;background:#ede7f6;border:1.5px dashed #b39ddb;border-radius:3px;"></div><div class="row" style="gap:2px;"><div class="b" style="height:12px;width:30%;background:#ede7f6;border:1px solid #b39ddb;border-radius:3px 3px 0 0;"></div><div class="b" style="height:10px;width:20%;background:#f3e5f5;border:1px solid #ce93d8;border-radius:3px 3px 0 0;"></div><div class="b" style="height:10px;width:20%;background:#f3e5f5;border:1px solid #ce93d8;border-radius:3px 3px 0 0;"></div><div class="b" style="height:10px;width:20%;background:#f3e5f5;border:1px solid #ce93d8;border-radius:3px 3px 0 0;"></div></div><div class="b" style="flex:1;background:#ede7f6;border-radius:0 4px 4px 4px;border:1px solid #b39ddb;"></div></div>',
                                        'galeri_foto' => '<div class="tpl-pw"><div class="b" style="height:9px;width:55%;background:#11998e;"></div><div class="b" style="height:7px;width:75%;"></div><div style="display:grid;grid-template-columns:repeat(3,1fr);gap:2px;flex:1;"><div class="b" style="background:#b2dfdb;border-radius:2px;"></div><div class="b" style="background:#b2dfdb;border-radius:2px;"></div><div class="b" style="background:#b2dfdb;border-radius:2px;"></div><div class="b" style="background:#b2dfdb;border-radius:2px;"></div><div class="b" style="background:#b2dfdb;border-radius:2px;"></div><div class="b" style="background:#b2dfdb;border-radius:2px;"></div></div></div>',
                                        'galeri_video' => '<div class="tpl-pw"><div class="b" style="height:9px;width:55%;background:#e53935;"></div><div class="b" style="height:7px;width:75%;"></div><div style="display:grid;grid-template-columns:repeat(3,1fr);gap:3px;flex:1;"><div class="b" style="background:#ffcdd2;border-radius:2px;display:flex;align-items:center;justify-content:center;font-size:11px;color:#c62828;">&#9654;</div><div class="b" style="background:#ffcdd2;border-radius:2px;display:flex;align-items:center;justify-content:center;font-size:11px;color:#c62828;">&#9654;</div><div class="b" style="background:#ffcdd2;border-radius:2px;display:flex;align-items:center;justify-content:center;font-size:11px;color:#c62828;">&#9654;</div></div></div>',
                                        'berita_halaman' => '<div class="tpl-pw"><div class="b" style="height:6px;background:#f4511e;border-radius:2px;"></div><div class="b" style="height:9px;width:60%;background:#f4511e;"></div><div style="display:grid;grid-template-columns:1fr 1fr;gap:3px;flex:1;"><div style="background:#fff3f0;border:1px solid #ffcdd2;border-radius:3px;padding:3px;display:flex;flex-direction:column;gap:2px;"><div class="b" style="flex:1;background:#ffccbc;border-radius:2px;"></div><div class="b" style="height:5px;width:80%;"></div><div class="b" style="height:5px;width:60%;"></div></div><div style="background:#fff3f0;border:1px solid #ffcdd2;border-radius:3px;padding:3px;display:flex;flex-direction:column;gap:2px;"><div class="b" style="flex:1;background:#ffccbc;border-radius:2px;"></div><div class="b" style="height:5px;width:80%;"></div><div class="b" style="height:5px;width:60%;"></div></div></div></div>',
                                        'acara_event' => '<div class="tpl-pw"><div class="b" style="height:9px;width:55%;background:#fb8c00;"></div><div class="row" style="gap:3px;"><div class="b" style="height:10px;width:48%;background:#fff3e0;border:1px solid #ffcc80;"></div><div class="b" style="height:10px;width:48%;background:#fff3e0;border:1px solid #ffcc80;"></div></div><div class="b" style="flex:1;background:#fff8e1;border:1.5px dashed #ffcc80;border-radius:3px;"></div><div class="b" style="height:1px;background:#ced4da;"></div><div class="b" style="height:7px;width:80%;"></div></div>',
                                        'kontak_info' => '<div class="tpl-pw"><div class="b" style="height:9px;width:55%;background:#00897b;"></div><div class="b" style="height:7px;width:75%;"></div><div class="b" style="height:1px;background:#ced4da;"></div><div class="row"><div class="b" style="height:9px;width:46%;background:#e0f2f1;border:1px solid #80cbc4;"></div><div class="b" style="height:9px;width:46%;background:#e0f2f1;border:1px solid #80cbc4;"></div></div><div class="b" style="height:9px;width:70%;background:#e0f2f1;border:1px solid #80cbc4;"></div><div class="b" style="flex:1;background:#e0f2f1;border:1px solid #80cbc4;border-radius:3px;"></div></div>',
                                        'pengumuman' => '<div class="tpl-pw"><div class="b" style="height:6px;background:#1565c0;border-radius:2px;"></div><div class="b" style="height:16px;background:#e3f2fd;border-left:3px solid #1565c0;border-radius:2px;"></div><div class="row"><div class="b" style="height:9px;width:38%;background:#bbdefb;border-radius:2px;"></div><div class="b" style="height:1px;flex:1;background:#ced4da;"></div></div><div class="b" style="height:7px;width:90%;"></div><div class="b" style="height:7px;width:70%;"></div><div class="row" style="margin-top:2px;"><div class="b" style="height:12px;width:50%;background:#1565c0;border-radius:3px;"></div></div></div>',
                                        'dokumen_elibrary' => '<div class="tpl-pw"><div class="b" style="height:9px;width:60%;background:#5c6bc0;"></div><div class="b" style="height:7px;width:80%;"></div><div class="b" style="height:1px;background:#ced4da;"></div><div class="b" style="flex:1;background:#e8eaf6;border:1.5px dashed #9fa8da;border-radius:3px;display:flex;align-items:center;justify-content:center;font-size:18px;">&#128196;</div><div class="row" style="margin-top:2px;"><div class="b" style="height:12px;width:50%;background:#5c6bc0;border-radius:3px;"></div></div></div>',
                                        'visi_misi' => '<div class="tpl-pw"><div class="b" style="height:9px;width:55%;background:#039be5;"></div><div class="b" style="height:8px;width:32%;background:#b3e5fc;"></div><div class="b" style="height:18px;background:#e1f5fe;border-left:3px solid #039be5;border-radius:0 3px 3px 0;"></div><div class="b" style="height:1px;background:#ced4da;"></div><div class="b" style="height:8px;width:28%;background:#b3e5fc;"></div><div class="b" style="height:7px;width:85%;"></div><div class="b" style="height:7px;width:70%;"></div></div>',
                                        'faq_accordion' => '<div class="tpl-pw"><div class="b" style="height:9px;width:55%;background:#7b1fa2;"></div><div class="b" style="height:7px;width:75%;"></div><div class="b" style="height:14px;background:#f3e5f5;border:1px solid #ce93d8;border-radius:3px;"></div><div class="b" style="height:14px;background:#fce4ec;border:1px solid #f48fb1;border-radius:3px;"></div><div class="b" style="height:14px;background:#f3e5f5;border:1px solid #ce93d8;border-radius:3px;"></div></div>',
                                        'landing_page' => '<div class="tpl-pw"><div class="b" style="height:6px;background:#43a047;border-radius:2px;"></div><div class="b" style="height:9px;width:55%;background:#43a047;"></div><div class="b" style="flex:1;background:#e8f5e9;border:1.5px dashed #a5d6a7;border-radius:3px;"></div><div class="b" style="height:7px;width:80%;"></div><div class="row" style="justify-content:center;margin-top:2px;"><div class="b" style="height:13px;width:48%;background:#43a047;border-radius:10px;"></div></div></div>',
                                        'statistik' => '<div class="tpl-pw"><div class="b" style="height:9px;width:55%;background:#00acc1;"></div><div style="display:grid;grid-template-columns:repeat(3,1fr);gap:3px;"><div style="background:#e0f7fa;border:1px solid #80deea;border-radius:3px;padding:3px;text-align:center;"><div style="font-size:9px;font-weight:700;color:#00acc1;line-height:1.3;">100+</div><div class="b" style="height:5px;background:#b2ebf2;border-radius:1px;"></div></div><div style="background:#e0f7fa;border:1px solid #80deea;border-radius:3px;padding:3px;text-align:center;"><div style="font-size:9px;font-weight:700;color:#00acc1;line-height:1.3;">50+</div><div class="b" style="height:5px;background:#b2ebf2;border-radius:1px;"></div></div><div style="background:#e0f7fa;border:1px solid #80deea;border-radius:3px;padding:3px;text-align:center;"><div style="font-size:9px;font-weight:700;color:#00acc1;line-height:1.3;">10+</div><div class="b" style="height:5px;background:#b2ebf2;border-radius:1px;"></div></div></div><div class="b" style="height:1px;background:#ced4da;margin:1px 0;"></div><div class="b" style="height:7px;width:90%;"></div><div class="b" style="height:7px;width:70%;"></div></div>',
                                        'strukt_org' => '<div class="tpl-pw"><div class="b" style="height:9px;width:60%;background:#6d4c41;"></div><div class="b" style="flex:1;background:#efebe9;border:1.5px dashed #bcaaa4;border-radius:3px;"></div><div class="b" style="height:1px;background:#ced4da;"></div><div class="b" style="height:8px;width:48%;background:#d7ccc8;"></div><div class="b" style="height:7px;width:80%;"></div><div class="b" style="height:7px;width:65%;"></div></div>',
                                    ];
                                    @endphp
                                    @foreach($pageTemplates as $tplKey => $tpl)
                                    <div class="tpl-card" data-key="{{ $tplKey }}" data-cat="{{ $tpl['category'] }}" data-name="{{ strtolower($tpl['name']) }} {{ strtolower($tpl['desc']) }}">
                                        <span class="tpl-badge">{{ $tpl['category'] }}</span>
                                        {!! $tplPreviews[$tplKey] ?? '<div class="tpl-pw" style="align-items:center;justify-content:center;"><i class="'.$tpl['icon'].'" style="font-size:22px;color:'.$tpl['color'].'"></i></div>' !!}
                                        <div class="tpl-name">{{ $tpl['name'] }}</div>
                                        <div class="tpl-desc">{{ $tpl['desc'] }}</div>
                                        <div class="mt-1 d-flex gap-1 justify-content-center" style="font-size:10px;color:#adb5bd;">
                                            {{ count($tpl['widgets']) }} widget
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
                            <div class="form-check ms-2">
                                <input type="checkbox" class="form-check-input" id="tplReplace" value="1">
                                <label class="form-check-label small" for="tplReplace">Hapus semua widget lama sebelum menerapkan</label>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.batal') }}</button>
                                <button type="button" class="btn btn-primary" id="tplApplyBtn" disabled>
                                    <i class="fas fa-check me-1"></i> Terapkan Template
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Hidden form to submit template apply --}}
            <form id="tplApplyForm" action="{{ route('admin.custom-menu.template.apply', $menu->id) }}" method="POST" style="display:none;">
                @csrf
                <input type="hidden" name="template" id="tplApplyKey">
                <input type="hidden" name="replace_all" id="tplApplyReplace" value="0">
            </form>

            {{-- ===== Widget Palette Strip (horizontal scroll) ===== --}}
            <div class="palette-strip-wrap">
                <div class="palette-strip-header">
                    <i class="fas fa-plus-circle"></i> Tambah Widget — geser kiri/kanan untuk melihat semua
                    <small class="ms-auto opacity-75">{{ count($widgetTypes) }} jenis widget tersedia</small>
                </div>
                <div class="palette-strip-scroll" id="paletteStrip">
                    @php
                        $paletteGroups = [
                            'Teks'    => ['judul','deskripsi','kutipan','daftar','banner','html_kustom','icon_teks','nomor_statistik','accordion'],
                            'Media'   => ['foto','video','audio','pdf','file_download','logo','galeri_foto_lokal','galeri_video_lokal','gambar_sidebar'],
                            'Link'    => ['link_url','logo_link','tombol','youtube','video_url','maps','instagram','iframe'],
                            'Info'    => ['tanggal','lokasi','email','no_hp'],
                            'Fitur'   => ['teks_berjalan','tab_frame','berita_lokal'],
                            'Layout'  => ['separator','spacer'],
                        ];
                    @endphp
                    @foreach($paletteGroups as $groupLabel => $groupKeys)
                        <div class="palette-category"><span>{{ $groupLabel }}</span></div>
                        @foreach($groupKeys as $typeKey)
                            @if(isset($widgetTypes[$typeKey]))
                            <form action="{{ route('admin.custom-menu.widget.add', $menu->id) }}" method="POST" style="flex-shrink:0;">
                                @csrf
                                <input type="hidden" name="widget_type" value="{{ $typeKey }}">
                                <button type="submit" class="palette-item border-0">
                                    <i class="{{ $widgetTypes[$typeKey]['icon'] }}"></i>
                                    <span>{{ $widgetTypes[$typeKey]['label'] }}</span>
                                </button>
                            </form>
                            @endif
                        @endforeach
                    @endforeach
                </div>
            </div>

            <div class="row g-4">
                {{-- Full-width Widget Builder --}}
                <div class="col-12">
                    <div class="card setting-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold"><i class="fas fa-puzzle-piece me-2"></i>Widget Konten</h6>
                            <span class="badge bg-light text-dark">{{ $menu->widgets->count() }} widget</span>
                        </div>
                        <div class="card-body p-3">
                            @if($menu->widgets->count())
                            <form action="{{ route('admin.custom-menu.widgets.save', $menu->id) }}" method="POST" enctype="multipart/form-data" id="widgetForm">
                                @csrf
                                {{-- Toolbar: Collapse / Expand All --}}
                                <div class="widget-list-toolbar">
                                    <span class="text-muted small fw-semibold"><i class="fas fa-layer-group me-1"></i>{{ $menu->widgets->count() }} widget</span>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="collapseAllWidgets()">
                                        <i class="fas fa-compress-alt me-1"></i> Tutup Semua
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="expandAllWidgets()">
                                        <i class="fas fa-expand-alt me-1"></i> Buka Semua
                                    </button>
                                    <button type="button" class="btn btn-outline-success btn-sm ms-auto" onclick="collapseAllExceptJudul()">
                                        <i class="fas fa-heading me-1"></i> Tampilkan Judul Saja
                                    </button>
                                </div>
                                <div id="widgetList">
                                @foreach($menu->widgets->sortBy('position') as $idx => $widget)
                                    @php
                                        $ti = $widget->typeInfo;
                                        $ws = $widget->settings ?? [];
                                        $isJudul = ($widget->widget_type === 'judul');
                                        $isLocked = in_array($widget->widget_type, ['teks_berjalan', 'tab_frame', 'gambar_sidebar']);
                                    @endphp
                                    <div class="widget-card {{ $widget->is_active ? '' : 'inactive' }} wc-collapsed {{ $isLocked ? 'widget-locked' : '' }}" data-widget-id="{{ $widget->id }}" data-type="{{ $widget->widget_type }}" {{ $isLocked ? 'data-locked="true"' : '' }}>
                                        @if($isJudul)
                                        {{-- ===== JUDUL: clicking area = toggle GROUP ===== --}}
                                        <div class="widget-header" onclick="toggleWidgetGroup(this.closest('.widget-card'))" style="cursor:pointer;">
                                            <div class="drag-handle" onclick="event.stopPropagation()"><i class="fas fa-grip-vertical"></i></div>
                                            <div class="widget-icon"><i class="{{ $ti['icon'] ?? 'fas fa-cube' }}"></i></div>
                                            <div class="widget-title">{{ $ti['label'] ?? $widget->widget_type }}</div>
                                            <span class="widget-preview-text">{{ Str::limit($widget->text_content ?? '', 50) }}</span>
                                            <span class="wg-count-badge">...</span>
                                            <div class="widget-toggle-btn ms-auto me-1" onclick="event.stopPropagation(); toggleWidgetGroup(this.closest('.widget-card'))" title="Buka/tutup grup">
                                                <i class="fas fa-chevron-down wg-chevron"></i>
                                            </div>
                                            <div class="widget-actions d-flex gap-1 align-items-center" onclick="event.stopPropagation()">
                                                <button type="button" class="btn btn-outline-secondary widget-edit-judul-btn"
                                                        onclick="event.stopPropagation(); toggleWidget(this.closest('.widget-card'))"
                                                        title="Edit teks judul"><i class="fas fa-pen"></i></button>
                                                <a href="{{ route('admin.custom-menu.widget.remove', [$menu->id, $widget->id]) }}"
                                                   class="btn btn-outline-danger" onclick="return confirm('Hapus widget ini?')"
                                                   title="Hapus Widget"><i class="fas fa-trash"></i></a>
                                            </div>
                                            <input type="hidden" name="widget_positions[{{ $widget->id }}]" value="{{ $idx }}" class="widget-position">
                                        </div>
                                        @else
                                        {{-- ===== NON-JUDUL: clicking area = toggle individual widget ===== --}}
                                        <div class="widget-header" onclick="toggleWidget(this.closest('.widget-card'))" style="cursor:pointer;">
                                            <div class="drag-handle" onclick="event.stopPropagation()"><i class="fas fa-grip-vertical"></i></div>
                                            <div class="widget-icon"><i class="{{ $ti['icon'] ?? 'fas fa-cube' }}"></i></div>
                                            <div class="widget-title">{{ $ti['label'] ?? $widget->widget_type }}</div>
                                            <span class="widget-preview-text">{{ Str::limit($widget->text_content ?? '', 50) }}</span>
                                            <div class="widget-toggle-btn ms-auto me-1" onclick="event.stopPropagation(); toggleWidget(this.closest('.widget-card'))">
                                                <i class="fas fa-chevron-down"></i>
                                            </div>
                                            <div class="widget-actions d-flex gap-1 align-items-center" onclick="event.stopPropagation()">
                                                <a href="{{ route('admin.custom-menu.widget.remove', [$menu->id, $widget->id]) }}"
                                                   class="btn btn-outline-danger" onclick="return confirm('Hapus widget ini?')"
                                                   title="Hapus Widget"><i class="fas fa-trash"></i></a>
                                            </div>
                                            <input type="hidden" name="widget_positions[{{ $widget->id }}]" value="{{ $idx }}" class="widget-position">
                                        </div>
                                        @endif
                                        <div class="widget-body">
                                            @php $inputType = $ti['input'] ?? 'text'; @endphp

                                            @if($inputType === 'ticker')
                                                {{-- Teks Berjalan widget --}}
                                                <small class="text-muted d-block mb-2">
                                                    <i class="fas fa-info-circle me-1 text-primary"></i>
                                                    Teks ini akan berjalan otomatis dari kanan ke kiri (seperti running text/ticker).
                                                </small>
                                                <textarea name="widgets[{{ $widget->id }}][text_content]"
                                                          class="form-control" rows="2"
                                                          placeholder="Ketik teks berjalan di sini...">{{ $widget->text_content }}</textarea>
                                                <div class="d-flex gap-3 mt-2 align-items-center flex-wrap">
                                                    <div>
                                                        <label class="form-label fw-semibold small mb-1">Kecepatan</label>
                                                        <select name="widgets[{{ $widget->id }}][extra_ticker_speed]" class="form-select form-select-sm" style="width:130px;">
                                                            <option value="slow" {{ ($ws['ticker_speed'] ?? 'normal') === 'slow' ? 'selected' : '' }}>Lambat</option>
                                                            <option value="normal" {{ ($ws['ticker_speed'] ?? 'normal') === 'normal' ? 'selected' : '' }}>Normal</option>
                                                            <option value="fast" {{ ($ws['ticker_speed'] ?? 'normal') === 'fast' ? 'selected' : '' }}>Cepat</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="form-label fw-semibold small mb-1">Warna Latar</label>
                                                        <input type="color" name="widgets[{{ $widget->id }}][extra_ticker_bg]" value="{{ $ws['ticker_bg'] ?? '#003d82' }}" class="form-control form-control-sm form-control-color" style="width:50px;height:31px;">
                                                    </div>
                                                    <div>
                                                        <label class="form-label fw-semibold small mb-1">Warna Teks</label>
                                                        <input type="color" name="widgets[{{ $widget->id }}][extra_ticker_color]" value="{{ $ws['ticker_color'] ?? '#ffffff' }}" class="form-control form-control-sm form-control-color" style="width:50px;height:31px;">
                                                    </div>
                                                </div>

                                            @elseif($inputType === 'tabs')
                                                {{-- Dynamic Tab Builder --}}
                                                @php
                                                    $existingTabs = [];
                                                    if ($widget->text_content) {
                                                        $decoded = json_decode($widget->text_content, true);
                                                        if (is_array($decoded)) {
                                                            $existingTabs = $decoded;
                                                        } else {
                                                            // backward compat: old pipe format
                                                            foreach (array_filter(array_map('trim', explode("\n", $widget->text_content))) as $line) {
                                                                $parts = explode('|', $line, 2);
                                                                $existingTabs[] = ['name' => trim($parts[0] ?? ''), 'type' => 'text', 'content' => trim($parts[1] ?? '')];
                                                            }
                                                        }
                                                    }
                                                    if (empty($existingTabs)) {
                                                        $existingTabs = [['name' => '', 'type' => 'text', 'content' => '']];
                                                    }
                                                    // Group media by tab index (position = tab_index * 1000 + file_order)
                                                    $tabMediaGroups = [];
                                                    foreach ($widget->media as $m) {
                                                        $tidx = (int)floor($m->position / 1000);
                                                        $tabMediaGroups[$tidx][] = $m;
                                                    }
                                                @endphp

                                                <div class="tab-builder-wrap border rounded-3 p-3 bg-light" id="tabBuilder_{{ $widget->id }}">
                                                    <div class="tab-builder-list" id="tabList_{{ $widget->id }}">
                                                        @foreach($existingTabs as $tIdx => $tab)
                                                        <div class="tab-builder-item card border-0 shadow-sm mb-2" data-tab-idx="{{ $tIdx }}">
                                                            <div class="card-header d-flex align-items-center gap-2 py-2 px-3" style="background:#eef2ff;border-bottom:1px solid #d0d5e0;">
                                                                <i class="fas fa-grip-vertical text-muted" style="cursor:grab;"></i>
                                                                <input type="text" class="form-control form-control-sm tab-name-input fw-semibold border-0 bg-transparent" style="max-width:200px;box-shadow:none;" placeholder="Nama Tab..." value="{{ $tab['name'] }}">
                                                                <select class="form-select form-select-sm tab-type-select ms-auto border" style="max-width:150px;">
                                                                    <option value="text"  {{ ($tab['type'] ?? 'text') === 'text'  ? 'selected' : '' }}>📝 Deskripsi</option>
                                                                    <option value="photo" {{ ($tab['type'] ?? 'text') === 'photo' ? 'selected' : '' }}>🖼️ Foto</option>
                                                                    <option value="pdf"   {{ ($tab['type'] ?? 'text') === 'pdf'   ? 'selected' : '' }}>📄 PDF</option>
                                                                </select>
                                                                <button type="button" class="btn btn-sm btn-outline-danger tab-remove-btn ms-1" title="Hapus Tab"><i class="fas fa-trash-alt"></i></button>
                                                            </div>
                                                            <div class="card-body p-3">
                                                                {{-- Text content --}}
                                                                <div class="tab-text-area{{ ($tab['type'] ?? 'text') !== 'text' ? ' d-none' : '' }}">
                                                                    <textarea class="form-control tab-content-input" rows="4" placeholder="Isi konten deskripsi tab ini...">{{ $tab['content'] ?? '' }}</textarea>
                                                                </div>
                                                                {{-- Photo content --}}
                                                                <div class="tab-photo-area{{ ($tab['type'] ?? 'text') !== 'photo' ? ' d-none' : '' }}">
                                                                    @if(!empty($tabMediaGroups[$tIdx]))
                                                                    <div class="media-grid mb-2">
                                                                        @foreach($tabMediaGroups[$tIdx] as $tm)
                                                                        <div class="media-thumb" id="media-{{ $tm->id }}">
                                                                            <img src="{{ asset('storage/'.$tm->file_path) }}" alt="{{ $tm->original_name }}">
                                                                            <label class="remove-media" title="Hapus">
                                                                                <input type="checkbox" name="delete_media[]" value="{{ $tm->id }}" class="d-none" onchange="this.closest('.media-thumb').style.opacity=this.checked?'0.3':'1'">
                                                                                <i class="fas fa-times"></i>
                                                                            </label>
                                                                        </div>
                                                                        @endforeach
                                                                    </div>
                                                                    @endif
                                                                    <label class="form-label small text-muted mb-1">Upload foto (bisa banyak):</label>
                                                                    <input type="file" name="widget_tab_files[{{ $widget->id }}][{{ $tIdx }}][]" class="form-control form-control-sm" accept="image/*" multiple>
                                                                </div>
                                                                {{-- PDF content --}}
                                                                <div class="tab-pdf-area{{ ($tab['type'] ?? 'text') !== 'pdf' ? ' d-none' : '' }}">
                                                                    @if(!empty($tabMediaGroups[$tIdx]))
                                                                    <ul class="list-unstyled mb-2">
                                                                        @foreach($tabMediaGroups[$tIdx] as $tm)
                                                                        <li class="d-flex align-items-center gap-2 mb-1 p-2 rounded" style="background:#fff3f3;">
                                                                            <i class="fas fa-file-pdf text-danger fs-5"></i>
                                                                            <small class="flex-1 text-truncate">{{ $tm->original_name }}</small>
                                                                            <label class="ms-auto" title="Hapus" style="cursor:pointer;">
                                                                                <input type="checkbox" name="delete_media[]" value="{{ $tm->id }}" class="d-none" onchange="this.closest('li').style.opacity=this.checked?'0.3':'1'">
                                                                                <i class="fas fa-times-circle text-danger"></i>
                                                                            </label>
                                                                        </li>
                                                                        @endforeach
                                                                    </ul>
                                                                    @endif
                                                                    <label class="form-label small text-muted mb-1">Upload file PDF:</label>
                                                                    <input type="file" name="widget_tab_pdf[{{ $widget->id }}][{{ $tIdx }}][]" class="form-control form-control-sm" accept=".pdf">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2 add-tab-btn" data-wid="{{ $widget->id }}">
                                                        <i class="fas fa-plus me-1"></i> Tambah Tab
                                                    </button>
                                                    <input type="hidden" name="widgets[{{ $widget->id }}][text_content]" id="tabJson_{{ $widget->id }}" class="tab-json-output">
                                                </div>

                                            @elseif($inputType === 'berita')
                                                {{-- Berita Lokal widget --}}
                                                <small class="text-muted d-block mb-2">
                                                    <i class="fas fa-newspaper me-1 text-primary"></i>
                                                    Tulis artikel berita khusus untuk halaman ini.
                                                    Format: <code>Judul | Isi artikel</code> — pisah artikel dengan baris <code>---</code>
                                                </small>
                                                <textarea name="widgets[{{ $widget->id }}][text_content]"
                                                          class="form-control" rows="8"
                                                          placeholder="Judul Berita Pertama | Isi artikel berita pertama di sini...
---
Judul Berita Kedua | Isi artikel berita kedua...">{{ $widget->text_content }}</textarea>
                                                <div class="row g-2 mt-1">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold small mb-1"><i class="fas fa-image me-1 text-success"></i> Upload Foto Thumbnail Artikel</label>
                                                        <input type="file" name="widget_files[{{ $widget->id }}][]" class="form-control form-control-sm" accept="image/*" multiple>
                                                        <small class="text-muted">Foto thumbnail (urutan sesuai urutan artikel).</small>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold small mb-1"><i class="fas fa-video me-1 text-danger"></i> Upload Video Artikel</label>
                                                        <input type="file" name="widget_files_video[{{ $widget->id }}][]" class="form-control form-control-sm" accept="video/*,video/mp4,video/webm,video/ogg" multiple>
                                                        <small class="text-muted">Video pendukung artikel (format MP4/WebM/OGG).</small>
                                                    </div>
                                                </div>
                                                @if($widget->media->count())
                                                <div class="mt-2">
                                                    <small class="fw-semibold text-muted d-block mb-1">Media Tersimpan:</small>
                                                <div class="media-grid">
                                                    @foreach($widget->media as $media)
                                                    <div class="media-thumb" id="media-{{ $media->id }}">
                                                        @if($media->media_type === 'image')
                                                            <img src="{{ asset('storage/' . $media->file_path) }}" alt="{{ $media->original_name }}">
                                                        @else
                                                            <video src="{{ asset('storage/' . $media->file_path) }}" muted></video>
                                                        @endif
                                                        <label class="remove-media" title="Hapus">
                                                            <input type="checkbox" name="delete_media[]" value="{{ $media->id }}" class="d-none"
                                                                   onchange="this.closest('.media-thumb').style.opacity=this.checked?'0.3':'1'">
                                                            <i class="fas fa-times"></i>
                                                        </label>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                </div>
                                                @endif

                                            @elseif($inputType === 'none')
                                                {{-- No-input widget: separator --}}
                                                <div class="text-center text-muted small">
                                                    <i class="fas fa-minus me-1"></i> Widget ini tidak memerlukan input — tampil otomatis.
                                                </div>

                                            @elseif($inputType === 'number')
                                                {{-- Number widget: spacer --}}
                                                <div class="d-flex align-items-center gap-2">
                                                    <label class="form-label fw-semibold small mb-0">Tinggi (px):</label>
                                                    <input type="number"
                                                           name="widgets[{{ $widget->id }}][text_content]"
                                                           class="form-control form-control-sm" style="max-width:140px;"
                                                           value="{{ $widget->text_content ?: 30 }}"
                                                           placeholder="30" min="5" max="500" step="5">
                                                    <span class="text-muted small">px</span>
                                                </div>

                                            @elseif($inputType === 'textarea')
                                                {{-- Textarea widget --}}
                                                @if($widget->widget_type === 'daftar')
                                                    <small class="text-muted d-block mb-1"><i class="fas fa-info-circle me-1"></i>Satu item per baris</small>
                                                @elseif($widget->widget_type === 'accordion')
                                                    <small class="text-muted d-block mb-1"><i class="fas fa-info-circle me-1"></i>Format: <code>Judul | Isi konten</code> (satu per baris, pisah dengan |)</small>
                                                @elseif($widget->widget_type === 'html_kustom')
                                                    <small class="text-muted d-block mb-1"><i class="fas fa-info-circle me-1"></i>Tulis kode HTML langsung</small>
                                                @elseif($widget->widget_type === 'banner')
                                                    <small class="text-muted d-block mb-1"><i class="fas fa-info-circle me-1"></i>Teks pengumuman/banner</small>
                                                @endif
                                                <textarea name="widgets[{{ $widget->id }}][text_content]"
                                                          class="form-control" rows="{{ in_array($widget->widget_type, ['html_kustom', 'accordion']) ? 6 : 4 }}"
                                                          placeholder="{{ $ti['label'] ?? 'Tulis konten...' }}">{{ $widget->text_content }}</textarea>

                                            @elseif($inputType === 'file')
                                                {{-- File upload widget: foto, video, pdf, logo, audio, file_download --}}
                                                @php
                                                    $acceptMap = [
                                                        'foto'               => 'image/*',
                                                        'video'              => 'video/*',
                                                        'pdf'                => '.pdf',
                                                        'logo'               => 'image/*',
                                                        'audio'              => 'audio/*',
                                                        'file_download'      => '*/*',
                                                        'galeri_foto_lokal'  => 'image/*',
                                                        'galeri_video_lokal' => 'video/*',
                                                        'gambar_sidebar'     => 'image/*',
                                                    ];
                                                    $accept = $acceptMap[$widget->widget_type] ?? '*/*';
                                                    $isMultiple = in_array($widget->widget_type, ['foto', 'video', 'pdf', 'audio', 'file_download', 'galeri_foto_lokal', 'galeri_video_lokal', 'gambar_sidebar']);
                                                @endphp

                                                {{-- Gambar Sidebar: posisi + hilangkan background --}}
                                                @if($widget->widget_type === 'gambar_sidebar')
                                                {{-- position selector as two visual buttons --}}
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold small mb-2"><i class="fas fa-columns me-1 text-primary"></i>Posisi Gambar</label>
                                                    <div class="d-flex gap-2">
                                                        <label class="sidebar-pos-btn flex-1 text-center p-3 rounded border cursor-pointer {{ ($ws['sidebar_position'] ?? 'left') === 'left' ? 'border-primary bg-primary bg-opacity-10' : 'border-light-subtle bg-light' }}" style="cursor:pointer;">
                                                            <input type="radio" class="d-none" name="widgets[{{ $widget->id }}][extra_sidebar_position]" value="left" {{ ($ws['sidebar_position'] ?? 'left') === 'left' ? 'checked' : '' }}
                                                                   onchange="this.closest('.d-flex').querySelectorAll('.sidebar-pos-btn').forEach(b=>b.className=b.className.replace('border-primary bg-primary bg-opacity-10','border-light-subtle bg-light'));this.closest('.sidebar-pos-btn').className=this.closest('.sidebar-pos-btn').className.replace('border-light-subtle bg-light','border-primary bg-primary bg-opacity-10')">
                                                            <div style="font-size:1.5rem;">◀️</div>
                                                            <div class="small fw-bold mt-1">Kiri</div>
                                                        </label>
                                                        <label class="sidebar-pos-btn flex-1 text-center p-3 rounded border cursor-pointer {{ ($ws['sidebar_position'] ?? 'left') === 'right' ? 'border-primary bg-primary bg-opacity-10' : 'border-light-subtle bg-light' }}" style="cursor:pointer;">
                                                            <input type="radio" class="d-none" name="widgets[{{ $widget->id }}][extra_sidebar_position]" value="right" {{ ($ws['sidebar_position'] ?? 'left') === 'right' ? 'checked' : '' }}
                                                                   onchange="this.closest('.d-flex').querySelectorAll('.sidebar-pos-btn').forEach(b=>b.className=b.className.replace('border-primary bg-primary bg-opacity-10','border-light-subtle bg-light'));this.closest('.sidebar-pos-btn').className=this.closest('.sidebar-pos-btn').className.replace('border-light-subtle bg-light','border-primary bg-primary bg-opacity-10')">
                                                            <div style="font-size:1.5rem;">▶️</div>
                                                            <div class="small fw-bold mt-1">Kanan</div>
                                                        </label>
                                                    </div>
                                                </div>
                                                {{-- Background removal toggle —  visual sticker mode --}}
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold small mb-2"><i class="fas fa-magic me-1 text-success"></i>Mode Tampilan Gambar</label>
                                                    <div class="d-flex gap-2">
                                                        <label class="flex-1 text-center p-3 rounded border cursor-pointer {{ empty($ws['remove_background']) ? 'border-success bg-success bg-opacity-10' : 'border-light-subtle bg-light' }}" style="cursor:pointer;">
                                                            <input type="radio" class="d-none" id="rmbg_off_{{ $widget->id }}" name="widgets[{{ $widget->id }}][extra_remove_background]" value="0" {{ empty($ws['remove_background']) ? 'checked' : '' }}>
                                                            <div style="font-size:1.5rem;">🖼️</div>
                                                            <div class="small fw-bold mt-1">Normal</div>
                                                            <div class="small text-muted" style="font-size:.7rem;">dengan background</div>
                                                        </label>
                                                        <label class="flex-1 text-center p-3 rounded border cursor-pointer {{ !empty($ws['remove_background']) ? 'border-success bg-success bg-opacity-10' : 'border-light-subtle bg-light' }}" style="cursor:pointer;">
                                                            <input type="radio" class="d-none" id="rmbg_on_{{ $widget->id }}" name="widgets[{{ $widget->id }}][extra_remove_background]" value="1" {{ !empty($ws['remove_background']) ? 'checked' : '' }}>
                                                            <div style="font-size:1.5rem;">🪄</div>
                                                            <div class="small fw-bold mt-1">Stiker</div>
                                                            <div class="small text-muted" style="font-size:.7rem;">hapus background putih</div>
                                                        </label>
                                                    </div>
                                                </div>
                                                @endif

                                                {{-- Galeri foto: judul + posisi judul + mode + orientasi --}}
                                                @if($widget->widget_type === 'galeri_foto_lokal')
                                                <div class="row g-2 mb-3">
                                                    {{-- Row 1: Judul + Posisi Judul --}}
                                                    <div class="col-md-8">
                                                        <label class="form-label fw-semibold small mb-1"><i class="fas fa-heading me-1 text-primary"></i>Judul Galeri <span class="text-muted fw-normal">(opsional)</span></label>
                                                        <input type="text"
                                                               name="widgets[{{ $widget->id }}][extra_galeri_title]"
                                                               class="form-control form-control-sm"
                                                               value="{{ $ws['galeri_title'] ?? '' }}"
                                                               placeholder="Contoh: Dokumentasi Kegiatan">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-semibold small mb-1"><i class="fas fa-align-center me-1 text-warning"></i>Posisi Judul</label>
                                                        <div class="d-flex gap-2 mt-1 flex-wrap">
                                                            @foreach(['left'=>'Kiri','center'=>'Tengah','right'=>'Kanan'] as $tpos=>$tlabel)
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                       name="widgets[{{ $widget->id }}][extra_galeri_title_pos]"
                                                                       id="gtpos_{{ $tpos }}_{{ $widget->id }}" value="{{ $tpos }}"
                                                                       {{ ($ws['galeri_title_pos'] ?? 'left') === $tpos ? 'checked' : '' }}>
                                                                <label class="form-check-label small" for="gtpos_{{ $tpos }}_{{ $widget->id }}">{{ $tlabel }}</label>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    {{-- Row 2: Mode + Orientasi --}}
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold small mb-1"><i class="fas fa-play-circle me-1 text-success"></i>Mode Tampilan</label>
                                                        <div class="d-flex gap-3 mt-1">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                       name="widgets[{{ $widget->id }}][extra_galeri_mode]"
                                                                       id="gmode_grid_{{ $widget->id }}" value="grid"
                                                                       {{ ($ws['galeri_mode'] ?? 'grid') === 'grid' ? 'checked' : '' }}>
                                                                <label class="form-check-label small" for="gmode_grid_{{ $widget->id }}"><i class="fas fa-th me-1"></i>Grid (diam)</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                       name="widgets[{{ $widget->id }}][extra_galeri_mode]"
                                                                       id="gmode_scroll_{{ $widget->id }}" value="scroll"
                                                                       {{ ($ws['galeri_mode'] ?? 'grid') === 'scroll' ? 'checked' : '' }}>
                                                                <label class="form-check-label small" for="gmode_scroll_{{ $widget->id }}"><i class="fas fa-arrows-alt-h me-1"></i>Berjalan</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold small mb-1"><i class="fas fa-expand me-1 text-info"></i>Orientasi Foto</label>
                                                        <div class="d-flex gap-3 mt-1 flex-wrap">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                       name="widgets[{{ $widget->id }}][extra_galeri_orient]"
                                                                       id="gorient_landscape_{{ $widget->id }}" value="landscape"
                                                                       {{ ($ws['galeri_orient'] ?? 'landscape') === 'landscape' ? 'checked' : '' }}>
                                                                <label class="form-check-label small" for="gorient_landscape_{{ $widget->id }}"><i class="fas fa-image me-1"></i>Landscape</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                       name="widgets[{{ $widget->id }}][extra_galeri_orient]"
                                                                       id="gorient_portrait_{{ $widget->id }}" value="portrait"
                                                                       {{ ($ws['galeri_orient'] ?? 'landscape') === 'portrait' ? 'checked' : '' }}>
                                                                <label class="form-check-label small" for="gorient_portrait_{{ $widget->id }}"><i class="fas fa-portrait me-1"></i>Potret</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                       name="widgets[{{ $widget->id }}][extra_galeri_orient]"
                                                                       id="gorient_auto_{{ $widget->id }}" value="auto"
                                                                       {{ ($ws['galeri_orient'] ?? 'landscape') === 'auto' ? 'checked' : '' }}>
                                                                <label class="form-check-label small" for="gorient_auto_{{ $widget->id }}"><i class="fas fa-expand-arrows-alt me-1"></i>Auto</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                {{-- Galeri video: URL embed opsional --}}
                                                @if($widget->widget_type === 'galeri_video_lokal')
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold small mb-1"><i class="fas fa-link me-1 text-primary"></i>URL Video Embed <span class="text-muted fw-normal">(opsional, YouTube atau link video langsung)</span></label>
                                                    <input type="url"
                                                           name="widgets[{{ $widget->id }}][extra_video_url]"
                                                           class="form-control form-control-sm"
                                                           value="{{ $ws['video_url'] ?? '' }}"
                                                           placeholder="https://youtu.be/xxx atau https://example.com/video.mp4">
                                                    <small class="text-muted">Jika diisi, video ini akan ditampilkan di atas galeri video lokal.</small>
                                                </div>
                                                @endif

                                                @if($widget->widget_type === 'gambar_sidebar')
                                                <div class="alert alert-primary border-0 py-2 px-3 mb-2" style="background:linear-gradient(90deg,#003d82,#0066cc);color:#fff;border-radius:8px;">
                                                    <i class="fas fa-upload me-2"></i><strong>Upload Gambar Sidebar</strong>
                                                    <div class="small mt-1" style="opacity:.9;">Pilih gambar yang akan tampil di sisi {{ ($ws['sidebar_position'] ?? 'kiri') === 'left' ? 'KIRI' : 'KANAN' }} halaman.</div>
                                                </div>
                                                @endif

                                                <input type="file" name="widget_files[{{ $widget->id }}]{{ $isMultiple ? '[]' : '' }}"
                                                       class="form-control form-control-sm mb-2"
                                                       accept="{{ $accept }}" {{ $isMultiple ? 'multiple' : '' }}>
                                                <small class="text-muted">
                                                    @if($isMultiple) Bisa upload banyak file sekaligus. @else Upload file baru untuk mengganti. @endif
                                                </small>

                                                {{-- Show existing media --}}
                                                @if($widget->media->count())
                                                <div class="media-grid">
                                                    @foreach($widget->media as $media)
                                                    <div class="media-thumb" id="media-{{ $media->id }}">
                                                        @if($media->media_type === 'image' || $media->media_type === 'logo')
                                                            <img src="{{ asset('storage/' . $media->file_path) }}" alt="{{ $media->original_name }}">
                                                        @elseif($media->media_type === 'video')
                                                            <video src="{{ asset('storage/' . $media->file_path) }}" muted></video>
                                                        @elseif($media->media_type === 'audio')
                                                            <div class="pdf-preview">
                                                                <i class="fas fa-music"></i>
                                                                <span>{{ Str::limit($media->original_name, 10) }}</span>
                                                            </div>
                                                        @elseif($media->media_type === 'pdf')
                                                            <div class="pdf-preview">
                                                                <i class="fas fa-file-pdf"></i>
                                                                <span>{{ Str::limit($media->original_name, 10) }}</span>
                                                            </div>
                                                        @else
                                                            <div class="pdf-preview">
                                                                <i class="fas fa-file"></i>
                                                                <span>{{ Str::limit($media->original_name, 10) }}</span>
                                                            </div>
                                                        @endif
                                                        <label class="remove-media" title="Hapus file ini">
                                                            <input type="checkbox" name="delete_media[]" value="{{ $media->id }}" class="d-none"
                                                                   onchange="this.closest('.media-thumb').style.opacity=this.checked?'0.3':'1'">
                                                            <i class="fas fa-times"></i>
                                                        </label>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                @endif

                                            @else
                                                {{-- Text/URL/Date/Email/Tel input --}}
                                                @if($widget->widget_type === 'tombol')
                                                    <div class="mb-2">
                                                        <label class="form-label fw-semibold small mb-1">Label Tombol</label>
                                                        <input type="text"
                                                               name="widgets[{{ $widget->id }}][extra_label]"
                                                               class="form-control form-control-sm"
                                                               value="{{ $ws['button_label'] ?? 'Klik Di Sini' }}"
                                                               placeholder="Teks tombol...">
                                                    </div>
                                                    <label class="form-label fw-semibold small mb-1">URL Tujuan</label>
                                                @endif
                                                @if($widget->widget_type === 'icon_teks')
                                                    <div class="mb-2">
                                                        <label class="form-label fw-semibold small mb-1">Kelas Ikon (FontAwesome)</label>
                                                        <div class="input-group input-group-sm">
                                                            <span class="input-group-text"><i class="{{ $ws['icon_class'] ?? 'fas fa-star' }}" id="iconPreview{{ $widget->id }}"></i></span>
                                                            <input type="text"
                                                                   name="widgets[{{ $widget->id }}][extra_icon]"
                                                                   class="form-control"
                                                                   value="{{ $ws['icon_class'] ?? 'fas fa-star' }}"
                                                                   placeholder="fas fa-star"
                                                                   oninput="document.getElementById('iconPreview{{ $widget->id }}').className=this.value||'fas fa-star'">
                                                        </div>
                                                    </div>
                                                    <label class="form-label fw-semibold small mb-1">Teks</label>
                                                @endif
                                                @if($widget->widget_type === 'nomor_statistik')
                                                    <div class="mb-2">
                                                        <label class="form-label fw-semibold small mb-1">Label</label>
                                                        <input type="text"
                                                               name="widgets[{{ $widget->id }}][extra_label]"
                                                               class="form-control form-control-sm"
                                                               value="{{ $ws['stat_label'] ?? '' }}"
                                                               placeholder="Misal: Jumlah Anggota">
                                                    </div>
                                                    <label class="form-label fw-semibold small mb-1">Angka</label>
                                                @endif
                                                @if($widget->widget_type === 'link_url')
                                                    {{-- Multi-URL Repeater (unlimited) --}}
                                                    @php
                                                        $existingUrls = [];
                                                        if ($widget->text_content) {
                                                            $decoded = json_decode($widget->text_content, true);
                                                            if (is_array($decoded)) {
                                                                $existingUrls = $decoded;
                                                            } else {
                                                                $existingUrls = [['label' => '', 'url' => $widget->text_content]];
                                                            }
                                                        }
                                                        if (!$existingUrls) $existingUrls = [['label' => '', 'url' => '']];
                                                    @endphp
                                                    <small class="text-muted d-block mb-2"><i class="fas fa-info-circle me-1 text-primary"></i>Tambah URL tanpa batas. Label bersifat opsional.</small>
                                                    <div class="url-repeater" id="urlRepeater_{{ $widget->id }}">
                                                        @foreach($existingUrls as $urlEntry)
                                                        <div class="url-repeater-row d-flex gap-2 mb-2 align-items-center">
                                                            <input type="text" class="form-control form-control-sm url-label-input" placeholder="Label tombol (opsional)" value="{{ $urlEntry['label'] ?? '' }}" oninput="serializeUrls('{{ $widget->id }}')">
                                                            <input type="url" class="form-control form-control-sm url-url-input" placeholder="https://example.com" value="{{ $urlEntry['url'] ?? '' }}" oninput="serializeUrls('{{ $widget->id }}')">
                                                            <button type="button" class="btn btn-sm btn-outline-danger flex-shrink-0" onclick="this.closest('.url-repeater-row').remove(); serializeUrls('{{ $widget->id }}')"><i class="fas fa-times"></i></button>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-outline-primary mb-2" onclick="addUrlRow('{{ $widget->id }}')"><i class="fas fa-plus me-1"></i> Tambah URL</button>
                                                    <input type="hidden" name="widgets[{{ $widget->id }}][text_content]" id="urlHidden_{{ $widget->id }}" value="{{ $widget->text_content }}">
                                                @else
                                                <input type="{{ $inputType }}"
                                                       name="widgets[{{ $widget->id }}][text_content]"
                                                       class="form-control form-control-sm"
                                                       value="{{ $widget->text_content }}"
                                                       placeholder="{{ $ti['label'] ?? '' }}...">
                                                @endif
                                            @endif
                                        </div>

                                        {{-- ===== LAYOUT SETTINGS PANEL (hidden — settings preserved) ===== --}}
                                        <div class="layout-panel" style="display:none;">
                                            <div class="layout-title"><i class="fas fa-ruler-combined me-1"></i> Pengaturan Ukuran & Posisi</div>
                                            <div class="layout-row">
                                                {{-- Lebar / Width --}}
                                                <div class="layout-field">
                                                    <label><i class="fas fa-arrows-alt-h me-1"></i> Lebar (%)</label>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <input type="range" class="form-range flex-grow-1" min="10" max="100" step="5"
                                                               name="widgets[{{ $widget->id }}][settings][max_width]"
                                                               value="{{ $ws['max_width'] ?? 100 }}"
                                                               oninput="this.nextElementSibling.textContent=this.value+'%'; this.closest('.layout-field').querySelector('.width-preview').style.width=this.value+'%'">
                                                        <span class="badge bg-primary" style="min-width:40px;font-size:11px;">{{ $ws['max_width'] ?? 100 }}%</span>
                                                    </div>
                                                    <div class="width-preview" style="width:{{ $ws['max_width'] ?? 100 }}%;"></div>
                                                </div>

                                                {{-- Tinggi Maks / Max Height --}}
                                                <div class="layout-field">
                                                    <label><i class="fas fa-arrows-alt-v me-1"></i> Tinggi Maks (px)</label>
                                                    <div class="input-group" style="max-width:140px;">
                                                        <input type="number" class="form-control"
                                                               name="widgets[{{ $widget->id }}][settings][max_height]"
                                                               value="{{ $ws['max_height'] ?? '' }}"
                                                               placeholder="Auto" min="0" max="2000" step="10">
                                                        <span class="input-group-text" style="font-size:11px;">px</span>
                                                    </div>
                                                    <small class="text-muted" style="font-size:10px;">Kosongkan = otomatis</small>
                                                </div>

                                                {{-- Posisi / Alignment --}}
                                                <div class="layout-field">
                                                    <label><i class="fas fa-align-center me-1"></i> Posisi</label>
                                                    <div class="align-selector">
                                                        @foreach(['left' => 'fa-align-left', 'center' => 'fa-align-center', 'right' => 'fa-align-right'] as $aKey => $aIcon)
                                                        <label class="align-btn {{ ($ws['alignment'] ?? 'center') === $aKey ? 'active' : '' }}"
                                                               onclick="selectAlign(this, '{{ $aKey }}')">
                                                            <input type="radio" name="widgets[{{ $widget->id }}][settings][alignment]"
                                                                   value="{{ $aKey }}" {{ ($ws['alignment'] ?? 'center') === $aKey ? 'checked' : '' }}>
                                                            <i class="fas {{ $aIcon }}"></i>
                                                        </label>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                {{-- Ukuran Font (untuk teks) --}}
                                                @if(in_array($inputType, ['text', 'textarea', 'url', 'email', 'tel', 'date', 'number']))
                                                <div class="layout-field">
                                                    <label><i class="fas fa-text-height me-1"></i> Ukuran Font (px)</label>
                                                    <div class="input-group" style="max-width:120px;">
                                                        <input type="number" class="form-control"
                                                               name="widgets[{{ $widget->id }}][settings][font_size]"
                                                               value="{{ $ws['font_size'] ?? '' }}"
                                                               placeholder="Auto" min="8" max="120" step="1">
                                                        <span class="input-group-text" style="font-size:11px;">px</span>
                                                    </div>
                                                </div>
                                                @endif

                                                {{-- Padding --}}
                                                <div class="layout-field">
                                                    <label><i class="fas fa-expand-arrows-alt me-1"></i> Padding</label>
                                                    <select name="widgets[{{ $widget->id }}][settings][padding]" class="form-select">
                                                        <option value="0" {{ ($ws['padding'] ?? '3') === '0' ? 'selected' : '' }}>Tidak ada</option>
                                                        <option value="1" {{ ($ws['padding'] ?? '3') === '1' ? 'selected' : '' }}>Sangat kecil</option>
                                                        <option value="2" {{ ($ws['padding'] ?? '3') === '2' ? 'selected' : '' }}>Kecil</option>
                                                        <option value="3" {{ ($ws['padding'] ?? '3') === '3' ? 'selected' : '' }}>Normal</option>
                                                        <option value="4" {{ ($ws['padding'] ?? '3') === '4' ? 'selected' : '' }}>Besar</option>
                                                        <option value="5" {{ ($ws['padding'] ?? '3') === '5' ? 'selected' : '' }}>Sangat besar</option>
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- Baris 2: Warna & Style --}}
                                            <div class="layout-title mt-3"><i class="fas fa-palette me-1"></i> Warna & Gaya</div>
                                            <div class="layout-row">
                                                {{-- Background Color --}}
                                                <div class="layout-field">
                                                    <label><i class="fas fa-fill-drip me-1"></i> Warna Latar</label>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <input type="color" class="form-control form-control-color" style="width:36px;height:30px;padding:2px;"
                                                               name="widgets[{{ $widget->id }}][settings][background_color]"
                                                               value="{{ $ws['background_color'] ?? '#ffffff' }}">
                                                        <input type="text" class="form-control" style="max-width:90px;"
                                                               value="{{ $ws['background_color'] ?? '' }}"
                                                               placeholder="Kosong"
                                                               oninput="this.previousElementSibling.value=this.value||'#ffffff'"
                                                               onchange="this.previousElementSibling.value=this.value||'#ffffff'">
                                                        <button type="button" class="btn btn-sm btn-outline-secondary" style="font-size:10px;padding:2px 6px;"
                                                                onclick="var inputs=this.closest('.layout-field').querySelectorAll('input');inputs[0].value='#ffffff';inputs[1].value='';">
                                                            Reset
                                                        </button>
                                                    </div>
                                                </div>

                                                {{-- Text Color --}}
                                                <div class="layout-field">
                                                    <label><i class="fas fa-font me-1"></i> Warna Teks</label>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <input type="color" class="form-control form-control-color" style="width:36px;height:30px;padding:2px;"
                                                               name="widgets[{{ $widget->id }}][settings][text_color]"
                                                               value="{{ $ws['text_color'] ?? '#212529' }}">
                                                        <input type="text" class="form-control" style="max-width:90px;"
                                                               value="{{ $ws['text_color'] ?? '' }}"
                                                               placeholder="Kosong"
                                                               oninput="this.previousElementSibling.value=this.value||'#212529'"
                                                               onchange="this.previousElementSibling.value=this.value||'#212529'">
                                                        <button type="button" class="btn btn-sm btn-outline-secondary" style="font-size:10px;padding:2px 6px;"
                                                                onclick="var inputs=this.closest('.layout-field').querySelectorAll('input');inputs[0].value='#212529';inputs[1].value='';">
                                                            Reset
                                                        </button>
                                                    </div>
                                                </div>

                                                {{-- Border Style --}}
                                                <div class="layout-field">
                                                    <label><i class="fas fa-border-all me-1"></i> Border</label>
                                                    <select name="widgets[{{ $widget->id }}][settings][border_style]" class="form-select">
                                                        <option value="none" {{ ($ws['border_style'] ?? 'none') === 'none' ? 'selected' : '' }}>Tidak ada</option>
                                                        <option value="solid" {{ ($ws['border_style'] ?? 'none') === 'solid' ? 'selected' : '' }}>Garis penuh</option>
                                                        <option value="dashed" {{ ($ws['border_style'] ?? 'none') === 'dashed' ? 'selected' : '' }}>Garis putus</option>
                                                        <option value="dotted" {{ ($ws['border_style'] ?? 'none') === 'dotted' ? 'selected' : '' }}>Titik-titik</option>
                                                    </select>
                                                </div>

                                                {{-- Border Radius --}}
                                                <div class="layout-field">
                                                    <label><i class="fas fa-vector-square me-1"></i> Sudut (px)</label>
                                                    <div class="input-group" style="max-width:120px;">
                                                        <input type="number" class="form-control"
                                                               name="widgets[{{ $widget->id }}][settings][border_radius]"
                                                               value="{{ $ws['border_radius'] ?? '' }}"
                                                               placeholder="0" min="0" max="50" step="2">
                                                        <span class="input-group-text" style="font-size:11px;">px</span>
                                                    </div>
                                                </div>

                                                {{-- Shadow --}}
                                                <div class="layout-field">
                                                    <label><i class="fas fa-clone me-1"></i> Bayangan</label>
                                                    <select name="widgets[{{ $widget->id }}][settings][shadow]" class="form-select">
                                                        <option value="" {{ empty($ws['shadow']) ? 'selected' : '' }}>Tidak ada</option>
                                                        <option value="small" {{ ($ws['shadow'] ?? '') === 'small' ? 'selected' : '' }}>Kecil</option>
                                                        <option value="medium" {{ ($ws['shadow'] ?? '') === 'medium' ? 'selected' : '' }}>Sedang</option>
                                                        <option value="large" {{ ($ws['shadow'] ?? '') === 'large' ? 'selected' : '' }}>Besar</option>
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- Live preview hint --}}
                                            <div class="mt-2" style="font-size:10px;color:#999;">
                                                <i class="fas fa-info-circle me-1"></i> Perubahan ukuran, posisi & gaya akan terlihat di halaman publik setelah disimpan.
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                                <div class="text-end mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Simpan Semua Konten
                                    </button>
                                </div>
                            </form>
                            @else
                            <div class="text-center py-5">
                                <i class="fas fa-puzzle-piece d-block mb-3" style="font-size:48px;color:#dee2e6;"></i>
                                <h6 class="text-muted fw-bold">Belum Ada Widget</h6>
                                <p class="text-muted small">Klik salah satu widget di strip <strong>Tambah Widget</strong> di atas untuk memulai.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ===== Live Page Preview (always visible, scroll to see) ===== --}}
<div class="preview-section" id="livePreviewSection">
    <div class="preview-header">
        <i class="fas fa-eye me-2"></i>
        <span class="fw-bold">Preview Halaman Web Langsung</span>
        <span class="badge bg-warning text-dark ms-2" style="font-size:10px;">LIVE</span>
        <small class="ms-2 opacity-75">{{ $menu->name }} &mdash; /halaman/{{ $menu->slug }}</small>
        <div class="ms-auto d-flex gap-2 align-items-center">
            <button type="button" class="btn btn-sm btn-outline-light" onclick="reloadPreview()" title="Reload">
                <i class="fas fa-sync-alt"></i>
            </button>
            <a href="{{ route('custom.page', $menu->slug) }}" target="_blank" class="btn btn-sm btn-warning text-dark fw-semibold">
                <i class="fas fa-external-link-alt me-1"></i>Buka Tab Baru
            </a>
        </div>
    </div>

    <div class="preview-toolbar">
        {{-- Zoom --}}
        <i class="fas fa-search-plus text-muted"></i>
        <label class="fw-semibold small mb-0">Zoom:</label>
        <select class="zoom-select" id="zoomSelect" onchange="applyZoom(parseFloat(this.value))">
            <option value="0.35">35%</option>
            <option value="0.45">45%</option>
            <option value="0.55">55%</option>
            <option value="0.65" selected>65%</option>
            <option value="0.75">75%</option>
            <option value="0.85">85%</option>
            <option value="1">100% (asli)</option>
        </select>

        {{-- Device presets --}}
        <span class="text-muted" style="margin-left:8px;">|</span>
        <div class="device-btn active" id="devDesktop" title="Desktop 1440px" onclick="setDevice('desktop',1440,900)"><i class="fas fa-desktop"></i></div>
        <div class="device-btn" id="devTablet" title="Tablet 768px" onclick="setDevice('tablet',768,1024)"><i class="fas fa-tablet-alt"></i></div>
        <div class="device-btn" id="devMobile" title="Mobile 390px" onclick="setDevice('mobile',390,844)"><i class="fas fa-mobile-alt"></i></div>

        <span class="text-muted small ms-auto" id="previewSizeLabel">1440 &times; 900px &nbsp;@&nbsp;65%</span>
    </div>

    <div class="preview-iframe-wrap" id="previewWrap" style="height:600px;">
        <iframe id="previewIframe"
                src="{{ route('custom.page', $menu->slug) }}"
                title="Live Preview: {{ $menu->name }}"
                scrolling="yes"
                style="width:1440px;height:900px;transform-origin:top left;transform:scale(0.65);"></iframe>
    </div>

    {{-- Vertical resize bar (drag to make preview taller/shorter) --}}
    <div class="preview-resize-bar" id="previewResizeBar" title="Seret untuk ubah tinggi preview"></div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
/* ============================================================
   WIDGET ACCORDION – Collapse / Expand
============================================================ */
const WC_STORAGE_KEY = 'wc_state_{{ $menu->id }}';

function toggleWidget(card) {
    card.classList.toggle('wc-collapsed');
    saveCollapseState();
}

function collapseAllWidgets() {
    document.querySelectorAll('#widgetList .widget-card').forEach(c => {
        c.classList.add('wc-collapsed');
        if (c.dataset.type === 'judul') collapseGroupMembers(c, true);
    });
    saveCollapseState();
}
function expandAllWidgets() {
    document.querySelectorAll('#widgetList .widget-card').forEach(c => {
        c.classList.remove('wc-collapsed');
        if (c.dataset.type === 'judul') collapseGroupMembers(c, false);
    });
    saveCollapseState();
}
function collapseAllExceptJudul() {
    document.querySelectorAll('#widgetList .widget-card').forEach(c => {
        if (c.dataset.type === 'judul') {
            c.classList.add('wc-collapsed');
            collapseGroupMembers(c, false); // show group members but judul body hidden
        } else {
            c.classList.add('wc-collapsed');
            c.style.display = '';
        }
    });
    saveCollapseState();
}

/* ============================================================
   WIDGET GROUP – Group-level collapse by Judul
============================================================ */
function buildGroupMembership() {
    const cards = [...document.querySelectorAll('#widgetList > .widget-card')];
    let currentGid = null;
    cards.forEach(card => {
        if (card.dataset.type === 'judul') {
            currentGid = card.dataset.widgetId;
            card.removeAttribute('data-group-id');
            card.classList.remove('wg-member');
        } else {
            card.classList.add('wg-member');
            if (currentGid) card.dataset.groupId = currentGid;
            else card.removeAttribute('data-group-id');
        }
    });
    // Update group member count badges on judul cards
    document.querySelectorAll('#widgetList .widget-card[data-type="judul"]').forEach(judulCard => {
        const gid = judulCard.dataset.widgetId;
        const cnt = document.querySelectorAll(`#widgetList .widget-card[data-group-id="${gid}"]`).length;
        const badge = judulCard.querySelector('.wg-count-badge');
        if (badge) badge.textContent = cnt + ' item';
    });
}

function collapseGroupMembers(judulCard, hide) {
    const gid = judulCard.dataset.widgetId;
    document.querySelectorAll(`#widgetList .widget-card[data-group-id="${gid}"]`)
        .forEach(c => { c.style.display = hide ? 'none' : ''; });
    if (hide) judulCard.classList.add('wg-group-collapsed');
    else judulCard.classList.remove('wg-group-collapsed');
}

function toggleWidgetGroup(judulCard) {
    const isCollapsed = judulCard.classList.contains('wg-group-collapsed');
    collapseGroupMembers(judulCard, !isCollapsed);
    // Also collapse the judul's own body when collapsing group
    if (!isCollapsed) judulCard.classList.add('wc-collapsed');
    saveCollapseState();
}

function saveCollapseState() {
    const state = {};
    document.querySelectorAll('#widgetList .widget-card').forEach(c => {
        state[c.dataset.widgetId] = c.classList.contains('wc-collapsed');
    });
    try { localStorage.setItem(WC_STORAGE_KEY, JSON.stringify(state)); } catch(e) {}
}

function restoreCollapseState() {
    try {
        const state = JSON.parse(localStorage.getItem(WC_STORAGE_KEY) || '{}');
        document.querySelectorAll('#widgetList .widget-card').forEach(c => {
            const id = c.dataset.widgetId;
            if (id in state) {
                if (state[id]) c.classList.add('wc-collapsed');
                else c.classList.remove('wc-collapsed');
            }
            // default: stay collapsed (class already added in Blade)
        });
    } catch(e) {}
}

document.addEventListener('DOMContentLoaded', function() {
    buildGroupMembership();
    restoreCollapseState();
    // Start all groups collapsed (hide group members, show judul)
    document.querySelectorAll('#widgetList .widget-card[data-type="judul"]').forEach(j => {
        collapseGroupMembers(j, true);
    });
});

// Drag-and-drop widget reorder with auto-scroll
const widgetList = document.getElementById('widgetList');
if (widgetList) {
    // Auto-scroll state
    let _scrollRAF = null;
    let _scrollDelta = 0;
    const SCROLL_ZONE = 120; // px from edge that triggers scroll
    const SCROLL_SPEED = 14; // px per frame

    function _autoScrollLoop() {
        if (_scrollDelta !== 0) {
            window.scrollBy(0, _scrollDelta);
            _scrollRAF = requestAnimationFrame(_autoScrollLoop);
        } else {
            _scrollRAF = null;
        }
    }

    document.addEventListener('dragover', function(e) {
        const y = e.clientY;
        const vh = window.innerHeight;
        if (y < SCROLL_ZONE) {
            _scrollDelta = -SCROLL_SPEED * ((SCROLL_ZONE - y) / SCROLL_ZONE);
        } else if (y > vh - SCROLL_ZONE) {
            _scrollDelta = SCROLL_SPEED * ((y - (vh - SCROLL_ZONE)) / SCROLL_ZONE);
        } else {
            _scrollDelta = 0;
        }
        if (_scrollDelta !== 0 && !_scrollRAF) {
            _scrollRAF = requestAnimationFrame(_autoScrollLoop);
        }
    });

    document.addEventListener('dragend', function() {
        _scrollDelta = 0;
        if (_scrollRAF) { cancelAnimationFrame(_scrollRAF); _scrollRAF = null; }
    });

    new Sortable(widgetList, {
        handle: '.drag-handle',
        animation: 200,
        ghostClass: 'bg-light',
        scroll: true,
        scrollSensitivity: 80,
        scrollSpeed: 12,
        onMove: function(evt) {
            // Prevent moving locked widgets (ticker, tab, sidebar)
            if (evt.dragged.dataset.locked === 'true') return false;
            if (evt.related && evt.related.dataset.locked === 'true') return false;
            // additional scroll nudge via pointer position
            const y = evt.originalEvent ? evt.originalEvent.clientY : 0;
            const vh = window.innerHeight;
            if (y < SCROLL_ZONE) {
                _scrollDelta = -SCROLL_SPEED * ((SCROLL_ZONE - y) / SCROLL_ZONE);
            } else if (y > vh - SCROLL_ZONE) {
                _scrollDelta = SCROLL_SPEED * ((y - (vh - SCROLL_ZONE)) / SCROLL_ZONE);
            } else {
                _scrollDelta = 0;
            }
            if (_scrollDelta !== 0 && !_scrollRAF) {
                _scrollRAF = requestAnimationFrame(_autoScrollLoop);
            }
        },
        onEnd: function() {
            _scrollDelta = 0;
            if (_scrollRAF) { cancelAnimationFrame(_scrollRAF); _scrollRAF = null; }
            // Update hidden position inputs
            widgetList.querySelectorAll('.widget-card').forEach((card, i) => {
                const posInput = card.querySelector('.widget-position');
                if (posInput) posInput.value = i;
            });
            buildGroupMembership();
            // AJAX reorder
            const order = [...widgetList.querySelectorAll('.widget-card')].map(c => c.dataset.widgetId);
            fetch('{{ route("admin.custom-menu.widgets.reorder", $menu->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ order }),
            });
        }
    });
}

// Icon preview in edit form
document.getElementById('editIconInput')?.addEventListener('input', function() {
    document.getElementById('editIconPreview').className = this.value || 'fas fa-file-alt';
});

// ===== URL REPEATER =====
window.addUrlRow = function(widgetId) {
    var container = document.getElementById('urlRepeater_' + widgetId);
    var row = document.createElement('div');
    row.className = 'url-repeater-row d-flex gap-2 mb-2 align-items-center';
    row.innerHTML = '<input type="text" class="form-control form-control-sm url-label-input" placeholder="Label tombol (opsional)" oninput="serializeUrls(\'' + widgetId + '\')">' +
        '<input type="url" class="form-control form-control-sm url-url-input" placeholder="https://example.com" oninput="serializeUrls(\'' + widgetId + '\')">' +
        '<button type="button" class="btn btn-sm btn-outline-danger flex-shrink-0" onclick="this.closest(\'.url-repeater-row\').remove(); serializeUrls(\'' + widgetId + '\')"><i class="fas fa-times"></i></button>';
    container.appendChild(row);
    serializeUrls(widgetId);
    row.querySelector('.url-url-input').focus();
};

window.serializeUrls = function(widgetId) {
    var container = document.getElementById('urlRepeater_' + widgetId);
    var hidden = document.getElementById('urlHidden_' + widgetId);
    if (!container || !hidden) return;
    var data = [];
    container.querySelectorAll('.url-repeater-row').forEach(function(row) {
        var label = (row.querySelector('.url-label-input') ? row.querySelector('.url-label-input').value.trim() : '');
        var url = (row.querySelector('.url-url-input') ? row.querySelector('.url-url-input').value.trim() : '');
        if (url) data.push({ label: label, url: url });
    });
    hidden.value = JSON.stringify(data);
};

// Auto-serialize all URL repeaters before form submit
document.querySelectorAll('form').forEach(function(form) {
    form.addEventListener('submit', function() {
        document.querySelectorAll('[id^="urlRepeater_"]').forEach(function(rep) {
            serializeUrls(rep.id.replace('urlRepeater_', ''));
        });
    });
});

// Alignment selector
function selectAlign(el, value) {
    const container = el.closest('.align-selector');
    container.querySelectorAll('.align-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    el.querySelector('input[type="radio"]').checked = true;
}

// ── Live Preview ────────────────────────────────────────────────────────────
let currentZoom = 0.65;
let currentW = 1440, currentH = 900;

function applyZoom(val) {
    currentZoom = parseFloat(val);
    const iframe = document.getElementById('previewIframe');
    const wrap   = document.getElementById('previewWrap');
    if (!iframe || !wrap) return;
    iframe.style.width     = currentW + 'px';
    iframe.style.height    = currentH + 'px';
    iframe.style.transform = 'scale(' + currentZoom + ')';
    // Adjust wrap height so content below doesn't overlap
    wrap.style.height = Math.round(currentH * currentZoom) + 'px';
    const sel = document.getElementById('zoomSelect');
    if (sel) sel.value = val;
    const lbl = document.getElementById('previewSizeLabel');
    if (lbl) lbl.innerHTML = currentW + ' &times; ' + currentH + 'px &nbsp;@&nbsp;' + Math.round(currentZoom * 100) + '%';
}

function setDevice(name, w, h) {
    currentW = w; currentH = h;
    document.querySelectorAll('.device-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('dev' + name.charAt(0).toUpperCase() + name.slice(1))?.classList.add('active');
    applyZoom(currentZoom);
}

function reloadPreview() {
    const iframe = document.getElementById('previewIframe');
    if (iframe) { iframe.src = iframe.src; }
}

// Init preview zoom on load
window.addEventListener('load', function() {
    applyZoom(currentZoom);
});

// ── Preview vertical resize (drag resize-bar) ────────────────────────────────
(function() {
    const bar  = document.getElementById('previewResizeBar');
    const wrap = document.getElementById('previewWrap');
    if (!bar || !wrap) return;
    let dragging = false, startY = 0, startH = 0;
    bar.addEventListener('mousedown', function(e) {
        dragging = true;
        startY = e.clientY;
        startH = wrap.offsetHeight;
        document.body.style.cursor = 'ns-resize';
        document.body.style.userSelect = 'none';
        e.preventDefault();
    });
    document.addEventListener('mousemove', function(e) {
        if (!dragging) return;
        const newH = Math.max(200, startH + (e.clientY - startY));
        wrap.style.height = newH + 'px';
    });
    document.addEventListener('mouseup', function() {
        if (dragging) {
            dragging = false;
            document.body.style.cursor = '';
            document.body.style.userSelect = '';
        }
    });
})();

// ── Widget Card Resize (drag bottom-right corner) ────────────────────────────
(function() {
    // Inject resize handles into every widget card
    document.querySelectorAll('.widget-card').forEach(function(card) {
        const widgetId = card.dataset.widgetId;
        if (!widgetId) return;

        // Add resize handle
        const handle = document.createElement('div');
        handle.className = 'widget-resize-handle';
        handle.title = 'Seret untuk ubah ukuran widget';
        handle.innerHTML = '<svg width="12" height="12" viewBox="0 0 12 12"><path d="M1 11 L11 1 M5 11 L11 5 M9 11 L11 9" stroke="#667eea" stroke-width="1.5" stroke-linecap="round"/></svg>';
        card.appendChild(handle);

        // Find or create hidden size inputs
        function getSizeInput(suffix) {
            let inp = card.querySelector('input[name="widgets[' + widgetId + '][settings][' + suffix + ']"]');
            if (!inp) {
                inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = 'widgets[' + widgetId + '][settings][' + suffix + ']';
                card.appendChild(inp);
            }
            return inp;
        }
        const widthInput  = getSizeInput('max_width');
        const heightInput = getSizeInput('max_height');

        // Size badge in widget header
        let badge = card.querySelector('.widget-size-badge');
        if (!badge) {
            badge = document.createElement('span');
            badge.className = 'widget-size-badge';
            const titleEl = card.querySelector('.widget-title');
            if (titleEl) titleEl.appendChild(badge);
        }
        function updateBadge() {
            const w = widthInput.value  ? widthInput.value + '%'  : '100%';
            const h = heightInput.value ? heightInput.value + 'px' : 'auto';
            badge.textContent = w + ' × ' + h;
        }
        updateBadge();

        // Drag state
        let dragging = false, startX = 0, startY = 0, startW = 0, startH = 0;
        const containW = card.offsetWidth || 600;

        handle.addEventListener('mousedown', function(e) {
            dragging = true;
            startX = e.clientX;
            startY = e.clientY;
            // Current dimensions from inputs or card
            startW = widthInput.value  ? parseInt(widthInput.value)  : 100;
            startH = heightInput.value ? parseInt(heightInput.value) : card.offsetHeight;
            card.classList.add('resizing');
            document.body.style.cursor = 'se-resize';
            document.body.style.userSelect = 'none';
            e.stopPropagation();
            e.preventDefault();
        });

        document.addEventListener('mousemove', function(e) {
            if (!dragging) return;
            const dx = e.clientX - startX;
            const dy = e.clientY - startY;
            // Width as % of parent container
            const newWpx  = Math.max(20, (startW / 100) * containW + dx);
            const newWpct = Math.min(100, Math.max(20, Math.round(newWpx / containW * 100)));
            const newH    = Math.max(40, startH + dy);
            widthInput.value  = newWpct;
            heightInput.value = newH;
            // Apply visual feedback
            card.style.maxWidth  = newWpct + '%';
            card.style.minHeight = newH + 'px';
            updateBadge();
        });

        document.addEventListener('mouseup', function() {
            if (!dragging) return;
            dragging = false;
            card.classList.remove('resizing');
            document.body.style.cursor = '';
            document.body.style.userSelect = '';
        });
    });
})();

// ===== Tab Builder =====
(function() {
    // Serialize a single tab builder widget to JSON
    function serializeTabs(wid) {
        const list = document.getElementById('tabList_' + wid);
        if (!list) return;
        const output = document.getElementById('tabJson_' + wid);
        if (!output) return;
        const tabs = [];
        list.querySelectorAll('.tab-builder-item').forEach(function(item) {
            const name    = (item.querySelector('.tab-name-input') || {}).value || '';
            const type    = (item.querySelector('.tab-type-select') || {}).value || 'text';
            const content = type === 'text' ? ((item.querySelector('.tab-content-input') || {}).value || '') : '';
            tabs.push({ name: name.trim(), type: type, content: content });
        });
        output.value = JSON.stringify(tabs);
    }

    function updateFileInputNames(wid) {
        const list = document.getElementById('tabList_' + wid);
        if (!list) return;
        list.querySelectorAll('.tab-builder-item').forEach(function(item, idx) {
            item.dataset.tabIdx = idx;
            const photoIn = item.querySelector('.tab-photo-area input[type=file]');
            if (photoIn) photoIn.name = 'widget_tab_files[' + wid + '][' + idx + '][]';
            const pdfIn = item.querySelector('.tab-pdf-area input[type=file]');
            if (pdfIn) pdfIn.name = 'widget_tab_pdf[' + wid + '][' + idx + '][]';
        });
    }

    // Init all existing builders
    document.querySelectorAll('.tab-builder-wrap').forEach(function(wrap) {
        const wid = wrap.id.replace('tabBuilder_', '');
        serializeTabs(wid);

        // Input/change events
        wrap.addEventListener('input', function(e) {
            if (e.target.classList.contains('tab-name-input') || e.target.classList.contains('tab-content-input')) {
                serializeTabs(wid);
            }
        });
        wrap.addEventListener('change', function(e) {
            if (e.target.classList.contains('tab-type-select')) {
                const item = e.target.closest('.tab-builder-item');
                item.querySelector('.tab-text-area').classList.toggle('d-none',  e.target.value !== 'text');
                item.querySelector('.tab-photo-area').classList.toggle('d-none', e.target.value !== 'photo');
                item.querySelector('.tab-pdf-area').classList.toggle('d-none',   e.target.value !== 'pdf');
                updateFileInputNames(wid);
                serializeTabs(wid);
            }
        });
        // Remove tab
        wrap.addEventListener('click', function(e) {
            const btn = e.target.closest('.tab-remove-btn');
            if (btn) {
                const list = document.getElementById('tabList_' + wid);
                if (list.querySelectorAll('.tab-builder-item').length > 1) {
                    btn.closest('.tab-builder-item').remove();
                    updateFileInputNames(wid);
                    serializeTabs(wid);
                }
            }
        });
        // Add tab button
        wrap.querySelector('.add-tab-btn')?.addEventListener('click', function() {
            const list = document.getElementById('tabList_' + wid);
            const idx  = list.querySelectorAll('.tab-builder-item').length;
            const div  = document.createElement('div');
            div.className = 'tab-builder-item card border-0 shadow-sm mb-2';
            div.dataset.tabIdx = idx;
            div.innerHTML =
                '<div class="card-header d-flex align-items-center gap-2 py-2 px-3" style="background:#eef2ff;border-bottom:1px solid #d0d5e0;">' +
                    '<i class="fas fa-grip-vertical text-muted" style="cursor:grab;"></i>' +
                    '<input type="text" class="form-control form-control-sm tab-name-input fw-semibold border-0 bg-transparent" style="max-width:200px;box-shadow:none;" placeholder="Nama Tab...">' +
                    '<select class="form-select form-select-sm tab-type-select ms-auto border" style="max-width:150px;">' +
                        '<option value="text" selected>📝 Deskripsi</option>' +
                        '<option value="photo">🖼️ Foto</option>' +
                        '<option value="pdf">📄 PDF</option>' +
                    '</select>' +
                    '<button type="button" class="btn btn-sm btn-outline-danger tab-remove-btn ms-1" title="Hapus Tab"><i class="fas fa-trash-alt"></i></button>' +
                '</div>' +
                '<div class="card-body p-3">' +
                    '<div class="tab-text-area"><textarea class="form-control tab-content-input" rows="4" placeholder="Isi konten deskripsi tab ini..."></textarea></div>' +
                    '<div class="tab-photo-area d-none"><label class="form-label small text-muted mb-1">Upload foto (bisa banyak):</label><input type="file" name="widget_tab_files[' + wid + '][' + idx + '][]" class="form-control form-control-sm" accept="image/*" multiple></div>' +
                    '<div class="tab-pdf-area d-none"><label class="form-label small text-muted mb-1">Upload file PDF:</label><input type="file" name="widget_tab_pdf[' + wid + '][' + idx + '][]" class="form-control form-control-sm" accept=".pdf"></div>' +
                '</div>';
            list.appendChild(div);
            serializeTabs(wid);
        });
    });

    // Serialize all tab builders before any form submit
    document.addEventListener('submit', function() {
        document.querySelectorAll('.tab-builder-wrap').forEach(function(wrap) {
            const wid = wrap.id.replace('tabBuilder_', '');
            serializeTabs(wid);
        });
    }, true); // capture phase so it runs before form submit
})();

// ===== Template Picker =====
(function() {
    const grid    = document.getElementById('tplGrid');
    const applyBtn = document.getElementById('tplApplyBtn');
    const applyForm = document.getElementById('tplApplyForm');
    const applyKey = document.getElementById('tplApplyKey');
    const applyReplace = document.getElementById('tplApplyReplace');
    const replaceChk  = document.getElementById('tplReplace');
    const searchBox   = document.getElementById('tplSearch');
    if (!grid) return;

    let selectedKey = null;

    // Category filter
    document.querySelectorAll('.tpl-cat').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tpl-cat').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const cat = btn.dataset.cat;
            filterCards(cat, searchBox ? searchBox.value : '');
        });
    });

    // Search
    if (searchBox) {
        searchBox.addEventListener('input', function() {
            const activeCat = (document.querySelector('.tpl-cat.active') || {}).dataset?.cat || 'all';
            filterCards(activeCat, this.value.toLowerCase());
        });
    }

    function filterCards(cat, search) {
        grid.querySelectorAll('.tpl-card').forEach(function(card) {
            const matchCat = (cat === 'all' || card.dataset.cat === cat);
            const matchSearch = (!search || card.dataset.name.includes(search));
            card.style.display = (matchCat && matchSearch) ? '' : 'none';
        });
    }

    // Select a template card
    grid.addEventListener('click', function(e) {
        const card = e.target.closest('.tpl-card');
        if (!card) return;
        grid.querySelectorAll('.tpl-card').forEach(c => c.classList.remove('selected'));
        card.classList.add('selected');
        selectedKey = card.dataset.key;
        applyBtn.disabled = false;
        applyBtn.innerHTML = '<i class="fas fa-magic me-1"></i> Terapkan: ' + card.querySelector('.tpl-name').textContent;
    });

    // Apply
    applyBtn.addEventListener('click', function() {
        if (!selectedKey) return;
        applyKey.value = selectedKey;
        applyReplace.value = replaceChk.checked ? '1' : '0';
        applyForm.submit();
    });
})();
</script>
@endpush
