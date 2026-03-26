<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'Disinfolahtaau TNI AU'],
            ['key' => 'site_description', 'value' => 'Website Resmi Dinas Informasi dan Pengolahan Data TNI Angkatan Udara'],
            ['key' => 'site_tagline', 'value' => 'Profesional, Modern, Tangguh'],
            ['key' => 'contact_email', 'value' => 'info@disinfolahtaau.tni-au.mil.id'],
            ['key' => 'contact_phone', 'value' => '(021) 7940040'],
            ['key' => 'contact_address', 'value' => 'Markas Besar TNI Angkatan Udara, Cilangkap, Jakarta Timur'],
            ['key' => 'footer_text', 'value' => '© 2026 Disinfolahtaau TNI AU. All rights reserved.'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                array_merge($setting, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        echo "✓ Settings seeder completed: " . count($settings) . " settings created\n";
    }
}
