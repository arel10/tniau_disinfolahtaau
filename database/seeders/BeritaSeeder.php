<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Berita;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Support\Str;

class BeritaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'admin')->get();
        $kategoris = Kategori::all();

        if ($users->isEmpty() || $kategoris->isEmpty()) {
            echo "⚠ Warning: Please run UserSeeder and KategoriSeeder first\n";
            return;
        }

        $beritas = [
            [
                'judul' => 'TNI AU Laksanakan Latihan Tempur Udara Skala Besar',
                'ringkasan' => 'TNI Angkatan Udara melaksanakan latihan tempur udara skala besar yang melibatkan berbagai jenis pesawat tempur untuk meningkatkan kesiapan operasional.',
                'konten' => "TNI Angkatan Udara (TNI AU) kembali menggelar latihan tempur udara skala besar yang melibatkan berbagai jenis pesawat tempur termasuk F-16, Sukhoi Su-27/30, dan Hawk 100/200. Latihan ini bertujuan untuk meningkatkan kesiapan operasional dan kemampuan tempur udara dalam menghadapi berbagai skenario ancaman.\n\nKepala Angkatan Udara menekankan pentingnya latihan rutin untuk mempertahankan profesionalisme dan kesiapan tempur prajurit TNI AU. Latihan ini juga melibatkan sistem pertahanan udara dan radar untuk simulasi pertempuran yang lebih realistis.\n\nLatihan berlangsung selama dua minggu dan mencakup berbagai misi termasuk air superiority, combat air patrol, dan ground attack simulation.",
                'kategori' => 'Berita TNI AU',
                'status' => 'published',
                'views' => rand(100, 500),
            ],
            [
                'judul' => 'Disinfolahtaau TNI AU Raih Penghargaan Terbaik Dalam Pelayanan Informasi Publik',
                'ringkasan' => 'Disinfolahtaau TNI AU mendapatkan apresiasi atas dedikasi dan kinerja dalam memberikan informasi kepada masyarakat.',
                'konten' => "Informasi dan Pengolahan Data (Disinfolahtaau) TNI Angkatan Udara meraih penghargaan sebagai unit terbaik dalam pelayanan informasi publik tahun ini. Penghargaan ini diberikan atas komitmen dan dedikasi dalam menyebarkan informasi yang akurat dan tepat waktu kepada masyarakat.\n\nKepala Disinfolahtaau menyampaikan bahwa penghargaan ini merupakan hasil kerja keras seluruh personel dalam memberikan pelayanan terbaik. Disinfolahtaau terus berinovasi dalam penyampaian informasi melalui berbagai platform media.\n\nKedepannya, Disinfolahtaau akan terus meningkatkan kualitas pelayanan dan memperluas jangkauan informasi untuk masyarakat.",
                'kategori' => 'Berita Disinfolahtaau',
                'status' => 'published',
                'views' => rand(150, 600),
            ],
            [
                'judul' => 'Upacara Pelantikan Perwira TNI AU Angkatan 2025',
                'ringkasan' => 'Pelantikan perwira baru TNI AU yang siap mengabdi untuk negara dan bangsa Indonesia.',
                'konten' => "TNI Angkatan Udara melaksanakan upacara pelantikan perwira angkatan 2025 yang dihadiri oleh Kepala Staf Angkatan Udara dan pejabat tinggi TNI. Sebanyak 120 perwira baru dilantik dan siap bertugas di berbagai satuan TNI AU.\n\nDalam arahannya, Kasau menekankan pentingnya profesionalisme, integritas, dan dedikasi dalam menjalankan tugas sebagai perwira TNI AU. Para perwira baru diharapkan dapat menjadi pemimpin yang baik dan memberikan kontribusi nyata bagi institusi.\n\nPara perwira baru akan menjalani pendidikan lanjutan sesuai dengan spesialisasi masing-masing sebelum ditempatkan di satuan operasional.",
                'kategori' => 'Berita Infolahta Jajaran',
                'status' => 'published',
                'views' => rand(90, 450),
            ],
            [
                'judul' => 'TNI AU Tingkatkan Kerja Sama Internasional Bidang Pertahanan Udara',
                'ringkasan' => 'Penguatan kerja sama bilateral dengan negara sahabat untuk meningkatkan kemampuan pertahanan udara.',
                'konten' => "TNI Angkatan Udara terus memperkuat kerja sama internasional di bidang pertahanan udara dengan berbagai negara sahabat. Kerja sama ini mencakup latihan bersama, pertukaran personel, dan transfer teknologi.\n\nDelegasi TNI AU menghadiri pertemuan bilateral dengan angkatan udara negara sahabat untuk membahas berbagai program kerja sama. Pertemuan ini menghasilkan kesepakatan untuk melaksanakan joint exercise dan program pendidikan bersama.\n\nKerja sama internasional ini sangat penting untuk meningkatkan kompetensi dan memperluas wawasan personel TNI AU dalam menghadapi tantangan keamanan global.",
                'kategori' => 'Berita TNI AU',
                'status' => 'published',
                'views' => rand(120, 550),
            ],
        ];

        $count = 0;
        foreach ($beritas as $data) {
            $kategori = $kategoris->where('nama_kategori', $data['kategori'])->first();
            $user = $users->random();

            Berita::create([
                'judul' => $data['judul'],
                'slug' => Str::slug($data['judul']),
                'ringkasan' => $data['ringkasan'],
                'konten' => $data['konten'],
                'kategori_id' => $kategori->id,
                'user_id' => $user->id,
                'status' => $data['status'],
                'views' => $data['views'],
                'published_at' => now()->subDays(rand(1, 30)),
            ]);
            $count++;
        }

        echo "✓ Berita seeder completed: {$count} news articles created\n";
    }
}
