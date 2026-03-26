<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeritaPelayananMedia extends Model
{
    use HasFactory;

    protected $table = 'berita_pelayanan_media';

    protected $fillable = [
        'berita_pelayanan_id',
        'file_path',
        'type',
        'original_name',
        'position',
    ];

    public function beritaPelayanan()
    {
        return $this->belongsTo(BeritaPelayanan::class);
    }
}
