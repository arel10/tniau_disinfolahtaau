<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZiPage extends Model
{
    use HasFactory;

    protected $table = 'zi_pages';

    protected $fillable = [
        'type',
        'judul',
        'konten',
        'gambar',
        'pdf_path',
    ];

    /**
     * Scope: filter by type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Relasi ke media (foto/video tambahan).
     */
    public function media()
    {
        return $this->hasMany(ZiPageMedia::class, 'zi_page_id');
    }
}
