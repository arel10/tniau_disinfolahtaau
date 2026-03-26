<?php

namespace App\Http\Controllers;

use App\Models\KompensasiPelayanan;

class KompensasiPelayananPublicController extends Controller
{
    public function index()
    {
        $items = KompensasiPelayanan::with('media')
            ->where('is_published', true)
            ->orderBy('position')->latest()->get();

        return view('public.pelayanan-publik.kompensasi', compact('items'));
    }
}
