<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KategoriGaleri extends Model
{
    use HasFactory;

    protected $table = 'kategori_galeris';

    protected $fillable = [
        'nama_kategori',
        'slug',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // Relasi ke Galeri (hasMany)
    public function galeris()
    {
        return $this->hasMany(Galeri::class, 'kategori_galeri', 'slug');
    }

    // Scope hanya kategori aktif
    public function scopeAktif($query)
    {
        return $query->where('status', true);
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
