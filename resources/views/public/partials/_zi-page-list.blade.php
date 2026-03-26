{{-- Partial reusable untuk menampilkan list halaman Zona Integritas --}}
@if($pages->count())
<div class="row">
    @foreach($pages as $page)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 shadow-sm">
            @if($page->gambar)
            <img src="{{ asset('storage/' . $page->gambar) }}" class="card-img-top" alt="{{ $page->judul }}" style="height: 200px; object-fit: cover;">
            @endif
            <div class="card-body">
                <h5 class="card-title">{{ $page->judul }}</h5>
                @if($page->konten)
                <p class="card-text">{{ Str::limit(strip_tags($page->konten), 150) }}</p>
                @endif
                <small class="text-muted">{{ $page->created_at->format('d M Y') }}</small>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="alert alert-info text-center">
    <i class="fas fa-info-circle"></i> {{ __('messages.empty_konten') }}
</div>
@endif
