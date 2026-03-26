<?php

namespace App\Http\Controllers;

use App\Models\BeritaPelayanan;

class BeritaPelayananPublicController extends Controller
{
    public function index()
    {
        $items = BeritaPelayanan::with('media')
            ->where('is_published', true)
            ->orderBy('position')
            ->latest()
            ->get();

        return view('public.pelayanan-publik.berita', compact('items'));
    }
}
