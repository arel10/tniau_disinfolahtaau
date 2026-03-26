<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuUtama extends Model
{
    protected $table = 'menu_utama';

    protected $fillable = ['nama', 'route_name', 'sort_order'];

    /**
     * All available menu options (name => route_name)
     */
    public static array $options = [
        'Beranda'          => 'home',
        'Profil'           => 'profil.index',
        'Berita'           => 'berita.index',
        'Zona Integritas'  => 'zona.index',
        'PIA'              => 'pia',
        'e-Library'        => 'e-library',
        'Galeri'           => 'galeri.index',
        'Pelayanan Publik' => 'pelayanan.berita',
        'SP4N-Lapor!'      => 'sp4n-lapor',
        'Whistle Blowing'  => 'whistle-blowing',
        'Tutorial'         => 'tutorial',
        'Kontak'           => 'kontak.index',
        'Events'           => 'events.index',
    ];
}
