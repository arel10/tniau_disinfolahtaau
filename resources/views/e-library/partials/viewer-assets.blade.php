<!-- Include dFlip (DearFlip) assets -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dflip/dist/dflip.min.css">
<script src="https://cdn.jsdelivr.net/npm/dflip/dist/dflip.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.flipbook-viewer').forEach(function(el) {
        const pdf = el.getAttribute('data-pdf');
        const title = el.getAttribute('data-title');
        // dFlip init
        new DFLIP({
            source: pdf,
            webgl: true,
            backgroundColor: '#888',
            height: el.id === 'flipbook-main' ? 480 : 400,
            enableDownload: true,
            enableSound: true,
            enableFullScreen: true,
            enableShare: true,
            enablePrint: false,
            enableThumbnails: true,
            enableZoom: true,
            title: title,
            controlsPosition: 'bottom',
            container: el
        });
    });
    // Share modal logic
    document.querySelectorAll('.btn-share').forEach(btn => {
        btn.addEventListener('click', function() {
            const url = btn.getAttribute('data-share-url');
            document.getElementById('share-link-input').value = url;
            document.getElementById('share-modal').classList.remove('hidden');
        });
    });
    document.getElementById('close-share-modal').onclick = function() {
        document.getElementById('share-modal').classList.add('hidden');
    };
    document.getElementById('copy-share-link').onclick = function() {
        const input = document.getElementById('share-link-input');
        input.select();
        document.execCommand('copy');
    };
});
</script>
<style>
.flipbook-viewer { width: 100%; height: 100%; }
</style>
