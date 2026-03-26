<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PiaLogoItem extends Model
{
    use HasFactory;

    protected $table = 'pia_logo_items';

    protected $fillable = [
        'pia_page_id',
        'title',
        'link_url',
        'logo_path',
        'position',
    ];

    public function page()
    {
        return $this->belongsTo(PiaPage::class, 'pia_page_id');
    }
}
