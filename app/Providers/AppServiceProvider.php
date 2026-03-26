<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Kategori;
use App\Models\Visitor;
use App\Models\MediaSosial;
use App\Models\HubungiKami;
use App\Models\MenuUtama;
use App\Models\Berita;
use App\Models\Event;
use App\Models\Galeri;
use App\Models\InstansiTerkait;
use App\Jobs\TranslateModelContent;
use App\Services\StatisticsCalculationService;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load view helpers (display_views) if present
        if (file_exists(app_path('Helpers/ViewHelper.php'))) {
            require_once app_path('Helpers/ViewHelper.php');
        }

        // ── Auto-translation observers ──────────────────────────────────────
        // When content is saved with Indonesian text, queue a job to
        // auto-translate it into all supported locales via Google Translate API.
        $locales = ['en', 'ar', 'fr', 'es', 'ru', 'ja'];

        Berita::saved(function (Berita $berita) use ($locales) {
            TranslateModelContent::dispatch(
                Berita::class, $berita->id, ['judul', 'ringkasan', 'konten'], $locales
            )->delay(now()->addSeconds(5));
        });

        Event::saved(function (Event $event) use ($locales) {
            TranslateModelContent::dispatch(
                Event::class, $event->id, ['nama_kegiatan', 'deskripsi'], $locales
            )->delay(now()->addSeconds(5));
        });

        Galeri::saved(function (Galeri $galeri) use ($locales) {
            TranslateModelContent::dispatch(
                Galeri::class, $galeri->id, ['judul', 'deskripsi'], $locales
            )->delay(now()->addSeconds(5));
        });

        Kategori::saved(function (Kategori $kategori) use ($locales) {
            TranslateModelContent::dispatch(
                Kategori::class, $kategori->id, ['nama_kategori'], $locales
            )->delay(now()->addSeconds(5));
        });
        // ───────────────────────────────────────────────────────────────────
        View::composer('layouts.public', function ($view) {
            $view->with('navKategoris', Kategori::orderBy('id')->get());
            $view->with('beritaTren', \App\Models\Berita::published()->orderBy('views', 'desc')->take(5)->get());

            // Visitor statistics - use proper baseline tracking
            // This ensures that manual manipulations work as starting points (baselines)
            // and don't interfere with automatic incrementing
            $statsService = new StatisticsCalculationService();
            $stats = $statsService->getDisplayedStats();

            $view->with('visitHariIni', $stats['visitHariIni']);
            $view->with('visitKemarin', $stats['visitKemarin']);
            $view->with('visitMingguIni', $stats['visitMingguIni']);
            $view->with('visitBulanIni', $stats['visitBulanIni']);
            $view->with('visitTahunIni', $stats['visitTahunIni']);
            $view->with('visitTotal', $stats['visitTotal']);
            $view->with('viewsHariIni', $stats['viewsHariIni']);
            $view->with('viewsTotal', $stats['viewsTotal']);
            $view->with('mediaSosialFooter', MediaSosial::orderBy('sort_order')->orderBy('id')->get());
            $view->with('hubungiKamiFooter', HubungiKami::orderBy('sort_order')->orderBy('id')->get());
            $view->with('menuUtamaFooter', MenuUtama::orderBy('sort_order')->orderBy('id')->get());

            // Shared sections for all public pages: Yang Terlewat, Galeri, Instansi
            $view->with('yang_terlewat', Berita::with(['kategori', 'user'])
                ->published()
                ->where('tanggal', '<', Carbon::now()->subDays(3))
                ->latest('tanggal')
                ->take(10)
                ->get());
            $view->with('galeri_terbaru', Galeri::latest('tanggal_kegiatan')->take(15)->get());
            $view->with('galeri_foto',    Galeri::foto()->latest('tanggal_kegiatan')->take(12)->get());
            $view->with('galeri_video',   Galeri::video()->latest('tanggal_kegiatan')->take(9)->get());
            $view->with('instansi_terkait', InstansiTerkait::orderBy('sort_order')->orderBy('id')->get());
        });
    }
}
