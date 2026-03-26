<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PiaPage extends Model
{
    use HasFactory;

    protected $table = 'pia_pages';

    protected $fillable = [
        'page_title',
        'history_title',
        'history_content',
    ];

    public function revisions()
    {
        return $this->hasMany(PiaHistoryRevision::class, 'pia_page_id')->orderByDesc('edited_at');
    }

    public function logoItems()
    {
        return $this->hasMany(PiaLogoItem::class, 'pia_page_id')->orderBy('position');
    }
}
