<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KompensasiPelayananMedia extends Model
{
    protected $table = 'kompensasi_pelayanan_media';

    protected $fillable = [
        'kompensasi_pelayanan_id',
        'file_path',
        'type',
        'original_name',
        'position',
    ];

    public function kompensasiPelayanan()
    {
        return $this->belongsTo(KompensasiPelayanan::class);
    }
}
