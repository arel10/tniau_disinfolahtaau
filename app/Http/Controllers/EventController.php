<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Berita;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('is_published', true)->orderBy('tanggal_kegiatan', 'desc')->orderBy('created_at', 'desc')->get();
        return view('public.events.index', compact('events'));
    }

    public function show(Event $event)
    {
        abort_unless($event->is_published, 404);
        $event->load(['heroes', 'galeriFotos', 'galeriVideos', 'media']);

        // Ambil berita terbaru (max 6)
        $berita = Berita::published()->latest()->limit(6)->get();

        return view('public.events.show', compact('event', 'berita'));
    }
}
