<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ELibraryDocument;

class ELibrarySeeder extends Seeder
{
    public function run(): void
    {
        $docs = [
            [
                'title' => 'Buku Panduan Sistem Informasi TNI AU',
                'description' => 'Panduan lengkap penggunaan sistem informasi di lingkungan TNI Angkatan Udara.',
                'pdf_path' => 'e-library/placeholder.pdf',
                'cover_path' => null,
                'is_published' => true,
            ],
            [
                'title' => 'Peraturan Zona Integritas',
                'description' => 'Dokumen peraturan mengenai pembangunan Zona Integritas di lingkungan TNI AU.',
                'pdf_path' => 'e-library/placeholder.pdf',
                'cover_path' => null,
                'is_published' => true,
            ],
        ];

        foreach ($docs as $doc) {
            ELibraryDocument::updateOrCreate(
                ['title' => $doc['title']],
                $doc
            );
        }

        echo "✓ ELibrary seeder completed: " . count($docs) . " documents created\n";
        echo "⚠ Note: PDF files use placeholder paths. Upload actual PDFs to storage/app/public/e-library/\n";
    }
}
