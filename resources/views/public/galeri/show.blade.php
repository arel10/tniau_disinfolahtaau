@extends('layouts.public')

@section('title', $galeri->localized_judul . ' - ' . __('messages.gallery') . __('messages.site_title_suffix'))

@section('content')
<div class="container my-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.breadcrumb_home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('galeri.index') }}">{{ __('messages.gallery') }}</a></li>
            <li class="breadcrumb-item active">{{ Str::limit($galeri->localized_judul, 50) }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    @if($galeri->tipe != 'video')
                    <span class="badge bg-secondary mb-3">
                        <i class="fas fa-{{ $galeri->tipe == 'foto' ? 'image' : 'video' }}"></i> {{ ucfirst($galeri->tipe) }}
                    </span>
                    <h2 class="mb-3">{{ $galeri->localized_judul }}</h2>
                    
                    <div class="text-muted mb-4">
                        <i class="fas fa-user"></i> {{ $galeri->user->name }} | 
                        <i class="fas fa-calendar"></i> {{ $galeri->tanggal_kegiatan ? $galeri->tanggal_kegiatan->format('d F Y') : $galeri->created_at->format('d F Y') }}
                    </div>
                    @endif

                    @if($galeri->tipe == 'foto' && $galeri->gambar)
                        <img src="{{ asset('storage/' . $galeri->gambar) }}" class="img-fluid rounded mb-4 w-100" alt="{{ $galeri->localized_judul }}">
                    @elseif($galeri->tipe == 'video')
                        @if($galeri->gambar)
                            <img src="{{ asset('storage/' . $galeri->gambar) }}" class="img-fluid rounded mb-3 w-100" alt="{{ $galeri->localized_judul }}">
                        @endif
                        @if($galeri->video_url)
                            <div class="ratio ratio-16x9 mb-4">
                                <iframe src="{{ $galeri->embed_url }}" allowfullscreen></iframe>
                            </div>
                        @endif
                    @endif

                    @if($galeri->pdf_path)
                    <div class="mb-4">
                        <div class="d-flex justify-content-end mb-1">
                            <a href="{{ asset('storage/' . $galeri->pdf_path) }}" target="_blank" class="text-primary text-decoration-none" style="font-size: 14px;">
                                <i class="fas fa-expand-alt"></i> View Fullscreen
                            </a>
                        </div>
                        <div class="rounded overflow-hidden" style="background: #404040;">
                            {{-- PDF.js Toolbar --}}
                            <div class="d-flex align-items-center px-2 py-1 flex-wrap gap-1" style="background: #323232; color: #d7d7d7; font-size: 13px; min-height: 40px;">
                                <button class="btn btn-sm text-light px-2" id="pdfSidebarToggle" title="Toggle Sidebar">
                                    <i class="fas fa-bars"></i>
                                </button>
                                <div class="vr mx-1" style="opacity: 0.3;"></div>
                                <button class="btn btn-sm text-light px-2" id="pdfFindBtn" title="Find in document">
                                    <i class="fas fa-search"></i>
                                </button>
                                <div class="vr mx-1" style="opacity: 0.3;"></div>
                                <button class="btn btn-sm text-light px-2" id="pdfPrev" title="Previous Page">
                                    <i class="fas fa-chevron-up"></i>
                                </button>
                                <button class="btn btn-sm text-light px-2" id="pdfNext" title="Next Page">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <div class="d-flex align-items-center mx-1">
                                    <input type="number" id="pdfPageNum" class="form-control form-control-sm text-center" style="width: 50px; background: #404040; color: #d7d7d7; border-color: #555; font-size: 13px;" value="1" min="1">
                                    <span class="ms-1 text-nowrap" id="pdfPageCount">of 0</span>
                                </div>
                                <div class="vr mx-1" style="opacity: 0.3;"></div>
                                <button class="btn btn-sm text-light px-2" id="pdfZoomOut" title="Zoom Out">
                                    <i class="fas fa-search-minus"></i>
                                </button>
                                <button class="btn btn-sm text-light px-2" id="pdfZoomIn" title="Zoom In">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                                <select id="pdfZoomSelect" class="form-select form-select-sm ms-1" style="width: 140px; background: #404040; color: #d7d7d7; border-color: #555; font-size: 12px;">
                                    <option value="auto" selected>Automatic Zoom</option>
                                    <option value="page-fit">Page Fit</option>
                                    <option value="page-width">Page Width</option>
                                    <option value="0.5">50%</option>
                                    <option value="0.75">75%</option>
                                    <option value="1">100%</option>
                                    <option value="1.25">125%</option>
                                    <option value="1.5">150%</option>
                                    <option value="2">200%</option>
                                </select>
                                <div class="ms-auto d-flex align-items-center gap-1">
                                    <a href="{{ asset('storage/' . $galeri->pdf_path) }}" class="btn btn-sm text-light px-2" title="Download" download>
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <a href="{{ asset('storage/' . $galeri->pdf_path) }}" target="_blank" class="btn btn-sm text-light px-2" title="Open in new tab">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                            </div>
                            {{-- PDF Viewer Container --}}
                            <div id="pdfViewerContainer" style="height: 650px; overflow: auto; background: #808080; text-align: center;">
                                <div id="pdfPagesWrapper" style="display: inline-block; padding: 10px 0;"></div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($galeri->localized_deskripsi)
                    <div class="mt-4">
                        <h5>{{ __('messages.heading_deskripsi') }}</h5>
                        <p>{{ $galeri->localized_deskripsi }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Galeri Terkait -->
            @if($galeri_terkait->count() > 0)
            <div class="mt-5">
                <h4 class="mb-4">{{ __('messages.heading_galeri_lainnya') }}</h4>
                <div class="row g-3">
                    @foreach($galeri_terkait as $related)
                    <div class="col-md-4 col-6">
                        <a href="{{ route('galeri.show', $related->id) }}">
                            @if($related->gambar)
                                <img src="{{ asset('storage/' . $related->gambar) }}" class="img-fluid rounded" alt="{{ $related->localized_judul }}" style="height: 150px; width: 100%; object-fit: cover;">
                            @endif
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@if(isset($galeri) && $galeri->pdf_path)
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
(function() {
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    const pdfUrl = "{{ asset('storage/' . $galeri->pdf_path) }}";
    const container = document.getElementById('pdfViewerContainer');
    const wrapper = document.getElementById('pdfPagesWrapper');
    let pdfDoc = null, currentPage = 1, totalPages = 0;
    let currentScale = 0; // 0 = auto
    let renderedPages = {};

    function getAutoScale(page) {
        const vp = page.getViewport({ scale: 1 });
        return (container.clientWidth - 40) / vp.width;
    }

    function getPageFitScale(page) {
        const vp = page.getViewport({ scale: 1 });
        return Math.min((container.clientWidth - 40) / vp.width, (container.clientHeight - 20) / vp.height);
    }

    function renderAllPages() {
        wrapper.innerHTML = '';
        renderedPages = {};
        if (!pdfDoc) return;

        for (let i = 1; i <= totalPages; i++) {
            const pageDiv = document.createElement('div');
            pageDiv.style.cssText = 'margin: 5px auto 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.3); background: #fff; display: block;';
            pageDiv.id = 'pdfPage_' + i;

            const canvas = document.createElement('canvas');
            canvas.id = 'pdfCanvas_' + i;
            pageDiv.appendChild(canvas);
            wrapper.appendChild(pageDiv);

            renderPage(i, canvas);
        }
    }

    function renderPage(num, canvas) {
        pdfDoc.getPage(num).then(function(page) {
            let scale = currentScale;
            if (scale === 0) scale = getAutoScale(page);

            const viewport = page.getViewport({ scale: scale });
            canvas.width = viewport.width;
            canvas.height = viewport.height;

            const ctx = canvas.getContext('2d');
            page.render({ canvasContext: ctx, viewport: viewport });
            renderedPages[num] = true;
        });
    }

    function updatePageNum() {
        document.getElementById('pdfPageNum').value = currentPage;
    }

    function scrollToPage(num) {
        const el = document.getElementById('pdfPage_' + num);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            currentPage = num;
            updatePageNum();
        }
    }

    // Detect current page on scroll
    container.addEventListener('scroll', function() {
        const containerTop = container.scrollTop;
        let closestPage = 1;
        let closestDist = Infinity;
        for (let i = 1; i <= totalPages; i++) {
            const el = document.getElementById('pdfPage_' + i);
            if (el) {
                const dist = Math.abs(el.offsetTop - wrapper.offsetTop - containerTop);
                if (dist < closestDist) {
                    closestDist = dist;
                    closestPage = i;
                }
            }
        }
        if (closestPage !== currentPage) {
            currentPage = closestPage;
            updatePageNum();
        }
    });

    // Load PDF
    pdfjsLib.getDocument(pdfUrl).promise.then(function(pdf) {
        pdfDoc = pdf;
        totalPages = pdf.numPages;
        document.getElementById('pdfPageCount').textContent = 'of ' + totalPages;
        document.getElementById('pdfPageNum').max = totalPages;
        renderAllPages();
    });

    // Prev / Next
    document.getElementById('pdfPrev').addEventListener('click', function() {
        if (currentPage <= 1) return;
        currentPage--;
        scrollToPage(currentPage);
    });
    document.getElementById('pdfNext').addEventListener('click', function() {
        if (currentPage >= totalPages) return;
        currentPage++;
        scrollToPage(currentPage);
    });

    // Page number input
    document.getElementById('pdfPageNum').addEventListener('change', function() {
        let num = parseInt(this.value);
        if (num >= 1 && num <= totalPages) {
            currentPage = num;
            scrollToPage(currentPage);
        } else {
            this.value = currentPage;
        }
    });

    // Zoom Out
    document.getElementById('pdfZoomOut').addEventListener('click', function() {
        if (currentScale === 0) {
            pdfDoc.getPage(1).then(function(p) {
                currentScale = getAutoScale(p);
                currentScale = Math.max(0.25, currentScale - 0.2);
                renderAllPages();
            });
        } else {
            currentScale = Math.max(0.25, currentScale - 0.2);
            renderAllPages();
        }
        document.getElementById('pdfZoomSelect').value = '';
    });

    // Zoom In
    document.getElementById('pdfZoomIn').addEventListener('click', function() {
        if (currentScale === 0) {
            pdfDoc.getPage(1).then(function(p) {
                currentScale = getAutoScale(p);
                currentScale += 0.2;
                renderAllPages();
            });
        } else {
            currentScale += 0.2;
            renderAllPages();
        }
        document.getElementById('pdfZoomSelect').value = '';
    });

    // Zoom Select
    document.getElementById('pdfZoomSelect').addEventListener('change', function() {
        const val = this.value;
        if (val === 'auto') {
            currentScale = 0;
            renderAllPages();
        } else if (val === 'page-fit') {
            pdfDoc.getPage(1).then(function(p) {
                currentScale = getPageFitScale(p);
                renderAllPages();
            });
            return;
        } else if (val === 'page-width') {
            pdfDoc.getPage(1).then(function(p) {
                currentScale = getAutoScale(p);
                renderAllPages();
            });
            return;
        } else {
            currentScale = parseFloat(val);
            renderAllPages();
        }
    });

    // Window resize handler
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (currentScale === 0) renderAllPages();
        }, 250);
    });
})();
</script>
@endpush
@endif
