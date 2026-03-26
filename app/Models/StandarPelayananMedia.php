<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandarPelayananMedia extends Model
{
    use HasFactory;

    protected $table = 'standar_pelayanan_media';

    protected $fillable = [
        'standar_pelayanan_id', 'file_path', 'type', 'original_name', 'position',
    ];

    public function standarPelayanan()
    {
        return $this->belongsTo(StandarPelayanan::class);
    }
}
