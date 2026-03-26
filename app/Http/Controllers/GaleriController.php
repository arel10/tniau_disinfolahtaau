<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use App\Models\KategoriGaleri;
use Illuminate\Http\Request;

class GaleriController extends Controller
{
    /**
     * Display a listing of the galeri.
     */
    public function index(Request $request)
    {
        $query = Galeri::query();

        // Filter by tipe
        if ($request->has('tipe') && $request->tipe != '') {
            $query->where('tipe', $request->tipe);
        }

        // Filter by kategori_galeri
        if ($request->has('kategori_galeri') && $request->kategori_galeri != '') {
            $query->where('kategori_galeri', $request->kategori_galeri);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $galeris = $query->latest('tanggal_kegiatan')->paginate(12);

        $counts = Galeri::selectRaw("
            SUM(CASE WHEN tipe = 'foto' THEN 1 ELSE 0 END) as foto_count,
            SUM(CASE WHEN tipe = 'video' THEN 1 ELSE 0 END) as video_count
        ")->first();
        $foto_count = $counts->foto_count ?? 0;
        $video_count = $counts->video_count ?? 0;

        return view('public.galeri.index', compact('galeris', 'foto_count', 'video_count'));
    }

    /**
     * Display galeri by kategori.
     */
    public function byKategori(Request $request, $kategori)
    {
        $query = Galeri::where('kategori_galeri', $kategori);

        // Filter by tipe within category
        if ($request->has('tipe') && $request->tipe != '') {
            $query->where('tipe', $request->tipe);
        }

        // Search within category
        if ($request->has('search') && $request->search != '') {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $galeris = $query->latest('tanggal_kegiatan')->paginate(12);

        // Ambil label dari KategoriGaleri DB, fallback ke static options
        $kategoriModel = KategoriGaleri::where('slug', $kategori)->first();
        $kategoriLabel = $kategoriModel ? $kategoriModel->nama_kategori : (Galeri::$kategoriGaleriOptions[$kategori] ?? ucfirst($kategori));

        // Count per kategori in single query instead of 2 separate queries
        $counts = Galeri::where('kategori_galeri', $kategori)->selectRaw("
            SUM(CASE WHEN tipe = 'foto' THEN 1 ELSE 0 END) as foto_count,
            SUM(CASE WHEN tipe = 'video' THEN 1 ELSE 0 END) as video_count
        ")->first();
        $foto_count = $counts->foto_count ?? 0;
        $video_count = $counts->video_count ?? 0;
        $currentKategori = $kategori;

        return view('public.galeri.index', compact('galeris', 'foto_count', 'video_count', 'kategoriLabel', 'currentKategori'));
    }

    /**
     * Display the specified galeri.
     */
    public function show(Galeri $galeri)
    {
        $galeri->load('user');

        // Galeri terkait
        $galeri_terkait = Galeri::where('tipe', $galeri->tipe)
            ->where('id', '!=', $galeri->id)
            ->latest('tanggal_kegiatan')
            ->take(6)
            ->get();

        return view('public.galeri.show', compact('galeri', 'galeri_terkait'));
    }

    /**
     * Display an album (group) containing all items uploaded together.
     */
    public function album($group)
    {
        $items = Galeri::where('group_id', $group)->orderBy('id')->get();
        if (!$items->count()) {
            abort(404);
        }

        return view('public.galeri.album', compact('items', 'group'));
    }

    /**
     * Display a single item inside an album with left/right navigation.
     * Large image is lazy-loaded only after user interaction.
     */
    public function albumItem($group, Galeri $galeri)
    {
        if ($galeri->group_id !== $group) {
            abort(404);
        }

        $items = Galeri::where('group_id', $group)->orderBy('id')->get();
        $ids = $items->pluck('id')->toArray();
        $currentIndex = array_search($galeri->id, $ids, true);

        return view('public.galeri.album_item', compact('items', 'galeri', 'group', 'currentIndex'));
    }

    /**
     * Display foto galeri.
     */
    public function foto()
    {
        $galeris = Galeri::where('tipe', 'foto')
            ->latest('tanggal_kegiatan')
            ->paginate(12);

        return view('public.galeri.foto', compact('galeris'));
    }

    /**
     * Display video galeri.
     */
    public function video()
    {
        $galeris = Galeri::where('tipe', 'video')
            ->latest('tanggal_kegiatan')
            ->paginate(12);

        return view('public.galeri.video', compact('galeris'));
    }
}
