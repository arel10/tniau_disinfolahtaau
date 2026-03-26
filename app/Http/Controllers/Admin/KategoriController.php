<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoris = Kategori::withCount('beritas')
            ->latest()
            ->paginate(10);

        return view('admin.kategori.index', compact('kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['nama_kategori']);

        Kategori::create($validated);

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', __('messages.flash_created', ['item' => __('messages.kategori')]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Kategori $kategori)
    {
        $kategori->load(['beritas' => function($query) {
            $query->latest()->paginate(10);
        }]);

        return view('admin.kategori.show', compact('kategori'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['nama_kategori']);

        $kategori->update($validated);

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', __('messages.flash_updated', ['item' => __('messages.kategori')]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kategori $kategori)
    {
        if ($kategori->beritas()->count() > 0) {
            return redirect()
                ->route('admin.kategori.index')
                ->with('error', __('messages.flash_cannot_delete_has_children'));
        }

        $kategori->delete();

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', __('messages.flash_deleted', ['item' => __('messages.kategori')]));
    }
}
