<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use App\Models\KategoriGaleri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GaleriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Galeri::with('user');

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
        $kategoriGaleris = KategoriGaleri::aktif()->orderByRaw("slug = 'video' DESC")->orderBy('id')->get();

        return view('admin.galeri.index', compact('galeris', 'kategoriGaleris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoriGaleris = KategoriGaleri::aktif()->orderByRaw("slug = 'video' DESC")->orderBy('id')->get();
        return view('admin.galeri.create', compact('kategoriGaleris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:jpeg,jpg,png,gif,webp,mp4,mov,avi,mkv,webm',
            'pdf' => 'nullable|file|mimes:pdf',
            'video_url' => 'nullable|url|max:500',
            'kategori_galeri' => 'required|string|max:100',
            'tanggal_kegiatan' => 'nullable|date',
        ], [
            'kategori_galeri.required' => 'Silakan pilih Kategori Galeri terlebih dahulu.',
        ]);

        // Harus ada file ATAU video_url ATAU pdf
        if (!$request->hasFile('files') && empty($validated['video_url']) && !$request->hasFile('pdf')) {
            return back()->withErrors(['files' => 'Harus upload file, PDF, atau mengisi Video URL.'])->withInput();
        }

        // Upload PDF jika ada
        $pdfPath = null;
        if ($request->hasFile('pdf')) {
            $pdfPath = $request->file('pdf')->store('galeri/pdf', 'public');
        }

        $videoExtensions = ['mp4', 'mov', 'avi', 'mkv', 'webm'];
        $count = 0;

        // Generate a group id for this upload batch (so multiple files belong to one group)
        $groupId = (string) Str::uuid();

        // Upload files
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $ext = strtolower($file->getClientOriginalExtension());
                $tipe = in_array($ext, $videoExtensions) ? 'video' : 'foto';
                $path = $file->store('galeri', 'public');

                Galeri::create([
                    'group_id' => $groupId,
                    'judul' => $validated['judul'] ?: pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                    'deskripsi' => $validated['deskripsi'] ?? null,
                    'gambar' => $path,
                    'pdf_path' => $pdfPath,
                    'tipe' => $tipe,
                    'video_url' => null,
                    'kategori_galeri' => $validated['kategori_galeri'] ?? 'kadisinfolahta',
                    'tanggal_kegiatan' => $validated['tanggal_kegiatan'] ?? null,
                    'user_id' => Auth::id(),
                ]);
                $count++;
            }
        }

        // Video URL (buat entri terpisah jika ada)
        if (!empty($validated['video_url'])) {
            Galeri::create([
                'group_id' => $groupId,
                'judul' => $validated['judul'] ?: 'Video',
                'deskripsi' => $validated['deskripsi'] ?? null,
                'gambar' => null,
                'pdf_path' => $pdfPath,
                'tipe' => 'video',
                'video_url' => $validated['video_url'],
                'kategori_galeri' => $validated['kategori_galeri'] ?? 'kadisinfolahta',
                'tanggal_kegiatan' => $validated['tanggal_kegiatan'] ?? null,
                'user_id' => Auth::id(),
            ]);
            $count++;
        }

        // Jika hanya PDF tanpa file/video_url
        if (!$request->hasFile('files') && empty($validated['video_url']) && $pdfPath) {
            Galeri::create([
                'group_id' => $groupId,
                'judul' => $validated['judul'] ?: 'Dokumen PDF',
                'deskripsi' => $validated['deskripsi'] ?? null,
                'gambar' => null,
                'pdf_path' => $pdfPath,
                'tipe' => 'foto',
                'video_url' => null,
                'kategori_galeri' => $validated['kategori_galeri'] ?? 'kadisinfolahta',
                'tanggal_kegiatan' => $validated['tanggal_kegiatan'] ?? null,
                'user_id' => Auth::id(),
            ]);
            $count++;
        }

        return redirect()
            ->route('admin.galeri.index')
            ->with('success', "$count item galeri berhasil ditambahkan!");
    }

    /**
     * Display the specified resource.
     */
    public function show(Galeri $galeri)
    {
        $galeri->load('user');
        return view('admin.galeri.show', compact('galeri'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Galeri $galeri)
    {
        $kategoriGaleris = KategoriGaleri::aktif()->orderByRaw("slug = 'video' DESC")->orderBy('id')->get();
        return view('admin.galeri.edit', compact('galeri', 'kategoriGaleris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Galeri $galeri)
    {
        $validated = $request->validate([
            'judul' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|file|mimes:jpeg,jpg,png,gif,webp,mp4,mov,avi,mkv,webm',
            'pdf' => 'nullable|file|mimes:pdf',
            'video_url' => 'nullable|url|max:500',
            'kategori_galeri' => 'required|string|max:100',
            'tanggal_kegiatan' => 'nullable|date',
            'hapus_pdf' => 'nullable',
        ], [
            'kategori_galeri.required' => 'Silakan pilih Kategori Galeri terlebih dahulu.',
        ]);

        $videoExtensions = ['mp4', 'mov', 'avi', 'mkv', 'webm'];

        // Handle file upload
        if ($request->hasFile('gambar')) {
            if ($galeri->gambar) {
                Storage::disk('public')->delete($galeri->gambar);
            }
            $file = $request->file('gambar');
            $ext = strtolower($file->getClientOriginalExtension());
            $validated['tipe'] = in_array($ext, $videoExtensions) ? 'video' : 'foto';
            $validated['gambar'] = $file->store('galeri', 'public');
        }

        // Handle PDF upload
        if ($request->hasFile('pdf')) {
            if ($galeri->pdf_path) {
                Storage::disk('public')->delete($galeri->pdf_path);
            }
            $validated['pdf_path'] = $request->file('pdf')->store('galeri/pdf', 'public');
        }

        // Handle hapus PDF
        if ($request->has('hapus_pdf')) {
            if ($galeri->pdf_path) {
                Storage::disk('public')->delete($galeri->pdf_path);
            }
            $validated['pdf_path'] = null;
        }

        // Remove non-model fields
        unset($validated['pdf'], $validated['hapus_pdf']);

        // Handle video_url
        $validated['video_url'] = $validated['video_url'] ?? null;
        if (!empty($validated['video_url']) && !$request->hasFile('gambar') && !$galeri->gambar) {
            $validated['tipe'] = 'video';
        }

        $galeri->update($validated);

        return redirect()
            ->route('admin.galeri.index')
            ->with('success', __('messages.flash_updated', ['item' => __('messages.admin_galeri')]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Galeri $galeri)
    {
        // Delete image if exists
        if ($galeri->gambar) {
            Storage::disk('public')->delete($galeri->gambar);
        }

        // Delete PDF if exists
        if ($galeri->pdf_path) {
            Storage::disk('public')->delete($galeri->pdf_path);
        }

        $galeri->delete();

        return redirect()
            ->route('admin.galeri.index')
            ->with('success', __('messages.flash_deleted', ['item' => __('messages.admin_galeri')]));
    }
}
