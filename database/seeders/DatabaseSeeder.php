<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        echo "\n";
        echo "========================================\n";
        echo "  SEEDING TNI AU DISINFOLAHTAAU DATABASE    \n";
        echo "========================================\n\n";

        // Urutan seeder penting karena ada relasi antar tabel
        $this->call([
            AdminUserSeeder::class,         // Seeder admin & user test
            UserSeeder::class,              //  1. User (admin) harus dibuat dulu
            SettingsSeeder::class,          //  2. Settings dasar website
            KategoriSeeder::class,          //  3. Kategori untuk berita
            KategoriGaleriSeeder::class,    //  4. Kategori galeri
            BeritaSeeder::class,            //  5. Berita (butuh user & kategori)
            GaleriSeeder::class,            //  6. Galeri (butuh user)
            KontakSeeder::class,            //  7. Kontak (pesan dummy)
            StrukturSeeder::class,          //  8. Struktur organisasi
            WhistleBlowingSeeder::class,    //  9. Whistle Blowing settings
            PiaPageSeeder::class,           // 10. PIA page
            ProfilContentSeeder::class,     // 11. Profil (visi, misi, tentang)
            HistoryDiagramSeeder::class,    // 12. Sejarah / history diagram
            ZiPageSeeder::class,            // 13. Zona Integritas pages + kategori
            TutorialSeeder::class,          // 14. Tutorial
            ELibrarySeeder::class,          // 15. E-Library documents
        ]);

        echo "\n========================================\n";
        echo "  SEEDING COMPLETED SUCCESSFULLY! ✓    \n";
        echo "========================================\n\n";

        echo "Login credentials:\n";
        echo "-------------------\n";
        echo "Email    : admin@tni.au.mil.id\n";
        echo "Password : password123\n\n";
        echo "Alternative accounts:\n";
        echo "- komandan@tni.au.mil.id (password123)\n";
        echo "- staff@tni.au.mil.id (password123)\n\n";
    }
}
