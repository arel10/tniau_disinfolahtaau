<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZiPenetapanKategori extends Model
{
    use HasFactory;

    protected $table = 'zi_penetapan_kategoris';

    protected $fillable = [
        'nama',
        'slug',
    ];

    public function items()
    {
        return $this->hasMany(ZiPenetapanItem::class, 'kategori_id');
    }

    /**
     * Total persen dari semua item di kategori ini.
     */
    public function getTotalPersenAttribute(): int
    {
        return (int) $this->items->sum('persen');
    }
}
