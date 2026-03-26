@extends('layouts.public')
@section('title', $document->title)
@section('content')
<div class="container py-5">
    <div class="text-center mb-4">
        <h1 class="font-bold text-3xl md:text-4xl">e-Library</h1>
        <nav class="text-sm text-gray-500 mt-2">
            <a href="/" class="hover:underline">Home</a> / <a href="{{ route('e-library.index') }}" class="hover:underline">e-Library</a> / <span>{{ $document->title }}</span>
        </nav>
    </div>
    <div class="flex flex-col items-center">
        <div class="bg-gray-200 rounded shadow p-4 w-full max-w-3xl flex items-center justify-center" style="height:520px;">
            <div id="flipbook-main" class="flipbook-viewer" data-pdf="{{ asset('storage/'.$document->pdf_path) }}" data-title="{{ $document->title }}" data-share-url="{{ route('e-library.show', $document->slug) }}"></div>
        </div>
        <div class="mt-4 flex gap-2">
            <a href="{{ route('e-library.download', $document->slug) }}" class="px-4 py-2 bg-green-600 text-white rounded">Download PDF</a>
            <button class="btn-share px-4 py-2 bg-blue-600 text-white rounded" data-share-url="{{ route('e-library.show', $document->slug) }}">Share</button>
        </div>
    </div>
</div>
@include('e-library.partials.viewer-assets')
@include('e-library.partials.share-modal')
<script>
fetch("{{ route('e-library.track-view', $document->slug) }}", {method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}});
</script>
@endsection
