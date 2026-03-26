<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZiPerancanganPhoto extends Model
{
    use HasFactory;

    protected $table = 'zi_perancangan_photos';

    protected $fillable = [
        'post_id',
        'path',
        'caption',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * Relasi: foto milik post
     */
    public function post()
    {
        return $this->belongsTo(ZiPerancanganPost::class, 'post_id');
    }
}
