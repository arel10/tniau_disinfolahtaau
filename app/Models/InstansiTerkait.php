<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstansiTerkait extends Model
{
    protected $table = 'instansi_terkait';

    protected $fillable = ['nama', 'logo', 'link', 'sort_order'];
}
