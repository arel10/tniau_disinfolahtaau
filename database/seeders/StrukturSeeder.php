<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Struktur;

class StrukturSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // ===== UNSUR PIMPINAN =====
            [
                'kode' => 'kadisinfolahtaau',
                'nama_jabatan' => 'KADISINFOLAHTAAU',
                'nama_lengkap_jabatan' => 'Kepala Dinas Informasi dan Pengolahan Data TNI AU',
                'unit' => 'Dinas Informasi dan Pengolahan Data TNI AU',
                'parent_kode' => null,
                'urutan' => 1,
            ],

            // ===== UNSUR PEMBANTU PIMPINAN/STAF =====
            [
                'kode' => 'setdis',
                'nama_jabatan' => 'SETDIS',
                'nama_lengkap_jabatan' => 'Sekretaris Dinas',
                'unit' => 'Sekretariat Dinas',
                'parent_kode' => 'kadisinfolahtaau',
                'urutan' => 2,
            ],
            [
                'kode' => 'bagum',
                'nama_jabatan' => 'BAGUM',
                'nama_lengkap_jabatan' => 'Kepala Bagian Umum',
                'unit' => 'Bagian Umum',
                'parent_kode' => 'setdis',
                'urutan' => 3,
            ],
            [
                'kode' => 'bagprogar',
                'nama_jabatan' => 'BAGPROGAR',
                'nama_lengkap_jabatan' => 'Kepala Bagian Program dan Anggaran',
                'unit' => 'Bagian Program dan Anggaran',
                'parent_kode' => 'setdis',
                'urutan' => 4,
            ],
            [
                'kode' => 'bagbinprof',
                'nama_jabatan' => 'BAGBINPROF',
                'nama_lengkap_jabatan' => 'Kepala Bagian Pembinaan Profesi',
                'unit' => 'Bagian Pembinaan Profesi',
                'parent_kode' => 'setdis',
                'urutan' => 5,
            ],
            [
                'kode' => 'subbagmin',
                'nama_jabatan' => 'SUBBAGMIN',
                'nama_lengkap_jabatan' => 'Kepala Sub Bagian Administrasi',
                'unit' => 'Sub Bagian Administrasi',
                'parent_kode' => 'bagum',
                'urutan' => 6,
            ],
            [
                'kode' => 'subbagpers',
                'nama_jabatan' => 'SUBBAGPERS',
                'nama_lengkap_jabatan' => 'Kepala Sub Bagian Personel',
                'unit' => 'Sub Bagian Personel',
                'parent_kode' => 'bagbinprof',
                'urutan' => 7,
            ],
            [
                'kode' => 'urtu',
                'nama_jabatan' => 'URTU',
                'nama_lengkap_jabatan' => 'Kepala Urusan Tata Usaha',
                'unit' => 'Urusan Tata Usaha',
                'parent_kode' => 'subbagmin',
                'urutan' => 8,
            ],
            [
                'kode' => 'urdal',
                'nama_jabatan' => 'URDAL',
                'nama_lengkap_jabatan' => 'Kepala Urusan Dalam',
                'unit' => 'Urusan Dalam',
                'parent_kode' => 'subbagmin',
                'urutan' => 9,
            ],
            [
                'kode' => 'urpers',
                'nama_jabatan' => 'URPERS',
                'nama_lengkap_jabatan' => 'Kepala Urusan Personel',
                'unit' => 'Urusan Personel',
                'parent_kode' => 'subbagmin',
                'urutan' => 10,
            ],
            [
                'kode' => 'urbmn',
                'nama_jabatan' => 'UR BMN',
                'nama_lengkap_jabatan' => 'Kepala Urusan Barang Milik Negara',
                'unit' => 'Urusan Barang Milik Negara',
                'parent_kode' => 'subbagmin',
                'urutan' => 11,
            ],

            // ===== UNSUR PELAKSANA: SUBDIS =====
            [
                'kode' => 'subdissidukops',
                'nama_jabatan' => 'SUBDISSIDUKOPS',
                'nama_lengkap_jabatan' => 'Kepala Sub Dinas Sistem Dukungan Operasi',
                'unit' => 'Sub Dinas Sistem Dukungan Operasi',
                'parent_kode' => 'kadisinfolahtaau',
                'urutan' => 12,
            ],
            [
                'kode' => 'subdissidukpers',
                'nama_jabatan' => 'SUBDISSIDUKPERS',
                'nama_lengkap_jabatan' => 'Kepala Sub Dinas Sistem Dukungan Personel',
                'unit' => 'Sub Dinas Sistem Dukungan Personel',
                'parent_kode' => 'kadisinfolahtaau',
                'urutan' => 13,
            ],
            [
                'kode' => 'subdissiduklog',
                'nama_jabatan' => 'SUBDISSIDUKLOG',
                'nama_lengkap_jabatan' => 'Kepala Sub Dinas Sistem Dukungan Logistik',
                'unit' => 'Sub Dinas Sistem Dukungan Logistik',
                'parent_kode' => 'kadisinfolahtaau',
                'urutan' => 14,
            ],
            [
                'kode' => 'subdissiduksissmin',
                'nama_jabatan' => 'SUBDISSIDUKSISSMIN',
                'nama_lengkap_jabatan' => 'Kepala Sub Dinas Sistem Dukungan Sistem Informasi Manajemen',
                'unit' => 'Sub Dinas Sistem Dukungan Sismin',
                'parent_kode' => 'kadisinfolahtaau',
                'urutan' => 15,
            ],

            // ===== SUBDISSIDUKOPS - Sub Items =====
            ['kode' => 'siapldatabase_ops', 'nama_jabatan' => 'SIAPLDATABASE', 'nama_lengkap_jabatan' => 'Kepala Seksi Aplikasi dan Database', 'unit' => 'Seksi Aplikasi dan Database (Ops)', 'parent_kode' => 'subdissidukops', 'urutan' => 16],
            ['kode' => 'subsiapl_ops', 'nama_jabatan' => 'SUBSIAPL', 'nama_lengkap_jabatan' => 'Kepala Sub Seksi Aplikasi', 'unit' => 'Sub Seksi Aplikasi (Ops)', 'parent_kode' => 'subdissidukops', 'urutan' => 17],
            ['kode' => 'urrenharapl_ops', 'nama_jabatan' => 'URRENHARAPL', 'nama_lengkap_jabatan' => 'Kepala Urusan Pemeliharaan Aplikasi', 'unit' => 'Urusan Pemeliharaan Aplikasi (Ops)', 'parent_kode' => 'subdissidukops', 'urutan' => 18],
            ['kode' => 'subsidatabase_ops', 'nama_jabatan' => 'SUBSIDATABASE', 'nama_lengkap_jabatan' => 'Kepala Sub Seksi Database', 'unit' => 'Sub Seksi Database (Ops)', 'parent_kode' => 'subdissidukops', 'urutan' => 19],
            ['kode' => 'urrenhardb_ops', 'nama_jabatan' => 'URRENHAR DATA BASE', 'nama_lengkap_jabatan' => 'Kepala Urusan Pemeliharaan Database', 'unit' => 'Urusan Pemeliharaan Database (Ops)', 'parent_kode' => 'subdissidukops', 'urutan' => 20],
            ['kode' => 'sikompjar_ops', 'nama_jabatan' => 'SIKOMPJAR', 'nama_lengkap_jabatan' => 'Kepala Seksi Komputer dan Jaringan', 'unit' => 'Seksi Komputer dan Jaringan (Ops)', 'parent_kode' => 'subdissidukops', 'urutan' => 21],
            ['kode' => 'subsikomp_ops', 'nama_jabatan' => 'SUBSIKOMP', 'nama_lengkap_jabatan' => 'Kepala Sub Seksi Komputer', 'unit' => 'Sub Seksi Komputer (Ops)', 'parent_kode' => 'subdissidukops', 'urutan' => 22],
            ['kode' => 'urrenharkomp_ops', 'nama_jabatan' => 'URRENHARKOMP', 'nama_lengkap_jabatan' => 'Kepala Urusan Pemeliharaan Komputer', 'unit' => 'Urusan Pemeliharaan Komputer (Ops)', 'parent_kode' => 'subdissidukops', 'urutan' => 23],
            ['kode' => 'subsjar_ops', 'nama_jabatan' => 'SUBSJAR', 'nama_lengkap_jabatan' => 'Kepala Sub Seksi Jaringan', 'unit' => 'Sub Seksi Jaringan (Ops)', 'parent_kode' => 'subdissidukops', 'urutan' => 24],
            ['kode' => 'urrenharjar_ops', 'nama_jabatan' => 'URRENHARJAR', 'nama_lengkap_jabatan' => 'Kepala Urusan Pemeliharaan Jaringan', 'unit' => 'Urusan Pemeliharaan Jaringan (Ops)', 'parent_kode' => 'subdissidukops', 'urutan' => 25],

            // ===== SUBDISSIDUKPERS - Sub Items =====
            ['kode' => 'siapldatabase_pers', 'nama_jabatan' => 'SIAPLDATABASE', 'nama_lengkap_jabatan' => 'Kepala Seksi Aplikasi dan Database', 'unit' => 'Seksi Aplikasi dan Database (Pers)', 'parent_kode' => 'subdissidukpers', 'urutan' => 26],
            ['kode' => 'subsiapl_pers', 'nama_jabatan' => 'SUBSIAPL', 'nama_lengkap_jabatan' => 'Kepala Sub Seksi Aplikasi', 'unit' => 'Sub Seksi Aplikasi (Pers)', 'parent_kode' => 'subdissidukpers', 'urutan' => 27],
            ['kode' => 'urrenharapl_pers', 'nama_jabatan' => 'URRENHARAPL', 'nama_lengkap_jabatan' => 'Kepala Urusan Pemeliharaan Aplikasi', 'unit' => 'Urusan Pemeliharaan Aplikasi (Pers)', 'parent_kode' => 'subdissidukpers', 'urutan' => 28],
            ['kode' => 'subsidatabase_pers', 'nama_jabatan' => 'SUBSIDATABASE', 'nama_lengkap_jabatan' => 'Kepala Sub Seksi Database', 'unit' => 'Sub Seksi Database (Pers)', 'parent_kode' => 'subdissidukpers', 'urutan' => 29],
            ['kode' => 'urrenhardb_pers', 'nama_jabatan' => 'URRENHAR DATA BASE', 'nama_lengkap_jabatan' => 'Kepala Urusan Pemeliharaan Database', 'unit' => 'Urusan Pemeliharaan Database (Pers)', 'parent_kode' => 'subdissidukpers', 'urutan' => 30],
            ['kode' => 'sikompjar_pers', 'nama_jabatan' => 'SIKOMPJAR', 'nama_lengkap_jabatan' => 'Kepala Seksi Komputer dan Jaringan', 'unit' => 'Seksi Komputer dan Jaringan (Pers)', 'parent_kode' => 'subdissidukpers', 'urutan' => 31],
            ['kode' => 'subsikomp_pers', 'nama_jabatan' => 'SUBSIKOMP', 'nama_lengkap_jabatan' => 'Kepala Sub Seksi Komputer', 'unit' => 'Sub Seksi Komputer (Pers)', 'parent_kode' => 'subdissidukpers', 'urutan' => 32],
            ['kode' => 'urrenharkomp_pers', 'nama_jabatan' => 'URRENHARKOMP', 'nama_lengkap_jabatan' => 'Kepala Urusan Pemeliharaan Komputer', 'unit' => 'Urusan Pemeliharaan Komputer (Pers)', 'parent_kode' => 'subdissidukpers', 'urutan' => 33],
            ['kode' => 'subsjar_pers', 'nama_jabatan' => 'SUBSJAR', 'nama_lengkap_jabatan' => 'Kepala Sub Seksi Jaringan', 'unit' => 'Sub Seksi Jaringan (Pers)', 'parent_kode' => 'subdissidukpers', 'urutan' => 34],
            ['kode' => 'urrenharjar_pers', 'nama_jabatan' => 'URRENHARJAR', 'nama_lengkap_jabatan' => 'Kepala Urusan Pemeliharaan Jaringan', 'unit' => 'Urusan Pemeliharaan Jaringan (Pers)', 'parent_kode' => 'subdissidukpers', 'urutan' => 35],
            ['kode' => 'sigarku_pers', 'nama_jabatan' => 'SIGARKU', 'nama_lengkap_jabatan' => 'Kepala Seksi Garda Terdepan Keamanan Siber', 'unit' => 'Seksi Garda Keamanan (Pers)', 'parent_kode' => 'subdissidukpers', 'urutan' => 36],
            ['kode' => 'subsiapl2_pers', 'nama_jabatan' => 'SUBSIAPL', 'nama_lengkap_jabatan' => 'Kepala Sub Seksi Aplikasi 2', 'unit' => 'Sub Seksi Aplikasi 2 (Pers)', 'parent_kode' => 'subdissidukpers', 'urutan' => 37],
            ['kode' => 'urrenharapl2_pers', 'nama_jabatan' => 'URRENHARAPL', 'nama_lengkap_jabatan' => 'Kepala Urusan Pemeliharaan Aplikasi 2', 'unit' => 'Urusan Pemeliharaan Aplikasi 2 (Pers)', 'parent_kode' => 'subdissidukpers', 'urutan' => 38],
            ['kode' => 'subsidatabase2_pers', 'nama_jabatan' => 'SUBSIDATABASE', 'nama_lengkap_jabatan' => 'Kepala Sub Seksi Database 2', 'unit' => 'Sub Seksi Database 2 (Pers)', 'parent_kode' => 'subdissidukpers', 'urutan' => 39],
            ['kode' => 'urrenhardb2_pers', 'nama_jabatan' => 'URRENHAR DATABASE', 'nama_lengkap_jabatan' => 'Kepala Urusan Pemeliharaan Database 2', 'unit' => 'Urusan Pemeliharaan Database 2 (Pers)', 'parent_kode' => 'subdissidukpers', 'urutan' => 40],

            // ===== SUBDISSIDUKLOG - Sub Items =====
            ['kode' => 'siapldatabase_log', 'nama_jabatan' => 'SIAPLDATABASE', 'nama_lengkap_jabatan' => 'Kepala Seksi Aplikasi dan Database', 'unit' => 'Seksi Aplikasi dan Database (Log)', 'parent_kode' => 'subdissiduklog', 'urutan' => 41],
            ['kode' => 'subsiapl_log', 'nama_jabatan' => 'SUBSIAPL', 'nama_lengkap_jabatan' => 'Kepala Sub Seksi Aplikasi', 'unit' => 'Sub Seksi Aplikasi (Log)', 'parent_kode' => 'subdissiduklog', 'urutan' => 42],
            ['kode' => 'urrenharapl_log', 'nama_jabatan' => 'URRENHARAPL', 'nama_lengkap_jabatan' => 'Kepala Urusan Pemeliharaan Aplikasi', 'unit' => 'Urusan Pemeliharaan Aplikasi (Log)', 'parent_kode' => 'subdissiduklog', 'urutan' => 43],
            ['kode' => 'subsidatabase_log', 'nama_jabatan' => 'SUBSIDATABASE', 'nama_lengkap_jabatan' => 'Kepala Sub Seksi Database', 'unit' => 'Sub Seksi Database (Log)', 'parent_kode' => 'subdissiduklog', 'urutan' => 44],
            ['kode' => 'urrenhardb_log', 'nama_jabatan' => 'URRENHAR DATA BASE', 'nama_lengkap_jabatan' => 'Kepala Urusan Pemeliharaan Database', 'unit' => 'Urusan Pemeliharaan Database (Log)', 'parent_kode' => 'subdissiduklog', 'urutan' => 45],
            ['kode' => 'sikompjar_log', 'nama_jabatan' => 'SIKOMPJAR', 'nama_lengkap_jabatan' => 'Kepala Seksi Komputer dan Jaringan', 'unit' => 'Seksi Komputer dan Jaringan (Log)', 'parent_kode' => 'subdissiduklog', 'urutan' => 46],
            ['kode' => 'subsikomp_log', 'nama_jabatan' => 'SUBSIKOMP', 'nama_lengkap_jabatan' => 'Kepala Sub Seksi Komputer', 'unit' => 'Sub Seksi Komputer (Log)', 'parent_kode' => 'subdissiduklog', 'urutan' => 47],
            ['kode' => 'urrenharkomp_log', 'nama_jabatan' => 'URRENHARKOMP', 'nama_lengkap_jabatan' => 'Kepala Urusan Pemeliharaan Komputer', 'unit' => 'Urusan Pemeliharaan Komputer (Log)', 'parent_kode' => 'subdissiduklog', 'urutan' => 48],
            ['kode' => 'subsjar_log', 'nama_jabatan' => 'SUBSJAR', 'nama_lengkap_jabatan' => 'Kepala Sub Seksi Jaringan', 'unit' => 'Sub Seksi Jaringan (Log)', 'parent_kode' => 'subdissiduklog', 'urutan' => 49],
            ['kode' => 'urrenharjar_log', 'nama_jabatan' => 'URRENHARJAR', 'nama_lengkap_jabatan' => 'Kepala Urusan Pemeliharaan Jaringan', 'unit' => 'Urusan Pemeliharaan Jaringan (Log)', 'parent_kode' => 'subdissiduklog', 'urutan' => 50],

            // ===== SUBDISSIDUKSISSMIN - Sub Items =====
            ['kode' => 'siapldatabase_sis', 'nama_jabatan' => 'SIAPLDATABASE', 'nama_lengkap_jabatan' => 'Kepala Seksi Aplikasi dan Database', 'unit' => 'Seksi Aplikasi dan Database (Sismin)', 'parent_kode' => 'subdissiduksissmin', 'urutan' => 51],
            ['kode' => 'subsiapl_sis', 'nama_jabatan' => 'SUBSIAPL', 'nama_lengkap_jabatan' => 'Kepala Sub Seksi Aplikasi', 'unit' => 'Sub Seksi Aplikasi (Sismin)', 'parent_kode' => 'subdissiduksissmin', 'urutan' => 52],
            ['kode' => 'urrenharapl_sis', 'nama_jabatan' => 'URRENHARAPL', 'nama_lengkap_jabatan' => 'Kepala Urusan Pemeliharaan Aplikasi', 'unit' => 'Urusan Pemeliharaan Aplikasi (Sismin)', 'parent_kode' => 'subdissiduksissmin', 'urutan' => 53],
            ['kode' => 'subsidatabase_sis', 'nama_jabatan' => 'SUBSIDATABASE', 'nama_lengkap_jabatan' => 'Kepala Sub Seksi Database', 'unit' => 'Sub Seksi Database (Sismin)', 'parent_kode' => 'subdissiduksissmin', 'urutan' => 54],
            ['kode' => 'urrenhardb_sis', 'nama_jabatan' => 'URRENHAR DATA BASE', 'nama_lengkap_jabatan' => 'Kepala Urusan Pemeliharaan Database', 'unit' => 'Urusan Pemeliharaan Database (Sismin)', 'parent_kode' => 'subdissiduksissmin', 'urutan' => 55],
            ['kode' => 'sikompjar_sis', 'nama_jabatan' => 'SIKOMPJAR', 'nama_lengkap_jabatan' => 'Kepala Seksi Komputer dan Jaringan', 'unit' => 'Seksi Komputer dan Jaringan (Sismin)', 'parent_kode' => 'subdissiduksissmin', 'urutan' => 56],
            ['kode' => 'subsikomp_sis', 'nama_jabatan' => 'SUBSIKOMP', 'nama_lengkap_jabatan' => 'Kepala Sub Seksi Komputer', 'unit' => 'Sub Seksi Komputer (Sismin)', 'parent_kode' => 'subdissiduksissmin', 'urutan' => 57],
            ['kode' => 'urrenharkomp_sis', 'nama_jabatan' => 'URRENHARKOMP', 'nama_lengkap_jabatan' => 'Kepala Urusan Pemeliharaan Komputer', 'unit' => 'Urusan Pemeliharaan Komputer (Sismin)', 'parent_kode' => 'subdissiduksissmin', 'urutan' => 58],
            ['kode' => 'subsjar_sis', 'nama_jabatan' => 'SUBSJAR', 'nama_lengkap_jabatan' => 'Kepala Sub Seksi Jaringan', 'unit' => 'Sub Seksi Jaringan (Sismin)', 'parent_kode' => 'subdissiduksissmin', 'urutan' => 59],
            ['kode' => 'urrenharjar_sis', 'nama_jabatan' => 'URRENHARJAR', 'nama_lengkap_jabatan' => 'Kepala Urusan Pemeliharaan Jaringan', 'unit' => 'Urusan Pemeliharaan Jaringan (Sismin)', 'parent_kode' => 'subdissiduksissmin', 'urutan' => 60],
            ['kode' => 'pustasinfo', 'nama_jabatan' => 'PUSTASINFO', 'nama_lengkap_jabatan' => 'Kepala Pusat Informasi', 'unit' => 'Pusat Informasi (Sismin)', 'parent_kode' => 'subdissiduksissmin', 'urutan' => 61],
            ['kode' => 'subsihar', 'nama_jabatan' => 'SUBSIHAR', 'nama_lengkap_jabatan' => 'Kepala Sub Seksi Pemeliharaan', 'unit' => 'Sub Seksi Pemeliharaan (Sismin)', 'parent_kode' => 'subdissiduksissmin', 'urutan' => 62],
            ['kode' => 'urharapljar', 'nama_jabatan' => 'URHARAPLJAR', 'nama_lengkap_jabatan' => 'Kepala Urusan Pemeliharaan Aplikasi dan Jaringan', 'unit' => 'Urusan Pemeliharaan Aplikasi dan Jaringan (Sismin)', 'parent_kode' => 'subdissiduksissmin', 'urutan' => 63],
            ['kode' => 'subsiops', 'nama_jabatan' => 'SUBSIOPS', 'nama_lengkap_jabatan' => 'Kepala Sub Seksi Operasi', 'unit' => 'Sub Seksi Operasi (Sismin)', 'parent_kode' => 'subdissiduksissmin', 'urutan' => 64],
            ['kode' => 'uropsapljar', 'nama_jabatan' => 'UROPSAPLJAR', 'nama_lengkap_jabatan' => 'Kepala Urusan Operasi Aplikasi dan Jaringan', 'unit' => 'Urusan Operasi Aplikasi dan Jaringan (Sismin)', 'parent_kode' => 'subdissiduksissmin', 'urutan' => 65],
        ];

        foreach ($data as $item) {
            Struktur::updateOrCreate(
                ['kode' => $item['kode']],
                array_merge($item, ['is_active' => true])
            );
        }
    }
}
