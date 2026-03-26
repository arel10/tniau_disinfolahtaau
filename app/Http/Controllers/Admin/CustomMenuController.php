<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomMenu;
use App\Models\CustomMenuWidget;
use App\Models\CustomMenuMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomMenuController extends Controller
{
    /**
     * List all custom menus + form to add new.
     */
    public function index()
    {
        $menus = CustomMenu::topLevel()->ordered()->with('children')->get();
        return view('admin.custom-menu.index', compact('menus'));
    }

    /**
     * Store a new custom menu.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:100',
            'parent_id'      => 'nullable|exists:custom_menus,id',
            'builtin_parent' => 'nullable|string|max:50',
        ]);

        // If builtin_parent is set, parent_id must be null
        $builtinParent = $request->input('builtin_parent') ?: null;
        $parentId      = $builtinParent ? null : ($request->parent_id ?: null);

        if ($request->filled('position')) {
            // Shift existing siblings to make room
            $pos = (int) $request->position;
            CustomMenu::where('parent_id', $parentId)
                ->where('builtin_parent', $builtinParent)
                ->where('position', '>=', $pos)
                ->increment('position');
        } else {
            $pos = (CustomMenu::where('parent_id', $parentId)
                ->where('builtin_parent', $builtinParent)
                ->max('position') ?? -1) + 1;
        }

        CustomMenu::create([
            'name'           => $request->name,
            'parent_id'      => $parentId,
            'builtin_parent' => $builtinParent,
            'icon'           => $request->icon ?: 'fas fa-file-alt',
            'position'       => $pos,
        ]);

        return redirect()->route('admin.custom-menu.index')->with('success', __('messages.flash_created', ['item' => 'Menu']));
    }

    /**
     * Edit menu page — widget builder.
     */
    public function edit($id)
    {
        $menu = CustomMenu::with(['widgets.media', 'parent', 'children'])->findOrFail($id);
        $widgetTypes = CustomMenuWidget::TYPES;
        $pageTemplates = self::getPageTemplates();
        return view('admin.custom-menu.edit', compact('menu', 'widgetTypes', 'pageTemplates'));
    }

    /**
     * Update menu info.
     */
    public function update(Request $request, $id)
    {
        $menu = CustomMenu::findOrFail($id);
        $request->validate([
            'name'      => 'required|string|max:100',
            'parent_id' => 'nullable|exists:custom_menus,id',
            'icon'      => 'nullable|string|max:100',
        ]);

        // Prevent self-parent or child as parent
        $parentId = $request->parent_id ?: null;
        if ($parentId == $id) $parentId = null;

        $menu->update([
            'name'      => $request->name,
            'slug'      => Str::slug($request->name),
            'parent_id' => $parentId,
            'icon'      => $request->icon ?: 'fas fa-file-alt',
        ]);

        return redirect()->route('admin.custom-menu.edit', $id)->with('success', __('messages.flash_updated', ['item' => 'Menu']));
    }

    /**
     * Delete a menu and all its children/widgets/media.
     */
    public function destroy($id)
    {
        $menu = CustomMenu::with(['widgets.media', 'children.widgets.media'])->findOrFail($id);

        // Delete all media files for this menu's widgets (eager loaded - no N+1)
        foreach ($menu->widgets as $widget) {
            foreach ($widget->media as $m) {
                Storage::disk('public')->delete($m->file_path);
            }
        }
        // Also delete children's media (eager loaded - no N+1)
        foreach ($menu->children as $child) {
            foreach ($child->widgets as $widget) {
                foreach ($widget->media as $m) {
                    Storage::disk('public')->delete($m->file_path);
                }
            }
        }

        $menu->delete(); // Cascades to children, widgets, media
        return redirect()->route('admin.custom-menu.index')->with('success', __('messages.flash_deleted', ['item' => 'Menu']));
    }

    /**
     * Toggle publish status.
     */
    public function togglePublish($id)
    {
        $menu = CustomMenu::findOrFail($id);
        $menu->update(['is_published' => !$menu->is_published]);
        return back()->with('success', __('messages.flash_status_changed'));
    }

    /**
     * Reorder menus via AJAX.
     * Accepts: { type: 'top'|'sub', parent_id: null|int, order: [id, id, ...] }
     */
    public function reorder(Request $request)
    {
        $order = $request->input('order', []);
        if (!empty($order)) {
            $cases = [];
            $ids = [];
            foreach ($order as $i => $id) {
                $cases[] = "WHEN id = " . (int)$id . " THEN " . (int)$i;
                $ids[] = (int)$id;
            }
            CustomMenu::whereIn('id', $ids)
                ->update(['position' => DB::raw('CASE ' . implode(' ', $cases) . ' END')]);
        }
        return response()->json(['success' => true]);
    }

    // ===================== WIDGET MANAGEMENT =====================

    /**
     * Add a widget to a menu.
     */
    public function addWidget(Request $request, $menuId)
    {
        $menu = CustomMenu::findOrFail($menuId);
        $request->validate([
            'widget_type' => 'required|string|in:' . implode(',', array_keys(CustomMenuWidget::TYPES)),
        ]);

        // Append at the bottom (after the last existing widget)
        $maxPos = $menu->widgets()->max('position') ?? -1;

        $menu->widgets()->create([
            'widget_type' => $request->widget_type,
            'position'    => $maxPos + 1,
        ]);

        return redirect()->route('admin.custom-menu.edit', $menuId)->with('success', __('messages.flash_created', ['item' => __('messages.admin_widget')]));
    }

    /**
     * Save all widgets content.
     */
    public function saveWidgets(Request $request, $menuId)
    {
        $menu = CustomMenu::findOrFail($menuId);

        $widgetData = $request->input('widgets', []);
        $widgetFiles = $request->file('widget_files', []);
        $widgetFilesVideo = $request->file('widget_files_video', []);
        $widgetTabFiles = $request->file('widget_tab_files', []);
        $widgetTabPdf   = $request->file('widget_tab_pdf',   []);
        $deleteMedia = $request->input('delete_media', []);
        $positions = $request->input('widget_positions', []);

        // Delete selected media
        foreach ($deleteMedia as $mediaId) {
            $media = CustomMenuMedia::find($mediaId);
            if ($media) {
                Storage::disk('public')->delete($media->file_path);
                $media->delete();
            }
        }

        // Update each widget
        foreach ($widgetData as $widgetId => $data) {
            $widget = CustomMenuWidget::where('id', $widgetId)->where('menu_id', $menuId)->first();
            if (!$widget) continue;

            // Parse layout settings
            $settings = $data['settings'] ?? [];
            $cleanSettings = [];
            if (!empty($settings['max_width']))        $cleanSettings['max_width']        = (int) $settings['max_width'];
            if (!empty($settings['max_height']))       $cleanSettings['max_height']       = (int) $settings['max_height'];
            if (!empty($settings['alignment']))        $cleanSettings['alignment']        = $settings['alignment'];
            if (!empty($settings['font_size']))        $cleanSettings['font_size']        = (int) $settings['font_size'];
            if (isset($settings['padding']))           $cleanSettings['padding']          = $settings['padding'];
            if (!empty($settings['background_color'])) $cleanSettings['background_color'] = $settings['background_color'];
            if (!empty($settings['text_color']))       $cleanSettings['text_color']       = $settings['text_color'];
            if (!empty($settings['border_style']) && $settings['border_style'] !== 'none')
                                                       $cleanSettings['border_style']     = $settings['border_style'];
            if (!empty($settings['border_radius']))    $cleanSettings['border_radius']    = (int) $settings['border_radius'];
            if (!empty($settings['shadow']))           $cleanSettings['shadow']           = $settings['shadow'];

            // Extra fields for special widget types
            if (!empty($data['extra_label'])) {
                if ($widget->widget_type === 'tombol') {
                    $cleanSettings['button_label'] = $data['extra_label'];
                } elseif ($widget->widget_type === 'nomor_statistik') {
                    $cleanSettings['stat_label'] = $data['extra_label'];
                }
            }
            if (!empty($data['extra_icon'])) {
                $cleanSettings['icon_class'] = $data['extra_icon'];
            }
            // Ticker widget settings
            if (!empty($data['extra_ticker_speed'])) $cleanSettings['ticker_speed'] = $data['extra_ticker_speed'];
            if (!empty($data['extra_ticker_bg']))    $cleanSettings['ticker_bg']    = $data['extra_ticker_bg'];
            if (!empty($data['extra_ticker_color'])) $cleanSettings['ticker_color'] = $data['extra_ticker_color'];
            // Galeri foto settings
            if (isset($data['extra_galeri_title']))       $cleanSettings['galeri_title']     = $data['extra_galeri_title'];
            if (!empty($data['extra_galeri_mode']))        $cleanSettings['galeri_mode']      = $data['extra_galeri_mode'];
            if (!empty($data['extra_galeri_orient']))      $cleanSettings['galeri_orient']    = $data['extra_galeri_orient'];
            if (!empty($data['extra_galeri_title_pos']))   $cleanSettings['galeri_title_pos'] = $data['extra_galeri_title_pos'];
            // Galeri video URL
            if (isset($data['extra_video_url']))           $cleanSettings['video_url']        = $data['extra_video_url'];
            // Gambar sidebar settings
            if (!empty($data['extra_sidebar_position']))    $cleanSettings['sidebar_position']  = $data['extra_sidebar_position'];
            if (isset($data['extra_remove_background']))    $cleanSettings['remove_background'] = (bool) $data['extra_remove_background'];

            // Update text content + settings
            $widget->update([
                'text_content' => $data['text_content'] ?? null,
                'position'     => $positions[$widgetId] ?? $widget->position,
                'settings'     => !empty($cleanSettings) ? $cleanSettings : null,
            ]);

            // Upload files
            if (isset($widgetFiles[$widgetId])) {
                $typeMap = [
                    'foto'               => 'image',
                    'video'              => 'video',
                    'pdf'                => 'pdf',
                    'logo'               => 'logo',
                    'audio'              => 'audio',
                    'file_download'      => 'file',
                    'galeri_foto_lokal'  => 'image',
                    'galeri_video_lokal' => 'video',
                    'berita_lokal'       => 'image',
                    'gambar_sidebar'     => 'image',
                ];
                // berita_lokal accepts both image and video — detect by MIME
                if ($widget->widget_type === 'berita_lokal') {
                    $firstFile = is_array($widgetFiles[$widgetId]) ? reset($widgetFiles[$widgetId]) : $widgetFiles[$widgetId];
                    $mime = $firstFile instanceof \Illuminate\Http\UploadedFile ? $firstFile->getMimeType() : '';
                    $mediaType = str_starts_with($mime, 'video/') ? 'video' : 'image';
                } else {
                    $mediaType = $typeMap[$widget->widget_type] ?? 'image';
                }
                $files = is_array($widgetFiles[$widgetId]) ? $widgetFiles[$widgetId] : [$widgetFiles[$widgetId]];

                // For logo, replace existing
                if ($widget->widget_type === 'logo') {
                    foreach ($widget->media as $old) {
                        Storage::disk('public')->delete($old->file_path);
                        $old->delete();
                    }
                }

                $maxMediaPos = $widget->media()->max('position') ?? -1;
                foreach ($files as $file) {
                    $path = $file->store('custom-menu/' . $menuId, 'public');
                    $widget->media()->create([
                        'file_path'     => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'media_type'    => $mediaType,
                        'position'      => ++$maxMediaPos,
                    ]);
                }
            }

            // Upload video files for berita_lokal (separate field: widget_files_video)
            if (isset($widgetFilesVideo[$widgetId])) {
                $videoFiles = is_array($widgetFilesVideo[$widgetId]) ? $widgetFilesVideo[$widgetId] : [$widgetFilesVideo[$widgetId]];
                $maxMediaPos = $widget->media()->max('position') ?? -1;
                foreach ($videoFiles as $vFile) {
                    if (!$vFile instanceof \Illuminate\Http\UploadedFile) continue;
                    $path = $vFile->store('custom-menu/' . $menuId, 'public');
                    $widget->media()->create([
                        'file_path'     => $path,
                        'original_name' => $vFile->getClientOriginalName(),
                        'media_type'    => 'video',
                        'position'      => ++$maxMediaPos,
                    ]);
                }
            }

            // Upload per-tab photos for tab_frame widgets
            // Position stored as tab_index * 1000 + file_order to allow grouping by tab
            if ($widget->widget_type === 'tab_frame' && isset($widgetTabFiles[$widgetId])) {
                foreach ($widgetTabFiles[$widgetId] as $tabIdx => $tabFiles) {
                    $tabFiles = is_array($tabFiles) ? $tabFiles : [$tabFiles];
                    $fileOrder = 0;
                    foreach ($tabFiles as $tFile) {
                        if (!$tFile instanceof \Illuminate\Http\UploadedFile) continue;
                        $path = $tFile->store('custom-menu/' . $menuId, 'public');
                        $widget->media()->create([
                            'file_path'     => $path,
                            'original_name' => $tFile->getClientOriginalName(),
                            'media_type'    => 'image',
                            'position'      => (int)$tabIdx * 1000 + $fileOrder++,
                        ]);
                    }
                }
            }

            // Upload per-tab PDF for tab_frame widgets
            if ($widget->widget_type === 'tab_frame' && isset($widgetTabPdf[$widgetId])) {
                foreach ($widgetTabPdf[$widgetId] as $tabIdx => $tabPdfs) {
                    $tabPdfs = is_array($tabPdfs) ? $tabPdfs : [$tabPdfs];
                    $fileOrder = 0;
                    foreach ($tabPdfs as $pFile) {
                        if (!$pFile instanceof \Illuminate\Http\UploadedFile) continue;
                        $path = $pFile->store('custom-menu/' . $menuId, 'public');
                        $widget->media()->create([
                            'file_path'     => $path,
                            'original_name' => $pFile->getClientOriginalName(),
                            'media_type'    => 'pdf',
                            'position'      => (int)$tabIdx * 1000 + $fileOrder++,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.custom-menu.edit', $menuId)->with('success', __('messages.flash_saved'));
    }

    /**
     * Remove a widget.
     */
    public function removeWidget($menuId, $widgetId)
    {
        $widget = CustomMenuWidget::where('id', $widgetId)->where('menu_id', $menuId)->firstOrFail();

        foreach ($widget->media as $m) {
            Storage::disk('public')->delete($m->file_path);
        }
        $widget->delete();

        return redirect()->route('admin.custom-menu.edit', $menuId)->with('success', __('messages.flash_deleted', ['item' => __('messages.admin_widget')]));
    }

    /**
     * Reorder widgets via AJAX.
     */
    public function reorderWidgets(Request $request, $menuId)
    {
        $order = $request->input('order', []);
        if (!empty($order)) {
            $cases = [];
            $ids = [];
            foreach ($order as $i => $id) {
                $cases[] = "WHEN id = " . (int)$id . " THEN " . (int)$i;
                $ids[] = (int)$id;
            }
            CustomMenuWidget::whereIn('id', $ids)
                ->where('menu_id', $menuId)
                ->update(['position' => DB::raw('CASE ' . implode(' ', $cases) . ' END')]);
        }
        return response()->json(['success' => true]);
    }

    /**
     * Apply a pre-made page template to a menu (bulk-add widgets).
     */
    public function applyTemplate(Request $request, $menuId)
    {
        $menu = CustomMenu::findOrFail($menuId);
        $templateKey = $request->input('template');
        $templates = self::getPageTemplates();

        if (!isset($templates[$templateKey])) {
            return redirect()->back()->with('error', __('messages.flash_not_found'));
        }

        // Optionally replace all existing widgets
        if ($request->boolean('replace_all')) {
            foreach ($menu->widgets as $w) {
                foreach ($w->media as $m) {
                    Storage::disk('public')->delete($m->file_path);
                }
                $w->delete();
            }
        }

        // Insert new widgets at TOP — shift existing widgets down first
        $newWidgetCount = count($templates[$templateKey]['widgets']);
        if (!$request->boolean('replace_all')) {
            $menu->widgets()->increment('position', $newWidgetCount);
        }
        foreach ($templates[$templateKey]['widgets'] as $i => $wDef) {
            $menu->widgets()->create([
                'widget_type'  => $wDef['type'],
                'text_content' => $wDef['text'] ?? null,
                'settings'     => isset($wDef['settings']) ? $wDef['settings'] : null,
                'position'     => $i,
                'is_active'    => true,
            ]);
        }

        return redirect()->route('admin.custom-menu.edit', $menuId)
            ->with('success', 'Template "' . $templates[$templateKey]['name'] . '" berhasil diterapkan!');
    }

    /**
     * All available page templates.
     */
    public static function getPageTemplates(): array
    {
        return [
            'profil' => [
                'name'     => 'Profil Organisasi',
                'icon'     => 'fas fa-id-card',
                'color'    => '#667eea',
                'category' => 'Informasi',
                'desc'     => 'Judul + Logo + Deskripsi — cocok untuk halaman profil & tentang kami.',
                'widgets'  => [
                    ['type' => 'judul',     'text' => 'Profil Organisasi'],
                    ['type' => 'logo'],
                    ['type' => 'separator'],
                    ['type' => 'deskripsi', 'text' => 'Tuliskan deskripsi singkat organisasi Anda di sini...'],
                ],
            ],
            'tab_navigasi' => [
                'name'     => 'Tab Navigasi',
                'icon'     => 'fas fa-folder-open',
                'color'    => '#764ba2',
                'category' => 'Layout',
                'desc'     => 'Judul + Frame Tab — seperti: The Winner | Sejarah | Visi dan Misi | Program Kerja | Organisasi | Susunan Pengurus.',
                'widgets'  => [
                    ['type' => 'judul',     'text' => 'Tentang Kami'],
                    ['type' => 'tab_frame', 'text' => "The Winner | Tuliskan konten tab The Winner di sini...\nSejarah | Tuliskan sejarah organisasi Anda di sini...\nVisi dan Misi | Tuliskan visi dan misi organisasi...\nProgram Kerja | Tuliskan program kerja periode ini...\nOrganisasi | Tuliskan informasi struktur organisasi...\nSusunan Pengurus | Tuliskan susunan pengurus periode ini..."],
                ],
            ],
            'tab_foto' => [
                'name'     => 'Tab + Foto',
                'icon'     => 'fas fa-images',
                'color'    => '#5c35b4',
                'category' => 'Media',
                'desc'     => 'Judul + Foto Utama + Tab Navigasi — cocok untuk halaman The Winner, Profil, atau halaman dengan foto unggulan dan konten bertab.',
                'widgets'  => [
                    ['type' => 'judul',     'text' => 'The Winner'],
                    ['type' => 'foto'],
                    ['type' => 'tab_frame', 'text' => "The Winner | Tuliskan keterangan foto The Winner di sini...\nSejarah | Tuliskan sejarah di sini...\nVisi dan Misi | Tuliskan visi dan misi di sini...\nProgram Kerja | Tuliskan program kerja di sini...\nOrganisasi | Tuliskan informasi organisasi di sini...\nSusunan Pengurus | Tuliskan susunan pengurus di sini..."],
                ],
            ],
            'galeri_foto' => [
                'name'     => 'Galeri Foto',
                'icon'     => 'fas fa-images',
                'color'    => '#11998e',
                'category' => 'Media',
                'desc'     => 'Judul + Deskripsi + Galeri Foto — upload banyak foto sekaligus.',
                'widgets'  => [
                    ['type' => 'judul',              'text' => 'Galeri Foto'],
                    ['type' => 'deskripsi',           'text' => 'Kumpulan dokumentasi foto kegiatan kami.'],
                    ['type' => 'galeri_foto_lokal'],
                ],
            ],
            'galeri_video' => [
                'name'     => 'Galeri Video',
                'icon'     => 'fas fa-film',
                'color'    => '#e53935',
                'category' => 'Media',
                'desc'     => 'Judul + Deskripsi + Galeri Video — upload banyak video.',
                'widgets'  => [
                    ['type' => 'judul',              'text' => 'Galeri Video'],
                    ['type' => 'deskripsi',           'text' => 'Kumpulan video dokumentasi kegiatan kami.'],
                    ['type' => 'galeri_video_lokal'],
                ],
            ],
            'berita_beranda' => [
                'name'     => 'Berita Kartu (Yang Terlewat)',
                'icon'     => 'fas fa-th-large',
                'color'    => '#0066cc',
                'category' => 'Konten',
                'desc'     => 'Kumpulan artikel berita yang tampil sebagai kartu geser (carousel) — seperti bagian "Yang Terlewat" di beranda. Setiap Judul menjadi satu kartu.',
                'widgets'  => [
                    ['type' => 'judul',     'text' => 'Berita Pertama'],
                    ['type' => 'tanggal',   'text' => date('Y-m-d')],
                    ['type' => 'foto'],
                    ['type' => 'deskripsi', 'text' => 'Tuliskan ringkasan berita pertama di sini...'],
                    ['type' => 'separator'],
                    ['type' => 'judul',     'text' => 'Berita Kedua'],
                    ['type' => 'tanggal',   'text' => date('Y-m-d')],
                    ['type' => 'foto'],
                    ['type' => 'deskripsi', 'text' => 'Tuliskan ringkasan berita kedua di sini...'],
                    ['type' => 'separator'],
                    ['type' => 'judul',     'text' => 'Berita Ketiga'],
                    ['type' => 'tanggal',   'text' => date('Y-m-d')],
                    ['type' => 'foto'],
                    ['type' => 'deskripsi', 'text' => 'Tuliskan ringkasan berita ketiga di sini...'],
                    ['type' => 'separator'],
                ],
            ],
            'berita_halaman' => [
                'name'     => 'Berita Halaman',
                'icon'     => 'fas fa-newspaper',
                'color'    => '#f4511e',
                'category' => 'Konten',
                'desc'     => 'Teks Berjalan + Judul + Berita Lokal — halaman berita khusus tanpa keluar ke modul Berita.',
                'widgets'  => [
                    ['type' => 'teks_berjalan', 'text' => 'Selamat datang! Simak berita terbaru dari kami.'],
                    ['type' => 'judul',         'text' => 'Berita Terkini'],
                    ['type' => 'berita_lokal',  'text' => "Judul Berita Pertama | Isi ringkas berita pertama di sini...\n---\nJudul Berita Kedua | Isi ringkas berita kedua di sini..."],
                ],
            ],
            'foto_deskripsi' => [
                'name'     => 'Foto + Deskripsi',
                'icon'     => 'fas fa-photo-video',
                'color'    => '#00897b',
                'category' => 'Media',
                'desc'     => 'Pasangan Foto + Deskripsi berulang — cocok untuk artikel bergambar, laporan kegiatan, atau berita panjang dengan ilustrasi.',
                'widgets'  => [
                    ['type' => 'judul',     'text' => 'Judul Artikel'],
                    ['type' => 'foto'],
                    ['type' => 'deskripsi', 'text' => 'Tuliskan deskripsi untuk foto pertama di sini...'],
                    ['type' => 'separator'],
                    ['type' => 'foto'],
                    ['type' => 'deskripsi', 'text' => 'Tuliskan deskripsi untuk foto kedua di sini...'],
                    ['type' => 'separator'],
                    ['type' => 'foto'],
                    ['type' => 'deskripsi', 'text' => 'Tuliskan deskripsi untuk foto ketiga di sini...'],
                ],
            ],
            'acara_event' => [
                'name'     => 'Acara / Event',
                'icon'     => 'fas fa-calendar-alt',
                'color'    => '#fb8c00',
                'category' => 'Informasi',
                'desc'     => 'Judul + Tanggal + Lokasi + Foto + Deskripsi — halaman detail acara.',
                'widgets'  => [
                    ['type' => 'judul',    'text' => 'Nama Acara'],
                    ['type' => 'tanggal',  'text' => date('Y-m-d')],
                    ['type' => 'lokasi',   'text' => 'Nama Tempat, Kota'],
                    ['type' => 'foto'],
                    ['type' => 'separator'],
                    ['type' => 'deskripsi', 'text' => 'Tuliskan deskripsi lengkap acara di sini...'],
                ],
            ],
            'kontak_info' => [
                'name'     => 'Kontak & Info',
                'icon'     => 'fas fa-address-book',
                'color'    => '#00897b',
                'category' => 'Informasi',
                'desc'     => 'Judul + Deskripsi + Email + No HP + Lokasi + Maps.',
                'widgets'  => [
                    ['type' => 'judul',     'text' => 'Hubungi Kami'],
                    ['type' => 'deskripsi', 'text' => 'Kami siap melayani pertanyaan dan masukan Anda.'],
                    ['type' => 'separator'],
                    ['type' => 'email',     'text' => 'info@organisasi.mil.id'],
                    ['type' => 'no_hp',     'text' => '+62 21 0000 0000'],
                    ['type' => 'lokasi',    'text' => 'Jl. Nama Jalan No. 1, Jakarta'],
                    ['type' => 'maps',      'text' => 'https://www.google.com/maps/embed?...'],
                ],
            ],
            'artikel_berita' => [
                'name'     => 'Artikel Berita',
                'icon'     => 'fas fa-newspaper',
                'color'    => '#e53935',
                'category' => 'Konten',
                'desc'     => 'Template satu artikel berita: Judul + Tanggal + Foto + Deskripsi + Separator. Tambahkan template ini berulang kali untuk membuat grid kartu berita otomatis.',
                'widgets'  => [
                    ['type' => 'judul',     'text' => 'Judul Artikel Berita'],
                    ['type' => 'tanggal',   'text' => date('Y-m-d')],
                    ['type' => 'foto'],
                    ['type' => 'deskripsi', 'text' => 'Tuliskan isi artikel berita di sini...'],
                    ['type' => 'separator'],
                ],
            ],
            'pengumuman' => [
                'name'     => 'Pengumuman',
                'icon'     => 'fas fa-bullhorn',
                'color'    => '#1565c0',
                'category' => 'Informasi',
                'desc'     => 'Teks Berjalan + Banner + Tanggal + Deskripsi + File Download.',
                'widgets'  => [
                    ['type' => 'teks_berjalan', 'text' => 'Pengumuman Penting — Harap dibaca dengan seksama!'],
                    ['type' => 'banner',        'text' => 'Perhatian: Pengumuman resmi dari pimpinan.'],
                    ['type' => 'tanggal',        'text' => date('Y-m-d')],
                    ['type' => 'separator'],
                    ['type' => 'deskripsi',      'text' => 'Tuliskan isi pengumuman lengkap di sini...'],
                    ['type' => 'file_download'],
                ],
            ],
            'dokumen_elibrary' => [
                'name'     => 'Dokumen / e-Library',
                'icon'     => 'fas fa-folder-open',
                'color'    => '#5c6bc0',
                'category' => 'Konten',
                'desc'     => 'Judul + Deskripsi + PDF Viewer + File Download — halaman koleksi dokumen.',
                'widgets'  => [
                    ['type' => 'judul',         'text' => 'Koleksi Dokumen'],
                    ['type' => 'deskripsi',      'text' => 'Unduh atau baca dokumen resmi yang tersedia.'],
                    ['type' => 'separator'],
                    ['type' => 'pdf'],
                    ['type' => 'file_download'],
                ],
            ],
            'visi_misi' => [
                'name'     => 'Visi & Misi',
                'icon'     => 'fas fa-eye',
                'color'    => '#039be5',
                'category' => 'Informasi',
                'desc'     => 'Judul + Kutipan Visi + Separator + Daftar Misi.',
                'widgets'  => [
                    ['type' => 'judul',    'text' => 'Visi & Misi'],
                    ['type' => 'judul',    'text' => 'Visi'],
                    ['type' => 'kutipan',  'text' => 'Tuliskan visi organisasi Anda di sini...'],
                    ['type' => 'separator'],
                    ['type' => 'judul',    'text' => 'Misi'],
                    ['type' => 'daftar',   'text' => "Misi pertama organisasi\nMisi kedua organisasi\nMisi ketiga organisasi"],
                ],
            ],
            'faq_accordion' => [
                'name'     => 'FAQ / Akordion',
                'icon'     => 'fas fa-question-circle',
                'color'    => '#7b1fa2',
                'category' => 'Konten',
                'desc'     => 'Judul + Deskripsi + Accordion — cocok untuk halaman FAQ atau tanya-jawab.',
                'widgets'  => [
                    ['type' => 'judul',     'text' => 'Pertanyaan yang Sering Ditanyakan'],
                    ['type' => 'deskripsi', 'text' => 'Temukan jawaban atas pertanyaan umum kami.'],
                    ['type' => 'accordion', 'text' => "Apa itu organisasi ini? | Organisasi kami adalah...\nBagaimana cara mendaftar? | Untuk mendaftar...\nSiapa yang bisa bergabung? | Siapapun yang memenuhi syarat..."],
                ],
            ],
            'landing_page' => [
                'name'     => 'Landing / Selamat Datang',
                'icon'     => 'fas fa-home',
                'color'    => '#43a047',
                'category' => 'Layout',
                'desc'     => 'Teks Berjalan + Judul + Foto + Deskripsi + Tombol — halaman sambutan/landing.',
                'widgets'  => [
                    ['type' => 'teks_berjalan', 'text' => 'Selamat datang di halaman kami!'],
                    ['type' => 'judul',         'text' => 'Selamat Datang'],
                    ['type' => 'foto'],
                    ['type' => 'deskripsi',     'text' => 'Tuliskan pesan sambutan atau deskripsi singkat halaman ini...'],
                    ['type' => 'tombol',        'text' => '#', 'settings' => ['button_label' => 'Pelajari Lebih Lanjut']],
                ],
            ],
            'statistik' => [
                'name'     => 'Statistik & Angka',
                'icon'     => 'fas fa-chart-bar',
                'color'    => '#00acc1',
                'category' => 'Konten',
                'desc'     => 'Judul + 3 Nomor Statistik + Deskripsi — tampilkan angka pencapaian.',
                'widgets'  => [
                    ['type' => 'judul',           'text' => 'Pencapaian Kami'],
                    ['type' => 'nomor_statistik', 'text' => '100+', 'settings' => ['stat_label' => 'Anggota Aktif']],
                    ['type' => 'nomor_statistik', 'text' => '50+',  'settings' => ['stat_label' => 'Kegiatan Tahunan']],
                    ['type' => 'nomor_statistik', 'text' => '10+',  'settings' => ['stat_label' => 'Tahun Berdiri']],
                    ['type' => 'separator'],
                    ['type' => 'deskripsi', 'text' => 'Kami terus berkembang dengan dukungan seluruh anggota.'],
                ],
            ],
            'strukt_org' => [
                'name'     => 'Struktur Organisasi',
                'icon'     => 'fas fa-sitemap',
                'color'    => '#6d4c41',
                'category' => 'Informasi',
                'desc'     => 'Judul + Foto Bagan + Deskripsi + Daftar Nama Pengurus.',
                'widgets'  => [
                    ['type' => 'judul',    'text' => 'Struktur Organisasi'],
                    ['type' => 'foto'],
                    ['type' => 'separator'],
                    ['type' => 'judul',   'text' => 'Susunan Pengurus'],
                    ['type' => 'daftar',  'text' => "Ketua Umum: Nama\nWakil Ketua: Nama\nSekretaris: Nama\nBendahara: Nama"],
                ],
            ],
        ];
    }
}
