@extends('layouts.public')

@section('title', __('messages.hero_pemantauan'))

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold mb-3">{{ __('messages.hero_pemantauan') }}</h2>
            <p class="text-muted">{{ __('messages.misc_info_pemantauan') }}</p>
        </div>
    </div>

    @include('public.partials._zi-page-list', ['pages' => $pages])
</div>
@endsection
