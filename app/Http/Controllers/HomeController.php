<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Galeri;
use App\Models\InstansiTerkait;
use App\Models\Kategori;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $now = now();

        $beritaBaseQuery = Berita::query()
            ->with(['kategori', 'user:id,name'])
            ->published();

        $data = [
            // === #Tagar Teratas (Top categories with published count) ===
            'tagar_teratas' => Kategori::withCount(['beritas' => function ($query) {
                $query->published();
            }])
                ->having('beritas_count', '>', 0)
                ->orderByDesc('beritas_count')
                ->take(12)
                ->get(),

            // === Berita Utama (carousel – top 5 latest) ===
            'berita_utama' => (clone $beritaBaseQuery)
                ->latest('tanggal')
                ->take(5)
                ->get(),

            // === Postingan Hari Ini ===
            'postingan_hari_ini' => (clone $beritaBaseQuery)
                ->whereDate('published_at', $today)
                ->latest('tanggal')
                ->take(6)
                ->get(),

            // === Pembaruan Hari Ini – Popular (most views today) ===
            'popular_hari_ini' => (clone $beritaBaseQuery)
                ->orderByDesc('views')
                ->take(6)
                ->get(),

            // === Pembaruan Hari Ini – Trending (recent with high views) ===
            'trending_hari_ini' => (clone $beritaBaseQuery)
                ->where('published_at', '>=', $now->copy()->subDays(7))
                ->orderByDesc('views')
                ->take(6)
                ->get(),

            // === Berita Fitur (featured – mix of top viewed + latest) ===
            'berita_fitur' => (clone $beritaBaseQuery)
                ->orderByRaw('(views * 2 + DATEDIFF(NOW(), published_at) * -1) DESC')
                ->take(10)
                ->get(),

            // === Galeri Berita (news gallery – latest with images) ===
            'berita_galeri' => (clone $beritaBaseQuery)
                ->whereNotNull('gambar_utama')
                ->latest('tanggal')
                ->take(8)
                ->get(),

            // === Yang Terlewat (older articles carousel) ===
            'yang_terlewat' => (clone $beritaBaseQuery)
                ->where('published_at', '<', $now->copy()->subDays(3))
                ->latest('tanggal')
                ->take(10)
                ->get(),

            // === Galeri Terbaru ===
            'galeri_terbaru' => Galeri::orderByDesc('tanggal_kegiatan')
                ->take(6)
                ->get(),

            // === Galeri Foto (foto type only) ===
            'galeri_foto' => Galeri::foto()->orderByDesc('tanggal_kegiatan')->take(12)->get(),

            // === Galeri Video (video type only) ===
            'galeri_video' => Galeri::video()->orderByDesc('tanggal_kegiatan')->take(9)->get(),

            // === Kategoris for sidebar/other uses ===
            'kategoris' => Kategori::withCount(['beritas' => function ($query) {
                $query->published();
            }])
                ->having('beritas_count', '>', 0)
                ->get(),

            // === Instansi Terkait ===
            'instansi_terkait' => InstansiTerkait::orderBy('sort_order')->orderBy('id')->get(),
        ];

        return view('public.home', $data);
    }
}
