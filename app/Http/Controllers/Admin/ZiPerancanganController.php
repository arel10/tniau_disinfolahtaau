<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ZiPerancanganPost;
use App\Models\ZiPerancanganPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ZiPerancanganController extends Controller
{
    public function index()
    {
        $posts = ZiPerancanganPost::with('photos')->latest()->paginate(15);
        return view('admin.zi-perancangan.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.zi-perancangan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'     => 'nullable|string|max:255',
            'konten'    => 'nullable|string',
            'pdf'       => 'nullable|mimes:pdf',
            'photos'    => 'nullable|array',
            'photos.*'  => 'file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,mkv,webm',
            'captions'  => 'nullable|array',
            'captions.*' => 'nullable|string|max:255',
        ]);

        $data = [
            'judul'     => $validated['judul'] ?? null,
            'konten'    => $validated['konten'] ?? null,
        ];

        if ($request->hasFile('pdf')) {
            $data['pdf_path'] = $request->file('pdf')->store('zi/perancangan/pdf', 'public');
        }

        $post = ZiPerancanganPost::create($data);

        // Upload multiple photos/videos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $i => $photo) {
                $post->photos()->create([
                    'path'       => $photo->store('zi/perancangan/photos', 'public'),
                    'caption'    => $request->input("captions.$i"),
                    'sort_order' => $i,
                ]);
            }
        }

        return redirect()
            ->route('admin.zi.perancangan.index')
            ->with('success', __('messages.flash_created', ['item' => __('messages.admin_perancangan')]));
    }


    public function edit(ZiPerancanganPost $perancangan)
    {
        $perancangan->load('photos');
        return view('admin.zi-perancangan.edit', ['post' => $perancangan]);
    }

    public function update(Request $request, ZiPerancanganPost $perancangan)
    {
        $validated = $request->validate([
            'judul'     => 'nullable|string|max:255',
            'konten'    => 'nullable|string',
            'pdf'       => 'nullable|mimes:pdf',
            'photos'    => 'nullable|array',
            'photos.*'  => 'file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,mkv,webm',
            'captions'  => 'nullable|array',
            'captions.*' => 'nullable|string|max:255',
        ]);

        $data = [
            'judul'     => $validated['judul'] ?? null,
            'konten'    => $validated['konten'] ?? null,
        ];

        // PDF upload / hapus
        if ($request->hasFile('pdf')) {
            if ($perancangan->pdf_path) {
                Storage::disk('public')->delete($perancangan->pdf_path);
            }
            $data['pdf_path'] = $request->file('pdf')->store('zi/perancangan/pdf', 'public');
        }

        if ($request->boolean('hapus_pdf') && !$request->hasFile('pdf')) {
            if ($perancangan->pdf_path) {
                Storage::disk('public')->delete($perancangan->pdf_path);
            }
            $data['pdf_path'] = null;
        }

        $perancangan->update($data);

        // Tambah foto baru (tanpa menghapus foto lama)
        if ($request->hasFile('photos')) {
            $startOrder = $perancangan->photos()->max('sort_order') + 1;
            foreach ($request->file('photos') as $i => $photo) {
                $perancangan->photos()->create([
                    'path'       => $photo->store('zi/perancangan/photos', 'public'),
                    'caption'    => $request->input("captions.$i"),
                    'sort_order' => $startOrder + $i,
                ]);
            }
        }

        return redirect()
            ->route('admin.zi.perancangan.index')
            ->with('success', __('messages.flash_updated', ['item' => __('messages.admin_perancangan')]));
    }

    public function destroy(ZiPerancanganPost $perancangan)
    {
        // Hapus PDF
        if ($perancangan->pdf_path) {
            Storage::disk('public')->delete($perancangan->pdf_path);
        }
        // Hapus semua foto
        foreach ($perancangan->photos as $photo) {
            Storage::disk('public')->delete($photo->path);
        }
        $perancangan->photos()->delete();
        $perancangan->delete();

        return redirect()
            ->route('admin.zi.perancangan.index')
            ->with('success', __('messages.flash_deleted', ['item' => __('messages.admin_perancangan')]));
    }

    /**
     * Hapus satu foto via AJAX / link
     */
    public function destroyPhoto(ZiPerancanganPhoto $photo)
    {
        Storage::disk('public')->delete($photo->path);
        $photo->delete();

        return back()->with('success', __('messages.flash_deleted', ['item' => __('messages.foto')]));
    }
}
