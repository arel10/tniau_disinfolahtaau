<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sp4nLapor;
use App\Models\Sp4nLaporMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class Sp4nLaporController extends Controller
{
    public function index()
    {
        $items = Sp4nLapor::with('media')->orderBy('position')->latest()->get();
        return view('admin.sp4n-lapor.index', compact('items'));
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
            // Upload logo
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('sp4n-lapor/logos', 'public');
            }

            $item = Sp4nLapor::create([
                'judul'        => $validated['judul'] ?? null,
                'deskripsi'    => $validated['deskripsi'] ?? null,
                'video_url'    => $validated['video_url'] ?? null,
                'logo_path'    => $logoPath,
                'logo_link'    => $validated['logo_link'] ?? null,
                'is_published' => $request->has('is_published') ? true : true,
                'user_id'      => Auth::id(),
                'position'     => Sp4nLapor::max('position') + 1,
            ]);

            // Upload media files (images/videos)
            if ($request->hasFile('media_files')) {
                $videoExts = ['mp4', 'mov', 'avi', 'mkv', 'webm'];
                $pos = 0;
                foreach ($request->file('media_files') as $file) {
                    $ext = strtolower($file->getClientOriginalExtension());
                    $type = in_array($ext, $videoExts) ? 'video' : 'image';
                    $path = $file->store('sp4n-lapor/media', 'public');

                    Sp4nLaporMedia::create([
                        'sp4n_lapor_id' => $item->id,
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
                    $path = $file->store('sp4n-lapor/pdf', 'public');

                    Sp4nLaporMedia::create([
                        'sp4n_lapor_id' => $item->id,
                        'file_path'     => $path,
                        'type'          => 'pdf',
                        'original_name' => $file->getClientOriginalName(),
                        'position'      => $pos++,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.sp4n-lapor.index')
                ->with('success', __('messages.flash_created', ['item' => __('messages.admin_sp4n_lapor')]));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $item = Sp4nLapor::with('media')->findOrFail($id);
        $items = Sp4nLapor::with('media')->orderBy('position')->latest()->get();
        return view('admin.sp4n-lapor.index', compact('item', 'items'));
    }

    public function update(Request $request, $id)
    {
        $item = Sp4nLapor::findOrFail($id);

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
            // Upload logo (replace old)
            $logoPath = $item->logo_path;
            if ($request->hasFile('logo')) {
                if ($logoPath) {
                    Storage::disk('public')->delete($logoPath);
                }
                $logoPath = $request->file('logo')->store('sp4n-lapor/logos', 'public');
            }
            // Remove logo if checkbox
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
                $toDelete = Sp4nLaporMedia::whereIn('id', $deleteIds)
                    ->where('sp4n_lapor_id', $item->id)->get();
                foreach ($toDelete as $media) {
                    Storage::disk('public')->delete($media->file_path);
                    $media->delete();
                }
            }

            // Upload new media files
            if ($request->hasFile('media_files')) {
                $videoExts = ['mp4', 'mov', 'avi', 'mkv', 'webm'];
                $pos = $item->media()->max('position') + 1;
                foreach ($request->file('media_files') as $file) {
                    $ext = strtolower($file->getClientOriginalExtension());
                    $type = in_array($ext, $videoExts) ? 'video' : 'image';
                    $path = $file->store('sp4n-lapor/media', 'public');

                    Sp4nLaporMedia::create([
                        'sp4n_lapor_id' => $item->id,
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
                    $path = $file->store('sp4n-lapor/pdf', 'public');

                    Sp4nLaporMedia::create([
                        'sp4n_lapor_id' => $item->id,
                        'file_path'     => $path,
                        'type'          => 'pdf',
                        'original_name' => $file->getClientOriginalName(),
                        'position'      => $pos++,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.sp4n-lapor.index')
                ->with('success', __('messages.flash_updated', ['item' => __('messages.admin_sp4n_lapor')]));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memperbarui: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        $item = Sp4nLapor::findOrFail($id);

        // Delete all media files
        foreach ($item->media as $media) {
            Storage::disk('public')->delete($media->file_path);
        }

        // Delete logo
        if ($item->logo_path) {
            Storage::disk('public')->delete($item->logo_path);
        }

        $item->delete();

        return redirect()->route('admin.sp4n-lapor.index')
            ->with('success', __('messages.flash_deleted', ['item' => __('messages.admin_sp4n_lapor')]));
    }

    public function togglePublish($id)
    {
        $item = Sp4nLapor::findOrFail($id);
        $item->update(['is_published' => !$item->is_published]);

        return back()->with('success', __('messages.flash_status_changed'));
    }
}
