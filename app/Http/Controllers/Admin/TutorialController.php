<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tutorial;
use App\Helpers\ImageLibraryHelper;
use Illuminate\Support\Facades\Storage;

class TutorialController extends Controller
{
    public function index()
    {
        $tutorials = Tutorial::latest()->get();
        return view('admin.tutorial.index', compact('tutorials'));
    }

    public function create()
    {
        $pustaka_gambar = ImageLibraryHelper::getTutorialImages();
        return view('admin.tutorial.create', compact('pustaka_gambar'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link' => 'nullable|url',
        ]);


        // Jika pilih dari pustaka
        if ($request->filled('gambar_pustaka')) {
            $validated['gambar'] = $request->input('gambar_pustaka');
        } elseif ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('tutorials', 'public');
        }

        Tutorial::create($validated);
        return redirect()->route('admin.tutorial.index')->with('success', __('messages.flash_created', ['item' => __('messages.admin_tutorial')]));
    }

    public function edit(Tutorial $tutorial)
    {
        $pustaka_gambar = ImageLibraryHelper::getTutorialImages();
        return view('admin.tutorial.edit', compact('tutorial', 'pustaka_gambar'));
    }

    public function update(Request $request, Tutorial $tutorial)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link' => 'nullable|url',
        ]);


        // Jika pilih dari pustaka
        if ($request->filled('gambar_pustaka')) {
            if ($tutorial->gambar && $tutorial->gambar !== $request->input('gambar_pustaka')) {
                Storage::disk('public')->delete($tutorial->gambar);
            }
            $validated['gambar'] = $request->input('gambar_pustaka');
        } elseif ($request->hasFile('gambar')) {
            if ($tutorial->gambar) {
                Storage::disk('public')->delete($tutorial->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('tutorials', 'public');
        }

        $tutorial->update($validated);
        return redirect()->route('admin.tutorial.index')->with('success', __('messages.flash_updated', ['item' => __('messages.admin_tutorial')]));
    }

    public function destroy(Tutorial $tutorial)
    {
        // Jangan hapus file gambar dari storage, hanya hapus data tutorial saja
        $tutorial->delete();
        return redirect()->route('admin.tutorial.index')->with('success', __('messages.flash_deleted', ['item' => __('messages.admin_tutorial')]));
    }
}
