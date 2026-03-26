<?php

namespace App\Models;

use App\Traits\HasTranslation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kategori extends Model
{
    use HasFactory, HasTranslation;

    protected array $translatable = ['nama_kategori'];

    protected $fillable = [
        'nama_kategori',
        'nama_kategori_en',
        'nama_kategori_ar',
        'nama_kategori_fr',
        'nama_kategori_es',
        'nama_kategori_ru',
        'nama_kategori_ja',
        'slug',
        'deskripsi',
    ];

    // Relasi ke Berita
    public function beritas()
    {
        return $this->hasMany(Berita::class, 'kategori_id');
    }

    // Auto generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kategori) {
            if (empty($kategori->slug)) {
                $kategori->slug = Str::slug($kategori->nama_kategori);
            }
        });

        static::updating(function ($kategori) {
            if ($kategori->isDirty('nama_kategori') && empty($kategori->slug)) {
                $kategori->slug = Str::slug($kategori->nama_kategori);
            }
        });
    }
}
