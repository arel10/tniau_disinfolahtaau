<?php

namespace App\Models;

use App\Traits\HasTranslation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use HasFactory, HasTranslation;

    protected array $translatable = ['judul', 'deskripsi'];

    protected $fillable = [
        'group_id',
        'judul',
        'judul_en', 'judul_ar', 'judul_fr', 'judul_es', 'judul_ru', 'judul_ja',
        'deskripsi',
        'deskripsi_en', 'deskripsi_ar', 'deskripsi_fr', 'deskripsi_es', 'deskripsi_ru', 'deskripsi_ja',
        'gambar',
        'pdf_path',
        'tipe',
        'kategori_galeri',
        'video_url',
        'video_file',
        'tanggal_kegiatan',
        'user_id',
    ];

    // Daftar kategori galeri
    public static $kategoriGaleriOptions = [
        'video' => 'Video',
        'kadisinfolahta' => 'Kadisinfolahta',
        'sesdisinfolahta' => 'Sesdisinfolahta',
        'kasubdissidukops' => 'Kasubdissidukops',
        'kasubdissidukpers' => 'Kasubdissidukpers',
        'kasubdisduksismin' => 'Kasubdisduksismin',
        'kasubdissiduklog' => 'Kasubdissiduklog',
        'kapustasisinfo' => 'Kapustasisinfo',
        'lain-lain' => 'Lain-lain',
    ];

    protected $casts = [
        'tanggal_kegiatan' => 'date',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke KategoriGaleri (belongsTo via slug)
    public function kategoriGaleriRelasi()
    {
        return $this->belongsTo(KategoriGaleri::class, 'kategori_galeri', 'slug');
    }

    // Scope untuk filter berdasarkan tipe
    public function scopeByTipe($query, $tipe)
    {
        return $query->where('tipe', $tipe);
    }

    // Scope untuk foto saja
    public function scopeFoto($query)
    {
        return $query->where('tipe', 'foto');
    }

    // Scope untuk video saja
    public function scopeVideo($query)
    {
        return $query->where('tipe', 'video');
    }

    // Konversi URL YouTube ke format embed
    public function getEmbedUrlAttribute()
    {
        $url = $this->video_url;
        if (!$url) return null;

        // youtu.be/VIDEO_ID
        if (preg_match('/youtu\.be\/([\w-]+)/', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }

        // youtube.com/watch?v=VIDEO_ID
        if (preg_match('/youtube\.com\/watch\?v=([\w-]+)/', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }

        // youtube.com/embed/VIDEO_ID (sudah format embed)
        if (preg_match('/youtube\.com\/embed\/([\w-]+)/', $url, $matches)) {
            return $url;
        }

        // URL lain, kembalikan apa adanya
        return $url;
    }

    // Ambil YouTube video ID dari URL
    public function getYoutubeIdAttribute()
    {
        $url = $this->video_url;
        if (!$url) return null;

        if (preg_match('/youtu\.be\/([\w-]+)/', $url, $matches)) {
            return $matches[1];
        }
        if (preg_match('/youtube\.com\/watch\?v=([\w-]+)/', $url, $matches)) {
            return $matches[1];
        }
        if (preg_match('/youtube\.com\/embed\/([\w-]+)/', $url, $matches)) {
            return $matches[1];
        }
        return null;
    }

    // Thumbnail dari YouTube
    public function getThumbnailUrlAttribute()
    {
        if ($this->gambar) {
            return asset('storage/' . $this->gambar);
        }
        if ($this->youtube_id) {
            return 'https://img.youtube.com/vi/' . $this->youtube_id . '/hqdefault.jpg';
        }
        return null;
    }

    // Scope untuk galeri terbaru
    public function scopeLatest($query)
    {
        return $query->orderBy('tanggal_kegiatan', 'desc');
    }
}
