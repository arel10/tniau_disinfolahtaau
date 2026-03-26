<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\BeritaPelayanan;
use App\Models\CustomMenu;
use App\Models\CustomMenuWidget;
use App\Models\ELibraryDocument;
use App\Models\Event;
use App\Models\Galeri;
use App\Models\HasilSurvei;
use App\Models\HistoryDiagram;
use App\Models\Kategori;
use App\Models\KompensasiPelayanan;
use App\Models\LayananPengaduan;
use App\Models\MenuUtama;
use App\Models\PiaPage;
use App\Models\ProfilContent;
use App\Models\StandarPelayanan;
use App\Models\Tutorial;
use App\Models\ZiPage;
use App\Models\ZiPenetapanItem;
use App\Models\ZiPenetapanKategori;
use App\Models\ZiPerancanganPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\Facades\Schema;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->query('q', ''));

        if (mb_strlen($q) < 2) {
            return view('public.search.results', [
                'q'       => $q,
                'results' => [],
                'total'   => 0,
            ]);
        }

        $like = '%' . $q . '%';
        $results = [];

        // Safe query helper: only run a query if the model's table exists.
        $safe = function ($modelClass, $callback) {
            try {
                if (! class_exists($modelClass)) return collect();
                $m = new $modelClass;
                if (! Schema::hasTable($m->getTable())) return collect();
                return $callback();
            } catch (\Throwable $e) {
                return collect();
            }
        };

        // ── 1. Berita (judul, ringkasan, konten, tags) ─────────────
        $beritas = $safe(Berita::class, function() use ($like) {
            return Berita::where('status', 'published')
                ->where(function ($query) use ($like) {
                    $query->where('judul', 'like', $like)
                          ->orWhere('judul_en', 'like', $like)
                          ->orWhere('judul_ja', 'like', $like)
                          ->orWhere('ringkasan', 'like', $like)
                          ->orWhere('ringkasan_en', 'like', $like)
                          ->orWhere('konten', 'like', $like)
                          ->orWhere('konten_en', 'like', $like)
                          ->orWhere('tags', 'like', $like);
                })
                ->orderByDesc('tanggal')
                ->limit(12)
                ->get();
        });

        foreach ($beritas as $b) {
            $results[] = [
                'type'    => 'Berita',
                'icon'    => 'fas fa-newspaper',
                'title'   => $b->localized_judul ?? $b->judul,
                'excerpt' => $b->localized_ringkasan ?? $b->ringkasan,
                'url'     => route('berita.show', $b->slug),
                'date'    => $b->tanggal ? $b->tanggal->format('d M Y') : null,
                'image'   => $b->gambar_utama ? asset('storage/' . $b->gambar_utama) : null,
            ];
        }

        // ── 2. Kategori Berita ─────────────────────────────────────
        $kategoris = $safe(Kategori::class, function() use ($like) {
            return Kategori::where(function ($query) use ($like) {
                $query->where('nama_kategori', 'like', $like)
                      ->orWhere('nama_kategori_en', 'like', $like)
                      ->orWhere('deskripsi', 'like', $like);
            })->limit(5)->get();
        });

        foreach ($kategoris as $k) {
            $results[] = [
                'type'    => 'Kategori Berita',
                'icon'    => 'fas fa-folder',
                'title'   => $k->localized_nama_kategori ?? $k->nama_kategori,
                'excerpt' => $k->deskripsi,
                'url'     => route('berita.kategori', $k->slug),
                'date'    => null,
                'image'   => null,
            ];
        }

        // ── 3. Menu Utama ──────────────────────────────────────────
        $menus = $safe(MenuUtama::class, function() use ($like) {
            return MenuUtama::where('nama', 'like', $like)->limit(5)->get();
        });
        foreach ($menus as $m) {
            try {
                $url = RouteFacade::has($m->route_name) ? route($m->route_name) : '#';
            } catch (\Throwable $e) {
                $url = '#';
            }
            $results[] = [
                'type'    => 'Menu Utama',
                'icon'    => 'fas fa-bars',
                'title'   => $m->nama,
                'excerpt' => null,
                'url'     => $url,
                'date'    => null,
                'image'   => null,
            ];
        }

        // ── 4. Custom Menu & Sub-menu ──────────────────────────────
        $customMenus = $safe(CustomMenu::class, function() use ($like) {
            return CustomMenu::where('is_published', true)
                ->where('name', 'like', $like)
                ->with('parent')
                ->limit(8)
                ->get();
        });

        foreach ($customMenus as $m) {
            if ($m->parent_id) {
                $parent = $m->parent;
                $url = $parent ? route('custom.page', [$parent->slug, $m->slug]) : route('custom.page', [$m->slug]);
            } else {
                $url = route('custom.page', [$m->slug]);
            }
            $results[] = [
                'type'    => $m->parent_id ? 'Sub-Menu' : 'Halaman',
                'icon'    => $m->parent_id ? 'fas fa-indent' : 'fas fa-file-alt',
                'title'   => $m->name,
                'excerpt' => null,
                'url'     => $url,
                'date'    => null,
                'image'   => null,
            ];
        }

        // ── 5. Konten Widget Halaman Kustom ────────────────────────
        $widgets = $safe(CustomMenuWidget::class, function() use ($like) {
            return CustomMenuWidget::where('is_active', true)
                ->where('text_content', 'like', $like)
                ->with('menu.parent')
                ->limit(6)
                ->get();
        });

        foreach ($widgets as $w) {
            $menu = $w->menu ?? null;
            if ($menu) {
                if ($menu->parent_id && $menu->parent) {
                    $url = route('custom.page', [$menu->parent->slug, $menu->slug]);
                } else {
                    $url = route('custom.page', [$menu->slug]);
                }
                $pageTitle = $menu->name;
            } else {
                $url = '#';
                $pageTitle = 'Halaman Kustom';
            }
            $results[] = [
                'type'    => 'Konten Halaman',
                'icon'    => 'fas fa-align-left',
                'title'   => $pageTitle,
                'excerpt' => strip_tags($w->text_content),
                'url'     => $url,
                'date'    => null,
                'image'   => null,
            ];
        }

        // ── 6. E-Library ───────────────────────────────────────────
        $docs = $safe(ELibraryDocument::class, function() use ($like) {
            return ELibraryDocument::where('status', ELibraryDocument::STATUS_PUBLISHED)
                ->where(function ($query) use ($like) {
                    $query->where('title', 'like', $like)
                          ->orWhere('description', 'like', $like);
                })
                ->limit(8)
                ->get();
        });

        foreach ($docs as $d) {
            $results[] = [
                'type'    => 'E-Library',
                'icon'    => 'fas fa-book',
                'title'   => $d->title,
                'excerpt' => $d->description,
                'url'     => route('e-library.show', $d->slug),
                'date'    => null,
                'image'   => $d->cover_path ? asset('storage/' . $d->cover_path) : null,
            ];
        }

        // ── 7. Galeri ──────────────────────────────────────────────
        $galeris = $safe(Galeri::class, function() use ($like) {
            return Galeri::where(function ($query) use ($like) {
                $query->where('judul', 'like', $like)
                      ->orWhere('judul_en', 'like', $like)
                      ->orWhere('deskripsi', 'like', $like)
                      ->orWhere('deskripsi_en', 'like', $like);
            })->limit(6)->get();
        });

        foreach ($galeris as $g) {
            $results[] = [
                'type'    => 'Galeri',
                'icon'    => 'fas fa-images',
                'title'   => $g->localized_judul ?? $g->judul,
                'excerpt' => $g->localized_deskripsi ?? $g->deskripsi,
                'url'     => route('galeri.show', $g->id),
                'date'    => $g->tanggal_kegiatan ? \Carbon\Carbon::parse($g->tanggal_kegiatan)->format('d M Y') : null,
                'image'   => $g->gambar ? asset('storage/' . $g->gambar) : null,
            ];
        }

        // ── 8. Events ──────────────────────────────────────────────
        $events = $safe(Event::class, function() use ($like) {
            return Event::where('is_published', true)
                ->where(function ($query) use ($like) {
                    $query->where('nama_kegiatan', 'like', $like)
                          ->orWhere('nama_kegiatan_en', 'like', $like)
                          ->orWhere('deskripsi', 'like', $like)
                          ->orWhere('deskripsi_en', 'like', $like);
                })
                ->limit(6)
                ->get();
        });

        foreach ($events as $e) {
            $results[] = [
                'type'    => 'Event',
                'icon'    => 'fas fa-calendar-check',
                'title'   => $e->localized_nama_kegiatan ?? $e->nama_kegiatan,
                'excerpt' => $e->localized_deskripsi ?? $e->deskripsi,
                'url'     => route('events.show', $e->slug),
                'date'    => $e->tanggal_kegiatan ? $e->tanggal_kegiatan->format('d M Y') : null,
                'image'   => $e->cover_image ? asset('storage/' . $e->cover_image) : null,
            ];
        }

        // ── 9. PIA ─────────────────────────────────────────────────
        $piaPages = $safe(PiaPage::class, function() use ($like) {
            return PiaPage::where(function ($query) use ($like) {
                $query->where('page_title', 'like', $like)
                      ->orWhere('history_title', 'like', $like)
                      ->orWhere('history_content', 'like', $like);
            })->limit(3)->get();
        });

        foreach ($piaPages as $p) {
            $results[] = [
                'type'    => 'PIA',
                'icon'    => 'fas fa-star',
                'title'   => $p->page_title ?: 'PIA',
                'excerpt' => $p->history_title,
                'url'     => route('pia'),
                'date'    => null,
                'image'   => null,
            ];
        }

        // ── 10. Profil ─────────────────────────────────────────────
        $profilContents = $safe(ProfilContent::class, function() use ($like) {
            return ProfilContent::where(function ($query) use ($like) {
                $query->where('title', 'like', $like)
                      ->orWhere('content', 'like', $like);
            })->limit(5)->get();
        });

        $profilRouteMap = [
            'tentang'        => 'profil.index',
            'kata_pengantar' => 'profil.kata-pengantar',
            'sejarah'        => 'profil.sejarah',
        ];

        foreach ($profilContents as $pc) {
            $routeName = $profilRouteMap[$pc->type] ?? 'profil.index';
            try {
                $url = route($routeName);
            } catch (\Throwable $e) {
                $url = route('profil.index');
            }
            $results[] = [
                'type'    => 'Profil',
                'icon'    => 'fas fa-building',
                'title'   => $pc->title ?: 'Profil',
                'excerpt' => strip_tags($pc->content),
                'url'     => $url,
                'date'    => null,
                'image'   => $pc->image ? asset('storage/' . $pc->image) : null,
            ];
        }

        // ── 11. Sejarah / History Diagram ──────────────────────────
        $histories = $safe(HistoryDiagram::class, function() use ($like) {
            return HistoryDiagram::where(function ($query) use ($like) {
                $query->where('title', 'like', $like)
                      ->orWhere('description', 'like', $like);
            })->limit(5)->get();
        });

        foreach ($histories as $h) {
            $results[] = [
                'type'    => 'Sejarah',
                'icon'    => 'fas fa-landmark',
                'title'   => $h->title . ($h->year ? ' (' . $h->year . ')' : ''),
                'excerpt' => $h->description,
                'url'     => route('profil.sejarah'),
                'date'    => $h->year ? null : null,
                'image'   => null,
            ];
        }

        // ── 12. Pelayanan Publik — Berita Pelayanan ────────────────
        $beritaPelayanan = $safe(BeritaPelayanan::class, function() use ($like) {
            return BeritaPelayanan::where('is_published', true)
                ->where(function ($query) use ($like) {
                    $query->where('judul', 'like', $like)
                          ->orWhere('deskripsi', 'like', $like);
                })->limit(5)->get();
        });

        foreach ($beritaPelayanan as $bp) {
            $results[] = [
                'type'    => 'Pelayanan',
                'icon'    => 'fas fa-concierge-bell',
                'title'   => $bp->judul,
                'excerpt' => $bp->deskripsi,
                'url'     => route('pelayanan.berita'),
                'date'    => null,
                'image'   => $bp->logo_path ? asset('storage/' . $bp->logo_path) : null,
            ];
        }

        // ── 13. Standar Pelayanan ──────────────────────────────────
        $standarPelayanan = $safe(StandarPelayanan::class, function() use ($like) {
            return StandarPelayanan::where('is_published', true)
                ->where(function ($query) use ($like) {
                    $query->where('judul', 'like', $like)
                          ->orWhere('deskripsi', 'like', $like);
                })->limit(5)->get();
        });

        foreach ($standarPelayanan as $sp) {
            $results[] = [
                'type'    => 'Standar Pelayanan',
                'icon'    => 'fas fa-clipboard-list',
                'title'   => $sp->judul,
                'excerpt' => $sp->deskripsi,
                'url'     => route('pelayanan.standar'),
                'date'    => null,
                'image'   => null,
            ];
        }

        // ── 14. Layanan Pengaduan ──────────────────────────────────
        $pengaduan = $safe(LayananPengaduan::class, function() use ($like) {
            return LayananPengaduan::where('is_published', true)
                ->where(function ($query) use ($like) {
                    $query->where('judul', 'like', $like)
                          ->orWhere('deskripsi', 'like', $like);
                })->limit(5)->get();
        });

        foreach ($pengaduan as $lp) {
            $results[] = [
                'type'    => 'Layanan Pengaduan',
                'icon'    => 'fas fa-exclamation-circle',
                'title'   => $lp->judul,
                'excerpt' => $lp->deskripsi,
                'url'     => route('pelayanan.pengaduan'),
                'date'    => null,
                'image'   => null,
            ];
        }

        // ── 15. Kompensasi Pelayanan ───────────────────────────────
        $kompensasi = $safe(KompensasiPelayanan::class, function() use ($like) {
            return KompensasiPelayanan::where('is_published', true)
                ->where(function ($query) use ($like) {
                    $query->where('judul', 'like', $like)
                          ->orWhere('deskripsi', 'like', $like);
                })->limit(5)->get();
        });

        foreach ($kompensasi as $kp) {
            $results[] = [
                'type'    => 'Kompensasi',
                'icon'    => 'fas fa-hand-holding-usd',
                'title'   => $kp->judul,
                'excerpt' => $kp->deskripsi,
                'url'     => route('pelayanan.kompensasi'),
                'date'    => null,
                'image'   => null,
            ];
        }

        // ── 16. Hasil Survei ───────────────────────────────────────
        $hasilSurvei = $safe(HasilSurvei::class, function() use ($like) {
            return HasilSurvei::where('is_published', true)
                ->where(function ($query) use ($like) {
                    $query->where('judul', 'like', $like)
                          ->orWhere('deskripsi', 'like', $like);
                })->limit(5)->get();
        });

        foreach ($hasilSurvei as $hs) {
            $results[] = [
                'type'    => 'Hasil Survei',
                'icon'    => 'fas fa-chart-bar',
                'title'   => $hs->judul,
                'excerpt' => $hs->deskripsi,
                'url'     => route('pelayanan.hasil-survei'),
                'date'    => null,
                'image'   => null,
            ];
        }

        // ── 17. Tutorial ───────────────────────────────────────────
        $tutorials = $safe(Tutorial::class, function() use ($like) {
            return Tutorial::where('judul', 'like', $like)->limit(5)->get();
        });

        foreach ($tutorials as $t) {
            $results[] = [
                'type'    => 'Tutorial',
                'icon'    => 'fas fa-play-circle',
                'title'   => $t->judul,
                'excerpt' => null,
                'url'     => $t->link ?: route('tutorial'),
                'date'    => null,
                'image'   => $t->gambar ? asset('storage/' . $t->gambar) : null,
            ];
        }

        // ── 18. Zona Integritas — Halaman ──────────────────────────
        $ziTypeRouteMap = [
            'zona_integritas' => 'zona.index',
            'pembangunan'     => 'zona.pembangunan',
            'pemantauan'      => 'zona.pemantauan',
        ];
        $ziPages = $safe(ZiPage::class, function() use ($like) {
            return ZiPage::where(function ($query) use ($like) {
                $query->where('judul', 'like', $like)
                      ->orWhere('konten', 'like', $like);
            })->limit(5)->get();
        });

        foreach ($ziPages as $zp) {
            $routeName = $ziTypeRouteMap[$zp->type] ?? 'zona.index';
            try {
                $url = route($routeName);
            } catch (\Throwable $e) {
                $url = route('zona.index');
            }
            $results[] = [
                'type'    => 'Zona Integritas',
                'icon'    => 'fas fa-award',
                'title'   => $zp->judul ?: 'Zona Integritas',
                'excerpt' => strip_tags($zp->konten),
                'url'     => $url,
                'date'    => null,
                'image'   => $zp->gambar ? asset('storage/' . $zp->gambar) : null,
            ];
        }

        // ── 19. Zona Integritas — Perancangan ──────────────────────
        $ziPosts = $safe(ZiPerancanganPost::class, function() use ($like) {
            return ZiPerancanganPost::where(function ($query) use ($like) {
                $query->where('judul', 'like', $like)
                      ->orWhere('konten', 'like', $like);
            })->limit(5)->get();
        });

        foreach ($ziPosts as $zp) {
            $results[] = [
                'type'    => 'ZI Perancangan',
                'icon'    => 'fas fa-drafting-compass',
                'title'   => $zp->judul ?: 'Perancangan ZI',
                'excerpt' => strip_tags($zp->konten),
                'url'     => route('zona.perancangan'),
                'date'    => null,
                'image'   => null,
            ];
        }

        // ── 20. Zona Integritas — Penetapan ────────────────────────
        $ziKategoris = $safe(ZiPenetapanKategori::class, function() use ($like) {
            return ZiPenetapanKategori::where('nama', 'like', $like)->limit(3)->get();
        });
        foreach ($ziKategoris as $zk) {
            $results[] = [
                'type'    => 'ZI Penetapan',
                'icon'    => 'fas fa-stamp',
                'title'   => $zk->nama,
                'excerpt' => null,
                'url'     => route('zona.penetapan'),
                'date'    => null,
                'image'   => null,
            ];
        }
        $ziItems = $safe(ZiPenetapanItem::class, function() use ($like) {
            return ZiPenetapanItem::where(function ($query) use ($like) {
                $query->where('judul', 'like', $like)
                      ->orWhere('konten', 'like', $like);
            })->limit(5)->get();
        });

        foreach ($ziItems as $zi) {
            $results[] = [
                'type'    => 'ZI Penetapan',
                'icon'    => 'fas fa-stamp',
                'title'   => $zi->judul,
                'excerpt' => strip_tags($zi->konten),
                'url'     => route('zona.penetapan'),
                'date'    => null,
                'image'   => null,
            ];
        }

        // Prepare terms for scoring/highlighting
        $terms = preg_split('/\s+/u', trim($q));

        foreach ($results as &$r) {
            $r['highlighted_title'] = $this->highlightText($r['title'] ?? '', $terms);
            $r['highlighted_excerpt'] = $this->highlightText($r['excerpt'] ?? '', $terms);
            $r['score'] = $this->computeScore($r, $terms);
        }
        unset($r);

        // Sort by score desc (higher relevance first). Keep stable ordering for equal scores.
        usort($results, function ($a, $b) {
            return ($b['score'] ?? 0) <=> ($a['score'] ?? 0);
        });

        // If request expects JSON (AJAX overlay), return structured JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'q' => $q,
                'total' => count($results),
                'results' => array_values($results),
            ]);
        }

        return view('public.search.results', [
            'q'       => $q,
            'results' => $results,
            'total'   => count($results),
        ]);
    }

    /**
     * Highlight occurrences of terms in given text and return escaped HTML.
     * Returns string containing <mark>...</mark> around matches.
     */
    private function highlightText(string $text, array $terms): string
    {
        $plain = strip_tags($text);
        if (trim($plain) === '') return '';

        $lower = mb_strtolower($plain);
        $ranges = [];

        foreach ($terms as $term) {
            $t = mb_strtolower(trim($term));
            if ($t === '') continue;
            $offset = 0;
            while (false !== ($pos = mb_stripos($lower, $t, $offset))) {
                $len = mb_strlen($t);
                $ranges[] = ['start' => $pos, 'length' => $len];
                $offset = $pos + $len;
            }
        }

        if (empty($ranges)) {
            return e($plain);
        }

        // Merge overlapping ranges
        usort($ranges, function ($a, $b) { return $a['start'] <=> $b['start']; });
        $merged = [];
        foreach ($ranges as $r) {
            if (empty($merged)) { $merged[] = $r; continue; }
            $last = &$merged[count($merged)-1];
            $lastEnd = $last['start'] + $last['length'];
            if ($r['start'] <= $lastEnd) {
                $newEnd = max($lastEnd, $r['start'] + $r['length']);
                $last['length'] = $newEnd - $last['start'];
            } else {
                $merged[] = $r;
            }
        }

        // Build highlighted string, escaping non-matched parts
        $out = '';
        $pos = 0;
        foreach ($merged as $m) {
            if ($m['start'] > $pos) {
                $out .= e(mb_substr($plain, $pos, $m['start'] - $pos));
            }
            $matchText = mb_substr($plain, $m['start'], $m['length']);
            $out .= '<mark>' . e($matchText) . '</mark>';
            $pos = $m['start'] + $m['length'];
        }
        if ($pos < mb_strlen($plain)) {
            $out .= e(mb_substr($plain, $pos));
        }

        return $out;
    }

    /**
     * Compute relevance score for an item given query terms.
     */
    private function computeScore(array $item, array $terms): int
    {
        $score = 0;
        $title = mb_strtolower($item['title'] ?? '');
        $excerpt = mb_strtolower(strip_tags($item['excerpt'] ?? ''));

        foreach ($terms as $t) {
            $term = mb_strtolower(trim($t));
            if ($term === '') continue;
            $score += substr_count($title, $term) * 5;   // title weight
            $score += substr_count($excerpt, $term) * 2; // excerpt weight
        }

        // boost by type priority (berita higher)
        $typePriority = [
            'Berita' => 10,
            'Berita Pelayanan' => 6,
            'E-Library' => 5,
        ];
        $score += $typePriority[$item['type']] ?? 0;

        return (int) $score;
    }
}
