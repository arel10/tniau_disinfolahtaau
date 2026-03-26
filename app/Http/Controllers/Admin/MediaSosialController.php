<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaSosial;
use Illuminate\Http\Request;

class MediaSosialController extends Controller
{
    public function index()
    {
        $mediaSosial = MediaSosial::orderBy('sort_order')->orderBy('id')->get();
        return view('admin.setting.media-sosial', compact('mediaSosial'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'nullable|string|max:255',
            'icon'  => 'nullable|string|max:100',
            'logo'  => 'nullable|image|max:4096',
            'link'  => 'nullable|url|max:500',
        ]);

        $data = [
            'nama'       => $request->nama,
            'icon'       => $request->icon,
            'link'       => $request->link,
            'sort_order' => MediaSosial::max('sort_order') + 1,
        ];

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('assets/image/media-sosial', 'public');
            $data['logo'] = 'storage/' . $path;
        }

        MediaSosial::create($data);

        return redirect()->route('admin.setting.media-sosial')
            ->with('success', __('messages.flash_created', ['item' => __('messages.admin_media_sosial')]));
    }

    public function update(Request $request, MediaSosial $mediaSosial)
    {
        $request->validate([
            'nama' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:100',
            'logo' => 'nullable|image|max:4096',
            'link' => 'nullable|url|max:500',
        ]);

        $data = [
            'nama' => $request->nama,
            'icon' => $request->icon,
            'link' => $request->link,
        ];

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('assets/image/media-sosial', 'public');
            $data['logo'] = 'storage/' . $path;
        }

        $mediaSosial->update($data);

        return redirect()->route('admin.setting.media-sosial')
            ->with('success', __('messages.flash_updated', ['item' => __('messages.admin_media_sosial')]));
    }

    public function destroy(MediaSosial $mediaSosial)
    {
        $mediaSosial->delete();
        return redirect()->route('admin.setting.media-sosial')
            ->with('success', __('messages.flash_deleted', ['item' => __('messages.admin_media_sosial')]));
    }
}
