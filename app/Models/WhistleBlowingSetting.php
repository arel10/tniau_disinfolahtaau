<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhistleBlowingSetting extends Model
{
    use HasFactory;

    protected $table = 'whistle_blowing_settings';

    protected $fillable = [
        'gambar',
        'link_tujuan',
        'judul',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get active whistle blowing setting
     */
    public static function getActive()
    {
        return self::where('is_active', true)->first();
    }
}
