<?php

namespace App\Http\Controllers;

use App\Models\ELibraryDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ELibraryController extends Controller
{
    public function index()
    {
        $query = ELibraryDocument::orderBy('position');

        if (auth()->check()) {
            // Logged-in users see published + private
            $query->whereIn('status', ['published', 'private']);
        } else {
            // Guests see only published
            $query->where('status', 'published');
        }

        $documents = $query->get();
        return view('e-library.index', compact('documents'));
    }

    public function show($slug)
    {
        $query = ELibraryDocument::where('slug', $slug);

        if (auth()->check()) {
            $query->whereIn('status', ['published', 'private']);
        } else {
            $query->where('status', 'published');
        }

        $document = $query->firstOrFail();
        return view('e-library.show', compact('document'));
    }

    public function download($slug)
    {
        $query = ELibraryDocument::where('slug', $slug);

        if (auth()->check()) {
            $query->whereIn('status', ['published', 'private']);
        } else {
            $query->where('status', 'published');
        }

        $document = $query->firstOrFail();
        $document->increment('downloads_count');
        $ext = pathinfo($document->pdf_path, PATHINFO_EXTENSION);
        return Storage::disk('public')->download($document->pdf_path, $document->title . '.' . $ext);
    }

    public function trackView($slug)
    {
        $query = ELibraryDocument::where('slug', $slug);

        if (auth()->check()) {
            $query->whereIn('status', ['published', 'private']);
        } else {
            $query->where('status', 'published');
        }

        $document = $query->firstOrFail();
        $document->increment('views_count');
        return response()->json(['success' => true]);
    }
}
