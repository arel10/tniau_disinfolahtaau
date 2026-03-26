<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhistleBlowingSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WhistleBlowingController extends Controller
{
    /**
     * Display the management page.
     */
    public function index()
    {
        $settings = WhistleBlowingSetting::orderByDesc('id')->get();
        return view('admin.whistle-blowing.index', compact('settings'));
    }

        public function create()
        {
            return view('admin.whistle-blowing.create');
        }

        public function store(Request $request)
        {
            $validated = $request->validate([
                'link_tujuan' => 'required|url',
                'judul' => 'nullable|string|max:255',
                'deskripsi' => 'nullable|string',
                'gambar' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
                'is_active' => 'nullable',
            ]);
            if ($request->hasFile('gambar')) {
                $validated['gambar'] = $request->file('gambar')->store('whistle-blowing', 'public');
            } else {
                $validated['gambar'] = 'assets/image/whistle.png';
            }
            $validated['is_active'] = $request->filled('is_active') ? true : false;
            WhistleBlowingSetting::create($validated);
            return redirect()->route('admin.whistle-blowing.index')->with('success', __('messages.flash_created', ['item' => 'Data']));
        }

        public function edit(WhistleBlowingSetting $whistleBlowing)
        {
            return view('admin.whistle-blowing.edit', ['setting' => $whistleBlowing]);
        }

        public function update(Request $request, WhistleBlowingSetting $whistleBlowing)
        {
            $validated = $request->validate([
                'link_tujuan' => 'required|url',
                'judul' => 'nullable|string|max:255',
                'deskripsi' => 'nullable|string',
                'gambar' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
                'is_active' => 'nullable',
            ]);
            if ($request->hasFile('gambar')) {
                if ($whistleBlowing->gambar && str_starts_with($whistleBlowing->gambar, 'whistle-blowing/')) {
                    Storage::disk('public')->delete($whistleBlowing->gambar);
                }
                $validated['gambar'] = $request->file('gambar')->store('whistle-blowing', 'public');
            }
            $validated['is_active'] = $request->filled('is_active') ? true : false;
            $whistleBlowing->update($validated);
            return redirect()->route('admin.whistle-blowing.index')->with('success', __('messages.flash_updated', ['item' => 'Data']));
        }

        public function destroy(WhistleBlowingSetting $whistleBlowing)
        {
            if ($whistleBlowing->gambar && str_starts_with($whistleBlowing->gambar, 'whistle-blowing/')) {
                Storage::disk('public')->delete($whistleBlowing->gambar);
            }
            $whistleBlowing->delete();
            return redirect()->route('admin.whistle-blowing.index')->with('success', __('messages.flash_deleted', ['item' => 'Data']));
        }
    }
