<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WhistleBlowingSetting;

class WhistleBlowingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WhistleBlowingSetting::create([
            'gambar' => 'assets/image/whistle.png',
            'link_tujuan' => 'https://docs.google.com/forms/d/e/1FAIpQLSfKQpjmY6xO0RgrTmq9oCWU-DeUEsltm-k1AaVqHDdlUoN47w/viewform?pli=1',
            'judul' => 'Whistle Blowing System',
            'deskripsi' => 'Klik icon di atas untuk membuat laporan!',
            'is_active' => true,
        ]);
    }
}
