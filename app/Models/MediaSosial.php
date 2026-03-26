<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaSosial extends Model
{
    protected $fillable = ['nama', 'icon', 'logo', 'link', 'sort_order'];
}
