<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriGaleri;
use Illuminate\Support\Str;

class KategoriGaleriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoriList = [
            'Kadisinfolahta',
            'Sesdisinfolahta',
            'Kasubdissidukops',
            'Kasubdissidukpers',
            'Kasubdisduksismin',
            'Kasubdissiduklog',
            'Kapustasisinfo',
            'Lain-lain', // Tambah kategori baru di sini jika diperlukan
        ];

        $added = 0;
        $skipped = 0;

        foreach ($kategoriList as $nama) {
            $slug = Str::slug($nama);

            $exists = KategoriGaleri::where('nama_kategori', $nama)
                ->orWhere('slug', $slug)
                ->first();

            if ($exists) {
                $skipped++;
                continue;
            }

            KategoriGaleri::firstOrCreate(
                ['slug' => $slug],
                [
                    'nama_kategori' => $nama,
                    'status' => true,
                ]
            );
            $added++;
        }

        echo "✓ KategoriGaleri seeder: {$added} added, {$skipped} skipped (already exist)\n";
    }
}
