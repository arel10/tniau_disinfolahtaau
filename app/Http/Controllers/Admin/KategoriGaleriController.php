<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriGaleri;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KategoriGaleriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoriGaleris = KategoriGaleri::withCount('galeris')
            ->orderBy('id')
            ->paginate(10);

        return view('admin.kategori-galeri.index', compact('kategoriGaleris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kategori-galeri.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_galeris,nama_kategori',
            'status' => 'required|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['nama_kategori']);

        KategoriGaleri::create($validated);

        return redirect()
            ->route('admin.kategori-galeri.index')
            ->with('success', __('messages.flash_created', ['item' => __('messages.admin_kategori_galeri')]));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KategoriGaleri $kategoriGaleri)
    {
        return view('admin.kategori-galeri.edit', compact('kategoriGaleri'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KategoriGaleri $kategoriGaleri)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_galeris,nama_kategori,' . $kategoriGaleri->id,
            'status' => 'required|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['nama_kategori']);

        $kategoriGaleri->update($validated);

        return redirect()
            ->route('admin.kategori-galeri.index')
            ->with('success', __('messages.flash_updated', ['item' => __('messages.admin_kategori_galeri')]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KategoriGaleri $kategoriGaleri)
    {
        if ($kategoriGaleri->galeris()->count() > 0) {
            return redirect()
                ->route('admin.kategori-galeri.index')
                ->with('error', __('messages.flash_cannot_delete_has_children'));
        }

        $kategoriGaleri->delete();

        return redirect()
            ->route('admin.kategori-galeri.index')
            ->with('success', __('messages.flash_deleted', ['item' => __('messages.admin_kategori_galeri')]));
    }
}
