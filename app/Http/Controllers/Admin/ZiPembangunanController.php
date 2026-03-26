<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ZiPage;
use App\Models\ZiPageMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ZiPembangunanController extends Controller
{
    private string $type = 'pembangunan';

    public function index()
    {
        $items = ZiPage::ofType($this->type)->latest()->paginate(15);
        return view('admin.zi-pembangunan.index', compact('items'));
    }

    public function create()
    {
        return view('admin.zi-pembangunan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'         => 'nullable|string|max:255',
            'keterangan'    => 'nullable|string',
            'gambar'        => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,mkv,webm',
            'pdf'           => 'nullable|mimes:pdf',
            'media_files'   => 'nullable|array',
            'media_files.*' => 'file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,mkv,webm,pdf',
        ]);

        $data = [
            'type'   => $this->type,
            'judul'  => $validated['judul'] ?? null,
            'konten' => $validated['keterangan'] ?? null,
        ];

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('zi/pembangunan', 'public');
        }

        if ($request->hasFile('pdf')) {
            $data['pdf_path'] = $request->file('pdf')->store('zi/pembangunan/pdf', 'public');
        }

        $item = ZiPage::create($data);

        // Save multiple media files
        if ($request->hasFile('media_files')) {
            foreach ($request->file('media_files') as $file) {
                $path = $file->store('zi/pembangunan/media', 'public');
                $ext = strtolower($file->getClientOriginalExtension());
                if ($ext === 'pdf') {
                    $tipe = 'pdf';
                } elseif (in_array($ext, ['mp4','mov','avi','mkv','webm'])) {
                    $tipe = 'video';
                } else {
                    $tipe = 'image';
                }
                $item->media()->create(['file_path' => $path, 'tipe' => $tipe]);
            }
        }

        return redirect()->route('admin.zi.pembangunan.index')
            ->with('success', __('messages.flash_created', ['item' => __('messages.admin_pembangunan')]));
    }

    public function edit($id)
    {
        $item = ZiPage::ofType($this->type)->with('media')->findOrFail($id);
        return view('admin.zi-pembangunan.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = ZiPage::ofType($this->type)->findOrFail($id);

        $validated = $request->validate([
            'judul'         => 'nullable|string|max:255',
            'keterangan'    => 'nullable|string',
            'gambar'        => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,mkv,webm',
            'pdf'           => 'nullable|mimes:pdf',
            'media_files'   => 'nullable|array',
            'media_files.*' => 'file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,mkv,webm,pdf',
            'hapus_media'   => 'nullable|array',
            'hapus_media.*' => 'integer',
        ]);

        $item->judul  = $validated['judul'] ?? null;
        $item->konten = $validated['keterangan'] ?? null;

        if ($request->hasFile('gambar')) {
            if ($item->gambar) {
                Storage::disk('public')->delete($item->gambar);
            }
            $item->gambar = $request->file('gambar')->store('zi/pembangunan', 'public');
        }

        if ($request->boolean('hapus_gambar') && !$request->hasFile('gambar')) {
            if ($item->gambar) {
                Storage::disk('public')->delete($item->gambar);
                $item->gambar = null;
            }
        }

        if ($request->hasFile('pdf')) {
            if ($item->pdf_path) {
                Storage::disk('public')->delete($item->pdf_path);
            }
            $item->pdf_path = $request->file('pdf')->store('zi/pembangunan/pdf', 'public');
        }

        if ($request->boolean('hapus_pdf') && !$request->hasFile('pdf')) {
            if ($item->pdf_path) {
                Storage::disk('public')->delete($item->pdf_path);
                $item->pdf_path = null;
            }
        }

        $item->save();

        // Delete selected media
        if ($request->has('hapus_media')) {
            $mediaToDelete = ZiPageMedia::whereIn('id', $request->input('hapus_media'))
                ->where('zi_page_id', $item->id)->get();
            foreach ($mediaToDelete as $media) {
                Storage::disk('public')->delete($media->file_path);
                $media->delete();
            }
        }

        // Add new media files
        if ($request->hasFile('media_files')) {
            foreach ($request->file('media_files') as $file) {
                $path = $file->store('zi/pembangunan/media', 'public');
                $ext = strtolower($file->getClientOriginalExtension());
                if ($ext === 'pdf') {
                    $tipe = 'pdf';
                } elseif (in_array($ext, ['mp4','mov','avi','mkv','webm'])) {
                    $tipe = 'video';
                } else {
                    $tipe = 'image';
                }
                $item->media()->create(['file_path' => $path, 'tipe' => $tipe]);
            }
        }

        return redirect()->route('admin.zi.pembangunan.index')
            ->with('success', __('messages.flash_updated', ['item' => __('messages.admin_pembangunan')]));
    }

    public function destroy($id)
    {
        $item = ZiPage::ofType($this->type)->with('media')->findOrFail($id);

        if ($item->gambar) {
            Storage::disk('public')->delete($item->gambar);
        }
        if ($item->pdf_path) {
            Storage::disk('public')->delete($item->pdf_path);
        }
        foreach ($item->media as $media) {
            Storage::disk('public')->delete($media->file_path);
        }

        $item->delete();

        return redirect()->route('admin.zi.pembangunan.index')
            ->with('success', __('messages.flash_deleted', ['item' => __('messages.admin_pembangunan')]));
    }
}
