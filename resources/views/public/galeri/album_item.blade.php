@extends('layouts.public')

@section('title', $galeri->localized_judul ?? __('messages.gallery'))

@section('content')
<div class="container-fluid p-0" style="height:100vh;">
    <div id="viewer" style="background:#000;height:100vh;display:flex;align-items:center;justify-content:center;position:relative;">
        <button id="prevBtn" class="viewer-nav" style="position:absolute;left:16px;top:50%;transform:translateY(-50%);z-index:50;font-size:24px;color:#fff;background:transparent;border:none;">&lt;</button>
        <button id="nextBtn" class="viewer-nav" style="position:absolute;right:16px;top:50%;transform:translateY(-50%);z-index:50;font-size:24px;color:#fff;background:transparent;border:none;">&gt;</button>

        <div id="imageContainer" style="max-width:100%;max-height:100%;display:flex;align-items:center;justify-content:center;">
            {{-- Show small preview first --}}
            @if($galeri->thumbnail_url)
            <img id="viewerImg" src="{{ $galeri->thumbnail_url }}" alt="{{ $galeri->localized_judul }}" style="max-width:100%;max-height:100%;object-fit:contain;cursor:pointer;opacity:0.98;">
            @else
            <div id="viewerPlaceholder" style="width:80%;height:60%;display:flex;align-items:center;justify-content:center;color:#fff;"><i class="fas fa-image fa-5x"></i></div>
            @endif
        </div>

        <div
            id="viewerData"
            style="display:none;"
            data-items='@json($items->map(function ($i) {
                return [
                    "id" => $i->id,
                    "thumb" => $i->thumbnail_url,
                    "large" => $i->gambar ? asset("storage/" . $i->gambar) : $i->thumbnail_url,
                    "type" => $i->tipe,
                ];
            }))'
            data-current-index="{{ (int) $currentIndex }}"
            data-item-base-url="{{ url('/galeri/album/' . $group . '/item') }}"
            data-back-url="{{ route('galeri.album', ['group' => $group]) }}"
        ></div>

        <a href="{{ route('galeri.album', ['group' => $group]) }}" style="position:absolute;top:16px;left:16px;color:#fff;z-index:60;">&larr; {{ __('messages.btn_kembali') }} {{ __('messages.gallery') }}</a>
    </div>
</div>

@push('scripts')
<script>
    (function() {
        const viewerData = document.getElementById('viewerData');
        const items = JSON.parse((viewerData && viewerData.dataset.items) || '[]');
        let currentIndex = Number((viewerData && viewerData.dataset.currentIndex) || 0);
        const itemBaseUrl = (viewerData && viewerData.dataset.itemBaseUrl) || '';
        const backUrl = (viewerData && viewerData.dataset.backUrl) || '';

        const viewerImg = document.getElementById('viewerImg');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const imageContainer = document.getElementById('imageContainer');

        function showIndex(idx, loadLarge = false) {
            if (!items.length) return;
            currentIndex = (idx + items.length) % items.length;
            const itm = items[currentIndex];
            if (!viewerImg) return;
            // Always show thumbnail first; only set large src when loadLarge = true
            viewerImg.src = itm.thumb || '';
            if (loadLarge) {
                // replace with large image
                const large = new Image();
                large.onload = function() {
                    viewerImg.src = large.src;
                };
                large.src = itm.large;
            }
            // Update URL without reloading
            const url = itemBaseUrl + '/' + itm.id;
            window.history.replaceState({}, '', url);
        }

        prevBtn.addEventListener('click', function() {
            showIndex(currentIndex - 1, true);
        });
        nextBtn.addEventListener('click', function() {
            showIndex(currentIndex + 1, true);
        });

        // click to load large
        if (viewerImg) {
            viewerImg.addEventListener('click', function() {
                showIndex(currentIndex, true);
            });
        }

        // swipe support
        let startX = null;
        imageContainer.addEventListener('touchstart', function(e) {
            startX = e.touches[0].clientX;
        });
        imageContainer.addEventListener('touchend', function(e) {
            if (startX === null) return;
            const diff = (e.changedTouches[0].clientX - startX);
            if (Math.abs(diff) > 40) {
                if (diff < 0) showIndex(currentIndex + 1, true);
                else showIndex(currentIndex - 1, true);
            }
            startX = null;
        });

        // Keyboard left/right
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') showIndex(currentIndex - 1, true);
            if (e.key === 'ArrowRight') showIndex(currentIndex + 1, true);
            if (e.key === 'Escape') window.location = backUrl;
        });
    })();
</script>
@endpush

@endsection