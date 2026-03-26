<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomMenuWidget extends Model
{
    protected $fillable = ['menu_id', 'widget_type', 'text_content', 'position', 'is_active', 'settings'];

    protected $casts = [
        'is_active' => 'boolean',
        'settings'  => 'array',
    ];

    /**
     * Get a layout setting with a default fallback.
     */
    public function getSetting(string $key, $default = null)
    {
        return $this->settings[$key] ?? $default;
    }

    /**
     * Build inline CSS style string from layout settings.
     */
    public function getLayoutStyleAttribute(): string
    {
        $styles = [];
        $s = $this->settings ?? [];

        if (!empty($s['max_width'])) {
            $styles[] = 'max-width:' . $s['max_width'] . '%';
        }
        if (!empty($s['max_height']) && $s['max_height'] > 0) {
            $styles[] = 'max-height:' . $s['max_height'] . 'px';
        }
        if (!empty($s['font_size']) && $s['font_size'] > 0) {
            $styles[] = 'font-size:' . $s['font_size'] . 'px';
        }
        if (!empty($s['background_color'])) {
            $styles[] = 'background-color:' . $s['background_color'];
        }
        if (!empty($s['text_color'])) {
            $styles[] = 'color:' . $s['text_color'];
        }
        if (!empty($s['border_style']) && $s['border_style'] !== 'none') {
            $styles[] = 'border:1px ' . $s['border_style'] . ' #dee2e6';
        }
        if (!empty($s['border_radius'])) {
            $styles[] = 'border-radius:' . $s['border_radius'] . 'px';
        }
        if (!empty($s['shadow'])) {
            $shadowMap = [
                'small'  => '0 1px 3px rgba(0,0,0,0.12)',
                'medium' => '0 4px 12px rgba(0,0,0,0.15)',
                'large'  => '0 8px 24px rgba(0,0,0,0.20)',
            ];
            if (isset($shadowMap[$s['shadow']])) {
                $styles[] = 'box-shadow:' . $shadowMap[$s['shadow']];
            }
        }

        return implode(';', $styles);
    }

    /**
     * Get alignment CSS class.
     */
    public function getAlignClassAttribute(): string
    {
        $align = $this->settings['alignment'] ?? 'center';
        return match($align) {
            'left'   => 'text-start',
            'right'  => 'text-end',
            default  => 'text-center',
        };
    }

    /**
     * Get justify-content class for flex containers.
     */
    public function getJustifyClassAttribute(): string
    {
        $align = $this->settings['alignment'] ?? 'center';
        return match($align) {
            'left'   => 'justify-content-start',
            'right'  => 'justify-content-end',
            default  => 'justify-content-center',
        };
    }

    // Widget type labels
    public const TYPES = [
        // === Konten Teks ===
        'judul'           => ['label' => 'Judul',              'icon' => 'fas fa-heading',            'input' => 'text'],
        'deskripsi'       => ['label' => 'Deskripsi',          'icon' => 'fas fa-align-left',         'input' => 'textarea'],
        'kutipan'         => ['label' => 'Kutipan / Quote',    'icon' => 'fas fa-quote-left',         'input' => 'textarea'],
        'daftar'          => ['label' => 'Daftar / List',      'icon' => 'fas fa-list-ul',            'input' => 'textarea'],
        'html_kustom'     => ['label' => 'HTML Kustom',        'icon' => 'fas fa-code',               'input' => 'textarea'],
        'banner'          => ['label' => 'Banner Info',        'icon' => 'fas fa-bullhorn',           'input' => 'textarea'],
        'icon_teks'       => ['label' => 'Ikon + Teks',       'icon' => 'fas fa-icons',              'input' => 'text'],
        'nomor_statistik' => ['label' => 'Statistik Angka',    'icon' => 'fas fa-sort-numeric-up',    'input' => 'text'],
        'accordion'       => ['label' => 'Akordion',           'icon' => 'fas fa-layer-group',        'input' => 'textarea'],

        // === Media & File ===
        'foto'            => ['label' => 'Foto / Gambar',      'icon' => 'fas fa-images',             'input' => 'file'],
        'video'           => ['label' => 'Video',              'icon' => 'fas fa-video',              'input' => 'file'],
        'audio'           => ['label' => 'Audio',              'icon' => 'fas fa-music',              'input' => 'file'],
        'pdf'             => ['label' => 'Dokumen PDF',        'icon' => 'fas fa-file-pdf',           'input' => 'file'],
        'file_download'   => ['label' => 'File Unduhan',       'icon' => 'fas fa-download',           'input' => 'file'],
        'logo'            => ['label' => 'Logo',               'icon' => 'fas fa-image',              'input' => 'file'],

        // === Link & Embed ===
        'link_url'        => ['label' => 'Link URL',           'icon' => 'fas fa-link',               'input' => 'url'],
        'logo_link'       => ['label' => 'Link Logo/Foto',     'icon' => 'fas fa-external-link-alt',  'input' => 'url'],
        'tombol'          => ['label' => 'Tombol / Button',    'icon' => 'fas fa-mouse-pointer',      'input' => 'url'],
        'youtube'         => ['label' => 'Video YouTube',      'icon' => 'fab fa-youtube',            'input' => 'url'],
        'maps'            => ['label' => 'Google Maps Embed',  'icon' => 'fas fa-map',                'input' => 'url'],
        'instagram'       => ['label' => 'Instagram Embed',    'icon' => 'fab fa-instagram',          'input' => 'url'],
        'iframe'          => ['label' => 'Embed iFrame',       'icon' => 'fas fa-window-maximize',    'input' => 'url'],

        // === Data & Info ===
        'tanggal'         => ['label' => 'Tanggal',            'icon' => 'fas fa-calendar-alt',       'input' => 'date'],
        'lokasi'          => ['label' => 'Lokasi',             'icon' => 'fas fa-map-marker-alt',     'input' => 'text'],
        'email'           => ['label' => 'Email',              'icon' => 'fas fa-envelope',           'input' => 'email'],
        'no_hp'           => ['label' => 'No. HP',             'icon' => 'fas fa-phone',              'input' => 'tel'],

        // === Layout & Pemisah ===
        'separator'          => ['label' => 'Garis Pemisah',      'icon' => 'fas fa-minus',              'input' => 'none'],
        'spacer'             => ['label' => 'Jarak Kosong',        'icon' => 'fas fa-arrows-alt-v',       'input' => 'number'],

        // === Fitur Halaman ===
        'teks_berjalan'      => ['label' => 'Teks Berjalan',        'icon' => 'fas fa-arrows-alt-h',       'input' => 'ticker'],
        'tab_frame'          => ['label' => 'Frame Tab (Tab)',     'icon' => 'fas fa-folder-open',        'input' => 'tabs'],
        'berita_lokal'       => ['label' => 'Berita Halaman Ini',  'icon' => 'fas fa-newspaper',          'input' => 'berita'],
        'galeri_foto_lokal'  => ['label' => 'Galeri Foto',         'icon' => 'fas fa-images',             'input' => 'file'],
        'galeri_video_lokal' => ['label' => 'Galeri Video',        'icon' => 'fas fa-film',               'input' => 'file'],
        'video_url'          => ['label' => 'Video URL (Link)',     'icon' => 'fas fa-play-circle',        'input' => 'url'],
        'gambar_sidebar'     => ['label' => 'Gambar Sidebar',      'icon' => 'fas fa-columns',            'input' => 'file'],
    ];

    public function menu()
    {
        return $this->belongsTo(CustomMenu::class, 'menu_id');
    }

    public function media()
    {
        return $this->hasMany(CustomMenuMedia::class, 'widget_id')->orderBy('position');
    }

    public function getTypeInfoAttribute()
    {
        return self::TYPES[$this->widget_type] ?? ['label' => $this->widget_type, 'icon' => 'fas fa-puzzle-piece', 'input' => 'text'];
    }
}
