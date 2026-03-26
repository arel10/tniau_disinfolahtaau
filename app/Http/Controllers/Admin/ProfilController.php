<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfilContent;
use App\Models\HistoryDiagram;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    /**
     * Dashboard / index profil — redirect ke kata-pengantar sebagai default.
     */
    public function index()
    {
        return redirect()->route('admin.profil.kata-pengantar');
    }

    // ─── Kata Pengantar ────────────────────────────────────────────

    public function kataPengantar()
    {
        $data = ProfilContent::where('type', 'kata_pengantar')->first();

        return view('admin.profil.kata-pengantar', [
            'title'   => $data?->title ?? '',
            'content' => $data?->content ?? '',
            'image'   => $data?->image ?? '',
        ]);
    }

    public function kataPengantarUpdate(Request $request)
    {
        $request->validate([
            'title'   => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'foto'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = ProfilContent::firstOrCreate(['type' => 'kata_pengantar']);
        $data->title   = $request->title ?? $data->title;
        // Only update content if it's actually submitted (preserve existing if left empty)
        if ($request->filled('content')) {
            $data->content = $request->content;
        }

        if ($request->hasFile('foto')) {
            // Delete old image if exists
            if ($data->image && \Storage::disk('public')->exists($data->image)) {
                \Storage::disk('public')->delete($data->image);
            }
            $data->image = $request->file('foto')->store('profil', 'public');
        }

        if ($request->has('hapus_foto') && $data->image) {
            \Storage::disk('public')->delete($data->image);
            $data->image = null;
        }

        $data->save();

        return redirect()->route('admin.profil.kata-pengantar')
            ->with('success', __('messages.flash_updated', ['item' => __('messages.admin_kata_pengantar')]));
    }

    // ─── Tentang Kami ──────────────────────────────────────────────

    public function tentang()
    {
        $tentang = ProfilContent::where('type', 'tentang')->first();

        return view('admin.profil.tentang', [
            'content' => $tentang?->content ?? '',
        ]);
    }

    public function tentangUpdate(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        ProfilContent::updateOrCreate(['type' => 'tentang'], ['content' => $request->content]);

        return redirect()->route('admin.profil.tentang')
            ->with('success', __('messages.flash_updated', ['item' => __('messages.admin_tentang_kami')]));
    }

    // ─── Sejarah (Diagram) ─────────────────────────────────────────

    public function sejarah()
    {
        $diagrams = HistoryDiagram::orderBy('id', 'asc')->get();

        return view('admin.profil.sejarah', compact('diagrams'));
    }

    /**
     * AJAX: simpan/update satu item diagram.
     */
    public function sejarahStore(Request $request)
    {
        $request->validate([
            'id'          => 'nullable|exists:history_diagrams,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'year'        => 'required|string|max:20',
        ]);

        $diagram = HistoryDiagram::updateOrCreate(
            ['id' => $request->id],
            $request->only('title', 'description', 'year')
        );

        return response()->json(['success' => true, 'diagram' => $diagram]);
    }

    /**
     * AJAX: hapus satu item diagram.
     */
    public function sejarahDestroy($id)
    {
        HistoryDiagram::findOrFail($id)->delete();

        return response()->json(['success' => true]);
    }
}
