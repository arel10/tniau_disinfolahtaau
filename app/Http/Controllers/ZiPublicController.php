<?php

namespace App\Http\Controllers;

use App\Models\ZiPage;
use App\Models\ZiPerancanganPost;
use App\Models\ZiPenetapanItem;
use App\Models\ZiPenetapanKategori;

class ZiPublicController extends Controller
{
    public function zonaIntegritas()
    {
        $pages = ZiPage::where('type', 'zona_integritas')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('public.zona-integritas.index', compact('pages'));
    }

    public function perancangan()
    {
        $posts = ZiPerancanganPost::with('photos')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('public.zona-integritas.perancangan', compact('posts'));
    }

    public function penetapan()
    {
        $kategoris = ZiPenetapanKategori::with(['items' => function ($q) {
            $q->orderBy('created_at');
        }])->orderBy('id')->get();

        // Items tanpa kategori (foto/logo header dll)
        $uncategorized = ZiPenetapanItem::whereNull('kategori_id')
            ->orderBy('created_at')->get();

        return view('public.zona-integritas.penetapan', compact('kategoris', 'uncategorized'));
    }

    public function pembangunan()
    {
        $pages = ZiPage::where('type', 'pembangunan')
            ->with('media')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('public.zona-integritas.pembangunan', compact('pages'));
    }

    public function pemantauan()
    {
        $pages = ZiPage::where('type', 'pemantauan')
            ->with('media')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('public.zona-integritas.pemantauan', compact('pages'));
    }
}
