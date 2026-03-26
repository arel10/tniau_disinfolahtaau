<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kontak extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'email',
        'subjek',
        'pesan',
        'status',
    ];

    // Scope untuk pesan baru
    public function scopeBaru($query)
    {
        return $query->where('status', 'baru');
    }

    // Scope untuk pesan yang sudah dibaca
    public function scopeDibaca($query)
    {
        return $query->where('status', 'dibaca');
    }

    // Scope untuk pesan yang sedang diproses
    public function scopeDiproses($query)
    {
        return $query->where('status', 'diproses');
    }

    // Scope untuk pesan yang sudah selesai
    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    // Update status
    public function updateStatus($status)
    {
        $this->update(['status' => $status]);
    }
}
