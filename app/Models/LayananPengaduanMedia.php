<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayananPengaduanMedia extends Model
{
    use HasFactory;

    protected $table = 'layanan_pengaduan_media';

    protected $fillable = [
        'layanan_pengaduan_id', 'file_path', 'type', 'original_name', 'position',
    ];

    public function layananPengaduan()
    {
        return $this->belongsTo(LayananPengaduan::class);
    }
}
