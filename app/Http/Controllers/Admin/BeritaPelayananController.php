<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BeritaPelayanan;
use App\Models\BeritaPelayananMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BeritaPelayananController extends Controller
{
    public function index()
    {
        $items = BeritaPelayanan::with('media')->orderBy('position')->latest()->get();
        return view('admin.pelayanan-publik.berita', compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'       => 'nullable|string|max:255',
            'deskripsi'   => 'nullable|string',
            'video_url'   => 'nullable|url|max:500',
            'logo'        => 'nullable|image',
            'logo_link'   => 'nullable|url|max:500',
            'media_files' => 'nullable|array',
            'media_files.*' => 'file|mimes:jpeg,jpg,png,gif,webp,mp4,mov,avi,mkv,webm',
            'pdf_files'   => 'nullable|array',
            'pdf_files.*' => 'file|mimes:pdf',
            'is_published' => 'nullable|boolean',
        ]);

        // At least one field must be filled
        $hasContent = !empty($validated['judul'])
            || !empty($validated['deskripsi'])
            || !empty($validated['video_url'])
            || !empty($validated['logo_link'])
            || $request->hasFile('logo')
            || $request->hasFile('media_files')
            || $request->hasFile('pdf_files');

        if (!$hasContent) {
            return back()->withErrors(['judul' => 'Minimal satu field harus diisi.'])->withInput();
        }

        DB::beginTransaction();
        try {
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('berita-pelayanan/logos', 'public');
            }

            $item = BeritaPelayanan::create([
                'judul'        => $validated['judul'] ?? null,
                'deskripsi'    => $validated['deskripsi'] ?? null,
                'video_url'    => $validated['video_url'] ?? null,
                'logo_path'    => $logoPath,
                'logo_link'    => $validated['logo_link'] ?? null,
                'is_published' => true,
                'user_id'      => Auth::id(),
                'position'     => BeritaPelayanan::max('position') + 1,
            ]);

            // Upload media files (images/videos)
            if ($request->hasFile('media_files')) {
                $videoExts = ['mp4', 'mov', 'avi', 'mkv', 'webm'];
                $pos = 0;
                foreach ($request->file('media_files') as $file) {
                    $ext = strtolower($file->getClientOriginalExtension());
                    $type = in_array($ext, $videoExts) ? 'video' : 'image';
                    $path = $file->store('berita-pelayanan/media', 'public');

                    BeritaPelayananMedia::create([
                        'berita_pelayanan_id' => $item->id,
                        'file_path'     => $path,
                        'type'          => $type,
                        'original_name' => $file->getClientOriginalName(),
                        'position'      => $pos++,
                    ]);
                }
            }

            // Upload PDF files
            if ($request->hasFile('pdf_files')) {
                $pos = 0;
                foreach ($request->file('pdf_files') as $file) {
                    $path = $file->store('berita-pelayanan/pdf', 'public');

                    BeritaPelayananMedia::create([
                        'berita_pelayanan_id' => $item->id,
                        'file_path'     => $path,
                        'type'          => 'pdf',
                        'original_name' => $file->getClientOriginalName(),
                        'position'      => $pos++,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.pelayanan-publik.berita.index')
                ->with('success', __('messages.flash_created', ['item' => __('messages.admin_berita_pelayanan')]));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $item = BeritaPelayanan::with('media')->findOrFail($id);
        $items = BeritaPelayanan::with('media')->orderBy('position')->latest()->get();
        return view('admin.pelayanan-publik.berita', compact('item', 'items'));
    }

    public function update(Request $request, $id)
    {
        $item = BeritaPelayanan::findOrFail($id);

        $validated = $request->validate([
            'judul'       => 'nullable|string|max:255',
            'deskripsi'   => 'nullable|string',
            'video_url'   => 'nullable|url|max:500',
            'logo'        => 'nullable|image',
            'logo_link'   => 'nullable|url|max:500',
            'media_files' => 'nullable|array',
            'media_files.*' => 'file|mimes:jpeg,jpg,png,gif,webp,mp4,mov,avi,mkv,webm',
            'pdf_files'   => 'nullable|array',
            'pdf_files.*' => 'file|mimes:pdf',
            'is_published' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            $logoPath = $item->logo_path;
            if ($request->hasFile('logo')) {
                if ($logoPath) Storage::disk('public')->delete($logoPath);
                $logoPath = $request->file('logo')->store('berita-pelayanan/logos', 'public');
            }
            if ($request->has('remove_logo') && !$request->hasFile('logo')) {
                if ($logoPath) Storage::disk('public')->delete($logoPath);
                $logoPath = null;
            }

            $item->update([
                'judul'        => $validated['judul'] ?? null,
                'deskripsi'    => $validated['deskripsi'] ?? null,
                'video_url'    => $validated['video_url'] ?? null,
                'logo_path'    => $logoPath,
                'logo_link'    => $validated['logo_link'] ?? null,
                'is_published' => $request->has('is_published') ? true : false,
            ]);

            // Delete removed media
            if ($request->has('delete_media')) {
                $deleteIds = $request->input('delete_media', []);
                $toDelete = BeritaPelayananMedia::whereIn('id', $deleteIds)
                    ->where('berita_pelayanan_id', $item->id)->get();

                foreach ($toDelete as $media) {
                    Storage::disk('public')->delete($media->file_path);
                }

                BeritaPelayananMedia::whereIn('id', $toDelete->pluck('id'))
                    ->where('berita_pelayanan_id', $item->id)
                    ->delete();
            }

            // Upload new media files
            if ($request->hasFile('media_files')) {
                $videoExts = ['mp4', 'mov', 'avi', 'mkv', 'webm'];
                $pos = $item->media()->max('position') + 1;
                foreach ($request->file('media_files') as $file) {
                    $ext = strtolower($file->getClientOriginalExtension());
                    $type = in_array($ext, $videoExts) ? 'video' : 'image';
                    $path = $file->store('berita-pelayanan/media', 'public');

                    BeritaPelayananMedia::create([
                        'berita_pelayanan_id' => $item->id,
                        'file_path'     => $path,
                        'type'          => $type,
                        'original_name' => $file->getClientOriginalName(),
                        'position'      => $pos++,
                    ]);
                }
            }

            // Upload new PDF files
            if ($request->hasFile('pdf_files')) {
                $pos = $item->pdfs()->max('position') + 1;
                foreach ($request->file('pdf_files') as $file) {
                    $path = $file->store('berita-pelayanan/pdf', 'public');

                    BeritaPelayananMedia::create([
                        'berita_pelayanan_id' => $item->id,
                        'file_path'     => $path,
                        'type'          => 'pdf',
                        'original_name' => $file->getClientOriginalName(),
                        'position'      => $pos++,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.pelayanan-publik.berita.index')
                ->with('success', __('messages.flash_updated', ['item' => __('messages.admin_berita_pelayanan')]));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memperbarui: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        $item = BeritaPelayanan::findOrFail($id);

        foreach ($item->media as $media) {
            Storage::disk('public')->delete($media->file_path);
        }
        if ($item->logo_path) {
            Storage::disk('public')->delete($item->logo_path);
        }

        $item->delete();

        return redirect()->route('admin.pelayanan-publik.berita.index')
            ->with('success', __('messages.flash_deleted', ['item' => __('messages.admin_berita_pelayanan')]));
    }

    public function togglePublish($id)
    {
        $item = BeritaPelayanan::findOrFail($id);
        $item->update(['is_published' => !$item->is_published]);

        return back()->with('success', __('messages.flash_status_changed'));
    }
}
