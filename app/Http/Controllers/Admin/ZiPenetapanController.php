<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ZiPenetapanItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ZiPenetapanController extends Controller
{
    public function index(Request $request)
    {
        $group = $request->get('group');
        $query = ZiPenetapanItem::latest();

        if ($group) {
            $query->where('section_group', $group);
        }

        $items = $query->paginate(15);

        return view('admin.zi-penetapan.index', compact('items', 'group'));
    }

    public function create()
    {
        $kategoris = \App\Models\ZiPenetapanKategori::orderBy('nama')->get();
        return view('admin.zi-penetapan.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id'   => 'nullable|exists:zi_penetapan_kategoris,id',
            'judul'         => 'nullable|string|max:255',
            'persen'        => 'nullable|integer|min:0|max:100',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'konten'        => 'nullable|string',
        ]);
        // Set section_group based on kategori name (lowercase)
        $kategori = !empty($validated['kategori_id']) ? \App\Models\ZiPenetapanKategori::find($validated['kategori_id']) : null;
        if ($kategori) {
            $validated['section_group'] = strtolower($kategori->nama);
        } else {
            $validated['section_group'] = null;
        }

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('zi/penetapan', 'public');
        }

        ZiPenetapanItem::create($validated);

        return redirect()
            ->route('admin.zi.penetapan.index')
            ->with('success', __('messages.flash_created', ['item' => __('messages.admin_penetapan')]));
    }

    public function edit(ZiPenetapanItem $penetapan)
    {
        $kategoris = \App\Models\ZiPenetapanKategori::orderBy('nama')->get();
        return view('admin.zi-penetapan.edit', ['item' => $penetapan, 'kategoris' => $kategoris]);
    }

    public function update(Request $request, ZiPenetapanItem $penetapan)
    {
        $validated = $request->validate([
            'kategori_id'   => 'nullable|exists:zi_penetapan_kategoris,id',
            'judul'         => 'nullable|string|max:255',
            'persen'        => 'nullable|integer|min:0|max:100',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'konten'        => 'nullable|string',
        ]);

        if ($request->hasFile('foto')) {
            if ($penetapan->foto) {
                Storage::disk('public')->delete($penetapan->foto);
            }
            $validated['foto'] = $request->file('foto')->store('zi/penetapan', 'public');
        }

        if ($request->boolean('hapus_foto') && !$request->hasFile('foto')) {
            if ($penetapan->foto) {
                Storage::disk('public')->delete($penetapan->foto);
            }
            $validated['foto'] = null;
        }

        // Set section_group on update as well (lowercase)
        $kategori = !empty($validated['kategori_id']) ? \App\Models\ZiPenetapanKategori::find($validated['kategori_id']) : null;
        if ($kategori) {
            $validated['section_group'] = strtolower($kategori->nama);
        } else {
            $validated['section_group'] = null;
        }
        $penetapan->update($validated);

        return redirect()
            ->route('admin.zi.penetapan.index')
            ->with('success', __('messages.flash_updated', ['item' => __('messages.admin_penetapan')]));
    }

    public function destroy(ZiPenetapanItem $penetapan)
    {
        if ($penetapan->foto) {
            Storage::disk('public')->delete($penetapan->foto);
        }

        $penetapan->delete();

        return redirect()
            ->route('admin.zi.penetapan.index')
            ->with('success', __('messages.flash_deleted', ['item' => __('messages.admin_penetapan')]));
    }
}
