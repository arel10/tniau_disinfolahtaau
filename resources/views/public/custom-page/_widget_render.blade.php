{{-- ============================================================
     WIDGET RENDER PARTIAL
     Expects: $widget (CustomMenuWidget model)
============================================================ --}}
@php
    $ti = $widget->typeInfo;
    $ws = $widget->settings ?? [];
    $maxW   = $ws['max_width'] ?? 100;
    $maxH   = $ws['max_height'] ?? null;
    $align  = $ws['alignment'] ?? 'center';
    $fSize  = $ws['font_size'] ?? null;
    $pad    = $ws['padding'] ?? '3';

    $wrapStyle = '';
    if ($maxW < 100) $wrapStyle .= "max-width:{$maxW}%;";
    if ($align === 'left')       $wrapStyle .= 'margin-right:auto;';
    elseif ($align === 'right')  $wrapStyle .= 'margin-left:auto;';
    else                         $wrapStyle .= 'margin-left:auto;margin-right:auto;';

    if (!empty($ws['background_color'])) $wrapStyle .= 'background-color:' . $ws['background_color'] . ';';
    if (!empty($ws['text_color']))       $wrapStyle .= 'color:' . $ws['text_color'] . ';';
    if (!empty($ws['border_style']) && $ws['border_style'] !== 'none') {
        $wrapStyle .= 'border:1px ' . $ws['border_style'] . ' #dee2e6;';
    }
    if (!empty($ws['border_radius'])) $wrapStyle .= 'border-radius:' . $ws['border_radius'] . 'px;';
    if (!empty($ws['shadow'])) {
        $shadowCss = match($ws['shadow']) {
            'small'  => '0 1px 3px rgba(0,0,0,0.12)',
            'medium' => '0 4px 12px rgba(0,0,0,0.15)',
            'large'  => '0 8px 24px rgba(0,0,0,0.20)',
            default  => '',
        };
        if ($shadowCss) $wrapStyle .= 'box-shadow:' . $shadowCss . ';';
    }

    $mediaStyle   = '';
    if ($maxH) $mediaStyle .= "max-height:{$maxH}px;object-fit:contain;";
    $alignClass   = $widget->alignClass;
    $paddingClass = "p-{$pad}";
@endphp

{{-- TEKS BERJALAN --}}
@if($widget->widget_type === 'teks_berjalan' && $widget->text_content)
@php $tkBg = $ws['ticker_bg'] ?? '#003d82'; $tkColor = $ws['ticker_color'] ?? '#ffffff'; $tkSpeed = $ws['ticker_speed'] ?? 'normal'; @endphp
<div class="ticker-wrap mb-3 {{ $paddingClass }}" style="background:{{ $tkBg }};border-radius:6px;">
    <div class="ticker-inner ticker-{{ $tkSpeed }}" style="color:{{ $tkColor }};font-weight:600;letter-spacing:0.3px;font-size:{{ $fSize ? $fSize.'px' : '14px' }};">
        &nbsp;&nbsp;<i class="fas fa-bullhorn me-2"></i>{{ $widget->text_content }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $widget->text_content }}&nbsp;&nbsp;
    </div>
</div>
@endif

{{-- JUDUL --}}
@if($widget->widget_type === 'judul' && $widget->text_content)
<div class="{{ $alignClass }} mb-4 {{ $paddingClass }}" style="{{ $wrapStyle }}">
    <h3 class="fw-bold" style="{{ $fSize ? "font-size:{$fSize}px;" : '' }}">{{ $widget->text_content }}</h3>
</div>
@endif

{{-- LOGO --}}
@if($widget->widget_type === 'logo' && $widget->media->count())
<div class="{{ $alignClass }} mb-3 {{ $paddingClass }}" style="{{ $wrapStyle }}">
    @php $logoMedia = $widget->media->first(); @endphp
    <img src="{{ asset('storage/' . $logoMedia->file_path) }}" alt="Logo"
         style="max-width:100%;{{ $maxH ? "max-height:{$maxH}px;" : 'max-height:220px;' }}">
</div>
@endif

{{-- FOTO --}}
@if($widget->widget_type === 'foto' && $widget->media->count())
@php $fotos = $widget->media; @endphp
<div class="{{ $paddingClass }}" style="{{ $wrapStyle }}">
    <div class="row g-3 mb-3">
        @foreach($fotos as $foto)
        <div class="{{ $fotos->count() > 1 ? 'col-md-6' : 'col-12' }}">
            <div class="{{ $alignClass }}">
                <img src="{{ asset('storage/' . $foto->file_path) }}" alt="{{ $foto->original_name }}"
                     class="img-fluid rounded shadow-sm" style="width:100%;cursor:pointer;{{ $mediaStyle }}"
                     onclick="window.open(this.src,'_blank')">
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- VIDEO --}}
@if($widget->widget_type === 'video' && $widget->media->count())
@php $videos = $widget->media; @endphp
<div class="{{ $paddingClass }}" style="{{ $wrapStyle }}">
    <div class="row g-3 mb-3">
        @foreach($videos as $vid)
        <div class="{{ $videos->count() > 1 ? 'col-md-6' : 'col-12' }}">
            <video src="{{ asset('storage/' . $vid->file_path) }}" controls class="rounded shadow-sm w-100" style="{{ $mediaStyle }}"></video>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- PDF --}}
@if($widget->widget_type === 'pdf' && $widget->media->count())
@php $pdfs = $widget->media; @endphp
<div class="{{ $paddingClass }}" style="{{ $wrapStyle }}">
    <div class="row g-3 mb-3">
        @foreach($pdfs as $pdf)
        <div class="{{ $pdfs->count() > 1 ? 'col-md-6' : 'col-12' }}">
            <div class="border rounded overflow-hidden">
                <iframe src="{{ asset('storage/' . $pdf->file_path) }}" width="100%" height="{{ $maxH ?: 500 }}" style="border:none;"></iframe>
                <div class="bg-light px-3 py-2 d-flex justify-content-between align-items-center border-top">
                    <small class="text-muted text-truncate me-2"><i class="fas fa-file-pdf text-danger me-1"></i>{{ $pdf->original_name }}</small>
                    <div class="d-flex gap-1">
                        <a href="{{ asset('storage/' . $pdf->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-external-link-alt me-1"></i>{{ __('messages.btn_buka') }}</a>
                        <a href="{{ asset('storage/' . $pdf->file_path) }}" download class="btn btn-sm btn-primary"><i class="fas fa-download me-1"></i>{{ __('messages.btn_unduh') }}</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- DESKRIPSI --}}
@if($widget->widget_type === 'deskripsi' && $widget->text_content)
<div class="{{ $alignClass }} mb-3 {{ $paddingClass }}" style="{{ $wrapStyle }}{{ $fSize ? "font-size:{$fSize}px;" : '' }}">
    {!! nl2br(e($widget->text_content)) !!}
</div>
@endif

{{-- LINK URL --}}
@if($widget->widget_type === 'link_url' && $widget->text_content)
@php
    $decodedLinks = json_decode($widget->text_content, true);
    $urlLinks = is_array($decodedLinks) ? $decodedLinks : [['label' => '', 'url' => $widget->text_content]];
    $hasLinks = count(array_filter($urlLinks, fn($l) => !empty($l['url'])));
@endphp
@if($hasLinks)
<div class="{{ $alignClass }} mb-3 {{ $paddingClass }}" style="{{ $wrapStyle }}">
    @foreach($urlLinks as $linkItem)
    @php $lUrl = trim($linkItem['url'] ?? ''); $lLabel = trim($linkItem['label'] ?? ''); @endphp
    @if($lUrl)
    <a href="{{ $lUrl }}" target="_blank" rel="noopener" class="btn btn-outline-primary me-2 mb-2" style="{{ $fSize ? "font-size:{$fSize}px;" : '' }}">
        <i class="fas fa-external-link-alt me-1"></i> {{ $lLabel ?: $lUrl }}
    </a>
    @endif
    @endforeach
</div>
@endif
@endif

{{-- YOUTUBE --}}
@if($widget->widget_type === 'youtube' && $widget->text_content)
@php
    $ytUrl = $widget->text_content;
    if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^\s&]+)/', $ytUrl, $m)) { $ytUrl = 'https://www.youtube.com/embed/' . $m[1]; }
@endphp
<div class="{{ $paddingClass }}" style="{{ $wrapStyle }}">
    <div class="ratio ratio-16x9 mb-3">
        <iframe src="{{ $ytUrl }}" allowfullscreen style="border-radius:10px;{{ $maxH ? "max-height:{$maxH}px;" : '' }}"></iframe>
    </div>
</div>
@endif

{{-- MAPS --}}
@if($widget->widget_type === 'maps' && $widget->text_content)
<div class="{{ $paddingClass }}" style="{{ $wrapStyle }}">
    <div class="ratio ratio-16x9 mb-3">
        <iframe src="{{ $widget->text_content }}" allowfullscreen style="border-radius:10px;border:0;{{ $maxH ? "max-height:{$maxH}px;" : '' }}"></iframe>
    </div>
</div>
@endif

{{-- INSTAGRAM --}}
@if($widget->widget_type === 'instagram' && $widget->text_content)
<div class="{{ $alignClass }} mb-3 {{ $paddingClass }}" style="{{ $wrapStyle }}">
    <a href="{{ $widget->text_content }}" target="_blank" rel="noopener" class="btn btn-outline-danger" style="{{ $fSize ? "font-size:{$fSize}px;" : '' }}">
        <i class="fab fa-instagram me-1"></i> {{ __('messages.btn_lihat_instagram') }}
    </a>
</div>
@endif

{{-- TANGGAL --}}
@if($widget->widget_type === 'tanggal' && $widget->text_content)
<div class="{{ $alignClass }} mb-3 {{ $paddingClass }}" style="{{ $wrapStyle }}{{ $fSize ? "font-size:{$fSize}px;" : '' }}">
    <div class="d-inline-flex align-items-center gap-2">
        <i class="fas fa-calendar-alt text-primary"></i>
        <span>{{ \Carbon\Carbon::parse($widget->text_content)->translatedFormat('d F Y') }}</span>
    </div>
</div>
@endif

{{-- LOKASI --}}
@if($widget->widget_type === 'lokasi' && $widget->text_content)
<div class="{{ $alignClass }} mb-3 {{ $paddingClass }}" style="{{ $wrapStyle }}{{ $fSize ? "font-size:{$fSize}px;" : '' }}">
    <div class="d-inline-flex align-items-center gap-2">
        <i class="fas fa-map-marker-alt text-danger"></i>
        <span>{{ $widget->text_content }}</span>
    </div>
</div>
@endif

{{-- EMAIL --}}
@if($widget->widget_type === 'email' && $widget->text_content)
<div class="{{ $alignClass }} mb-3 {{ $paddingClass }}" style="{{ $wrapStyle }}{{ $fSize ? "font-size:{$fSize}px;" : '' }}">
    <div class="d-inline-flex align-items-center gap-2">
        <i class="fas fa-envelope text-info"></i>
        <a href="mailto:{{ $widget->text_content }}">{{ $widget->text_content }}</a>
    </div>
</div>
@endif

{{-- NO HP --}}
@if($widget->widget_type === 'no_hp' && $widget->text_content)
<div class="{{ $alignClass }} mb-3 {{ $paddingClass }}" style="{{ $wrapStyle }}{{ $fSize ? "font-size:{$fSize}px;" : '' }}">
    <div class="d-inline-flex align-items-center gap-2">
        <i class="fas fa-phone text-success"></i>
        <a href="tel:{{ $widget->text_content }}">{{ $widget->text_content }}</a>
    </div>
</div>
@endif

{{-- SEPARATOR --}}
@if($widget->widget_type === 'separator')
<div class="{{ $paddingClass }}" style="{{ $wrapStyle }}">
    <hr style="border-top:2px solid #dee2e6;margin:8px 0;">
</div>
@endif

{{-- SPACER --}}
@if($widget->widget_type === 'spacer')
<div style="height:{{ intval($widget->text_content ?: 30) }}px;"></div>
@endif

{{-- KUTIPAN --}}
@if($widget->widget_type === 'kutipan' && $widget->text_content)
<div class="{{ $alignClass }} mb-3 {{ $paddingClass }}" style="{{ $wrapStyle }}">
    <blockquote class="blockquote" style="border-left:4px solid #667eea;padding-left:16px;font-style:italic;{{ $fSize ? "font-size:{$fSize}px;" : '' }}">
        {!! nl2br(e($widget->text_content)) !!}
    </blockquote>
</div>
@endif

{{-- DAFTAR --}}
@if($widget->widget_type === 'daftar' && $widget->text_content)
<div class="{{ $alignClass }} mb-3 {{ $paddingClass }}" style="{{ $wrapStyle }}{{ $fSize ? "font-size:{$fSize}px;" : '' }}">
    <ul class="mb-0" style="text-align:left;display:inline-block;">
        @foreach(array_filter(explode("\n", $widget->text_content)) as $item)
        <li>{{ trim($item) }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- HTML KUSTOM --}}
@if($widget->widget_type === 'html_kustom' && $widget->text_content)
<div class="mb-3 {{ $paddingClass }}" style="{{ $wrapStyle }}{{ $fSize ? "font-size:{$fSize}px;" : '' }}">
    {!! $widget->text_content !!}
</div>
@endif

{{-- BANNER --}}
@if($widget->widget_type === 'banner' && $widget->text_content)
<div class="{{ $alignClass }} mb-3 {{ $paddingClass }}" style="{{ $wrapStyle }}">
    <div class="alert alert-info d-flex align-items-start gap-2 mb-0" style="{{ $fSize ? "font-size:{$fSize}px;" : '' }}">
        <i class="fas fa-bullhorn mt-1 flex-shrink-0"></i>
        <div>{!! nl2br(e($widget->text_content)) !!}</div>
    </div>
</div>
@endif

{{-- ICON TEKS --}}
@if($widget->widget_type === 'icon_teks' && $widget->text_content)
@php $iconClass = $ws['icon_class'] ?? 'fas fa-star'; @endphp
<div class="{{ $alignClass }} mb-3 {{ $paddingClass }}" style="{{ $wrapStyle }}{{ $fSize ? "font-size:{$fSize}px;" : '' }}">
    <div class="d-inline-flex align-items-center gap-3">
        <i class="{{ $iconClass }}" style="font-size:{{ $fSize ? ($fSize * 1.5) : 24 }}px;color:#667eea;"></i>
        <span>{{ $widget->text_content }}</span>
    </div>
</div>
@endif

{{-- NOMOR STATISTIK --}}
@if($widget->widget_type === 'nomor_statistik' && $widget->text_content)
@php $statLabel = $ws['stat_label'] ?? ''; @endphp
<div class="{{ $alignClass }} mb-3 {{ $paddingClass }}" style="{{ $wrapStyle }}">
    <div class="d-inline-block text-center">
        <div class="fw-bold" style="font-size:{{ $fSize ?: 36 }}px;color:#667eea;line-height:1.2;">{{ $widget->text_content }}</div>
        @if($statLabel)<div class="text-muted" style="font-size:{{ $fSize ? max(12, $fSize * 0.5) : 14 }}px;">{{ $statLabel }}</div>@endif
    </div>
</div>
@endif

{{-- ACCORDION --}}
@if($widget->widget_type === 'accordion' && $widget->text_content)
<div class="{{ $alignClass }} mb-3 {{ $paddingClass }}" style="{{ $wrapStyle }}{{ $fSize ? "font-size:{$fSize}px;" : '' }}">
    <div class="accordion" id="accordion{{ $widget->id }}">
        @foreach(array_filter(explode("\n", $widget->text_content)) as $aIdx => $aLine)
        @php $parts = explode('|', $aLine, 2); $aTitle = trim($parts[0]); $aBody = trim($parts[1] ?? $parts[0]); @endphp
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#acc{{ $widget->id }}_{{ $aIdx }}">{{ $aTitle }}</button>
            </h2>
            <div id="acc{{ $widget->id }}_{{ $aIdx }}" class="accordion-collapse collapse" data-bs-parent="#accordion{{ $widget->id }}">
                <div class="accordion-body">{{ $aBody }}</div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- AUDIO --}}
@if($widget->widget_type === 'audio' && $widget->media->count())
<div class="{{ $paddingClass }}" style="{{ $wrapStyle }}">
    @foreach($widget->media as $audioMedia)
    <div class="{{ $alignClass }} mb-2">
        <audio controls class="w-100" style="max-width:500px;"><source src="{{ asset('storage/' . $audioMedia->file_path) }}"></audio>
        <small class="text-muted d-block">{{ $audioMedia->original_name }}</small>
    </div>
    @endforeach
</div>
@endif

{{-- FILE DOWNLOAD --}}
@if($widget->widget_type === 'file_download' && $widget->media->count())
<div class="{{ $paddingClass }}" style="{{ $wrapStyle }}">
    <div class="list-group mb-3" style="display:inline-block;min-width:250px;">
        @foreach($widget->media as $dlMedia)
        <a href="{{ asset('storage/' . $dlMedia->file_path) }}" download class="list-group-item list-group-item-action d-flex align-items-center gap-2">
            <i class="fas fa-file text-primary"></i>
            <span class="flex-grow-1 text-truncate">{{ $dlMedia->original_name }}</span>
            <i class="fas fa-download text-muted"></i>
        </a>
        @endforeach
    </div>
</div>
@endif

{{-- TOMBOL --}}
@if($widget->widget_type === 'tombol' && $widget->text_content)
@php $btnLabel = $ws['button_label'] ?? __('messages.btn_klik_di_sini'); @endphp
<div class="{{ $alignClass }} mb-3 {{ $paddingClass }}" style="{{ $wrapStyle }}">
    <a href="{{ $widget->text_content }}" target="_blank" rel="noopener" class="btn btn-primary" style="{{ $fSize ? "font-size:{$fSize}px;" : '' }}">
        <i class="fas fa-arrow-right me-1"></i> {{ $btnLabel }}
    </a>
</div>
@endif

{{-- IFRAME --}}
@if($widget->widget_type === 'iframe' && $widget->text_content)
<div class="{{ $paddingClass }}" style="{{ $wrapStyle }}">
    <div class="ratio ratio-16x9 mb-3">
        <iframe src="{{ $widget->text_content }}" allowfullscreen style="border-radius:10px;border:0;{{ $maxH ? "max-height:{$maxH}px;" : '' }}"></iframe>
    </div>
</div>
@endif

{{-- TAB FRAME --}}
@if($widget->widget_type === 'tab_frame' && $widget->text_content)
@php
    $tabLines = array_values(array_filter(explode("\n", $widget->text_content), fn($l) => trim($l) !== ''));
    $tabId = 'wTab' . $widget->id;
@endphp
<div class="{{ $paddingClass }}" style="{{ $wrapStyle }}">
    <ul class="nav nav-tabs mb-3 flex-wrap" id="{{ $tabId }}" role="tablist">
        @foreach($tabLines as $tIdx => $tLine)
        @php $tParts = explode('|', $tLine, 2); $tName = trim($tParts[0]); @endphp
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tIdx === 0 ? 'active' : '' }}" id="{{ $tabId }}-tab{{ $tIdx }}"
                    data-bs-toggle="tab" data-bs-target="#{{ $tabId }}-pane{{ $tIdx }}"
                    type="button" role="tab">{{ $tName }}</button>
        </li>
        @endforeach
    </ul>
    <div class="tab-content" id="{{ $tabId }}Content">
        @foreach($tabLines as $tIdx => $tLine)
        @php $tParts = explode('|', $tLine, 2); $tBody = trim($tParts[1] ?? ''); @endphp
        <div class="tab-pane fade {{ $tIdx === 0 ? 'show active' : '' }}" id="{{ $tabId }}-pane{{ $tIdx }}" role="tabpanel">
            <div class="py-2" style="{{ $fSize ? "font-size:{$fSize}px;" : '' }}">{!! nl2br(e($tBody)) !!}</div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- BERITA LOKAL --}}
@if($widget->widget_type === 'berita_lokal' && $widget->text_content)
@php
    $rawArticles  = array_values(array_filter(explode('---', $widget->text_content), fn($a) => trim($a) !== ''));
    $beritaImages = $widget->media->where('media_type', 'image')->values();
    $beritaVideos = $widget->media->where('media_type', 'video')->values();
@endphp
<div class="{{ $paddingClass }}" style="{{ $wrapStyle }}">
    <div class="row g-4 mb-3">
        @foreach($rawArticles as $aIdx => $rawArticle)
        @php
            $aParts   = explode('|', trim($rawArticle), 2);
            $aTitle   = trim($aParts[0]);
            $aContent = trim($aParts[1] ?? '');
            $aImg     = $beritaImages[$aIdx] ?? null;
            $aVid     = $beritaVideos[$aIdx] ?? null;
        @endphp
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                @if($aImg)
                <img src="{{ asset('storage/' . $aImg->file_path) }}" class="card-img-top" style="height:180px;object-fit:cover;" alt="{{ $aTitle }}">
                @elseif($aVid)
                <video src="{{ asset('storage/' . $aVid->file_path) }}" class="card-img-top" controls style="height:180px;object-fit:cover;"></video>
                @else
                <div class="bg-light d-flex align-items-center justify-content-center" style="height:100px;">
                    <i class="fas fa-newspaper text-muted" style="font-size:36px;opacity:.3;"></i>
                </div>
                @endif
                <div class="card-body">
                    <h6 class="card-title fw-bold" style="{{ $fSize ? "font-size:{$fSize}px;" : '' }}">{{ $aTitle }}</h6>
                    @if($aContent)<p class="card-text text-muted small">{{ \Illuminate\Support\Str::limit($aContent, 160) }}</p>@endif
                    @if($aVid && $aImg)<video src="{{ asset('storage/' . $aVid->file_path) }}" controls class="w-100 mt-2 rounded" style="max-height:160px;"></video>@endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- GALERI FOTO LOKAL --}}
@if($widget->widget_type === 'galeri_foto_lokal' && $widget->media->count())
@php
    $gTitle    = $widget->settings['galeri_title']     ?? '';
    $gMode     = $widget->settings['galeri_mode']      ?? 'grid';
    $gOrient   = $widget->settings['galeri_orient']    ?? 'landscape';
    $gTitlePos = $widget->settings['galeri_title_pos'] ?? 'left';
    $gTitleStyle = match($gTitlePos) {
        'center' => 'text-align:center;border-bottom:3px solid #667eea;padding-bottom:8px;display:block;',
        'right'  => 'text-align:right;border-right:3px solid #667eea;padding-right:10px;',
        default  => 'border-left:3px solid #667eea;padding-left:10px;',
    };
    $gImgStyle = match($gOrient) {
        'portrait' => 'aspect-ratio:3/4;object-fit:cover;width:100%;cursor:pointer;',
        'auto'     => 'width:100%;height:auto;cursor:pointer;',
        default    => 'aspect-ratio:4/3;object-fit:cover;width:100%;cursor:pointer;',
    };
@endphp
<div class="{{ $paddingClass }}" style="{{ $wrapStyle }}">
    @if($gTitle)<h5 class="mb-3 fw-bold" style="{{ $gTitleStyle }}">{{ $gTitle }}</h5>@endif
    @if($gMode === 'scroll')
    <div class="galeri-scroll-wrap mb-3" style="overflow:hidden;position:relative;">
        <div class="galeri-scroll-inner" style="display:flex;gap:10px;align-items:flex-start;animation:galeriScroll {{ max(15, $widget->media->count() * 3) }}s linear infinite;">
            @php $sW = $gOrient === 'portrait' ? '135px' : ($gOrient === 'auto' ? 'auto' : '240px'); $sH = '180px'; $sFit = $gOrient === 'auto' ? 'contain' : 'cover'; @endphp
            @foreach($widget->media as $gFoto)
            <img src="{{ asset('storage/'.$gFoto->file_path) }}" alt="{{ $gFoto->original_name }}" class="rounded shadow-sm flex-shrink-0" style="height:{{ $sH }};width:{{ $sW }};object-fit:{{ $sFit }};cursor:pointer;" onclick="window.open(this.src,'_blank')">
            @endforeach
            @foreach($widget->media as $gFoto)
            <img src="{{ asset('storage/'.$gFoto->file_path) }}" alt="{{ $gFoto->original_name }}" class="rounded shadow-sm flex-shrink-0" style="height:{{ $sH }};width:{{ $sW }};object-fit:{{ $sFit }};cursor:pointer;" onclick="window.open(this.src,'_blank')" aria-hidden="true">
            @endforeach
        </div>
    </div>
    @else
    @php $gCount = $widget->media->count(); $gRowClass = $gCount > 10 ? 'row-galeri-10' : ''; $gColClass = $gCount > 10 ? '' : 'col-6 col-md-4 col-lg-2'; @endphp
    <div class="row g-1 mb-3 {{ $gRowClass }}">
        @foreach($widget->media as $gFoto)
        <div class="{{ $gColClass }}">
            <img src="{{ asset('storage/'.$gFoto->file_path) }}" alt="{{ $gFoto->original_name }}" class="img-fluid rounded shadow-sm" style="{{ $gImgStyle }}" onclick="window.open(this.src,'_blank')" title="{{ $gFoto->original_name }}">
        </div>
        @endforeach
    </div>
    @endif
</div>
@endif

{{-- GALERI VIDEO LOKAL --}}
@if($widget->widget_type === 'galeri_video_lokal')
@php $gvUrl = $widget->settings['video_url'] ?? ''; $hasFiles = $widget->media->count() > 0; @endphp
@if($gvUrl || $hasFiles)
<div class="galeri-video-lokal-widget {{ $paddingClass }}">
    @if($gvUrl)
    @php
        $gvEmbed = $gvUrl; $gvIsYt = false;
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^\s&]+)/', $gvUrl, $gvM)) { $gvEmbed = 'https://www.youtube.com/embed/'.$gvM[1]; $gvIsYt = true; }
    @endphp
    <div class="mb-3">
        @if($gvIsYt)<div class="ratio ratio-16x9"><iframe src="{{ $gvEmbed }}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen style="border-radius:10px;border:none;"></iframe></div>
        @else<video src="{{ $gvEmbed }}" controls class="rounded shadow-sm w-100" style="max-height:480px;"></video>@endif
    </div>
    @endif
    @if($hasFiles)
    <div class="row g-3 mb-3">
        @foreach($widget->media as $gVid)
        <div class="{{ $widget->media->count() > 1 ? 'col-md-6' : 'col-12' }}">
            <video src="{{ asset('storage/'.$gVid->file_path) }}" controls class="rounded shadow-sm w-100" style="height:220px;object-fit:contain;{{ $mediaStyle }}"></video>
            <small class="text-muted d-block mt-1 text-truncate">{{ $gVid->original_name }}</small>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endif
@endif

{{-- VIDEO URL (gallery-video card style) --}}
@if($widget->widget_type === 'video_url' && $widget->text_content)
@php
    $vuUrl = $widget->text_content; $vuIsYt = false; $vuEmbed = $vuUrl; $vuThumb = null; $vuVideoId = null;
    if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^\s&]+)/', $vuUrl, $vuM)) {
        $vuVideoId = $vuM[1];
        $vuEmbed = 'https://www.youtube.com/embed/'.$vuVideoId;
        $vuThumb = 'https://img.youtube.com/vi/'.$vuVideoId.'/hqdefault.jpg';
        $vuIsYt = true;
    }
    $vuTitle = $widget->settings['title'] ?? ($widget->settings['label'] ?? '');
@endphp
<div class="{{ $paddingClass }}" style="{{ $wrapStyle }}">
    <div class="widget-video-card" onclick="this.querySelector('.widget-video-overlay-player').style.display='flex';this.querySelector('.widget-video-preview').style.display='none';" style="position:relative;border-radius:12px;overflow:hidden;background:#0a0f1e;cursor:pointer;max-width:480px;">
        {{-- Preview (thumbnail + play button) --}}
        <div class="widget-video-preview" style="position:relative;">
            @if($vuThumb)
                <img src="{{ $vuThumb }}" alt="{{ $vuTitle }}" style="width:100%;aspect-ratio:16/9;object-fit:cover;display:block;">
            @else
                <div style="width:100%;aspect-ratio:16/9;background:linear-gradient(135deg,#001a3a,#003d82);display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-film" style="font-size:48px;color:rgba(255,255,255,.3);"></i>
                </div>
            @endif
            <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.35);">
                <i class="fas fa-play-circle" style="font-size:52px;color:#fff;filter:drop-shadow(0 2px 8px rgba(0,0,0,.5));transition:transform .2s;"></i>
            </div>
        </div>
        {{-- Actual player (hidden until clicked) --}}
        <div class="widget-video-overlay-player" style="display:none;width:100%;aspect-ratio:16/9;">
            @if($vuIsYt)
                <iframe src="{{ $vuEmbed }}?autoplay=1" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen style="width:100%;height:100%;border:none;"></iframe>
            @else
                <video src="{{ $vuEmbed }}" controls autoplay class="w-100" style="height:100%;object-fit:contain;"></video>
            @endif
        </div>
        @if($vuTitle)
        <p style="margin:0;padding:10px 12px;color:#fff;font-size:.85rem;font-weight:600;background:rgba(0,0,0,.6);">{{ Str::limit($vuTitle, 60) }}</p>
        @endif
    </div>
</div>
@endif
{{-- ============================================================
     GAMBAR SIDEBAR — rendered via show.blade.php template section
     (this is a fallback for linear mode)
============================================================ --}}
@if($widget->widget_type === 'gambar_sidebar' && $widget->media->count())
@php $removeBg = !empty($widget->settings['remove_background']); $sbPos = $widget->settings['sidebar_position'] ?? 'left'; @endphp
<div class="mb-3">
    <small class="text-muted d-block mb-2"><i class="fas fa-columns me-1"></i> {{ __('messages.label_gambar_sidebar') }} ({{ $sbPos === 'left' ? __('messages.label_kiri') : __('messages.label_kanan') }})</small>
    <div class="d-flex flex-wrap gap-2">
        @foreach($widget->media as $sbMedia)
        <div style="width:120px;border-radius:8px;overflow:hidden;border:1px solid #e9ecef;">
            <img src="{{ asset('storage/'.$sbMedia->file_path) }}" alt="{{ $sbMedia->original_name }}"
                 style="width:100%;display:block;object-fit:cover;{{ $removeBg ? 'mix-blend-mode:multiply;' : '' }}">
        </div>
        @endforeach
    </div>
</div>
@endif