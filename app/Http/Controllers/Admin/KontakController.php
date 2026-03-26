<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kontak;
use Illuminate\Http\Request;

class KontakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Kontak::query();

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('subjek', 'like', '%' . $request->search . '%');
            });
        }

        $kontaks = $query->latest()->paginate(10);

        $statusCounts = Kontak::selectRaw("
            COUNT(*) as semua,
            SUM(CASE WHEN status = 'baru' THEN 1 ELSE 0 END) as baru,
            SUM(CASE WHEN status = 'dibaca' THEN 1 ELSE 0 END) as dibaca,
            SUM(CASE WHEN status = 'diproses' THEN 1 ELSE 0 END) as diproses,
            SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as selesai
        ")->first()->toArray();

        return view('admin.kontak.index', compact('kontaks', 'statusCounts'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Kontak $kontak)
    {
        // Auto mark as read when viewing
        if ($kontak->status == 'baru') {
            $kontak->updateStatus('dibaca');
        }

        return view('admin.kontak.show', compact('kontak'));
    }

    /**
     * Update the status of the resource.
     */
    public function updateStatus(Request $request, Kontak $kontak)
    {
        $validated = $request->validate([
            'status' => 'required|in:baru,dibaca,diproses,selesai',
        ]);

        $kontak->updateStatus($validated['status']);

        return redirect()
            ->back()
            ->with('success', __('messages.flash_status_changed'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kontak $kontak)
    {
        $kontak->delete();

        return redirect()
            ->route('admin.kontak.index')
            ->with('success', __('messages.flash_deleted', ['item' => __('messages.pesan')]));
    }

    /**
     * Mark multiple messages as read.
     */
    public function markAsRead(Request $request)
    {
        $ids = $request->input('ids', []);
        
        Kontak::whereIn('id', $ids)
            ->where('status', 'baru')
            ->update(['status' => 'dibaca']);

        return redirect()
            ->back()
            ->with('success', __('messages.flash_marked_read'));
    }

    /**
     * Delete multiple messages.
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        
        Kontak::whereIn('id', $ids)->delete();

        return redirect()
            ->back()
            ->with('success', __('messages.flash_deleted', ['item' => __('messages.pesan')]));
    }
}
