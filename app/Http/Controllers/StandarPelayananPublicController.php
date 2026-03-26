<?php

namespace App\Http\Controllers;

use App\Models\StandarPelayanan;

class StandarPelayananPublicController extends Controller
{
    public function index()
    {
        $items = StandarPelayanan::with('media')
            ->where('is_published', true)
            ->orderBy('position')->latest()->get();

        return view('public.pelayanan-publik.standar', compact('items'));
    }
}
