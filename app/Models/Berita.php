<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasTranslation;

class Berita extends Model
{
    use HasFactory, HasTranslation;

    /**
     * Fields that support multi-language translations.
     * Base columns (Indonesian): judul, ringkasan, konten
     * Additional columns: judul_en, judul_ja, ringkasan_en, ringkasan_ja, konten_en, konten_ja
     *
     * Access translated content via: $berita->localized_judul, etc.
     */
    protected array $translatable = ['judul', 'ringkasan', 'konten'];

    protected $fillable = [
        'judul',
        'judul_en', 'judul_ja', 'judul_ar', 'judul_fr', 'judul_es', 'judul_ru',
        'slug',
        'ringkasan',
        'ringkasan_en', 'ringkasan_ja', 'ringkasan_ar', 'ringkasan_fr', 'ringkasan_es', 'ringkasan_ru',
        'konten',
        'konten_en', 'konten_ja', 'konten_ar', 'konten_fr', 'konten_es', 'konten_ru',
        'gambar_utama',
        'gambar_tambahan',
        'kategori_id',
        'user_id',
        'views',
        'status',
        'tanggal',
        'tags',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'tanggal' => 'date',
        'views' => 'integer',
        'gambar_tambahan' => 'array',
    ];

    // Relasi ke Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // Relasi ke User (Penulis)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scope untuk berita yang sudah dipublish
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    // Scope untuk berita terbaru
    public function scopeLatest($query, $column = 'tanggal')
    {
        return $query->orderBy($column, 'desc');
    }

    // Auto generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($berita) {
            if (empty($berita->slug)) {
                $berita->slug = Str::slug($berita->judul);
            }
        });

        static::updating(function ($berita) {
            if ($berita->isDirty('judul') && empty($berita->slug)) {
                $berita->slug = Str::slug($berita->judul);
            }
        });
    }

    // Increment view count
    public function incrementViews()
    {
        $this->increment('views');
    }
}
