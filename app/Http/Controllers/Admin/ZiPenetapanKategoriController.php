<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ZiPenetapanKategori;
use Illuminate\Http\Request;

class ZiPenetapanKategoriController extends Controller
{
    public function index()
    {
        $kategoris = ZiPenetapanKategori::orderBy('nama')->paginate(15);
        return view('admin.zi-penetapan.kategori-index', compact('kategoris'));
    }

    public function create()
    {
        return view('admin.zi-penetapan.kategori-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
        ]);
        $validated['slug'] = \Str::slug($validated['nama']);
        ZiPenetapanKategori::create($validated);
        return redirect()->route('admin.zi.penetapan.kategori.index')->with('success', __('messages.flash_created', ['item' => __('messages.kategori')]));
    }

    public function edit(ZiPenetapanKategori $kategori)
    {
        return view('admin.zi-penetapan.kategori-edit', compact('kategori'));
    }

    public function update(Request $request, ZiPenetapanKategori $kategori)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
        ]);
        $validated['slug'] = \Str::slug($validated['nama']);
        $kategori->update($validated);
        return redirect()->route('admin.zi.penetapan.kategori.index')->with('success', __('messages.flash_updated', ['item' => __('messages.kategori')]));
    }

    public function destroy(ZiPenetapanKategori $kategori)
    {
        $kategori->delete();
        return redirect()->route('admin.zi.penetapan.kategori.index')->with('success', __('messages.flash_deleted', ['item' => __('messages.kategori')]));
    }
}
