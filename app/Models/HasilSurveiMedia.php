<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilSurveiMedia extends Model
{
    protected $table = 'hasil_survei_media';

    protected $fillable = [
        'hasil_survei_id', 'file_path', 'type', 'original_name', 'position',
    ];

    public function hasilSurvei()
    {
        return $this->belongsTo(HasilSurvei::class);
    }
}
