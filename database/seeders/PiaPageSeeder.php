<?php

namespace Database\Seeders;

use App\Models\PiaPage;
use Illuminate\Database\Seeder;

class PiaPageSeeder extends Seeder
{
    public function run(): void
    {
        PiaPage::firstOrCreate(
            ['id' => 1],
            [
                'page_title'      => 'PIA',
                'history_title'   => 'PIA Ardhya Garini',
                'history_content' => 'PIA Ardhya Garini adalah organisasi istri prajurit TNI Angkatan Udara yang berperan aktif dalam mendukung tugas dan kesejahteraan keluarga besar TNI AU.',
            ]
        );
    }
}
