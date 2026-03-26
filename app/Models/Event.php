<?php

namespace App\Models;

use App\Traits\HasTranslation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory, HasTranslation;

    protected array $translatable = ['nama_kegiatan', 'deskripsi'];

    protected $fillable = [
        'nama_kegiatan',
        'nama_kegiatan_en', 'nama_kegiatan_ar', 'nama_kegiatan_fr',
        'nama_kegiatan_es', 'nama_kegiatan_ru', 'nama_kegiatan_ja',
        'slug',
        'deskripsi',
        'deskripsi_en', 'deskripsi_ar', 'deskripsi_fr',
        'deskripsi_es', 'deskripsi_ru', 'deskripsi_ja',
        'cover_image',
        'tanggal_kegiatan',
        'is_published',
        'position',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'tanggal_kegiatan' => 'date',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function media()
    {
        return $this->hasMany(EventMedia::class)->orderBy('position');
    }

    public function fotos()
    {
        return $this->hasMany(EventMedia::class)->where('type', 'foto')->orderBy('position');
    }

    public function videos()
    {
        return $this->hasMany(EventMedia::class)->where('type', 'video')->orderBy('position');
    }

    public function heroes()
    {
        return $this->hasMany(EventMedia::class)->where('section', 'hero')->orderBy('position');
    }

    public function galeriFotos()
    {
        return $this->hasMany(EventMedia::class)->where('section', 'galeri')->where('type', 'foto')->orderBy('position');
    }

    public function galeriVideos()
    {
        return $this->hasMany(EventMedia::class)->where('section', 'galeri')->where('type', 'video')->orderBy('position');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->nama_kegiatan);
            }
            // Ensure unique slug
            $original = $event->slug;
            $count = 1;
            while (static::where('slug', $event->slug)->exists()) {
                $event->slug = $original . '-' . $count++;
            }
        });
    }
}
