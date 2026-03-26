<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProfilContent;

class ProfilContentSeeder extends Seeder
{
    public function run(): void
    {
        $contents = [
            [
                'type' => 'visi',
                'content' => 'Menjadi Dinas Informasi dan Pengolahan Data TNI AU yang profesional, modern, dan tangguh dalam mendukung tugas pokok TNI Angkatan Udara.',
            ],
            [
                'type' => 'misi',
                'content' => "<ul>\n<li>Menyelenggarakan dukungan sistem informasi yang handal bagi operasi TNI AU.</li>\n<li>Mengembangkan teknologi informasi untuk meningkatkan efektivitas dan efisiensi kerja.</li>\n<li>Membina profesionalisme personel dalam bidang teknologi informasi.</li>\n<li>Menyelenggarakan pengolahan data yang akurat dan tepat waktu.</li>\n</ul>",
            ],
            [
                'type' => 'tentang',
                'content' => "Dinas Informasi dan Pengolahan Data TNI Angkatan Udara (Disinfolahtaau) merupakan badan pelaksana pusat pada tingkat Mabesau yang berkedudukan langsung di bawah Kasau. Disinfolahtaau bertugas menyelenggarakan pembinaan dan pengembangan sistem informasi serta pengolahan data di lingkungan TNI AU.\n\nDisinfolahtaau bertanggung jawab dalam perencanaan, pengembangan, dan pemeliharaan sistem informasi yang mendukung seluruh aspek tugas TNI Angkatan Udara, mulai dari operasi, personel, logistik, hingga manajemen.",
            ],
        ];

        foreach ($contents as $content) {
            ProfilContent::updateOrCreate(
                ['type' => $content['type']],
                $content
            );
        }

        echo "✓ ProfilContent seeder completed: " . count($contents) . " profil items created\n";
    }
}
