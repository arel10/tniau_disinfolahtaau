<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ZiPage;
use App\Models\ZiPageMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ZiPemantauanController extends Controller
{
    private string $type = 'pemantauan';

    public function index()
    {
        $items = ZiPage::ofType($this->type)->latest()->paginate(15);
        return view('admin.zi-pemantauan.index', compact('items'));
    }

    public function create()
    {
        return view('admin.zi-pemantauan.create');
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
            $data['gambar'] = $request->file('gambar')->store('zi/pemantauan', 'public');
        }

        if ($request->hasFile('pdf')) {
            $data['pdf_path'] = $request->file('pdf')->store('zi/pemantauan/pdf', 'public');
        }

        $item = ZiPage::create($data);

        if ($request->hasFile('media_files')) {
            foreach ($request->file('media_files') as $file) {
                $path = $file->store('zi/pemantauan/media', 'public');
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

        return redirect()->route('admin.zi.pemantauan.index')
            ->with('success', __('messages.flash_created', ['item' => __('messages.admin_pemantauan')]));
    }

    public function edit($id)
    {
        $item = ZiPage::ofType($this->type)->with('media')->findOrFail($id);
        return view('admin.zi-pemantauan.edit', compact('item'));
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
        ]);

        $item->judul  = $validated['judul'] ?? null;
        $item->konten = $validated['keterangan'] ?? null;

        // Gambar utama
        if ($request->hasFile('gambar')) {
            if ($item->gambar) Storage::disk('public')->delete($item->gambar);
            $item->gambar = $request->file('gambar')->store('zi/pemantauan', 'public');
        }
        if ($request->has('hapus_gambar') && !$request->hasFile('gambar')) {
            if ($item->gambar) { Storage::disk('public')->delete($item->gambar); $item->gambar = null; }
        }

        // PDF utama
        if ($request->hasFile('pdf')) {
            if ($item->pdf_path) Storage::disk('public')->delete($item->pdf_path);
            $item->pdf_path = $request->file('pdf')->store('zi/pemantauan/pdf', 'public');
        }
        if ($request->has('hapus_pdf') && !$request->hasFile('pdf')) {
            if ($item->pdf_path) { Storage::disk('public')->delete($item->pdf_path); $item->pdf_path = null; }
        }

        $item->save();

        // Hapus media yang dicentang
        if ($request->filled('hapus_media')) {
            $mediaToDelete = ZiPageMedia::whereIn('id', $request->hapus_media)->where('zi_page_id', $item->id)->get();
            foreach ($mediaToDelete as $m) {
                Storage::disk('public')->delete($m->file_path);
                $m->delete();
            }
        }

        // Tambah media baru
        if ($request->hasFile('media_files')) {
            foreach ($request->file('media_files') as $file) {
                $path = $file->store('zi/pemantauan/media', 'public');
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

        return redirect()->route('admin.zi.pemantauan.index')
            ->with('success', __('messages.flash_updated', ['item' => __('messages.admin_pemantauan')]));
    }

    public function destroy($id)
    {
        $item = ZiPage::ofType($this->type)->findOrFail($id);

        if ($item->gambar) Storage::disk('public')->delete($item->gambar);
        if ($item->pdf_path) Storage::disk('public')->delete($item->pdf_path);

        foreach ($item->media as $m) {
            Storage::disk('public')->delete($m->file_path);
            $m->delete();
        }

        $item->delete();

        return redirect()->route('admin.zi.pemantauan.index')
            ->with('success', __('messages.flash_deleted', ['item' => __('messages.admin_pemantauan')]));
    }
}
