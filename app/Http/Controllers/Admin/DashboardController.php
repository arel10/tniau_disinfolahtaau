<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Kontak;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Single query for berita stats instead of 3 separate counts
        $beritaStats = Berita::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'published' THEN 1 ELSE 0 END) as published,
            SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) as draft
        ")->first();

        // Single query for kontak stats instead of 2 separate counts
        $kontakStats = Kontak::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'baru' THEN 1 ELSE 0 END) as baru
        ")->first();

        $data = [
            'total_berita' => $beritaStats->total,
            'berita_published' => $beritaStats->published,
            'berita_draft' => $beritaStats->draft,
            'total_galeri' => Galeri::count(),
            'pesan_baru' => $kontakStats->baru,
            'total_pesan' => $kontakStats->total,
            'total_admin' => User::where('role', 'admin')->count(),
            'berita_terbaru' => Berita::with(['kategori', 'user'])
                ->latest('created_at')
                ->take(5)
                ->get(),
            'pesan_terbaru' => Kontak::where('status', 'baru')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('admin.dashboard', $data);
    }
}
