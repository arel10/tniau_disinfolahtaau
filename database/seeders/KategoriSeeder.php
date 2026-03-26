<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            [
                'nama_kategori' => 'Berita TNI AU',
                'slug' => 'berita-tni-au',
                'deskripsi' => 'Berita resmi dari TNI Angkatan Udara',
            ],
            [
                'nama_kategori' => 'Berita Disinfolahtaau',
                'slug' => 'berita-disinfolahtaau',
                'deskripsi' => 'Berita dari Dinas Informasi dan Pengolahan Data TNI AU',
            ],
            [
                'nama_kategori' => 'Berita Infolahta Jajaran',
                'slug' => 'berita-infolahta-jajaran',
                'deskripsi' => 'Berita dari Infolahta Jajaran TNI AU',
            ],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }

        echo "✓ Kategori seeder completed: " . count($kategoris) . " categories created\n";
    }
}
