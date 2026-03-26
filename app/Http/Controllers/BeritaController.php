<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    /**
     * Display a listing of the berita.
     */
    public function index(Request $request)
    {
        $query = Berita::with(['kategori', 'user'])->published();

        // Filter by kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $kategori = Kategori::where('slug', $request->kategori)->first();
            if ($kategori) {
                $query->where('kategori_id', $kategori->id);
            }
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('ringkasan', 'like', '%' . $request->search . '%')
                  ->orWhere('konten', 'like', '%' . $request->search . '%');
            });
        }

        // Sort
        $sort = $request->get('sort', 'terbaru');
        if ($sort == 'terbaru') {
            $query->latest('tanggal');
        } elseif ($sort == 'terpopuler') {
            $query->orderBy('views', 'desc');
        }

        $beritas = $query->paginate(12);
        $kategoris = Kategori::withCount(['beritas' => function($q) {
            $q->published();
        }])->get();

        $berita_populer = Berita::with(['kategori', 'user'])
            ->published()
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        return view('public.berita.index', compact('beritas', 'kategoris', 'berita_populer'));
    }

    /**
     * Display the specified berita.
     */
    public function show($slug)
    {
        $berita = Berita::with(['kategori', 'user'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        // Increment views
        $berita->incrementViews();

        // Berita terkait (kategori yang sama)
        $berita_terkait = Berita::with(['kategori', 'user'])
            ->published()
            ->where('kategori_id', $berita->kategori_id)
            ->where('id', '!=', $berita->id)
            ->latest('tanggal')
            ->take(4)
            ->get();

        // Berita terbaru
        $berita_terbaru = Berita::with(['kategori', 'user'])
            ->published()
            ->where('id', '!=', $berita->id)
            ->latest('tanggal')
            ->take(5)
            ->get();

        return view('public.berita.show', compact('berita', 'berita_terkait', 'berita_terbaru'));
    }

    /**
     * Display beritas by kategori.
     */
    public function byKategori($slug)
    {
        $kategori = Kategori::where('slug', $slug)->firstOrFail();

        $beritas = Berita::with(['kategori', 'user'])
            ->published()
            ->where('kategori_id', $kategori->id)
            ->latest('tanggal')
            ->paginate(12);

        $kategoris = Kategori::withCount(['beritas' => function($q) {
            $q->published();
        }])->get();

        return view('public.berita.kategori', compact('beritas', 'kategori', 'kategoris'));
    }
}
