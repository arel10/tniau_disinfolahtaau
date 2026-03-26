<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomMenuMedia extends Model
{
    protected $table = 'custom_menu_media';

    protected $fillable = ['widget_id', 'file_path', 'original_name', 'media_type', 'position'];

    public function widget()
    {
        return $this->belongsTo(CustomMenuWidget::class, 'widget_id');
    }
}
