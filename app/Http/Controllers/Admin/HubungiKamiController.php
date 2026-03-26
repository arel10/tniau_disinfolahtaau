<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HubungiKami;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HubungiKamiController extends Controller
{
    public function index()
    {
        $items = HubungiKami::orderBy('sort_order')->orderBy('id')->get();
        return view('admin.setting.hubungi-kami', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'icon_image' => 'nullable|image|max:2048',
            'icon'       => 'nullable|string|max:100',
            'teks'       => 'required|string|max:500',
            'link'       => 'nullable|string|max:500',
        ]);

        $iconImagePath = null;
        if ($request->hasFile('icon_image')) {
            $iconImagePath = $request->file('icon_image')->store('hubungi-kami', 'public');
        }

        HubungiKami::create([
            'icon'       => $request->icon ?: null,
            'icon_image' => $iconImagePath,
            'teks'       => $request->teks,
            'link'       => $request->link ?: null,
            'sort_order' => HubungiKami::max('sort_order') + 1,
        ]);

        return back()->with('success', __('messages.flash_created', ['item' => 'Item']));
    }

    public function update(Request $request, HubungiKami $hubungiKami)
    {
        $request->validate([
            'icon_image' => 'nullable|image|max:2048',
            'icon'       => 'nullable|string|max:100',
            'teks'       => 'required|string|max:500',
            'link'       => 'nullable|string|max:500',
        ]);

        $iconImagePath = $hubungiKami->icon_image;
        if ($request->hasFile('icon_image')) {
            if ($iconImagePath) {
                Storage::disk('public')->delete($iconImagePath);
            }
            $iconImagePath = $request->file('icon_image')->store('hubungi-kami', 'public');
        }

        $hubungiKami->update([
            'icon'       => $request->icon ?: null,
            'icon_image' => $iconImagePath,
            'teks'       => $request->teks,
            'link'       => $request->link ?: null,
        ]);

        return back()->with('success', __('messages.flash_updated', ['item' => 'Item']));
    }

    public function destroy(HubungiKami $hubungiKami)
    {
        $hubungiKami->delete();
        return back()->with('success', __('messages.flash_deleted', ['item' => 'Item']));
    }
}
