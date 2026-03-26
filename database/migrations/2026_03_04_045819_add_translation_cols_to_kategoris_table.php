<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kategoris', function (Blueprint $table) {
            $table->string('nama_kategori_en')->nullable()->after('nama_kategori');
            $table->string('nama_kategori_ar')->nullable()->after('nama_kategori_en');
            $table->string('nama_kategori_fr')->nullable()->after('nama_kategori_ar');
            $table->string('nama_kategori_es')->nullable()->after('nama_kategori_fr');
            $table->string('nama_kategori_ru')->nullable()->after('nama_kategori_es');
            $table->string('nama_kategori_ja')->nullable()->after('nama_kategori_ru');
        });

        // Seed translations for existing categories
        $translations = [
            'Berita TNI AU' => [
                'en' => 'TNI AU News',
                'ar' => 'أخبار TNI AU',
                'fr' => 'Actualités TNI AU',
                'es' => 'Noticias TNI AU',
                'ru' => 'Новости TNI AU',
                'ja' => 'TNI AU ニュース',
            ],
            'Berita Disinfolahtaau' => [
                'en' => 'Disinfolahtaau News',
                'ar' => 'أخبار Disinfolahtaau',
                'fr' => 'Actualités Disinfolahtaau',
                'es' => 'Noticias Disinfolahtaau',
                'ru' => 'Новости Disinfolahtaau',
                'ja' => 'Disinfolahtaau ニュース',
            ],
            'Berita Infolahta Jajaran' => [
                'en' => 'Infolahta Jajaran News',
                'ar' => 'أخبار Infolahta Jajaran',
                'fr' => 'Actualités Infolahta Jajaran',
                'es' => 'Noticias Infolahta Jajaran',
                'ru' => 'Новости Infolahta Jajaran',
                'ja' => 'Infolahta Jajaran ニュース',
            ],
        ];

        foreach ($translations as $nama => $trans) {
            DB::table('kategoris')
                ->where('nama_kategori', $nama)
                ->update([
                    'nama_kategori_en' => $trans['en'],
                    'nama_kategori_ar' => $trans['ar'],
                    'nama_kategori_fr' => $trans['fr'],
                    'nama_kategori_es' => $trans['es'],
                    'nama_kategori_ru' => $trans['ru'],
                    'nama_kategori_ja' => $trans['ja'],
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('kategoris', function (Blueprint $table) {
            $table->dropColumn(['nama_kategori_en','nama_kategori_ar','nama_kategori_fr','nama_kategori_es','nama_kategori_ru','nama_kategori_ja']);
        });
    }
};
