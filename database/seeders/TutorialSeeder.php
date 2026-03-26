<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tutorial;

class TutorialSeeder extends Seeder
{
    public function run(): void
    {
        $tutorials = [
            [
                'judul' => 'Panduan Penggunaan Website',
                'gambar' => null,
                'link' => null,
            ],
            [
                'judul' => 'Tutorial Akses E-Library',
                'gambar' => null,
                'link' => null,
            ],
        ];

        foreach ($tutorials as $tutorial) {
            Tutorial::updateOrCreate(
                ['judul' => $tutorial['judul']],
                $tutorial
            );
        }

        echo "✓ Tutorial seeder completed: " . count($tutorials) . " tutorials created\n";
    }
}
