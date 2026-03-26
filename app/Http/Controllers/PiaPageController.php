<?php

namespace App\Http\Controllers;

use App\Models\PiaPage;

class PiaPageController extends Controller
{
    public function index()
    {
        $page = PiaPage::with('logoItems')->first();

        if (!$page) {
            abort(404);
        }

        return view('public.pia.index', compact('page'));
    }
}
