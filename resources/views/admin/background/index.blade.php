@extends('layouts.admin')

@section('title', 'Pengaturan Background & Teks')
@section('page-title', 'Pengaturan Background & Teks')

@push('styles')
<style>
    .setting-card {
        border: none;
        box-shadow: 0 2px 12px rgba(0,61,130,0.10);
        border-radius: 12px;
    }
    .setting-card .card-header {
        background: linear-gradient(135deg, #001f3f 0%, #003d82 100%);
        color: white;
        border-radius: 12px 12px 0 0 !important;
        padding: 16px 24px;
    }
    .preview-box {
        border: 2px dashed #0066cc;
        border-radius: 8px;
        padding: 10px;
        text-align: center;
        background: #f0f6ff;
        min-height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .preview-box img {
        max-height: 160px;
        max-width: 100%;
        border-radius: 6px;
        object-fit: cover;
    }
    .preview-box video {
        max-height: 160px;
        max-width: 100%;
        border-radius: 6px;
    }
    .preview-box .preview-placeholder {
        color: #aaa;
        font-size: 0.9rem;
    }
    .form-label { font-weight: 600; color: #003d82; }
    .btn-simpan {
        background: linear-gradient(135deg, #001f3f 0%, #0066cc 100%);
        color: white;
        font-weight: 700;
        padding: 10px 36px;
        border-radius: 8px;
        font-size: 1rem;
        border: none;
        transition: transform 0.15s, box-shadow 0.15s;
    }
    .btn-simpan:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,61,130,0.35);
    }
    .btn-back-x {
        position: absolute;
        top: 12px;
        right: 16px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(255,255,255,0.15);
        border: none;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        cursor: pointer;
        transition: background 0.2s;
        text-decoration: none;
    }
    .btn-back-x:hover { background: rgba(255,255,255,0.35); color: white; }
    .btn-rotate {
        background: rgba(0,61,130,0.08);
        border: 1px solid #0066cc;
        color: #003d82;
        border-radius: 6px;
        padding: 4px 12px;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s, color 0.2s;
        display: inline-flex; align-items: center; gap: 5px;
    }
    .btn-rotate:hover { background: #0066cc; color: #fff; }
    .rotation-badge {
        background: #001f3f;
        color: #fff;
        border-radius: 20px;
        font-size: 0.78rem;
        padding: 3px 12px;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    .rotate-bar { padding-top: 8px; display:flex; align-items:center; justify-content:center; gap:8px; flex-wrap:wrap; }

    /* ── Focal Point Picker ── */
    .focal-picker-wrap {
        position: relative;
        border: 2px solid #0066cc;
        border-radius: 8px;
        overflow: hidden;
        cursor: crosshair;
        user-select: none;
        background: #f0f6ff;
    }
    .focal-picker-wrap img,
    .focal-picker-wrap video {
        width: 100%;
        display: block;
        pointer-events: none;
    }
    .focal-picker-overlay {
        position: absolute;
        inset: 0;
        z-index: 2;
    }
    .focal-picker-stripe {
        position: absolute;
        left: 0; right: 0;
        height: 60px;
        border: 2px dashed rgba(255,255,255,0.85);
        background: rgba(0,102,204,0.15);
        transform: translateY(-50%);
        pointer-events: none;
        transition: top 0.15s ease;
        z-index: 3;
    }
    .focal-picker-dot {
        position: absolute;
        width: 20px; height: 20px;
        background: #0066cc;
        border: 3px solid #fff;
        border-radius: 50%;
        transform: translate(-50%, -50%);
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        pointer-events: none;
        transition: top 0.15s ease, left 0.15s ease;
        z-index: 4;
    }
    .focal-picker-label {
        position: absolute;
        bottom: 6px; right: 8px;
        background: rgba(0,31,63,0.8);
        color: #fff;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 2px 10px;
        border-radius: 12px;
        z-index: 5;
    }
    .focal-presets {
        display: flex; gap: 6px; flex-wrap: wrap; padding-top: 8px; justify-content: center;
    }
    .focal-preset-btn {
        background: rgba(0,61,130,0.08);
        border: 1px solid #0066cc;
        color: #003d82;
        border-radius: 6px;
        padding: 3px 10px;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s, color 0.2s;
    }
    .focal-preset-btn:hover, .focal-preset-btn.active {
        background: #0066cc; color: #fff;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-9 col-lg-11">

            {{-- Page header --}}
            <div class="d-flex align-items-center mb-4 gap-3">
                <div style="background:linear-gradient(135deg,#001f3f,#0066cc);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-image text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-0 fw-bold text-dark">Pengaturan Background & Teks</h4>
                    <small class="text-muted">Kelola teks hero, background video, dan gambar halaman.</small>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="ms-auto" title="Kembali ke Dashboard"
                   style="width:36px;height:36px;border-radius:50%;background:#e9ecef;border:none;color:#555;display:flex;align-items:center;justify-content:center;font-size:1.1rem;text-decoration:none;transition:background 0.2s;flex-shrink:0;"
                   onmouseover="this.style.background='#d0d7e2'" onmouseout="this.style.background='#e9ecef'">
                    <i class="fas fa-times"></i>
                </a>
            </div>

            <form action="{{ route('admin.setting.background.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- ─── TEKS HERO ─── --}}
                <div class="card setting-card mb-4">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="fas fa-font"></i>
                        <span class="fw-bold">Teks Hero (Halaman Utama)</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label for="hero_title" class="form-label">Judul Utama (Hero Title)</label>
                            <input type="text" class="form-control @error('hero_title') is-invalid @enderror"
                                   id="hero_title" name="hero_title"
                                   value="{{ old('hero_title', $settings['hero_title']) }}"
                                   placeholder="Contoh: TNI AU – Disinfolahtaau">
                            @error('hero_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="hero_subtitle" class="form-label">Sub-Judul (Hero Subtitle)</label>
                            <textarea class="form-control @error('hero_subtitle') is-invalid @enderror"
                                      id="hero_subtitle" name="hero_subtitle" rows="2"
                                      placeholder="Contoh: Dinas Informasi dan Pengolahan Data TNI Angkatan Udara">{{ old('hero_subtitle', $settings['hero_subtitle']) }}</textarea>
                            @error('hero_subtitle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ─── VIDEO / FOTO BACKGROUND HERO ─── --}}
                <div class="card setting-card mb-4">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="fas fa-photo-video"></i>
                        <span class="fw-bold">Media Background Hero (Foto atau Video)</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3 align-items-start">
                            <div class="col-md-7">
                                <label for="hero_media_file" class="form-label">
                                    Upload Foto <em class="text-muted fw-normal">atau</em> Video Baru
                                    <small class="text-muted fw-normal d-block">Foto: JPG / PNG / WebP — Video: MP4 / WebM</small>
                                </label>
                                <input type="file" class="form-control @error('hero_media_file') is-invalid @enderror"
                                       id="hero_media_file" name="hero_media_file"
                                       accept="image/*,video/mp4,video/webm,video/ogg"
                                       onchange="previewHeroMedia(this)">
                                @error('hero_media_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Preview</label>
                                <div class="preview-box" id="heroMediaPreviewBox" style="overflow:hidden;">
                                    @if($settings['hero_media_type'] === 'image' && $settings['hero_image'])
                                        <img src="{{ asset($settings['hero_image']) }}" alt="Hero BG" style="max-height:160px;max-width:100%;border-radius:6px;object-fit:cover;transition:transform .3s;">
                                    @elseif($settings['hero_video'])
                                        <video src="{{ asset($settings['hero_video']) }}" muted loop autoplay playsinline style="max-height:160px;max-width:100%;border-radius:6px;transition:transform .3s;"></video>
                                    @else
                                        <span class="preview-placeholder"><i class="fas fa-photo-video fa-2x d-block mb-2 text-primary opacity-50"></i>Belum ada media</span>
                                    @endif
                                </div>
                                <input type="hidden" name="hero_media_rotation" id="heroMediaRotation" value="{{ $settings['hero_media_rotation'] }}">
                                <div class="rotate-bar">
                                    <span class="text-muted small">Rotasi:</span>
                                    <button type="button" class="btn-rotate" onclick="rotateMedia('heroMediaPreviewBox','heroMediaRotation','heroRotBadge',-90)"><i class="fas fa-undo"></i> Kiri</button>
                                    <button type="button" class="btn-rotate" onclick="rotateMedia('heroMediaPreviewBox','heroMediaRotation','heroRotBadge',90)"><i class="fas fa-redo"></i> Kanan</button>
                                    <span class="rotation-badge" id="heroRotBadge">{{ $settings['hero_media_rotation'] }}°</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ─── PAGE HERO BACKGROUND ─── --}}
                <div class="card setting-card mb-4">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="fas fa-photo-video"></i>
                        <span class="fw-bold">Media Background Halaman Dalam (Page Hero)</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3 align-items-start">
                            <div class="col-md-7">
                                <label for="page_hero_bg_file" class="form-label">
                                    Upload Foto <em class="text-muted fw-normal">atau</em> Video Baru
                                    <small class="text-muted fw-normal d-block">Foto: JPG / PNG / WebP — Video: MP4 / WebM</small>
                                </label>
                                <input type="file" class="form-control @error('page_hero_bg_file') is-invalid @enderror"
                                       id="page_hero_bg_file" name="page_hero_bg_file"
                                       accept="image/*,video/mp4,video/webm,video/ogg"
                                       onchange="previewPageHeroMedia(this)">
                                @error('page_hero_bg_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Preview</label>
                                <div class="preview-box" id="pageHeroPreviewBox" style="overflow:hidden;">
                                    @if($settings['page_hero_bg_type'] === 'video' && $settings['page_hero_bg'])
                                        <video src="{{ asset($settings['page_hero_bg']) }}" muted loop autoplay playsinline style="max-height:160px;max-width:100%;border-radius:6px;transition:transform .3s;"></video>
                                    @elseif($settings['page_hero_bg'])
                                        <img src="{{ asset($settings['page_hero_bg']) }}" alt="Page Hero BG" style="max-height:160px;max-width:100%;border-radius:6px;object-fit:cover;transition:transform .3s;">
                                    @else
                                        <span class="preview-placeholder"><i class="fas fa-photo-video fa-2x d-block mb-2 text-primary opacity-50"></i>Belum ada media</span>
                                    @endif
                                </div>
                                <input type="hidden" name="page_hero_bg_rotation" id="pageHeroBgRotation" value="{{ $settings['page_hero_bg_rotation'] }}">
                                <div class="rotate-bar">
                                    <span class="text-muted small">Rotasi:</span>
                                    <button type="button" class="btn-rotate" onclick="rotateMedia('pageHeroPreviewBox','pageHeroBgRotation','pageRotBadge',-90)"><i class="fas fa-undo"></i> Kiri</button>
                                    <button type="button" class="btn-rotate" onclick="rotateMedia('pageHeroPreviewBox','pageHeroBgRotation','pageRotBadge',90)"><i class="fas fa-redo"></i> Kanan</button>
                                    <span class="rotation-badge" id="pageRotBadge">{{ $settings['page_hero_bg_rotation'] }}°</span>
                                </div>
                            </div>
                        </div>

                        {{-- Focal Point / Position Picker --}}
                        <hr class="my-4">
                        <label class="form-label"><i class="fas fa-crosshairs text-primary me-1"></i> Posisi Fokus Gambar (Area yang tampil di banner)</label>
                        <p class="text-muted small mb-2">Klik pada gambar di bawah untuk memilih bagian mana yang akan tampil sebagai background banner. Strip biru menunjukkan area yang terlihat.</p>
                        <div class="row justify-content-center">
                            <div class="col-lg-10 col-md-12">
                                <div class="focal-picker-wrap" id="focalPickerWrap" onclick="handleFocalClick(event)">
                                    @if($settings['page_hero_bg_type'] === 'video' && $settings['page_hero_bg'])
                                        <video src="{{ asset($settings['page_hero_bg']) }}" muted loop autoplay playsinline></video>
                                    @elseif($settings['page_hero_bg'])
                                        <img src="{{ asset($settings['page_hero_bg']) }}" alt="Pilih area fokus" id="focalPickerImg">
                                    @else
                                        <div style="height:200px;display:flex;align-items:center;justify-content:center;color:#aaa;"><i class="fas fa-image fa-3x"></i></div>
                                    @endif
                                    <div class="focal-picker-overlay" id="focalOverlay"></div>
                                    <div class="focal-picker-stripe" id="focalStripe"></div>
                                    <div class="focal-picker-dot" id="focalDot"></div>
                                    <div class="focal-picker-label" id="focalLabel">{{ $settings['page_hero_bg_position'] }}</div>
                                </div>
                                <input type="hidden" name="page_hero_bg_position" id="pageHeroBgPosition" value="{{ $settings['page_hero_bg_position'] }}">
                                <div class="focal-presets">
                                    <span class="text-muted small me-1" style="line-height:28px;">Preset:</span>
                                    <button type="button" class="focal-preset-btn" onclick="setFocalPreset('center 0%')">Atas</button>
                                    <button type="button" class="focal-preset-btn" onclick="setFocalPreset('center 20%')">Atas-Tengah</button>
                                    <button type="button" class="focal-preset-btn" onclick="setFocalPreset('center 50%')">Tengah</button>
                                    <button type="button" class="focal-preset-btn" onclick="setFocalPreset('center 80%')">Bawah-Tengah</button>
                                    <button type="button" class="focal-preset-btn" onclick="setFocalPreset('center 100%')">Bawah</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ─── BACKGROUND HALAMAN LOGIN ─── --}}
                <div class="card setting-card mb-4">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="fas fa-sign-in-alt"></i>
                        <span class="fw-bold">Background Halaman Login</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3 align-items-start">
                            <div class="col-md-7">
                                <label for="login_bg_file" class="form-label">
                                    Upload Foto <em class="text-muted fw-normal">atau</em> Video Baru
                                    <small class="text-muted fw-normal d-block">Foto: JPG / PNG / WebP — Video: MP4 / WebM</small>
                                </label>
                                <input type="file" class="form-control @error('login_bg_file') is-invalid @enderror"
                                       id="login_bg_file" name="login_bg_file"
                                       accept="image/*,video/mp4,video/webm,video/ogg"
                                       onchange="previewLoginMedia(this)">
                                @error('login_bg_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Preview</label>
                                <div class="preview-box" id="loginBgPreviewBox" style="overflow:hidden;">
                                    @if($settings['login_bg_type'] === 'video' && $settings['login_bg'])
                                        <video src="{{ asset($settings['login_bg']) }}" muted loop autoplay playsinline style="max-height:160px;max-width:100%;border-radius:6px;transition:transform .3s;"></video>
                                    @elseif($settings['login_bg'])
                                        <img src="{{ asset($settings['login_bg']) }}" alt="Login BG" style="max-height:160px;max-width:100%;border-radius:6px;object-fit:cover;transition:transform .3s;">
                                    @else
                                        <span class="preview-placeholder"><i class="fas fa-photo-video fa-2x d-block mb-2 text-primary opacity-50"></i>Belum ada media</span>
                                    @endif
                                </div>
                                <input type="hidden" name="login_bg_rotation" id="loginBgRotation" value="{{ $settings['login_bg_rotation'] }}">
                                <div class="rotate-bar">
                                    <span class="text-muted small">Rotasi:</span>
                                    <button type="button" class="btn-rotate" onclick="rotateMedia('loginBgPreviewBox','loginBgRotation','loginRotBadge',-90)"><i class="fas fa-undo"></i> Kiri</button>
                                    <button type="button" class="btn-rotate" onclick="rotateMedia('loginBgPreviewBox','loginBgRotation','loginRotBadge',90)"><i class="fas fa-redo"></i> Kanan</button>
                                    <span class="rotation-badge" id="loginRotBadge">{{ $settings['login_bg_rotation'] }}°</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ─── BUTTON SIMPAN ─── --}}
                <div class="d-flex justify-content-end gap-2 mb-5">
                    <button type="submit" class="btn btn-simpan">
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    /* ── Rotation helpers ── */
    function getRotationTransform(deg) {
        var scale = (deg === 90 || deg === 270) ? ' scale(1.45)' : '';
        return deg ? 'rotate(' + deg + 'deg)' + scale : '';
    }
    function applyRotationToPreview(boxId, deg) {
        var media = document.querySelector('#' + boxId + ' img, #' + boxId + ' video');
        if (media) {
            media.style.transition = 'transform 0.35s';
            media.style.transform = getRotationTransform(deg);
        }
    }
    function rotateMedia(boxId, inputId, badgeId, delta) {
        var input  = document.getElementById(inputId);
        var badge  = document.getElementById(badgeId);
        var cur    = (parseInt(input.value) || 0);
        cur        = ((cur + delta) % 360 + 360) % 360;
        input.value = cur;
        badge.textContent = cur + '°';
        applyRotationToPreview(boxId, cur);
    }
    /* Apply saved rotation on page load */
    document.addEventListener('DOMContentLoaded', function () {
        applyRotationToPreview('heroMediaPreviewBox',  parseInt(document.getElementById('heroMediaRotation').value)  || 0);
        applyRotationToPreview('pageHeroPreviewBox',   parseInt(document.getElementById('pageHeroBgRotation').value) || 0);
        applyRotationToPreview('loginBgPreviewBox',    parseInt(document.getElementById('loginBgRotation').value)    || 0);
    });

    function previewHeroMedia(input) {
        const box = document.getElementById('heroMediaPreviewBox');
        if (!input.files || !input.files[0]) return;
        const file = input.files[0];
        const url  = URL.createObjectURL(file);
        const rot  = parseInt(document.getElementById('heroMediaRotation').value) || 0;
        const tf   = getRotationTransform(rot);
        if (file.type.startsWith('video/')) {
            box.innerHTML = '<video src="' + url + '" muted loop autoplay playsinline style="max-height:160px;max-width:100%;border-radius:6px;transition:transform .3s;transform:' + tf + '"></video>';
        } else {
            box.innerHTML = '<img src="' + url + '" style="max-height:160px;max-width:100%;border-radius:6px;object-fit:cover;transition:transform .3s;transform:' + tf + '">';
        }
    }

    function previewPageHeroMedia(input) {
        const box = document.getElementById('pageHeroPreviewBox');
        if (!input.files || !input.files[0]) return;
        const file = input.files[0];
        const url  = URL.createObjectURL(file);
        const rot  = parseInt(document.getElementById('pageHeroBgRotation').value) || 0;
        const tf   = getRotationTransform(rot);
        if (file.type.startsWith('video/')) {
            box.innerHTML = '<video src="' + url + '" muted loop autoplay playsinline style="max-height:160px;max-width:100%;border-radius:6px;transition:transform .3s;transform:' + tf + '"></video>';
        } else {
            box.innerHTML = '<img src="' + url + '" style="max-height:160px;max-width:100%;border-radius:6px;object-fit:cover;transition:transform .3s;transform:' + tf + '">';
        }
        // Update focal picker with new image
        var picker = document.getElementById('focalPickerWrap');
        if (picker) {
            if (file.type.startsWith('video/')) {
                var vid = picker.querySelector('video');
                if (!vid) {
                    picker.querySelectorAll('img').forEach(function(el){ el.remove(); });
                    vid = document.createElement('video');
                    vid.muted = true; vid.loop = true; vid.autoplay = true; vid.playsInline = true;
                    picker.insertBefore(vid, picker.firstChild);
                }
                vid.src = url;
            } else {
                var img = picker.querySelector('img');
                if (!img) {
                    picker.querySelectorAll('video').forEach(function(el){ el.remove(); });
                    img = document.createElement('img');
                    img.id = 'focalPickerImg'; img.alt = 'Pilih area fokus';
                    picker.insertBefore(img, picker.firstChild);
                }
                img.src = url;
            }
        }
    }

    /* ── Focal Point Picker Logic ── */
    function parseFocalPosition(val) {
        // Parse "center 20%" → { x: 50, y: 20 }
        var parts = (val || 'center 20%').split(/\s+/);
        var x = 50, y = 20;
        if (parts[0] === 'left') x = 0;
        else if (parts[0] === 'right') x = 100;
        else if (parts[0] === 'center') x = 50;
        else x = parseFloat(parts[0]) || 50;
        if (parts.length > 1) y = parseFloat(parts[1]) || 20;
        return { x: x, y: y };
    }

    function updateFocalUI(xPct, yPct) {
        var dot    = document.getElementById('focalDot');
        var stripe = document.getElementById('focalStripe');
        var label  = document.getElementById('focalLabel');
        var input  = document.getElementById('pageHeroBgPosition');
        var val    = 'center ' + Math.round(yPct) + '%';
        if (dot)    { dot.style.top = yPct + '%'; dot.style.left = xPct + '%'; }
        if (stripe) { stripe.style.top = yPct + '%'; }
        if (label)  { label.textContent = val; }
        if (input)  { input.value = val; }
        // Highlight active preset
        document.querySelectorAll('.focal-preset-btn').forEach(function(btn) {
            btn.classList.toggle('active', btn.getAttribute('onclick').indexOf(val) !== -1);
        });
    }

    function handleFocalClick(e) {
        var wrap = document.getElementById('focalPickerWrap');
        if (!wrap) return;
        var rect = wrap.getBoundingClientRect();
        var xPct = ((e.clientX - rect.left) / rect.width * 100);
        var yPct = ((e.clientY - rect.top) / rect.height * 100);
        xPct = Math.max(0, Math.min(100, xPct));
        yPct = Math.max(0, Math.min(100, yPct));
        updateFocalUI(xPct, yPct);
    }

    function setFocalPreset(val) {
        var pos = parseFocalPosition(val);
        updateFocalUI(pos.x, pos.y);
    }

    // Initialize focal point on page load
    document.addEventListener('DOMContentLoaded', function() {
        var initVal = document.getElementById('pageHeroBgPosition');
        if (initVal) {
            var pos = parseFocalPosition(initVal.value);
            updateFocalUI(pos.x, pos.y);
        }
    });

    function previewLoginMedia(input) {
        const box = document.getElementById('loginBgPreviewBox');
        if (!input.files || !input.files[0]) return;
        const file = input.files[0];
        const url  = URL.createObjectURL(file);
        const rot  = parseInt(document.getElementById('loginBgRotation').value) || 0;
        const tf   = getRotationTransform(rot);
        if (file.type.startsWith('video/')) {
            box.innerHTML = '<video src="' + url + '" muted loop autoplay playsinline style="max-height:160px;max-width:100%;border-radius:6px;transition:transform .3s;transform:' + tf + '"></video>';
        } else {
            box.innerHTML = '<img src="' + url + '" style="max-height:160px;max-width:100%;border-radius:6px;object-fit:cover;transition:transform .3s;transform:' + tf + '">';
        }
    }
</script>
@endpush
