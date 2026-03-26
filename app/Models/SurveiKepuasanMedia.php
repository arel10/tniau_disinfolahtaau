<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveiKepuasanMedia extends Model
{
    protected $table = 'survei_kepuasan_media';

    protected $fillable = [
        'survei_kepuasan_id', 'file_path', 'type', 'original_name', 'position',
    ];

    public function surveiKepuasan()
    {
        return $this->belongsTo(SurveiKepuasan::class);
    }
}
