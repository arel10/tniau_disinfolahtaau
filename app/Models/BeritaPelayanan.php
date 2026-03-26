<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeritaPelayanan extends Model
{
    use HasFactory;

    protected $table = 'berita_pelayanan';

    protected $fillable = [
        'judul',
        'deskripsi',
        'video_url',
        'logo_path',
        'logo_link',
        'position',
        'is_published',
        'user_id',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function media()
    {
        return $this->hasMany(BeritaPelayananMedia::class)->orderBy('position');
    }

    public function images()
    {
        return $this->media()->where('type', 'image');
    }

    public function videos()
    {
        return $this->media()->where('type', 'video');
    }

    public function pdfs()
    {
        return $this->media()->where('type', 'pdf');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getEmbedUrlAttribute()
    {
        $url = $this->video_url;
        if (!$url) return null;

        if (preg_match('/youtu\.be\/([\w-]+)/', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }
        if (preg_match('/youtube\.com\/watch\?v=([\w-]+)/', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }
        if (preg_match('/youtube\.com\/embed\/([\w-]+)/', $url, $m)) {
            return $url;
        }
        return $url;
    }
}
