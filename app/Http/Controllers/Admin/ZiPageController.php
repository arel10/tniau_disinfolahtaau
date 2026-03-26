<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ZiPage;
use App\Models\ZiPageMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ZiPageController extends Controller
{
    private array $types = ['zona_integritas', 'pembangunan', 'pemantauan'];

    private function validateType($type)
    {
        if (!in_array($type, $this->types)) {
            abort(404);
        }
        return $type;
    }

    public function index($type)
    {
        $type = $this->validateType($type);
        $pages = ZiPage::where('type', $type)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.zi-pages.index', compact('pages', 'type'));
    }

    public function create($type)
    {
        $type = $this->validateType($type);
        return view('admin.zi-pages.create', compact('type'));
    }

    public function store($type, Request $request)
    {
        $type = $this->validateType($type);
        
        $validated = $request->validate([
            'judul'         => 'nullable|string|max:255',
            'konten'        => 'nullable|string',
            'gambar'        => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,mkv,webm',
            'pdf'           => 'nullable|mimes:pdf',
            'media_files'   => 'nullable|array',
            'media_files.*' => 'file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,mkv,webm',
        ]);
        
        $validated['type'] = $type;
        
        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('zi/pages', 'public');
        }

        if ($request->hasFile('pdf')) {
            $validated['pdf_path'] = $request->file('pdf')->store('zi/pages/pdf', 'public');
        }
        unset($validated['pdf'], $validated['media_files']);
        
        $ziPage = ZiPage::create($validated);

        // Save multiple media files
        if ($request->hasFile('media_files')) {
            foreach ($request->file('media_files') as $file) {
                $path = $file->store('zi/pages/media', 'public');
                $ext = strtolower($file->getClientOriginalExtension());
                $tipe = in_array($ext, ['mp4','mov','avi','mkv','webm']) ? 'video' : 'image';
                $ziPage->media()->create(['file_path' => $path, 'tipe' => $tipe]);
            }
        }
        
        return redirect()->route('admin.zi.pages.index', ['type' => $type])
            ->with('success', __('messages.flash_created', ['item' => 'Data']));
    }

    public function edit($type, $id)
    {
        $type = $this->validateType($type);
        $ziPage = ZiPage::with('media')->where('id', $id)->where('type', $type)->firstOrFail();
        return view('admin.zi-pages.edit', compact('ziPage', 'type'));
    }

    public function update($type, $id, Request $request)
    {
        $type = $this->validateType($type);
        $ziPage = ZiPage::where('id', $id)->where('type', $type)->firstOrFail();
        
        $validated = $request->validate([
            'judul'         => 'nullable|string|max:255',
            'konten'        => 'nullable|string',
            'gambar'        => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,mkv,webm',
            'pdf'           => 'nullable|mimes:pdf',
            'media_files'   => 'nullable|array',
            'media_files.*' => 'file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,mkv,webm',
            'hapus_media'   => 'nullable|array',
            'hapus_media.*' => 'integer',
        ]);
        
        if ($request->hasFile('gambar')) {
            if ($ziPage->gambar) {
                Storage::disk('public')->delete($ziPage->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('zi/pages', 'public');
        }
        
        if ($request->boolean('hapus_gambar') && !$request->hasFile('gambar')) {
            if ($ziPage->gambar) {
                Storage::disk('public')->delete($ziPage->gambar);
            }
            $validated['gambar'] = null;
        }

        if ($request->hasFile('pdf')) {
            if ($ziPage->pdf_path) {
                Storage::disk('public')->delete($ziPage->pdf_path);
            }
            $validated['pdf_path'] = $request->file('pdf')->store('zi/pages/pdf', 'public');
        }

        if ($request->boolean('hapus_pdf') && !$request->hasFile('pdf')) {
            if ($ziPage->pdf_path) {
                Storage::disk('public')->delete($ziPage->pdf_path);
            }
            $validated['pdf_path'] = null;
        }
        unset($validated['pdf'], $validated['media_files'], $validated['hapus_media']);
        
        $ziPage->update($validated);

        // Delete selected media
        if ($request->has('hapus_media')) {
            $mediaToDelete = ZiPageMedia::whereIn('id', $request->input('hapus_media'))
                ->where('zi_page_id', $ziPage->id)->get();
            foreach ($mediaToDelete as $media) {
                Storage::disk('public')->delete($media->file_path);
                $media->delete();
            }
        }

        // Add new media files
        if ($request->hasFile('media_files')) {
            foreach ($request->file('media_files') as $file) {
                $path = $file->store('zi/pages/media', 'public');
                $ext = strtolower($file->getClientOriginalExtension());
                $tipe = in_array($ext, ['mp4','mov','avi','mkv','webm']) ? 'video' : 'image';
                $ziPage->media()->create(['file_path' => $path, 'tipe' => $tipe]);
            }
        }
        
        return redirect()->route('admin.zi.pages.index', ['type' => $type])
            ->with('success', __('messages.flash_updated', ['item' => 'Data']));
    }

    public function destroy($type, $id)
    {
        $type = $this->validateType($type);
        $ziPage = ZiPage::with('media')->where('id', $id)->where('type', $type)->firstOrFail();
        
        if ($ziPage->gambar) {
            Storage::disk('public')->delete($ziPage->gambar);
        }
        if ($ziPage->pdf_path) {
            Storage::disk('public')->delete($ziPage->pdf_path);
        }
        // Delete all media files
        foreach ($ziPage->media as $media) {
            Storage::disk('public')->delete($media->file_path);
        }
        
        $ziPage->delete();
        
        return redirect()->route('admin.zi.pages.index', ['type' => $type])
            ->with('success', __('messages.flash_deleted', ['item' => 'Data']));
    }
}
