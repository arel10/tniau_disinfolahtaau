<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Galeri;
use App\Models\User;

class GaleriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'admin')->get();

        if ($users->isEmpty()) {
            echo "⚠ Warning: Please run UserSeeder first\n";
            return;
        }

        $galeris = [
            [
                'judul' => 'Latihan Tempur Udara TNI AU 2026',
                'deskripsi' => 'Dokumentasi latihan tempur udara yang melibatkan berbagai jenis pesawat tempur TNI AU',
                'tipe' => 'foto',
                'video_url' => null,
                'tanggal_kegiatan' => now()->subDays(5),
            ],
            [
                'judul' => 'Upacara Pelantikan Perwira TNI AU',
                'deskripsi' => 'Momen pelantikan perwira baru TNI Angkatan Udara angkatan 2025',
                'tipe' => 'foto',
                'video_url' => null,
                'tanggal_kegiatan' => now()->subDays(10),
            ],
            [
                'judul' => 'Video Profil TNI Angkatan Udara',
                'deskripsi' => 'Video profil lengkap tentang TNI Angkatan Udara dan tugasnya dalam menjaga kedaulatan udara Indonesia',
                'tipe' => 'video',
                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'tanggal_kegiatan' => now()->subDays(7),
            ],
        ];

        $count = 0;
        foreach ($galeris as $data) {
            $user = $users->random();

            Galeri::create([
                'judul' => $data['judul'],
                'deskripsi' => $data['deskripsi'],
                'gambar' => 'galeri/placeholder.jpg', // Placeholder, bisa diganti dengan gambar asli
                'tipe' => $data['tipe'],
                'video_url' => $data['video_url'],
                'tanggal_kegiatan' => $data['tanggal_kegiatan'],
                'user_id' => $user->id,
            ]);
            $count++;
        }

        echo "✓ Galeri seeder completed: {$count} gallery items created\n";
        echo "⚠ Note: Gallery images use placeholder. Upload actual images to storage/app/public/galeri/\n";
    }
}
