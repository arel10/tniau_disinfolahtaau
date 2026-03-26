<?php

namespace App\Http\Controllers;

use App\Models\Sp4nLapor;

class Sp4nLaporPublicController extends Controller
{
    public function index()
    {
        $items = Sp4nLapor::with('media')
            ->where('is_published', true)
            ->orderBy('position')
            ->latest()
            ->get();

        return view('public.sp4n-lapor', compact('items'));
    }
}
