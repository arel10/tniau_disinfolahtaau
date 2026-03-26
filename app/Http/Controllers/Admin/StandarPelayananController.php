<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StandarPelayanan;
use App\Models\StandarPelayananMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class StandarPelayananController extends Controller
{
    public function index()
    {
        $items = StandarPelayanan::with('media')->orderBy('position')->latest()->get();
        return view('admin.pelayanan-publik.standar', compact('items'));
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
        ]);

        $hasContent = !empty($validated['judul']) || !empty($validated['deskripsi'])
            || !empty($validated['video_url']) || !empty($validated['logo_link'])
            || $request->hasFile('logo') || $request->hasFile('media_files') || $request->hasFile('pdf_files');

        if (!$hasContent) {
            return back()->withErrors(['judul' => 'Minimal satu field harus diisi.'])->withInput();
        }

        DB::beginTransaction();
        try {
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('standar-pelayanan/logos', 'public');
            }

            $item = StandarPelayanan::create([
                'judul'        => $validated['judul'] ?? null,
                'deskripsi'    => $validated['deskripsi'] ?? null,
                'video_url'    => $validated['video_url'] ?? null,
                'logo_path'    => $logoPath,
                'logo_link'    => $validated['logo_link'] ?? null,
                'is_published' => true,
                'user_id'      => Auth::id(),
                'position'     => StandarPelayanan::max('position') + 1,
            ]);

            if ($request->hasFile('media_files')) {
                $videoExts = ['mp4', 'mov', 'avi', 'mkv', 'webm'];
                $pos = 0;
                foreach ($request->file('media_files') as $file) {
                    $ext = strtolower($file->getClientOriginalExtension());
                    $type = in_array($ext, $videoExts) ? 'video' : 'image';
                    StandarPelayananMedia::create([
                        'standar_pelayanan_id' => $item->id,
                        'file_path'     => $file->store('standar-pelayanan/media', 'public'),
                        'type'          => $type,
                        'original_name' => $file->getClientOriginalName(),
                        'position'      => $pos++,
                    ]);
                }
            }

            if ($request->hasFile('pdf_files')) {
                $pos = 0;
                foreach ($request->file('pdf_files') as $file) {
                    StandarPelayananMedia::create([
                        'standar_pelayanan_id' => $item->id,
                        'file_path'     => $file->store('standar-pelayanan/pdf', 'public'),
                        'type'          => 'pdf',
                        'original_name' => $file->getClientOriginalName(),
                        'position'      => $pos++,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.pelayanan-publik.standar.index')
                ->with('success', __('messages.flash_created', ['item' => __('messages.admin_standar_pelayanan')]));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $item = StandarPelayanan::with('media')->findOrFail($id);
        $items = StandarPelayanan::with('media')->orderBy('position')->latest()->get();
        return view('admin.pelayanan-publik.standar', compact('item', 'items'));
    }

    public function update(Request $request, $id)
    {
        $item = StandarPelayanan::findOrFail($id);

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
        ]);

        DB::beginTransaction();
        try {
            $logoPath = $item->logo_path;
            if ($request->hasFile('logo')) {
                if ($logoPath) Storage::disk('public')->delete($logoPath);
                $logoPath = $request->file('logo')->store('standar-pelayanan/logos', 'public');
            }
            if ($request->has('remove_logo') && !$request->hasFile('logo')) {
                if ($logoPath) Storage::disk('public')->delete($logoPath);
                $logoPath = null;
            }

            $item->update([
                'judul'      => $validated['judul'] ?? null,
                'deskripsi'  => $validated['deskripsi'] ?? null,
                'video_url'  => $validated['video_url'] ?? null,
                'logo_path'  => $logoPath,
                'logo_link'  => $validated['logo_link'] ?? null,
                'is_published' => $request->has('is_published') ? true : false,
            ]);

            if ($request->has('delete_media')) {
                $toDelete = StandarPelayananMedia::whereIn('id', $request->input('delete_media', []))
                    ->where('standar_pelayanan_id', $item->id)->get();
                foreach ($toDelete as $media) {
                    Storage::disk('public')->delete($media->file_path);
                    $media->delete();
                }
            }

            if ($request->hasFile('media_files')) {
                $videoExts = ['mp4', 'mov', 'avi', 'mkv', 'webm'];
                $pos = $item->media()->max('position') + 1;
                foreach ($request->file('media_files') as $file) {
                    $ext = strtolower($file->getClientOriginalExtension());
                    $type = in_array($ext, $videoExts) ? 'video' : 'image';
                    StandarPelayananMedia::create([
                        'standar_pelayanan_id' => $item->id,
                        'file_path'     => $file->store('standar-pelayanan/media', 'public'),
                        'type'          => $type,
                        'original_name' => $file->getClientOriginalName(),
                        'position'      => $pos++,
                    ]);
                }
            }

            if ($request->hasFile('pdf_files')) {
                $pos = $item->pdfs()->max('position') + 1;
                foreach ($request->file('pdf_files') as $file) {
                    StandarPelayananMedia::create([
                        'standar_pelayanan_id' => $item->id,
                        'file_path'     => $file->store('standar-pelayanan/pdf', 'public'),
                        'type'          => 'pdf',
                        'original_name' => $file->getClientOriginalName(),
                        'position'      => $pos++,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.pelayanan-publik.standar.index')
                ->with('success', __('messages.flash_updated', ['item' => __('messages.admin_standar_pelayanan')]));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memperbarui: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        $item = StandarPelayanan::findOrFail($id);
        foreach ($item->media as $media) Storage::disk('public')->delete($media->file_path);
        if ($item->logo_path) Storage::disk('public')->delete($item->logo_path);
        $item->delete();
        return redirect()->route('admin.pelayanan-publik.standar.index')
            ->with('success', __('messages.flash_deleted', ['item' => __('messages.admin_standar_pelayanan')]));
    }

    public function togglePublish($id)
    {
        $item = StandarPelayanan::findOrFail($id);
        $item->update(['is_published' => !$item->is_published]);
        return back()->with('success', __('messages.flash_status_changed'));
    }
}
