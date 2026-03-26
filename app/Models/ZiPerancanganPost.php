<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZiPerancanganPost extends Model
{
    use HasFactory;

    protected $table = 'zi_perancangan_posts';

    protected $fillable = [
        'judul',
        'konten',
        'pdf_path',
        'pdf_label',
    ];

    /**
     * Relasi: satu post punya banyak foto
     */
    public function photos()
    {
        return $this->hasMany(ZiPerancanganPhoto::class, 'post_id')->orderBy('sort_order');
    }
}
