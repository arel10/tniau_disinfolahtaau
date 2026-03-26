<?php

namespace App\Http\Controllers;

use App\Models\LayananPengaduan;

class LayananPengaduanPublicController extends Controller
{
    public function index()
    {
        $items = LayananPengaduan::with('media')
            ->where('is_published', true)
            ->orderBy('position')->latest()->get();

        return view('public.pelayanan-publik.pengaduan', compact('items'));
    }
}
