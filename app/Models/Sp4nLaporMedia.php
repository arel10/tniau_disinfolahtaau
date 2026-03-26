<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sp4nLaporMedia extends Model
{
    use HasFactory;

    protected $table = 'sp4n_lapor_media';

    protected $fillable = [
        'sp4n_lapor_id',
        'file_path',
        'type',
        'original_name',
        'position',
    ];

    public function sp4nLapor()
    {
        return $this->belongsTo(Sp4nLapor::class);
    }
}
