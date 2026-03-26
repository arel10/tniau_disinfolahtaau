<?php

namespace App\Http\Controllers;

use App\Models\WhistleBlowingSetting;
use Illuminate\Http\Request;

class WhistleBlowingController extends Controller
{
    /**
     * Display the whistle blowing page.
     */
    public function index()
    {
        $setting = WhistleBlowingSetting::first();
        
        return view('public.whistle-blowing', compact('setting'));
    }
}
