<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HistoryDiagram;

class HistoryDiagramSeeder extends Seeder
{
    public function run(): void
    {
        $diagrams = [
            [
                'title' => 'Pembentukan Disinfolahtaau',
                'description' => 'Dinas Informasi dan Pengolahan Data TNI AU resmi dibentuk sebagai badan pelaksana pusat di lingkungan Mabesau.',
                'year' => '2000',
            ],
            [
                'title' => 'Modernisasi Sistem Informasi',
                'description' => 'Dimulainya program modernisasi sistem informasi dan jaringan komputer di seluruh satuan TNI AU.',
                'year' => '2010',
            ],
            [
                'title' => 'Era Transformasi Digital',
                'description' => 'Disinfolahtaau memasuki era transformasi digital dengan pengembangan berbagai aplikasi dan sistem informasi terintegrasi.',
                'year' => '2020',
            ],
        ];

        foreach ($diagrams as $diagram) {
            HistoryDiagram::updateOrCreate(
                ['year' => $diagram['year'], 'title' => $diagram['title']],
                $diagram
            );
        }

        echo "✓ HistoryDiagram seeder completed: " . count($diagrams) . " items created\n";
    }
}
