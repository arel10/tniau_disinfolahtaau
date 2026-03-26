<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kontak;

class KontakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kontaks = [
            [
                'nama' => 'Budi Santoso',
                'email' => 'budi.santoso@email.com',
                'subjek' => 'Pertanyaan Seputar Rekrutmen TNI AU',
                'pesan' => 'Selamat siang, saya ingin menanyakan informasi lebih lanjut tentang persyaratan dan jadwal rekrutmen TNI AU untuk tahun 2026. Mohon informasinya. Terima kasih.',
                'status' => 'baru',
            ],
        ];

        $count = 0;
        foreach ($kontaks as $data) {
            Kontak::create($data);
            $count++;
        }

        echo "✓ Kontak seeder completed: {$count} contact messages created\n";
    }
}
