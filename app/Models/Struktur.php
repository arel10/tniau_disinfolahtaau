<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Struktur extends Model
{
    use HasFactory;

    protected $table = 'strukturs';

    protected $fillable = [
        'kode',
        'nama_jabatan',
        'nama_lengkap_jabatan',
        'unit',
        'nama_pejabat',
        'pangkat',
        'nrp',
        'tanggal_lahir',
        'foto',
        'parent_kode',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the parent struktur
     */
    public function parent()
    {
        return $this->belongsTo(Struktur::class, 'parent_kode', 'kode');
    }

    /**
     * Get the children
     */
    public function children()
    {
        return $this->hasMany(Struktur::class, 'parent_kode', 'kode')->orderBy('urutan');
    }

    /**
     * Get formatted tanggal lahir
     */
    public function getTanggalLahirFormattedAttribute()
    {
        if ($this->tanggal_lahir) {
            return $this->tanggal_lahir->translatedFormat('d F Y');
        }
        return '-';
    }
}
