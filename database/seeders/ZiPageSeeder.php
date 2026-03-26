<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ZiPage;
use App\Models\ZiPenetapanKategori;

class ZiPageSeeder extends Seeder
{
    public function run(): void
    {
        // Halaman-halaman Zona Integritas
        $pages = [
            [
                'type' => 'zona_integritas',
                'judul' => 'Zona Integritas',
                'konten' => '<p>Zona Integritas (ZI) adalah predikat yang diberikan kepada instansi pemerintah yang pimpinan dan jajarannya mempunyai komitmen untuk mewujudkan WBK/WBBM melalui reformasi birokrasi, khususnya dalam hal pencegahan korupsi dan peningkatan kualitas pelayanan publik.</p>',
                'gambar' => null,
            ],
            [
                'type' => 'pembangunan',
                'judul' => 'Pembangunan Zona Integritas',
                'konten' => '<p>Pembangunan Zona Integritas di lingkungan Disinfolahtaau TNI AU dilaksanakan melalui program-program reformasi birokrasi yang terstruktur dan terukur.</p>',
                'gambar' => null,
            ],
            [
                'type' => 'pemantauan',
                'judul' => 'Pemantauan Zona Integritas',
                'konten' => '<p>Pemantauan dilakukan secara berkala untuk memastikan komitmen dan implementasi Zona Integritas berjalan sesuai dengan target yang telah ditetapkan.</p>',
                'gambar' => null,
            ],
        ];

        foreach ($pages as $page) {
            ZiPage::updateOrCreate(
                ['type' => $page['type']],
                $page
            );
        }

        // Kategori Penetapan ZI
        $kategoris = [
            ['nama' => 'Pengungkit', 'slug' => 'pengungkit'],
            ['nama' => 'Hasil', 'slug' => 'hasil'],
        ];

        foreach ($kategoris as $kat) {
            ZiPenetapanKategori::updateOrCreate(
                ['slug' => $kat['slug']],
                $kat
            );
        }

        echo "✓ ZiPage seeder completed: " . count($pages) . " pages + " . count($kategoris) . " penetapan kategoris created\n";
    }
}
