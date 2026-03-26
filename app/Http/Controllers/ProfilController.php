<?php

namespace App\Http\Controllers;

use App\Models\ProfilContent;
use App\Models\HistoryDiagram;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    /**
     * Display the profil page (Tentang Kami).
     */
    public function index()
    {
        $tentang = ProfilContent::where('type', 'tentang')->first();
        $sejarah = $tentang?->content ?? '';

        return view('public.profil.index', compact('sejarah'));
    }

    /**
     * Display the kata pengantar page.
     */
    public function kataPengantar()
    {
        $data = ProfilContent::where('type', 'kata_pengantar')->first();

        return response()
            ->view('public.profil.kata-pengantar', [
                'title'   => $data?->title ?? null,
                'content' => $data?->content ?? '',
                'image'   => $data?->image ?? null,
            ])
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Display the struktur organisasi page.
     */
    public function strukturOrganisasi()
    {
        $strukturs = \App\Models\Struktur::where('is_active', true)->orderBy('urutan')->get()->keyBy('kode');
        return view('public.profil.struktur-organisasi', compact('strukturs'));
    }

    /**
     * Display the sejarah page (timeline dari database).
     */
    public function sejarah()
    {
        $diagrams = HistoryDiagram::orderBy('id', 'asc')->get();

        return view('public.profil.sejarah', compact('diagrams'));
    }
}
