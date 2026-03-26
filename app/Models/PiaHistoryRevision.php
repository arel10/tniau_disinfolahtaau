<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PiaHistoryRevision extends Model
{
    use HasFactory;

    protected $table = 'pia_history_revisions';

    protected $fillable = [
        'pia_page_id',
        'old_history_title',
        'old_history_content',
        'edited_by',
        'edited_at',
    ];

    protected $casts = [
        'edited_at' => 'datetime',
    ];

    public function page()
    {
        return $this->belongsTo(PiaPage::class, 'pia_page_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'edited_by');
    }
}
