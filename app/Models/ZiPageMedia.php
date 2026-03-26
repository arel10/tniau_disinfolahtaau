<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZiPageMedia extends Model
{
    use HasFactory;

    protected $table = 'zi_page_media';

    protected $fillable = [
        'zi_page_id',
        'file_path',
        'tipe',
    ];

    public function ziPage()
    {
        return $this->belongsTo(ZiPage::class, 'zi_page_id');
    }

    /**
     * Check if this media is a video.
     */
    public function getIsVideoAttribute(): bool
    {
        return in_array(
            strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION)),
            ['mp4', 'mov', 'avi', 'mkv', 'webm']
        );
    }
}
