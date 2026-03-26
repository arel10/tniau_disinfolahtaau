<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LogoFooterController extends Controller
{
    public function index()
    {
        return view('admin.setting.logo-footer');
    }

    public function update(Request $request)
    {
        $request->validate([
            'footer_logo'         => 'nullable|image|max:2048',
            'footer_site_name'    => 'required|string|max:100',
            'footer_site_subtitle'=> 'required|string|max:100',
            'footer_description'  => 'required|string|max:500',
        ]);

        // Handle logo upload
        if ($request->hasFile('footer_logo')) {
            // Delete old logo if stored in storage
            $oldLogo = setting('footer_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            $path = $request->file('footer_logo')->store('footer-logo', 'public');
            setting(['footer_logo' => $path]);
        }

        setting([
            'footer_site_name'     => $request->footer_site_name,
            'footer_site_subtitle' => $request->footer_site_subtitle,
            'footer_description'   => $request->footer_description,
        ]);

        return back()->with('success', __('messages.flash_saved'));
    }
}
