<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZiPenetapanItem extends Model
{
    use HasFactory;

    protected $table = 'zi_penetapan_items';

    protected $fillable = [
        'kategori_id',
        'judul',
        'persen',
        'foto',
        'konten',
    ];

    protected $casts = [
        'persen' => 'integer',
    ];

    public function kategori()
    {
        return $this->belongsTo(ZiPenetapanKategori::class, 'kategori_id');
    }
}
