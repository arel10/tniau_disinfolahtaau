<?php

namespace App\Http\Controllers;

use App\Models\HasilSurvei;

class HasilSurveiPublicController extends Controller
{
    public function index()
    {
        $items = HasilSurvei::with('media')
            ->where('is_published', true)
            ->orderBy('position')->latest()->get();

        return view('public.pelayanan-publik.hasil-survei', compact('items'));
    }
}
