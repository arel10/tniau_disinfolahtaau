<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreELibraryDocumentRequest;
use App\Http\Requests\Admin\UpdateELibraryDocumentRequest;
use App\Models\ELibraryDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ELibraryAdminController extends Controller
{
    public function index()
    {
        $documents = ELibraryDocument::orderBy('position')->get();
        return view('admin.e-library.index', compact('documents'));
    }

    public function create()
    {
        return view('admin.e-library.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|max:51200',
        ]);

        $file = $request->file('file');
        $filePath = $file->store('e-library/files', 'public');
        $title = $request->title ?: pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        ELibraryDocument::create([
            'title' => $title,
            'description' => $request->description,
            'pdf_path' => $filePath,
            'cover_path' => null,
            'status' => $request->input('status', 'published'),
        ]);

        return redirect()->route('admin.e-library.index')->with('success', __('messages.flash_created', ['item' => __('messages.dokumen')]));
    }

    public function edit($id)
    {
        $document = ELibraryDocument::findOrFail($id);
        return view('admin.e-library.edit', compact('document'));
    }

    public function update(Request $request, $id)
    {
        $document = ELibraryDocument::findOrFail($id);

        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|max:51200',
            'status' => 'in:published,draft,private',
        ]);

        $data = $request->only(['description', 'status']);
        $data['title'] = $request->title ?: $document->title;

        if ($request->hasFile('file')) {
            if ($document->pdf_path) Storage::disk('public')->delete($document->pdf_path);
            $data['pdf_path'] = $request->file('file')->store('e-library/files', 'public');
            if (!$request->title) {
                $data['title'] = pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_FILENAME);
            }
        }

        $document->update($data);
        return redirect()->route('admin.e-library.index')->with('success', __('messages.flash_updated', ['item' => __('messages.dokumen')]));
    }

    public function destroy($id)
    {
        $document = ELibraryDocument::findOrFail($id);
        if ($document->pdf_path) Storage::disk('public')->delete($document->pdf_path);
        if ($document->cover_path) Storage::disk('public')->delete($document->cover_path);
        $document->delete();
        return redirect()->route('admin.e-library.index')->with('success', __('messages.flash_deleted', ['item' => __('messages.dokumen')]));
    }

    public function moveUp($id)
    {
        $doc = ELibraryDocument::findOrFail($id);
        $prev = ELibraryDocument::where('position', '<', $doc->position)->orderBy('position', 'desc')->first();
        if ($prev) {
            [$doc->position, $prev->position] = [$prev->position, $doc->position];
            $doc->save();
            $prev->save();
        }
        return back();
    }

    public function moveDown($id)
    {
        $doc = ELibraryDocument::findOrFail($id);
        $next = ELibraryDocument::where('position', '>', $doc->position)->orderBy('position')->first();
        if ($next) {
            [$doc->position, $next->position] = [$next->position, $doc->position];
            $doc->save();
            $next->save();
        }
        return back();
    }

    public function togglePublish($id)
    {
        $doc = ELibraryDocument::findOrFail($id);
        // Cycle: published -> private -> draft -> published
        $cycle = [
            'published' => 'private',
            'private'   => 'draft',
            'draft'     => 'published',
        ];
        $doc->status = $cycle[$doc->status] ?? 'published';
        $doc->save();
        return back();
    }
}
