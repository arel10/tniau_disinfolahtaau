<?php

namespace App\Http\Controllers;

use App\Models\SurveiKepuasan;

class SurveiKepuasanPublicController extends Controller
{
    public function index()
    {
        $items = SurveiKepuasan::with('media')
            ->where('is_published', true)
            ->orderBy('position')->latest()->get();

        return view('public.pelayanan-publik.survei', compact('items'));
    }
}
