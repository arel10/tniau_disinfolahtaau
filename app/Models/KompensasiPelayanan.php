<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KompensasiPelayanan extends Model
{
    protected $table = 'kompensasi_pelayanan';

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function media()
    {
        return $this->hasMany(KompensasiPelayananMedia::class);
    }

    public function images()
    {
        return $this->media()->where('type', 'image')->orderBy('position');
    }

    public function videos()
    {
        return $this->media()->where('type', 'video')->orderBy('position');
    }

    public function pdfs()
    {
        return $this->media()->where('type', 'pdf')->orderBy('position');
    }

    public function getYoutubeEmbedUrlAttribute()
    {
        if (empty($this->video_url)) return null;
        preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $this->video_url, $m);
        return isset($m[1]) ? 'https://www.youtube.com/embed/' . $m[1] : $this->video_url;
    }
}
