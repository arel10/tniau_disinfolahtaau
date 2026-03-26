<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    private function kategoriOptions()
    {
        return Kategori::query()
            ->select(['id', 'nama_kategori'])
            ->orderBy('nama_kategori')
            ->get();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Berita::with(['kategori', 'user']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by kategori
        if ($request->has('kategori_id') && $request->kategori_id != '') {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $beritas = $query->latest('tanggal')->paginate(10);
        $kategoris = $this->kategoriOptions();

        return view('admin.berita.index', compact('beritas', 'kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = $this->kategoriOptions();
        return view('admin.berita.create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'judul_en' => 'nullable|string|max:255',
            'judul_ja' => 'nullable|string|max:255',
            'konten' => 'required|string',
            'konten_en' => 'nullable|string',
            'konten_ja' => 'nullable|string',
            'gambar_utama' => 'nullable|image',
            'gambar_tambahan.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,mkv,webm',
            'kategori_id' => 'required|exists:kategoris,id',
            'status' => 'required|in:draft,published',
            'tanggal' => 'nullable|date',
        ]);

        unset($validated['gambar_tambahan']);

        // Auto-generate ringkasan from konten (first 200 chars, strip HTML)
        $validated['ringkasan'] = Str::limit(strip_tags($validated['konten']), 200);
        if (!empty($validated['konten_en'])) {
            $validated['ringkasan_en'] = Str::limit(strip_tags($validated['konten_en']), 200);
        }
        if (!empty($validated['konten_ja'])) {
            $validated['ringkasan_ja'] = Str::limit(strip_tags($validated['konten_ja']), 200);
        }

        $validated['slug'] = Str::slug($validated['judul']);
        $validated['user_id'] = Auth::id();

        // Handle main image upload
        if ($request->hasFile('gambar_utama')) {
            $validated['gambar_utama'] = $request->file('gambar_utama')
                ->store('berita', 'public');
        }

        // Handle additional images
        $tambahan = [];
        if ($request->hasFile('gambar_tambahan')) {
            foreach ($request->file('gambar_tambahan') as $file) {
                $tambahan[] = $file->store('berita', 'public');
            }
        }
        $validated['gambar_tambahan'] = $tambahan;

        // Set published_at if status is published
        if ($validated['status'] == 'published') {
            $validated['published_at'] = now();
        }

        Berita::create($validated);

        return redirect()
            ->route('admin.berita.index')
            ->with('success', __('messages.flash_created', ['item' => __('messages.admin_berita')]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Berita $berita)
    {
        $berita->load(['kategori', 'user']);
        return view('admin.berita.show', compact('berita'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Berita $berita)
    {
        $kategoris = $this->kategoriOptions();
        return view('admin.berita.edit', compact('berita', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Berita $berita)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'judul_en' => 'nullable|string|max:255',
            'judul_ja' => 'nullable|string|max:255',
            'konten' => 'required|string',
            'konten_en' => 'nullable|string',
            'konten_ja' => 'nullable|string',
            'gambar_utama' => 'nullable|image',
            'gambar_tambahan.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,mkv,webm',
            'kategori_id' => 'required|exists:kategoris,id',
            'status' => 'required|in:draft,published',
            'tanggal' => 'nullable|date',
        ]);

        unset($validated['gambar_tambahan']);

        // Auto-generate ringkasan from konten (first 200 chars, strip HTML)
        $validated['ringkasan'] = Str::limit(strip_tags($validated['konten']), 200);
        if (!empty($validated['konten_en'])) {
            $validated['ringkasan_en'] = Str::limit(strip_tags($validated['konten_en']), 200);
        }
        if (!empty($validated['konten_ja'])) {
            $validated['ringkasan_ja'] = Str::limit(strip_tags($validated['konten_ja']), 200);
        }

        $validated['slug'] = Str::slug($validated['judul']);

        // Handle main image upload
        if ($request->hasFile('gambar_utama')) {
            if ($berita->gambar_utama) {
                Storage::disk('public')->delete($berita->gambar_utama);
            }
            $validated['gambar_utama'] = $request->file('gambar_utama')
                ->store('berita', 'public');
        }

        // Handle delete additional images
        $existing = $berita->gambar_tambahan ?? [];
        $hapus = $request->input('hapus_gambar', []);
        foreach ($hapus as $path) {
            Storage::disk('public')->delete($path);
            $existing = array_values(array_filter($existing, fn($p) => $p !== $path));
        }

        // Handle new additional images
        if ($request->hasFile('gambar_tambahan')) {
            foreach ($request->file('gambar_tambahan') as $file) {
                $existing[] = $file->store('berita', 'public');
            }
        }
        $validated['gambar_tambahan'] = array_values($existing);

        // Set published_at if status changed to published
        if ($validated['status'] == 'published' && $berita->status != 'published') {
            $validated['published_at'] = now();
        }

        $berita->update($validated);

        return redirect()
            ->route('admin.berita.index')
            ->with('success', __('messages.flash_updated', ['item' => __('messages.admin_berita')]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Berita $berita)
    {
        // Delete image if exists
        if ($berita->gambar_utama) {
            Storage::disk('public')->delete($berita->gambar_utama);
        }

        $berita->delete();

        return redirect()
            ->route('admin.berita.index')
            ->with('success', __('messages.flash_deleted', ['item' => __('messages.admin_berita')]));
    }
}
